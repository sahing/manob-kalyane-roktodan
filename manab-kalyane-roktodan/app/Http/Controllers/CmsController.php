<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteMenuItem;
use App\Models\HomepageSection;
use App\Models\CmsPage;
use App\Models\MediaAsset;
use App\Models\SiteContent;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CmsController extends Controller
{
    // LOGO & BRANDING MANAGEMENT
    public function updateBranding(Request $request)
    {
        $request->validate([
            'site_title' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'dark_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'favicon' => 'nullable|image|mimes:png,ico,svg|max:1024',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('branding', 'public');
            SiteContent::setValue('site_logo', Storage::url($path));
        }

        if ($request->hasFile('dark_logo')) {
            $path = $request->file('dark_logo')->store('branding', 'public');
            SiteContent::setValue('site_dark_logo', Storage::url($path));
        }

        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('branding', 'public');
            SiteContent::setValue('site_favicon', Storage::url($path));
        }

        if ($request->filled('site_title')) {
            SiteContent::setValue('organization_name', $request->site_title);
        }

        if ($request->filled('tagline')) {
            SiteContent::setValue('site_tagline', $request->tagline);
        }

        return back()->with('success', 'Website branding, logos & favicon updated successfully!');
    }

    public function removeLogo(Request $request, $type)
    {
        if (in_array($type, ['site_logo', 'site_dark_logo', 'site_favicon'])) {
            SiteContent::setValue($type, null);
        }

        return back()->with('success', 'Logo asset removed successfully!');
    }

    // MENU MANAGER
    public function storeMenuItem(Request $request)
    {
        $validated = $request->validate([
            'id' => 'nullable|exists:site_menu_items,id',
            'location' => 'required|string|in:header,footer',
            'parent_id' => 'nullable|exists:site_menu_items,id',
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:500',
            'target' => 'required|in:_self,_blank',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        SiteMenuItem::updateOrCreate(
            ['id' => $request->id],
            [
                'location' => $validated['location'],
                'parent_id' => $validated['parent_id'] ?? null,
                'title' => $validated['title'],
                'url' => $validated['url'],
                'target' => $validated['target'],
                'sort_order' => $validated['sort_order'] ?? 0,
                'is_active' => $request->boolean('is_active', true),
            ]
        );

        return back()->with('success', "Menu item '{$validated['title']}' saved successfully!");
    }

    public function deleteMenuItem($id)
    {
        $item = SiteMenuItem::findOrFail($id);
        $item->delete();

        return back()->with('success', 'Menu item deleted successfully!');
    }

    // HOMEPAGE BUILDER & SECTIONS
    public function storeSection(Request $request)
    {
        $validated = $request->validate([
            'id' => 'nullable|exists:homepage_sections,id',
            'key' => 'required|string|max:100|alpha_dash|unique:homepage_sections,key,' . ($request->id ?? 'NULL'),
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:500',
            'secondary_button_text' => 'nullable|string|max:100',
            'secondary_button_url' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:5120',
            'image_url' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer',
            'is_visible' => 'nullable|boolean',
        ]);

        $imageUrl = $validated['image_url'] ?? null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('homepage', 'public');
            $imageUrl = Storage::url($path);
        }

        HomepageSection::updateOrCreate(
            ['id' => $request->id],
            [
                'key' => strtolower($validated['key']),
                'title' => $validated['title'],
                'subtitle' => $validated['subtitle'] ?? null,
                'content' => $validated['content'] ?? null,
                'button_text' => $validated['button_text'] ?? null,
                'button_url' => $validated['button_url'] ?? null,
                'secondary_button_text' => $validated['secondary_button_text'] ?? null,
                'secondary_button_url' => $validated['secondary_button_url'] ?? null,
                'image_url' => $imageUrl,
                'sort_order' => $validated['sort_order'] ?? 0,
                'is_visible' => $request->boolean('is_visible', true),
                'is_custom' => $request->boolean('is_custom', false),
            ]
        );

        return back()->with('success', "Homepage section '{$validated['title']}' saved successfully!");
    }

    public function deleteSection($id)
    {
        $sec = HomepageSection::findOrFail($id);
        $sec->delete();

        return back()->with('success', 'Homepage section removed!');
    }

    // DYNAMIC CMS PAGES
    public function storePage(Request $request)
    {
        $validated = $request->validate([
            'id' => 'nullable|exists:cms_pages,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|alpha_dash|unique:cms_pages,slug,' . ($request->id ?? 'NULL'),
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|max:4096',
        ]);

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('pages', 'public');
            $imagePath = Storage::url($path);
        }

        $page = CmsPage::updateOrCreate(
            ['id' => $request->id],
            [
                'title' => $validated['title'],
                'slug' => Str::slug($validated['slug']),
                'content' => $validated['content'],
                'status' => $validated['status'],
                'meta_title' => $validated['meta_title'] ?? $validated['title'],
                'meta_description' => $validated['meta_description'] ?? null,
                'featured_image' => $imagePath ?? ($request->id ? CmsPage::find($request->id)->featured_image : null),
            ]
        );

        return back()->with('success', "Page '{$page->title}' saved successfully!");
    }

    public function deletePage($id)
    {
        $page = CmsPage::findOrFail($id);
        $page->delete();

        return back()->with('success', 'CMS page deleted!');
    }

    public function showPage($slug)
    {
        $page = CmsPage::where('slug', $slug)->where('status', 'published')->firstOrFail();
        return view('pages.show', compact('page'));
    }

    // MEDIA LIBRARY MANAGEMENT
    public function storeMedia(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,webp,svg,gif,pdf|max:10240',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $path = $file->store('media', 'public');
        $url = Storage::url($path);

        $media = MediaAsset::create([
            'filename' => $file->getClientOriginalName(),
            'url' => $url,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'alt_text' => $request->alt_text,
        ]);

        return back()->with('success', "Media file '{$media->filename}' uploaded to library successfully!");
    }

    public function deleteMedia($id)
    {
        $media = MediaAsset::findOrFail($id);
        $media->delete();

        return back()->with('success', 'Media file removed from library!');
    }

    // CUSTOM CSS & CUSTOM JS MANAGEMENT
    public function updateCustomCode(Request $request)
    {
        $validated = $request->validate([
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
            'enable_custom_css' => 'nullable|boolean',
        ]);

        SiteContent::setValue('custom_css', $validated['custom_css'] ?? '');
        SiteContent::setValue('custom_js', $validated['custom_js'] ?? '');
        SiteContent::setValue('enable_custom_css', $request->boolean('enable_custom_css') ? '1' : '0');

        return back()->with('success', 'Custom CSS & Scripting settings updated successfully!');
    }
}
