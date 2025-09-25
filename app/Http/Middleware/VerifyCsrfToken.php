<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */

    protected $except = [
    'midtrans/notification',
    'midtrans/notification/*',
    'midtrans/*',
    '/midtrans/notification*',
    'savereservasi',
    '/savereservasi',
    'http://127.0.0.1:8000/savereservasi',
    'http://127.0.0.1:8000/midtrans/notification',
    '/savereservasi*',
];

}
