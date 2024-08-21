<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'id',
        'Image',
        'Image_cover',
        'lived',
        'gender',
        'birthdate',
        'Job',
        'studing',
        'bio',
    ];
    protected $hidden=[
        'user_id',
        'created_at',
        'updated_at'
    ];

  

}