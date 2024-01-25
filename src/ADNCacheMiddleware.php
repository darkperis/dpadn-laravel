<?php

namespace Darkpony\ADNCache;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ADNCacheMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  string                   $adncache_control
     * @return mixed
     */
    public function handle($request, Closure $next, string $adncache_control = null)
    {
        $response = $next($request);

        if (!in_array($request->getMethod(), ['GET', 'HEAD']) || !$response->getContent()) {
            return $response;
        }

        $esi_enabled    = config('adncache.esi');
        $maxage         = config('adncache.default_ttl', 0);
        $cacheability   = config('adncache.default_cacheability');
        $guest_only     = config('adncache.guest_only', false);

        if ($maxage === 0 && $adncache_control === null) {
            return $response;
        }

        //'cache.headers:public;max_age=2628000;etag'
        if ($guest_only && Auth::check()) {
            $response->headers->set('Cache-Control', 'no-cache');

            return $response;
        }

        $adncache_string = "max-age=$maxage,$cacheability";

        if (isset($adncache_control)) {
            $adncache_string = str_replace(';', ',', $adncache_control);
        }

        if (Str::contains($adncache_string, 'esi=on') == false) {
            $adncache_string = $adncache_string.($esi_enabled ? ',esi=on' : null);
        }

        if ($response->headers->has('Cache-Control') == false) {
            $response->headers->set('Cache-Control', $adncache_string);
        }

        return $response;
    }
}
