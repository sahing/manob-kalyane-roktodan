<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PortalMessage;
use App\Models\BloodRequest;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Fetch conversation thread messages by request_id or phone number.
     */
    public function fetchMessages(Request $request)
    {
        $requestId = $request->query('blood_request_id');
        $phone = $request->query('phone');

        $query = PortalMessage::query();

        if ($requestId) {
            $query->where('blood_request_id', $requestId);
        } elseif ($phone) {
            $cleanedPhone = preg_replace('/[^0-9]/', '', $phone);
            $query->where(function($q) use ($phone, $cleanedPhone) {
                $q->where('sender_phone', 'LIKE', "%{$cleanedPhone}%")
                  ->orWhere('receiver_phone', 'LIKE', "%{$cleanedPhone}%")
                  ->orWhere('sender_phone', $phone)
                  ->orWhere('receiver_phone', $phone);
            });
        } else {
            return response()->json(['messages' => []]);
        }

        $messages = $query->orderBy('created_at', 'asc')->get()->map(function($msg) {
            return [
                'id' => $msg->id,
                'sender_id' => $msg->sender_id,
                'sender_name' => $msg->sender_name,
                'sender_phone' => $msg->sender_phone,
                'receiver_name' => $msg->receiver_name,
                'receiver_phone' => $msg->receiver_phone,
                'message' => $msg->message,
                'time_ago' => $msg->created_at->diffForHumans(),
                'created_at_formatted' => $msg->created_at->format('d M h:i A'),
                'is_mine' => Auth::check() ? ($msg->sender_id == Auth::id()) : false,
            ];
        });

        return response()->json(['messages' => $messages]);
    }

    /**
     * Store and send a portal message.
     */
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'blood_request_id' => 'nullable|exists:blood_requests,id',
            'receiver_phone' => 'nullable|string|max:20',
            'receiver_name' => 'nullable|string|max:255',
            'sender_name' => 'nullable|string|max:255',
            'sender_phone' => 'nullable|string|max:20',
        ]);

        $senderUser = Auth::user();
        $senderName = $senderUser ? $senderUser->name : ($validated['sender_name'] ?? 'Visitor Requester');
        $senderPhone = $senderUser ? $senderUser->phone : ($validated['sender_phone'] ?? 'Guest');

        $receiverId = null;
        $receiverName = $validated['receiver_name'] ?? null;
        $receiverPhone = $validated['receiver_phone'] ?? null;

        // If message relates to a blood request
        if (!empty($validated['blood_request_id'])) {
            $bloodReq = BloodRequest::find($validated['blood_request_id']);
            if ($bloodReq) {
                $receiverName = $receiverName ?: $bloodReq->patient_name;
                $receiverPhone = $receiverPhone ?: $bloodReq->contact_number;
                if ($bloodReq->user_id) {
                    $receiverId = $bloodReq->user_id;
                }
            }
        }

        // Try resolving receiver user by phone if not found
        if (!$receiverId && $receiverPhone) {
            $cleaned = preg_replace('/[^0-9]/', '', $receiverPhone);
            $foundUser = User::where('phone', 'LIKE', "%{$cleaned}%")->first();
            if ($foundUser) {
                $receiverId = $foundUser->id;
                $receiverName = $receiverName ?: $foundUser->name;
            }
        }

        $msg = PortalMessage::create([
            'sender_id' => $senderUser ? $senderUser->id : null,
            'receiver_id' => $receiverId,
            'sender_name' => $senderName,
            'sender_phone' => $senderPhone,
            'receiver_name' => $receiverName ?: 'Blood Donor / Requester',
            'receiver_phone' => $receiverPhone ?: 'N/A',
            'blood_request_id' => $validated['blood_request_id'] ?? null,
            'message' => $validated['message'],
        ]);

        // Send in-portal notification to receiver if registered user
        if ($receiverId) {
            UserNotification::create([
                'user_id' => $receiverId,
                'title' => '💬 New Message from ' . $senderName,
                'message' => '"' . substr($validated['message'], 0, 80) . '..."',
                'type' => 'chat_message',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully.',
            'data' => [
                'id' => $msg->id,
                'sender_name' => $msg->sender_name,
                'sender_phone' => $msg->sender_phone,
                'message' => $msg->message,
                'time_ago' => $msg->created_at->diffForHumans(),
                'created_at_formatted' => $msg->created_at->format('d M h:i A'),
                'is_mine' => true,
            ]
        ]);
    }
}
