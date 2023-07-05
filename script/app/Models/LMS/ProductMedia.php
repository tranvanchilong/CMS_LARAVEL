<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class ProductMedia extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        ProductMedia::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_product_media';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    static $types = ['thumbnail', 'image', 'video'];
    static $thumbnail = 'thumbnail';
    static $image = 'image';
    static $video = 'video';

    public function product()
    {
        return $this->belongsTo('App\Models\LMS\Product', 'product_id', 'id');
    }
}
