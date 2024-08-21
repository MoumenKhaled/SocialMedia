<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\HistoryPosts;
use App\Models\Post;
use App\Models\Owner;
use App\Models\Profile;
use Illuminate\Http\Request;

class PostHistoryController extends Controller
{
   public function historypost($id){
    $owner_id=auth()->user()->id;
    if(HistoryPosts::where([['post_id','=',$id] , ['user_id','=',$owner_id]])->exists()){
        return response()->json([
            'already exists'
        ]);
    }
    $owner_id=auth()->user()->id;
    $history=new HistoryPosts();
    $history->user_id=$owner_id;
    $history->post_id=(int)$id;
    $post=Post::where('id','=',$id)->get();
    $data=Post::find($id)['user_id'];
    $name=Owner::find($data)['name'];
    $image=Profile::find($data)['Image'];
    $history->save();
    return response()->json([
        'status'=>true,
        'messege'=>'successfully',
        'name'=>$name,
        'image'=>$image,
        'post'=>$post,
        'history_data'=>$history
    ]);
   }
   public function deleteposthis($id){
    $owner_id=auth()->user()->id;
    if (HistoryPosts::where([
        ['user_id','=',$owner_id],
        ['post_id','=',$id]
    ])->exists()){
        $post=HistoryPosts::where('post_id','=',$id);
        $post->delete();
        return response()->json([
            'status'=>true,
            'messege'=>'successfully'
        ]);
    }
    return response()->json([
        'status'=>false,
        'messege'=>'you can not delete this'
    ]);
   }

   public function Historypostlist(){
    $owner_id=auth()->user()->id;
    $historynull=HistoryPosts::where('user_id','=',$owner_id)->first();
    if ($historynull == null){
        return response()->json([
            null
        ]);
    }
    $history=HistoryPosts::where('user_id','=',$owner_id)->get();
    foreach($history as $post){
        $array1[]=$post['post_id'];
    }
    foreach ($array1 as $posts){
        $owner=Post::find($posts)['user_id'];
        $array2[]=[
            'name'=>Owner::find($owner)['name'],
            'image'=>Profile::find($owner)['Image'],
            'post'=>Post::find($posts)
        ];
    }
    $res=collect($array2)->values();
    return response()->json([
        'stauts'=>true,
        'data'=>$array2,
        'history'=>$history
    ]);
}
}
