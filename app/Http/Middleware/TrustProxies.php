<?php

namespace App\Http\Middleware;

/**
 * Minimal TrustProxies using fully-qualified constant to avoid Intelephense warning.
 */
class TrustProxies extends \Illuminate\Http\Middleware\TrustProxies
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * Use fully-qualified class constant to avoid IDE unresolved constant.
     *
     * @var int
     */
    protected $headers = \Illuminate\Http\Request::/* `HEADER_X_FORWARDED_FOR` is a constant used in the TrustProxies middleware class in Laravel to specify the header that should be used to detect proxies. In this case, it indicates that the middleware should look for the client's IP address in the `X-Forwarded-For` header when determining the client's IP address behind a proxy server. This is important for correctly identifying the client's IP address when the application is behind a proxy or load balancer. */
    HEADER_X_FORWARDED_FOR;
}
