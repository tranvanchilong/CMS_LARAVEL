<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\LMS\Scopes\ScopeDomain;

class ProductCategory extends Model implements TranslatableContract
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        ProductCategory::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    use Translatable;

    protected $table = 'lms_product_categories';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public $translatedAttributes = ['title'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }


    public function category()
    {
        return $this->belongsTo('App\Models\LMS\ProductCategory', 'parent_id', 'id');
    }

    public function subCategories()
    {
        return $this->hasMany($this, 'parent_id', 'id')->orderBy('order', 'asc');
    }

    public function filters()
    {
        return $this->hasMany('App\Models\LMS\ProductFilter', 'category_id', 'id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\LMS\Product', 'category_id', 'id');
    }

    public function getUrl()
    {
        return '/products?category_id=' . $this->id;
    }

    public function getSelfAndChideProductsCount($productType = null)
    {
        $ids = [$this->id];
        $subCategoriesIds = $this->subCategories->pluck('id')->toArray();
        $ids = array_merge($ids, $subCategoriesIds);

        $query = Product::whereIn('category_id', $ids);

        if (!empty($productType)) {
            $query->where('type', $productType);
        }

        return $query->count();
    }
}
