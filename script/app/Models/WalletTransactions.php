<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransactions extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_id',
        'target_id',
        'transaction_type',
        'amount',
        'balance',
        'status',
        'blockchain_amount',
        'blockchain_result',
        'blockchain_transaction',
        'amblockchain_address_fromount'

    ];
}
