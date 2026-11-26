<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;
use XMLWriter;
use ZipArchive;

class SpreadsheetExportService
{
    /**
     * Download tabular data as a UTF-8 CSV (with BOM for Excel compatibility and formula neutralization).
     *
     * @param  array<int, string>  $headers
     * @param  iterable<int, array<int, mixed>>  $rows
     */
    public function downloadCsv(string $fileBaseName, array $headers, iterable $rows): Response
    {
        $safeName = $this->sanitizeFileName($fileBaseName) . '.csv';

        return response()->streamDownload(function () use ($headers, $rows): void {
            $out = fopen('php://output', 'wb');
            if ($out === false) {
                return;
            }

            // UTF-8 BOM ensures Microsoft Excel opens accented/Indian characters cleanly.
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, array_map([$this, 'sanitizeCsvCell'], $headers));

            foreach ($rows as $row) {
                $cleanRow = [];
                foreach ($headers as $idx => $unused) {
                    $cleanRow[] = $this->sanitizeCsvCell($row[$idx] ?? '');
                }
                fputcsv($out, $cleanRow);
            }

            fclose($out);
        }, $safeName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
        ]);
    }

    /**
     * Build and download a native OOXML (.xlsx) workbook using ZipArchive + XMLWriter.
     *
     * @param  array<int, string>  $headers
     * @param  iterable<int, array<int, mixed>>  $rows
     */
    public function downloadXlsx(string $fileBaseName, array $headers, iterable $rows, string $sheetTitle = 'Users'): Response
    {
        if (!class_exists(ZipArchive::class) || !class_exists(XMLWriter::class)) {
            return $this->downloadCsv($fileBaseName, $headers, $rows);
        }

        $safeName = $this->sanitizeFileName($fileBaseName) . '.xlsx';
        $safeSheetTitle = $this->sanitizeSheetTitle($sheetTitle);

        $tempSheetPath = tempnam(sys_get_temp_dir(), 'vpa_sheet_');
        $tempZipPath = tempnam(sys_get_temp_dir(), 'vpa_xlsx_');

        if ($tempSheetPath === false || $tempZipPath === false) {
            @unlink((string) $tempSheetPath);
            @unlink((string) $tempZipPath);

            return $this->downloadCsv($fileBaseName, $headers, $rows);
        }

        try {
            $colCount = max(1, count($headers));
            $colWidths = [];
            foreach ($headers as $i => $h) {
                $colWidths[$i] = min(44, max(12, mb_strlen((string) $h) + 5));
            }

            // Pass 1: write cell rows into a temporary body buffer so <cols> widths reflect content cleanly.
            $tempBodyPath = tempnam(sys_get_temp_dir(), 'vpa_body_');
            if ($tempBodyPath === false) {
                throw new \RuntimeException('Unable to create temporary worksheet body file.');
            }

            $bodyWriter = new XMLWriter();
            $bodyWriter->openUri($tempBodyPath);
            $rowNumber = 1;

            // Header Row (Style index 1: bold white on navy fill)
            $this->writeRowXml($bodyWriter, $rowNumber++, $headers, 1, $colCount, $colWidths);

            foreach ($rows as $row) {
                $this->writeRowXml($bodyWriter, $rowNumber++, $row, 0, $colCount, $colWidths);
            }

            $totalRows = $rowNumber - 1;
            $bodyWriter->flush();
            unset($bodyWriter);

            // Pass 2: assemble complete xl/worksheets/sheet1.xml with frozen header & column widths.
            $colsXml = '<cols>';
            for ($i = 0; $i < $colCount; $i++) {
                $width = (int) ($colWidths[$i] ?? 18);
                $colIdx = $i + 1;
                $colsXml .= '<col min="' . $colIdx . '" max="' . $colIdx . '" width="' . $width . '" customWidth="1"/>';
            }
            $colsXml .= '</cols>';

            $sheetOut = fopen($tempSheetPath, 'wb');
            $bodyIn = fopen($tempBodyPath, 'rb');
            if ($sheetOut === false || $bodyIn === false) {
                if ($bodyIn !== false) {
                    fclose($bodyIn);
                }
                if ($sheetOut !== false) {
                    fclose($sheetOut);
                }
                throw new \RuntimeException('Unable to assemble worksheet XML.');
            }

            $headerXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
                . '<sheetViews><sheetView workbookViewId="0">'
                . '<pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/>'
                . '</sheetView></sheetViews>'
                . $colsXml
                . '<sheetData>';

            fwrite($sheetOut, $headerXml);
            stream_copy_to_stream($bodyIn, $sheetOut);
            $lastCol = $this->columnIndexToLetter($colCount - 1);
            fwrite($sheetOut, '</sheetData><autoFilter ref="A1:' . $lastCol . $totalRows . '"/></worksheet>');
            fclose($bodyIn);
            fclose($sheetOut);
            @unlink($tempBodyPath);

            $zip = new ZipArchive();
            if ($zip->open($tempZipPath, ZipArchive::OVERWRITE | ZipArchive::CREATE) !== true) {
                throw new \RuntimeException('Failed to create XLSX archive.');
            }

            $zip->addFromString('[Content_Types].xml', $this->buildContentTypesXml());
            $zip->addFromString('_rels/.rels', $this->buildRootRelsXml());
            $zip->addFromString('xl/workbook.xml', $this->buildWorkbookXml($safeSheetTitle));
            $zip->addFromString('xl/_rels/workbook.xml.rels', $this->buildWorkbookRelsXml());
            $zip->addFromString('xl/styles.xml', $this->buildStylesXml());
            $zip->addFile($tempSheetPath, 'xl/worksheets/sheet1.xml');
            $zip->close();

            $binary = file_get_contents($tempZipPath);
            if ($binary === false) {
                throw new \RuntimeException('Failed reading generated XLSX archive.');
            }

            return response($binary, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $safeName . '"',
                'Cache-Control' => 'private, max-age=0, must-revalidate',
            ]);
        } finally {
            @unlink($tempSheetPath);
            @unlink($tempZipPath);
            if (isset($tempBodyPath) && is_string($tempBodyPath) && file_exists($tempBodyPath)) {
                @unlink($tempBodyPath);
            }
        }
    }

    private function writeRowXml(
        XMLWriter $writer,
        int $rowNumber,
        array $cells,
        int $styleIndex,
        int $colCount,
        array &$colWidths
    ): void {
        $writer->startElement('row');
        $writer->writeAttribute('r', (string) $rowNumber);

        for ($col = 0; $col < $colCount; $col++) {
            $raw = $cells[$col] ?? '';
            $text = $this->cleanXmlString($raw);
            $len = mb_strlen($text);
            $colWidths[$col] = max($colWidths[$col] ?? 12, min(44, $len + 4));

            $cellRef = $this->columnIndexToLetter($col) . $rowNumber;
            $writer->startElement('c');
            $writer->writeAttribute('r', $cellRef);
            $writer->writeAttribute('t', 'inlineStr');
            $writer->writeAttribute('s', (string) $styleIndex);
            $writer->startElement('is');
            $writer->startElement('t');
            $writer->writeAttribute('xml:space', 'preserve');
            $writer->text($text);
            $writer->endElement(); // t
            $writer->endElement(); // is
            $writer->endElement(); // c
        }

        $writer->endElement(); // row
    }

    private function sanitizeCsvCell(mixed $value): string
    {
        $str = str_replace(["\r\n", "\r", "\n"], ' ', trim((string) ($value ?? '')));

        if ($str !== '' && preg_match('/^[=+\-@\t]/', $str) === 1) {
            return "'" . $str;
        }

        return $str;
    }

    private function cleanXmlString(mixed $value): string
    {
        $str = (string) ($value ?? '');
        $cleaned = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', $str);

        return $cleaned ?? '';
    }

    private function sanitizeFileName(string $name): string
    {
        $clean = preg_replace('/[^A-Za-z0-9_\-]/', '_', trim($name));

        return $clean !== '' ? $clean : 'export';
    }

    private function sanitizeSheetTitle(string $title): string
    {
        $clean = str_replace(['\\', '/', '*', '[', ']', ':', '?'], ' ', trim($title));
        $clean = trim(preg_replace('/\s+/', ' ', $clean) ?? 'Sheet1');

        return mb_substr($clean !== '' ? $clean : 'Sheet1', 0, 31);
    }

    private function columnIndexToLetter(int $index): string
    {
        $letter = '';
        $num = $index + 1;
        while ($num > 0) {
            $rem = ($num - 1) % 26;
            $letter = chr(65 + $rem) . $letter;
            $num = intdiv($num - 1, 26);
        }

        return $letter;
    }

    private function buildContentTypesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '</Types>';
    }

    private function buildRootRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="/xl/workbook.xml"/>'
            . '</Relationships>';
    }

    private function buildWorkbookRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';
    }

    private function buildWorkbookXml(string $sheetTitle): string
    {
        $escapedTitle = htmlspecialchars($sheetTitle, ENT_XML1 | ENT_QUOTES, 'UTF-8');

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
            . 'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="' . $escapedTitle . '" sheetId="1" r:id="rId1"/></sheets>'
            . '</workbook>';
    }

    private function buildStylesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<fonts count="2">'
            . '<font><sz val="11"/><name val="Calibri"/><color rgb="FF1F2937"/></font>'
            . '<font><b/><sz val="11"/><name val="Calibri"/><color rgb="FFFFFFFF"/></font>'
            . '</fonts>'
            . '<fills count="3">'
            . '<fill><patternFill patternType="none"/></fill>'
            . '<fill><patternFill patternType="gray125"/></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="FF031B4E"/><bgColor indexed="64"/></patternFill></fill>'
            . '</fills>'
            . '<borders count="2">'
            . '<border><left/><right/><top/><bottom/><diagonal/></border>'
            . '<border><left style="thin"><color rgb="FFD1D5DB"/></left><right style="thin"><color rgb="FFD1D5DB"/></right><top style="thin"><color rgb="FF031B4E"/></top><bottom style="thin"><color rgb="FF031B4E"/></bottom><diagonal/></border>'
            . '</borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="2">'
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            . '<xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1"/>'
            . '</cellXfs>'
            . '</styleSheet>';
    }
}
