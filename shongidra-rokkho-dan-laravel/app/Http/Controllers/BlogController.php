<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogPost;
use App\Models\SeoSetting;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::where('is_published', true)->orderBy('created_at', 'desc');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(9);
        $categories = BlogPost::select('category')->distinct()->pluck('category');

        $seo = SeoSetting::getMetaForPage(
            'blog.index',
            'Blood Donation Blog & Health Awareness — Bhagwangola',
            'Read articles, health tips, and eligibility guidelines on voluntary blood donation in Bhagwangola & Murshidabad.'
        );

        return view('blog.index', compact('posts', 'categories', 'seo'));
    }

    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $post->increment('views_count');

        $relatedPosts = BlogPost::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->where('category', $post->category)
            ->take(3)
            ->get();

        $seo = [
            'title' => $post->meta_title ?: ($post->title . ' — Manab Kalyane Rokto Dan Blog'),
            'description' => $post->meta_description ?: ($post->excerpt ?: substr(strip_tags($post->content), 0, 150)),
            'keywords' => $post->meta_keywords ?: 'blood donation, ' . $post->category . ', Bhagwangola blood article',
            'og_image' => $post->og_image ?: ($post->cover_image ?: asset('images/og-default.jpg')),
            'canonical' => url()->current(),
        ];

        return view('blog.show', compact('post', 'relatedPosts', 'seo'));
    }
}
