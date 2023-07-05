<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class Group extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        Group::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    public $timestamps = false;
    protected $table = 'lms_groups';

    protected $guarded = ['id'];

    public function groupUsers()
    {
        return $this->hasMany('App\Models\LMS\GroupUser', 'group_id', 'id');
    }

    public function users()
    {
        return $this->hasMany('App\Models\LMS\GroupUser', 'id', 'group_id');
    }

    public function groupRegistrationPackage()
    {
        return $this->hasOne('App\Models\LMS\GroupRegistrationPackage', 'group_id', 'id');
    }

}
