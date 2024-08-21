<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id',
        'Image',
        'video',
        'description',
        'likes',
        'num_comments',
        'created_at',
    ];
    protected $hidden=[
        'updated_at'
    ];
    public function comment(){
        return $this->hasmany(Comment::class);
    }
    public function like(){
        return $this->hasone(Like::class);
    }
    public function history(){
        return $this->hasmany(History::class);
    }
}
