<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

config(['filesystems.disks.s3.throw' => true]);

try {
    $disk = Illuminate\Support\Facades\Storage::disk('s3');
    $success = $disk->put('test_visibility.txt', 'test', 'public');
    echo 'Put Result: ' . ($success ? 'Success' : 'Failed') . "\n";
    echo 'URL: ' . $disk->url('test_visibility.txt') . "\n";
} catch (\Exception $e) {
    echo 'Exception: ' . $e->getMessage() . "\n";
}
