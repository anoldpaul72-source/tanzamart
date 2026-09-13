<?php

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$captured = Illuminate\Http\Request::capture();

$qs = $captured->getQueryString() ? '?' . $captured->getQueryString() : '';
$request = $captured->duplicate(
    null, null, null, null, null,
    array_merge($_SERVER, [
        'REQUEST_URI' => '/admin/products' . $qs,
        'PATH_INFO' => '/admin/products',
    ])
);

$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
