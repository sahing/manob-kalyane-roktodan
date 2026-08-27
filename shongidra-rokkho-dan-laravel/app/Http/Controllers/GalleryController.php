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
}
