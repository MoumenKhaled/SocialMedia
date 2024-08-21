<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Owner;
use App\Models\Profile;
use App\Models\Friend;
use App\Models\Imagepost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class PostController extends Controller
{
    public function addpost (Request $request){
        $data=$request->validate ([
            'video',
            'Image',
            'description'
        ]);

        $post=new Post();
        $id=auth()->user()->id;
        $post->user_id=$id;
        $post->description=isset ($request->description) ? $request->description : $post->description;
        ///////////////video///////////////
    if ($request->hasFile('video'))
    {
      $allowedFileExtension=['gif','mov','mp4','mpg','mkv'];
      $file=$request->file('video');
      $extension=$file->getClientOriginalExtension();
      $check=in_array($extension,$allowedFileExtension);
      if ($check){
        $videoname=rand() . '.'. $file->getClientOriginalExtension();
        $file->move(public_path('uploads/posts/video'),$videoname);
        $path="public/uploads/posts/video/$videoname";
        $post->video=$path;
      }
      else {
        return response()->json([
            'status'=>false,
            'message'=>'invalid video format'
        ],500);
      }
    }
        //Image
        if($request->hasFile('Image'))
        {
            $allowedFileExtension=['jpg','jpeg','png','bmp'];
            $file=$request->file('Image');
            $errors=[];
            $extension=$file->getClientOriginalExtension();
            $check=in_array($extension,$allowedFileExtension);
            if($check)
        {
        $file=$request->file('Image');
        $imagename=rand() . '.'. $file->getClientOriginalExtension();
        $file->move(public_path('uploads/posts/images'),$imagename);
        $path="public/uploads/posts/images/$imagename";
        $post->Image=$path;
        }
        else
        {
            return response()->json([
                'status'=>false,
                'message'=>'invalid image format'
            ],500);
        }
    }
    $post->save();
    // response
    return response()->json([
        'status'=>true,
        'message'=>'post added successfully',
        'data'=>$post
    ],200);
    
}
    /////////////edit post//////////////////////

    public function editpost(Request $request,$id){
        $data=$request->validate ([
            'Image',
            'video',
            'description'
        ]);
        $post=Post::find($id);
        $owner_id=auth()->user()->id;
        $idDb = Post::where('id', '=', $id)->first();
        if($id < 1 || $idDb === null || $post['user_id'!=$owner_id])
        {
            return response()->json([
                'status'=>false,
                'message'=>'post dose not exist'
            ],500);
        }
        
        $post->description=isset ($request->description) ? $request->description : $post->description;
        //Image
        if($request->hasFile('Image'))
        {
            $allowedFileExtension=['jpg','jpeg','png','bmp'];
            $file=$request->file('Image');
            $errors=[];
            $extension=$file->getClientOriginalExtension();
            $check=in_array($extension,$allowedFileExtension);
            if($check)
        {
        $file=$request->file('Image');
        $imagename=rand() . '.'. $file->getClientOriginalExtension();
        $file->move(public_path('uploads/posts/images'),$imagename);
        $path="public/uploads/posts/images/$imagename";
        $post->Image=$path;
        }
        else
        {
            return response()->json([
                'status'=>false,
                'message'=>'invalid image format'
            ],500);
        }
    }
    ///////////////video///////////////
    if ($request->hasFile('video'))
    {
        $allowedFileExtension=['gif','mov','mp4','mpg','mkv'];
        $file=$request->file('video');
        $extension=$file->getClientOriginalExtension();
        $check=in_array($extension,$allowedFileExtension);
        if ($check){
          $videoname=rand() . '.'. $file->getClientOriginalExtension();
          $file->move(public_path('uploads/posts/video'),$videoname);
          $path="public/uploads/posts/video/$videoname";
          $post->video=$path;
        }
      else {
        return response()->json([
            'status'=>false,
            'message'=>'invalid video format'
        ],500);
      }
    }
    $post->save();
    // response
    return response()->json([
        'status'=>true,
        'message'=>'post edited successfully',
        'data'=>$post
    ],200);
}
    
    public function deletepost($id){
        $owner_id=auth()->user()->id;
        if(Post::where([
            ['id','=',$id],
            ['user_id','=',$owner_id],
        ])->exists()){
            $post=Post::find($id);
            $post->delete();
            return response()->json([
                'status'=>true,
                'message'=>"you delete this post",
            ]);
    }
    return response()->json([
        'status'=>false,
        'message'=>'you can not delete this post '
    ]);
}

public function showMyposts (){
    $owner_id=auth()->user()->id;
    $post=Post::where('user_id','=',$owner_id)->get();
    return response ()->json([
        'Myposts'=>$post->sortBy('id')->reverse()->values()
    ]);
}

 public function homepage (){
    $owner_id=auth()->user()->id;  
    $friend =Friend::query()->where('user_id','=',$owner_id)->first();
    if ($friend == null){
        return response()->json([
            'status'=>false,
            'message'=>'you did not have a friend'
        ]);
    }
    $friends =Friend::query()->where('user_id','=',$owner_id)->get();
    foreach($friends as $friends1){
        $array[]=$friends1['friend_id'];
    }
    foreach($array as $posts){
        $post[]=[
            'Post'=>Post::query()->where('user_id','=',$posts)->get()
        ];
   
    }
    
    $data= Arr::flatten($post);
    $res=collect($data)->sortByDesc('id')->values();
    if ($res->first() == null ){
        return response()->json([
            null
        ]);
    }
    else {
    foreach ($res as $rs){
        $id=$rs['user_id'];
        $array3[]=[
            'name'=>Owner::find($id)['name'],
            'Image'=>Profile::find($id)['Image'],
            'Post'=>$rs
        ];
    }
    return response()->json([
        'status'=>true,
        'data'=>$array3
        
    ]);
}
 }


}


    

