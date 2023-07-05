<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactLists extends Model
{
    use HasFactory;

    protected $table = 'contact_list';

    protected $fillable = [
        'user_id',
        'url',
        'image',
        'serial_number',
        'status'
    ];
}
