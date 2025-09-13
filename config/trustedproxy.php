<?php

use Symfony\Component\HttpFoundation\Request;

return [

    /*
    |--------------------------------------------------------------------------
    | Trusted Proxies
    |--------------------------------------------------------------------------
    | Trage hier deine Proxy-IP(s) ein. Für deinen IONOS NPM:
    | z.B. '203.0.113.45' (ohne Port). Mehrere als Array.
    */
    'proxies' => '85.215.226.9',

    /*
    |--------------------------------------------------------------------------
    | Trusted Headers
    |--------------------------------------------------------------------------
    | Welche Forwarded-Header Laravel auswertet. "ALL" deckt das Übliche ab.
    */
    'headers' =>
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB,
];