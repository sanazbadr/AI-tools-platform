<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DomainMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $domain = $request->getHost();
        
        // Store domain info in session for use in controllers/views
        session(['current_domain' => $domain]);
        
        // Add domain info to request for easy access
        $request->attributes->set('current_domain', $domain);
        
        return $next($request);
    }
} 