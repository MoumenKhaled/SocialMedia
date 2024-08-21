<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id',
        'name',
    ];
    protected $hidden=[
        'created_at',
        'updated_at'
    ];
}
