<?php
$projectRoot = dirname(__DIR__);
$targetZipDir = $projectRoot . '/tools/releases';
if (!is_dir($targetZipDir)) {
    mkdir($targetZipDir, 0755, true);
}

$zipFileName = $targetZipDir . '/Update_ERP_Peternakan.zip';
$zip = new ZipArchive();

if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    $dir = new RecursiveDirectoryIterator($projectRoot, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::SELF_FIRST);

    foreach ($files as $file) {
        $realPath = $file->getRealPath();
        $relativePath = substr($realPath, strlen($projectRoot) + 1);
        $relativePath = str_replace('\\', '/', $relativePath);

        // Exclude large or environment-specific files
        if (
            strpos($relativePath, 'node_modules') === 0 ||
            strpos($relativePath, '.git') === 0 ||
            strpos($relativePath, 'storage/logs') === 0 ||
            strpos($relativePath, 'storage/framework/cache') === 0 ||
            strpos($relativePath, 'storage/framework/views') === 0 ||
            strpos($relativePath, 'storage/framework/sessions') === 0 ||
            strpos($relativePath, 'vendor') === 0 ||
            strpos($relativePath, '.env') === 0 ||
            strpos($relativePath, 'tools/releases') === 0 ||
            strpos($relativePath, 'scratch') === 0
        ) {
            continue;
        }

        if ($file->isDir()) {
            $zip->addEmptyDir($relativePath);
        } else {
            $zip->addFile($realPath, $relativePath);
        }
    }
    $zip->close();
    echo "ZIP Release Package successfully generated at:\n" . $zipFileName . "\n";
} else {
    echo "Failed to create ZIP package.\n";
}
