<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PageAnalytic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AnalyticsController extends Controller
{
    public function logClick(Request $request)
    {
        $validated = $request->validate([
            'action_type' => 'required|string|max:100',
            'target_details' => 'required|string|max:1000',
            'path' => 'nullable|string|max:255',
        ]);

        $trackingId = $request->cookie('mkrd_visitor_id') ?: session('mkrd_visitor_id');
        if (!$trackingId) {
            $trackingId = 'VT-' . strtoupper(Str::random(8));
            session(['mkrd_visitor_id' => $trackingId]);
        }

        $user = Auth::user();
        $userName = $user ? $user->name . " (" . ucfirst($user->role) . ")" : "Guest Visitor";
        $userAgent = $request->userAgent() ?? '';
        $isMobile = preg_match('/(android|bb\d+|meego).+mobile|avail|blackberry|iemobile|iphone|ipad|ipod|opera mini|mobile/i', $userAgent);

        PageAnalytic::create([
            'tracking_id' => $trackingId,
            'user_id' => $user?->id,
            'user_name' => $userName,
            'url' => $request->fullUrl(),
            'path' => $validated['path'] ?? ('/' . ltrim($request->path(), '/')),
            'ip_address' => $request->ip(),
            'user_agent' => substr($userAgent, 0, 250),
            'device_type' => $isMobile ? 'mobile' : 'desktop',
            'referrer' => substr($request->header('referer', ''), 0, 250),
            'action_type' => $validated['action_type'],
            'target_details' => $validated['target_details'],
        ]);

        return response()->json(['status' => 'logged', 'tracking_id' => $trackingId]);
    }
}
