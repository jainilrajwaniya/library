<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkouts extends Model
{
    protected $table = 'checkouts';
    protected $fillable = [
        'user_id',
        'book_id',
        'checkout_date',
        'return_date',
    ];
}
