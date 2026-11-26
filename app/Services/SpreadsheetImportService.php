<?php

namespace App\Services;

use RuntimeException;
use SimpleXMLElement;
use ZipArchive;

class SpreadsheetImportService
{
    private const HEADER_ALIASES = [
        'name' => 'name',
        'full_name' => 'name',
        'user_name' => 'name',
        'candidate_name' => 'name',
        'email' => 'email',
        'email_address' => 'email',
        'e_mail' => 'email',
        'phone' => 'phone',
        'phone_number' => 'phone',
        'mobile' => 'phone',
        'mobile_number' => 'phone',
        'contact_number' => 'phone',
        'role' => 'role',
        'user_role' => 'role',
        'account_type' => 'role',
        'temporary_password' => 'password',
        'temp_password' => 'password',
        'password' => 'password',
        'account_status' => 'status',
        'status' => 'status',
        'vpa_id_school_name' => 'vpa_or_school',
        'vpa_id' => 'vpa_or_school',
        'school_name' => 'vpa_or_school',
        'organization' => 'vpa_or_school',
        'contact_person' => 'contact_person',
        'category_subject_contact_person' => 'contact_person',
        'address' => 'address',
    ];

    /**
     * Parse an uploaded .xlsx or .csv file into normalized associative row records.
     *
     * @return array{headers: array<int, string>, rows: array<int, array{row_number: int, data: array<string, string>}>}
     */
    public function parse(string $filePath, string $extension): array
    {
        $ext = strtolower(trim($extension));

        $rawMatrix = match ($ext) {
            'xlsx' => $this->readXlsxMatrix($filePath),
            'csv', 'txt' => $this->readCsvMatrix($filePath),
            default => throw new RuntimeException("Unsupported import file format: .{$ext}. Please upload .xlsx or .csv."),
        };

        if (empty($rawMatrix)) {
            throw new RuntimeException('The uploaded file is empty.');
        }

        // First non-empty row is treated as header row.
        $headerIndex = null;
        foreach ($rawMatrix as $idx => $row) {
            if ($this->rowHasData($row)) {
                $headerIndex = $idx;
                break;
            }
        }

        if ($headerIndex === null) {
            throw new RuntimeException('No header row was found in the uploaded file.');
        }

        $rawHeaders = $rawMatrix[$headerIndex];
        $mappedHeaders = [];
        foreach ($rawHeaders as $colIdx => $label) {
            $normalized = $this->normalizeHeaderKey((string) $label);
            $mappedHeaders[$colIdx] = self::HEADER_ALIASES[$normalized] ?? $normalized;
        }

        if (!in_array('email', $mappedHeaders, true) || !in_array('name', $mappedHeaders, true)) {
            throw new RuntimeException('Missing required columns "Name" and "Email". Please use the downloadable import template.');
        }

        $rows = [];
        $totalMatrixCount = count($rawMatrix);
        for ($i = $headerIndex + 1; $i < $totalMatrixCount; $i++) {
            $rawRow = $rawMatrix[$i];
            if (!$this->rowHasData($rawRow)) {
                continue;
            }

            $assoc = [];
            foreach ($mappedHeaders as $colIdx => $canonicalKey) {
                if ($canonicalKey === '') {
                    continue;
                }
                $val = trim((string) ($rawRow[$colIdx] ?? ''));
                if ($val !== '' && !isset($assoc[$canonicalKey])) {
                    $assoc[$canonicalKey] = $val;
                } elseif (!isset($assoc[$canonicalKey])) {
                    $assoc[$canonicalKey] = $val;
                }
            }

            $rows[] = [
                'row_number' => $i + 1,
                'data' => $assoc,
            ];
        }

        return [
            'headers' => array_values($mappedHeaders),
            'rows' => $rows,
        ];
    }

