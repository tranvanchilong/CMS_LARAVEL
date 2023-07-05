<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Http\Request;
use App\Models\LMS\Scopes\ScopeDomain;

class FloatingBar extends Model implements TranslatableContract
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        FloatingBar::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    use Translatable;

    protected $table = 'lms_floating_bars';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public $translatedAttributes = ['title', 'description', 'btn_text'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function getDescriptionAttribute()
    {
        return getTranslateAttributeValue($this, 'description');
    }

    public function getBtnTextAttribute()
    {
        return getTranslateAttributeValue($this, 'btn_text');
    }

    public static function getFloatingBar(Request $request)
    {
        $testPreview = !empty($request->get('preview_floating_bar'));

        $time = time();

        $query = FloatingBar::query();

        $query->where(function ($query) use ($time) {
            $query->whereNull('start_at');
            $query->orWhere('start_at', '<', $time);
        });

        $query->where(function ($query) use ($time) {
            $query->whereNull('end_at');
            $query->orWhere('end_at', '>', $time);
        });

        if (!$testPreview) {
            $query->where('enable', true);
        }

        return $query->first();
    }
}
