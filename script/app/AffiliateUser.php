<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\ScopeUserDomain;
use App\Models\Customer;

class AffiliateUser extends Model
{
    public function customer(){
    	return $this->belongsTo(Customer::class,'customer_id');
    }

    public function affiliate_payments()
    {
      return $this->hasMany(AffiliatePayment::class)->orderBy('created_at', 'desc')->paginate(10);
    }
}
