<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Attribute;
use DB;

class Discount extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['start_at', 'end_at'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

}
