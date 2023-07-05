<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    //protected $table="sub_domains";
    protected $fillable = [
        'domain',
        'userplan_id',
        'full_domain',
        'status',
        'type',
        'will_expire',
        'data',
        'is_trial',
        'user_id',
        'template_id',
        'template_domain_id',
        'shop_type',
        'featured',
        'is_default',
        'thumbnail',
        'serial_number',
        'template_enable',
        'menu_type',
        'top_bar_contact_status',
        'float_contact_status',
        'is_maintainance_mode',
        'maintainance_mode_password',
        'permalinks'
    ];

    protected $casts = [
        'permalinks' => 'array',
    ];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function theme()
    {
    	return $this->belongsTo('App\Models\Template','template_id','id');
    }

    public function orderlog()
    {
        return $this->hasMany('App\Models\Planlog');
    }

    public function orderwithplan()
    {
        return $this->belongsTo('App\Models\Userplan','userplan_id')->with('plan');
    }
}
