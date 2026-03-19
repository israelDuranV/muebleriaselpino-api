<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class TrustProxies extends Middleware
{
    /**
     * Los proxies confiables para esta aplicación.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*'; // o puedes usar ['192.168.1.1'] si quieres especificar

    /**
     * Los encabezados que deben usarse para detectar proxies.
     *
     * @var int
     */
    protected $headers = SymfonyRequest::HEADER_X_FORWARDED_ALL;
}