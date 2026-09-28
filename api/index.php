<?php

// Prepare storage directories in /tmp for Vercel serverless read-only filesystem
$storageDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/bootstrap',
    '/tmp/storage/logs',
    '/tmp/temp_reports',
];

foreach ($storageDirs as $dir) {
    if (!file_exists($dir)) {
        @mkdir($dir, 0777, true);
    }
}

// Override storage & cache paths for serverless execution
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
putenv('APP_SERVICES_CACHE=/tmp/storage/bootstrap/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/storage/bootstrap/packages.php');

$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_ENV['APP_SERVICES_CACHE'] = '/tmp/storage/bootstrap/services.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/storage/bootstrap/packages.php';

require __DIR__ . '/../public/index.php';
