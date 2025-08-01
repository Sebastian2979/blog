<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * Den Proxies vertrauen (z. B. deinem Nginx Proxy Manager).
     * Mit '*' vertraust du allen Proxies.
     */
    protected $proxies = '85.215.226.9';

    /**
     * Welche Header Laravel auswertet, um HTTPS zu erkennen.
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO;
}
