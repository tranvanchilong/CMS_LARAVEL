<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class Permission extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        Permission::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    public $timestamps = false;
    protected $table = 'lms_permissions';

    protected $guarded = ['id'];

    /**
     * Get the sections for the permission.
     */
    public function sections()
    {
        return $this->belongsTo('App\Models\LMS\Section', 'section_id');
    }
}
