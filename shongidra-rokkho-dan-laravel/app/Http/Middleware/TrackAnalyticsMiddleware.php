<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PageAnalytic;
use Symfony\Component\HttpFoundation\Response;

class TrackAnalyticsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Track GET requests for HTML pages (ignore ajax polling, admin inquiries live feed, assets, vendor files)
        if ($request->isMethod('GET') && !$request->ajax() && !$request->is('admin/inquiries/live*') && !$request->is('api/*')) {
            try {
                $userAgent = $request->userAgent() ?? '';
                $isMobile = preg_match('/(android|bb\d+|meego).+mobile|avail|blackberry|iemobile|iphone|ipad|ipod|opera mini|mobile/i', $userAgent);

                PageAnalytic::create([
                    'url' => $request->fullUrl(),
                    'path' => '/' . ltrim($request->path(), '/'),
                    'ip_address' => $request->ip(),
                    'user_agent' => substr($userAgent, 0, 250),
                    'device_type' => $isMobile ? 'mobile' : 'desktop',
                    'referrer' => substr($request->header('referer', ''), 0, 250),
                ]);
            } catch (\Exception $e) {
                // Silently ignore analytics logging errors
            }
        }

        return $response;
    }
}
