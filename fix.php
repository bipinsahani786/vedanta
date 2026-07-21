<?php
$dir = new RecursiveDirectoryIterator('c:/Users/Employee/Desktop/Vedanta/resources/views/admin');
foreach (new RecursiveIteratorIterator($dir) as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $newContent = preg_replace('/<template x-if="isEdit">\s*@method\(\'PUT\'\)\s*<\/template>/m', '<input type="hidden" name="_method" value="PUT" x-bind:disabled="!isEdit">', $content);
        if ($content !== $newContent) {
            file_put_contents($file->getPathname(), $newContent);
            echo "Fixed " . $file->getFilename() . "\n";
        }
    }
}
echo "Done\n";
