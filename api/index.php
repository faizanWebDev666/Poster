<?php

// Symlink storage to /tmp for Vercel's read-only filesystem
$tmp = '/tmp/storage_' . md5(__DIR__);
$storage = dirname(__DIR__) . '/storage';

if (!file_exists($tmp)) {
    mkdir($tmp . '/framework/cache/data', 0777, true);
    mkdir($tmp . '/framework/sessions', 0777, true);
    mkdir($tmp . '/framework/views', 0777, true);
    mkdir($tmp . '/app/public', 0777, true);
    mkdir($tmp . '/app/private', 0777, true);
    mkdir($tmp . '/logs', 0777, true);
}

if (!is_link($storage)) {
    // Remove existing storage dir if it exists, then symlink
    if (is_dir($storage) && !is_link($storage)) {
        // Can't remove on read-only FS, but can rename if needed
        // Just override the path via config instead
    } else {
        symlink($tmp, $storage);
    }
}

// Load Composer autoloader
require dirname(__DIR__) . '/vendor/autoload.php';

// If symlink failed (read-only FS), bind storage path to /tmp
if (!is_link($storage)) {
    $app = require_once dirname(__DIR__) . '/bootstrap/app.php';

    $app->useStoragePath($tmp);

    // Handle the request
    $app->handleRequest(
        Illuminate\Http\Request::capture()
    );
} else {
    require dirname(__DIR__) . '/public/index.php';
}
