<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\ScopeUserDomain;
use App\Models\Customer;

class AffiliateWithdrawRequest extends Model
{
    public function customer(){
    	return $this->belongsTo(Customer::class,'customer_id');
    }
}
