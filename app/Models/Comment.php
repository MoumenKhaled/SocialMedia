<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',     
        'user_id',
        'user_name',
        'user_image',
        'description',
    ];
    protected $hidden=[
        'created_at',
        'updated_at'
    ];
}
