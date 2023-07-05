<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class SupportConversation extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        SupportConversation::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_support_conversations';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public function sender()
    {
        return $this->belongsTo('App\Models\LMS\User', 'sender_id', 'id');
    }

    public function supporter()
    {
        return $this->belongsTo('App\Models\LMS\User', 'supporter_id', 'id');
    }

}
