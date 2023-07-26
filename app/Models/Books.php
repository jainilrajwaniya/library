<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    protected $table = 'books';
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'published_at',
        'copies',
    ];
}
