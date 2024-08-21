<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;  
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticateContract;


class Owner extends Model implements AuthenticateContract
{
    use HasApiTokens, HasFactory ,Authenticatable;
    public $timestamps = false ;
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    protected $hidden=[
        'created_at',
        'updated_at'
    ];
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    public function post(){
        return $this->hasMany(Post::class);
    }
    public function like(){
        return $this->hasmany(Like::class);
    }
    public function comment(){
        return $this->hasMany(Comment::class);
    }
    public function friends(){
        return $this->hasmany(Friend::class);
    }
    public function history(){
        return $this ->hasmany(History::class);
    }
}
