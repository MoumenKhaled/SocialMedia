<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Owner;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
   public function like($post_id){
       $owner_id=auth()->user()->id;
       $like =new Like();
       if (Like::where([
        ['user_id','=',$owner_id],
        ['post_id','=',$post_id],
    ])->exists()){
        $like=Like::where([[ 'user_id' , '=', $owner_id],['post_id','=',$post_id]]);
        $like->delete();
        $post=Post::find($post_id);
        $count=$post ['likes'];
        $post->update(['likes'=>$count-1]);
        $post->save();
        return response ()->json([
            'status'=>true,
            'message'=>'Dislike'
        ]);
    }
    $like=new Like();
    $like->user_id=$owner_id;
    $like->post_id=$post_id;
    $like->save();
    $post=Post::find($post_id);
    $count=$post ['likes'];
    $post->update(['likes'=>$count+1]);
    $post->save();
    return response ()->json([
        'status'=>true,
        'message'=>'you liked this post'
    ]);
   }
}