    /**
     * @return array<int, array<int, string>>
     */
    private function readCsvMatrix(string $filePath): array
    {
        $handle = fopen($filePath, 'rb');
        if ($handle === false) {
            throw new RuntimeException('Unable to read uploaded CSV file.');
        }

        try {
            $firstChunk = fgets($handle);
            if ($firstChunk === false) {
                return [];
            }

            $firstChunk = preg_replace('/^\xEF\xBB\xBF/', '', $firstChunk) ?? $firstChunk;
            $commaCount = substr_count($firstChunk, ',');
            $semiCount = substr_count($firstChunk, ';');
            $delimiter = ($semiCount > $commaCount) ? ';' : ',';

            rewind($handle);
            $matrix = [];
            $isFirstRow = true;

            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                if ($isFirstRow && isset($row[0])) {
                    $row[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $row[0]) ?? (string) $row[0];
                    $isFirstRow = false;
                }
                $matrix[] = array_map(fn ($cell) => $this->unquoteFormulaPrefix((string) ($cell ?? '')), $row);
            }

            return $matrix;
        } finally {
            fclose($handle);
        }
    }

    /**
     * @return array<int, array<int, string>>
     */
    private function readXlsxMatrix(string $filePath): array
    {
        if (!class_exists(ZipArchive::class)) {
            throw new RuntimeException('PHP ZipArchive extension is required to read .xlsx files. Please upload a .csv file instead.');
        }

        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new RuntimeException('Invalid .xlsx file: could not open workbook archive.');
        }

        try {
            $sharedStrings = $this->parseSharedStrings($zip);
            $sheetPath = $this->resolveFirstWorksheetPath($zip);
            $sheetXmlStr = $zip->getFromName($sheetPath);

            if ($sheetXmlStr === false) {
                throw new RuntimeException('Worksheet XML could not be found inside .xlsx archive.');
            }

            $sheetXml = @simplexml_load_string($sheetXmlStr);
            if ($sheetXml === false || !isset($sheetXml->sheetData)) {
                throw new RuntimeException('Invalid worksheet XML inside .xlsx file.');
            }

            $matrix = [];
            foreach ($sheetXml->sheetData->row as $rowEl) {
                $rowArr = [];
                $fallbackCol = 0;

                foreach ($rowEl->c as $cell) {
                    $ref = (string) ($cell['r'] ?? '');
                    $colIdx = $ref !== '' ? $this->columnIndexFromCellRef($ref) : $fallbackCol;
                    $fallbackCol = $colIdx + 1;

                    $type = (string) ($cell['t'] ?? '');
                    $value = '';

                    if ($type === 's') {
                        $ssIdx = (int) ($cell->v ?? -1);
                        $value = $sharedStrings[$ssIdx] ?? '';
                    } elseif ($type === 'inlineStr') {
                        $value = $this->extractInlineText($cell->is ?? null);
                    } elseif ($type === 'b') {
                        $value = ((string) ($cell->v ?? '0')) === '1' ? '1' : '0';
                    } else {
                        $value = (string) ($cell->v ?? '');
                    }

                    $rowArr[$colIdx] = trim($value);
                }

                if (!empty($rowArr)) {
                    $maxCol = max(array_keys($rowArr));
                    for ($c = 0; $c <= $maxCol; $c++) {
                        if (!array_key_exists($c, $rowArr)) {
                            $rowArr[$c] = '';
                        }
                    }
                    ksort($rowArr);
                }

                $matrix[] = $rowArr;
            }

            return $matrix;
        } finally {
            $zip->close();
        }
    }

    /**
     * @return array<int, string>
     */
    private function parseSharedStrings(ZipArchive $zip): array
    {
        $xmlStr = $zip->getFromName('xl/sharedStrings.xml');
        if ($xmlStr === false) {
            return [];
        }

        $xml = @simplexml_load_string($xmlStr);
        if ($xml === false) {
            return [];
        }

        $strings = [];
        foreach ($xml->si as $si) {
            if (isset($si->t)) {
                $strings[] = (string) $si->t;
            } elseif (isset($si->r)) {
                $combined = '';
                foreach ($si->r as $run) {
                    $combined .= (string) ($run->t ?? '');
                }
                $strings[] = $combined;
            } else {
                $strings[] = '';
            }
        }

        return $strings;
    }

    private function extractInlineText(?SimpleXMLElement $isNode): string
    {
        if ($isNode === null) {
            return '';
        }

        if (isset($isNode->t)) {
            return (string) $isNode->t;
        }

        if (isset($isNode->r)) {
            $combined = '';
            foreach ($isNode->r as $run) {
                $combined .= (string) ($run->t ?? '');
            }
            return $combined;
        }

        return '';
    }

    private function resolveFirstWorksheetPath(ZipArchive $zip): string
    {
        $wbStr = $zip->getFromName('xl/workbook.xml');
        $relsStr = $zip->getFromName('xl/_rels/workbook.xml.rels');

        if ($wbStr !== false && $relsStr !== false) {
            $wb = @simplexml_load_string($wbStr);
            $rels = @simplexml_load_string($relsStr);

            if ($wb !== false && $rels !== false && isset($wb->sheets->sheet[0])) {
                $rAttrs = $wb->sheets->sheet[0]->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships');
                $firstRid = (string) ($rAttrs['id'] ?? '');

                foreach ($rels->Relationship as $rel) {
                    if ((string) $rel['Id'] === $firstRid) {
                        $target = ltrim((string) $rel['Target'], '/');
                        if (!str_starts_with($target, 'xl/')) {
                            $target = 'xl/' . $target;
                        }
                        if ($zip->locateName($target) !== false) {
                            return $target;
                        }
                    }
                }
            }
        }

        return 'xl/worksheets/sheet1.xml';
    }

    private function columnIndexFromCellRef(string $ref): int
    {
        $letters = strtoupper(preg_replace('/[^A-Za-z]/', '', $ref) ?? 'A');
        $index = 0;
        $len = strlen($letters);
        for ($i = 0; $i < $len; $i++) {
            $index = ($index * 26) + (ord($letters[$i]) - 64);
        }

        return max(0, $index - 1);
    }

    private function normalizeHeaderKey(string $label): string
    {
        $clean = strtolower(trim($label));
        $clean = preg_replace('/[^a-z0-9]+/', '_', $clean) ?? '';

        return trim($clean, '_');
    }

    private function unquoteFormulaPrefix(string $value): string
    {
        if (strlen($value) >= 2 && $value[0] === "'" && in_array($value[1], ['=', '+', '-', '@', "\t"], true)) {
            return substr($value, 1);
        }

        return $value;
    }

    private function rowHasData(array $row): bool
    {
        foreach ($row as $cell) {
            if (trim((string) $cell) !== '') {
                return true;
            }
        }

        return false;
    }
}
