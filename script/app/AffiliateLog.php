<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\ScopeUserDomain;

class AffiliateLog extends Model
{
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    public function order_item()
    {
        return $this->belongsTo('App\Orderitem', 'order_detail_id', 'id');
    }
}
