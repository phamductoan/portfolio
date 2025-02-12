<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ApiSecurityMiddleware
{
    protected $rateLimiter;

    public function __construct(RateLimiter $rateLimiter)
    {
        $this->rateLimiter = $rateLimiter;
    }

    public function handle(Request $request, Closure $next)
    {
        // 1. Kiểm tra API key trong header
        if (!$request->hasHeader('X-API-KEY') || $request->header('X-API-KEY') !== config('app.api_key')) {
            return response()->json([
                'error' => 'Invalid API key',
                'status' => 401
            ], 401);
        }

        // 2. Kiểm tra IP whitelist (nếu được cấu hình)
        $whitelistedIps = config('security.whitelisted_ips', []);
        if (!empty($whitelistedIps) && !in_array($request->ip(), $whitelistedIps)) {
            Log::warning('Access attempt from unauthorized IP: ' . $request->ip());
            return response()->json([
                'error' => 'Unauthorized IP address',
                'status' => 403
            ], 403);
        }

        // 3. Rate limiting
        $key = 'api:' . $request->ip();
        if ($this->rateLimiter->tooManyAttempts($key, 60)) { // 60 requests per minute
            Log::warning('Rate limit exceeded for IP: ' . $request->ip());
            return response()->json([
                'error' => 'Too many requests',
                'status' => 429
            ], 429);
        }
        $this->rateLimiter->hit($key, 60);

        // 4. Kiểm tra Content-Type
        if ($request->isMethod('POST') || $request->isMethod('PUT')) {
            if (!$request->isJson()) {
                return response()->json([
                    'error' => 'Content-Type must be application/json',
                    'status' => 400
                ], 400);
            }
        }

        // 5. Kiểm tra và log payload size
        $contentLength = $request->header('Content-Length');
        if ($contentLength && $contentLength > 5242880) { // 5MB limit
            return response()->json([
                'error' => 'Payload too large',
                'status' => 413
            ], 413);
        }

        // 6. Log request details
        $this->logRequest($request);

        // 7. Thêm security headers vào response
        $response = $next($request);
        
        return $this->addSecurityHeaders($response);
    }

    protected function logRequest(Request $request)
    {
        Log::channel('api')->info('API Request', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'method' => $request->method(),
            'path' => $request->path(),
            'user_id' => Auth::id() ?? 'guest',
            'headers' => $request->headers->all(),
            'timestamp' => now()->toIso8601String()
        ]);
    }

    protected function addSecurityHeaders($response)
    {
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $response->headers->set('Content-Security-Policy', "default-src 'self'");
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        return $response;
    }
}