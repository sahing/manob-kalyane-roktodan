<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonorStory;
use App\Models\DonorStoryLike;
use App\Models\DonorStoryComment;
use App\Models\SeoSetting;
use Illuminate\Support\Facades\Auth;

class StoryController extends Controller
{
    public function index()
    {
        $stories = DonorStory::with(['user', 'comments'])->where('status', 'approved')->latest()->paginate(9);

        $userId = Auth::id();
        $userIp = request()->ip();

        foreach ($stories as $s) {
            $s->user_has_liked = $s->isLikedByUser($userId, $userIp);
        }

        $seo = SeoSetting::getMetaForPage(
            'stories.index',
            'Inspiring Voluntary Blood Donor Stories — Bhagwangola',
            'Read real voluntary blood donation experiences and photos shared by donors across Bhagwangola & Murshidabad.'
        );

        return view('stories.index', compact('stories', 'seo'));
    }

    public function show($id)
    {
        $story = DonorStory::with(['user', 'comments.user'])->where('status', 'approved')->findOrFail($id);

        $userId = Auth::id();
        $userIp = request()->ip();
        $story->user_has_liked = $story->isLikedByUser($userId, $userIp);

        $relatedStories = DonorStory::where('status', 'approved')
            ->where('id', '!=', $story->id)
            ->latest()
            ->take(3)
            ->get();

        $seo = [
            'title' => $story->title . ' — Voluntary Donor Story',
            'description' => substr(strip_tags($story->experience), 0, 160),
            'keywords' => 'donor story, blood donation experience, ' . $story->blood_group . ' donor Bhagwangola',
            'og_image' => $story->photo_url ?: asset('images/og-default.jpg'),
            'canonical' => route('stories.show', $story->id),
        ];

        return view('stories.show', compact('story', 'relatedStories', 'seo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'donor_name' => 'required|string|max:255',
            'blood_group' => 'nullable|string|max:10',
            'title' => 'required|string|max:255',
            'experience' => 'required|string|max:3000',
            'location' => 'nullable|string|max:255',
            'photo_url' => 'nullable|url',
            'photo_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5000',
        ]);

        $photoUrl = $validated['photo_url'] ?? null;

        if ($request->hasFile('photo_file')) {
            $path = $request->file('photo_file')->store('donor_stories', 'public');
            $photoUrl = asset('storage/' . $path);
        }

        DonorStory::create([
            'user_id' => Auth::id(),
            'donor_name' => $validated['donor_name'],
            'blood_group' => $validated['blood_group'] ?? Auth::user()?->donorProfile?->blood_group,
            'title' => $validated['title'],
            'experience' => $validated['experience'],
            'location' => $validated['location'] ?? 'Bhagwangola',
            'photo_url' => $photoUrl,
            'status' => 'approved',
        ]);

        return back()->with('success', 'Thank you! Your donation experience and photo have been published to inspire others.');
    }

    public function toggleLike(Request $request, $id)
    {
        $story = DonorStory::findOrFail($id);
        $userId = Auth::id();
        $ip = $request->ip();

        $likeQuery = DonorStoryLike::where('story_id', $story->id);
        if ($userId) {
            $likeQuery->where('user_id', $userId);
        } else {
            $likeQuery->where('ip_address', $ip);
        }

        $existing = $likeQuery->first();

        if ($existing) {
            $existing->delete();
            $story->decrement('likes_count');
            $liked = false;
        } else {
            DonorStoryLike::create([
                'story_id' => $story->id,
                'user_id' => $userId,
                'ip_address' => $ip,
            ]);
            $story->increment('likes_count');
            $liked = true;
        }

        $freshCount = $story->fresh()->likes_count;

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'liked' => $liked,
                'likes_count' => $freshCount,
            ]);
        }

        return back();
    }

    public function trackShare(Request $request, $id)
    {
        $story = DonorStory::findOrFail($id);
        $story->increment('shares_count');

        return response()->json([
            'status' => 'success',
            'shares_count' => $story->fresh()->shares_count,
        ]);
    }

    public function storeComment(Request $request, $id)
    {
        $story = DonorStory::findOrFail($id);

        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        DonorStoryComment::create([
            'story_id' => $story->id,
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'comment' => $validated['comment'],
        ]);

        $story->increment('comments_count');

        return back()->with('success', 'Your comment has been posted.');
    }

    public function updateComment(Request $request, $commentId)
    {
        $comment = DonorStoryComment::findOrFail($commentId);

        if ($comment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $comment->update(['comment' => $validated['comment']]);

        return back()->with('success', 'Comment updated.');
    }

    public function deleteComment($commentId)
    {
        $comment = DonorStoryComment::findOrFail($commentId);

        if ($comment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $story = $comment->story;
        $comment->delete();
        if ($story && $story->comments_count > 0) {
            $story->decrement('comments_count');
        }

        return back()->with('success', 'Comment deleted.');
    }
}
