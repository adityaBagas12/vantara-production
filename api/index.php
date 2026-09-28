<?php

// Prepare storage directories in /tmp for Vercel serverless read-only filesystem
$storageDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/temp_reports',
];

foreach ($storageDirs as $dir) {
    if (!file_exists($dir)) {
        @mkdir($dir, 0755, true);
    }
}

require __DIR__ . '/../public/index.php';
