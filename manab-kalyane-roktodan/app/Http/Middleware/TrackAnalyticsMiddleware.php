<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PageAnalytic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackAnalyticsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Ensure Visitor Tracking ID exists in session/cookie for both registered & non-registered users
        $trackingId = $request->cookie('mkrd_visitor_id') ?: session('mkrd_visitor_id');
        if (!$trackingId) {
            $trackingId = 'VT-' . strtoupper(Str::random(8));
            session(['mkrd_visitor_id' => $trackingId]);
            Cookie::queue('mkrd_visitor_id', $trackingId, 525600); // 1 year cookie
        }

        $response = $next($request);

        // 2. Track GET page views (excluding ajax polling, live feeds, assets, etc.)
        if ($request->isMethod('GET') && !$request->ajax() && !$request->is('admin/inquiries/live*') && !$request->is('api/*')) {
            try {
                $userAgent = $request->userAgent() ?? '';
                $isMobile = preg_match('/(android|bb\d+|meego).+mobile|avail|blackberry|iemobile|iphone|ipad|ipod|opera mini|mobile/i', $userAgent);
                
                $user = Auth::user();
                $userName = $user ? $user->name . " (" . ucfirst($user->role) . ")" : "Guest Visitor";

                PageAnalytic::create([
                    'tracking_id' => $trackingId,
                    'user_id' => $user?->id,
                    'user_name' => $userName,
                    'url' => $request->fullUrl(),
                    'path' => '/' . ltrim($request->path(), '/'),
                    'ip_address' => $request->ip(),
                    'user_agent' => substr($userAgent, 0, 250),
                    'device_type' => $isMobile ? 'mobile' : 'desktop',
                    'referrer' => substr($request->header('referer', ''), 0, 250),
                    'action_type' => 'page_view',
                    'target_details' => 'Visited page: ' . '/' . ltrim($request->path(), '/'),
                ]);
            } catch (\Exception $e) {
                // Silently ignore analytics logging errors
            }
        }

        return $response;
    }
}
