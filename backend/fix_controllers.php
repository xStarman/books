<?php

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('app/Http/Controllers'));
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php' && $file->getFilename() !== 'Controller.php') {
        $content = file_get_contents($file->getPathname());
        if (strpos($content, 'extends Controller') !== false && strpos($content, 'use App\Http\Controllers\Controller;') === false) {
            $content = preg_replace('/namespace (.*?);/', "namespace $1;\n\nuse App\Http\Controllers\Controller;", $content);
            file_put_contents($file->getPathname(), $content);
        }
    }
}

echo "Fixed controllers.\n";

