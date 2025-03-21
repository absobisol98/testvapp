<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Set Content Security Policy header
        $response->headers->set(
            'Content-Security-Policy',
            "connect-src 'self' https://l.sharethis.com/ https://bam.nr-data.net/; img-src https://platform-cdn.sharethis.com/ https://ui-avatars.com/ 'self' data:; font-src data: https://fonts.gstatic.com/ https://fonts.bunny.net; default-src 'self'; script-src 'unsafe-eval' 'unsafe-inline'; script-src-elem 'self' https://code.jquery.com/ https://cdnjs.cloudflare.com/ https://unpkg.com/  https://cdn.tailwindcss.com/ https://js-agent.newrelic.com/ https://buttons-config.sharethis.com/ https://platform-api.sharethis.com/ https://cdn.jsdelivr.net/npm/ 'self' 'unsafe-inline'; style-src https://unpkg.com/  https://fonts.googleapis.com/ 'self' https://cdnjs.cloudflare.com/ https://cdn.jsdelivr.net/npm/ https://fonts.bunny.net/  'unsafe-inline'; "
        );

        return $response;
    }
}
