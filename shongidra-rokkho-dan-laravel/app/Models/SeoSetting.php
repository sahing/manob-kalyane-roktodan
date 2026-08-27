<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_key',
        'page_name',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'canonical_url',
    ];

    public static function getMetaForPage($pageKey, $defaultTitle = null, $defaultDesc = null)
    {
        $setting = self::where('page_key', $pageKey)->first();

        return [
            'title' => $setting->meta_title ?? $defaultTitle ?? 'Manab Kalyane Rokto Dan — Bhagwangola Voluntary Blood Network',
            'description' => $setting->meta_description ?? $defaultDesc ?? 'Voluntary blood donor platform in Bhagwangola, Murshidabad. Search verified blood donors, post emergency requests, and save lives 24/7.',
            'keywords' => $setting->meta_keywords ?? 'blood donation, Bhagwangola blood donor, Murshidabad voluntary blood, emergency blood request, blood bank Bhagwangola',
            'og_image' => $setting->og_image ?? asset('images/og-default.jpg'),
            'canonical' => $setting->canonical_url ?? url()->current(),
        ];
    }
}
