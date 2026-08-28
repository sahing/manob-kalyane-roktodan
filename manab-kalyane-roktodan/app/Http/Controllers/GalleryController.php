<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $query = Gallery::query();

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        $items = $query->latest()->paginate(18);
        $categories = Gallery::select('category')->distinct()->pluck('category');

        return view('gallery', compact('items', 'categories', 'category'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'image_url' => 'nullable|url',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5000',
            'description' => 'nullable|string|max:500',
            'event_date' => 'nullable|date',
        ]);

        $imageUrl = $validated['image_url'] ?? null;

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('gallery', 'public');
            $imageUrl = asset('storage/' . $path);
        }

        if (!$imageUrl) {
            return back()->with('error', 'Please provide an image URL or upload an image file.');
        }

        Gallery::create([
            'title' => $validated['title'],
            'image_url' => $imageUrl,
            'category' => strtolower($validated['category']),
            'description' => $validated['description'] ?? null,
            'event_date' => $validated['event_date'] ?? now(),
        ]);

        return back()->with('success', 'New photo card added to the gallery!');
    }
}
