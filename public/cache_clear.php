<?php

apcu_clear_cache();

$dir = dirname(__FILE__) . '/../var/cache';

$it = new RecursiveDirectoryIterator($dir);
$files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
foreach ($files as $file) {
    if ($file->isDir()) {
        rmdir($file->getRealPath());
    } else {
        unlink($file->getRealPath());
    }
}

rmdir($dir);

$dir = dirname(__FILE__) . '/../var/log';

$it = new RecursiveDirectoryIterator($dir);
$files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

foreach ($files as $file) {
    if ($file->isDir()) {
        rmdir($file->getRealPath());
    } else {
        unlink($file->getRealPath());
    }
}
rmdir($dir);