<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Owner;
use App\Models\Post;
use App\Models\Profile;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function addcomment(Request $request,$id){
        $owner_id=auth()->user()->id;
        $idDb = Post::where('id', '=', $id)->first();
        if($id < 1 || $idDb === null )
        {
            return response()->json([
                'status'=>false,
                'message'=>'post dose not exist'
            ],500);
        }
        $data=$request->validate([
            'description'=>'required'
        ]);
        $post=Post::find($id);
        $count=$post['num_comments'];
        $post->update(['num_comments'=>$count+1]);
        $post->save();
        $comment=new Comment();
        $comment->user_id=$owner_id;
        $comment->user_name=Owner::find($owner_id)['name'];
        $comment->user_image=Profile::find($owner_id)['Image'];
        $comment->post_id=$id;
        $comment->description=$request->description;
        $comment->save();
        return response()->json([
            'status'=>true,
            'message'=>'you add comment',
            'message'=>$comment
        ]);
    }
    public function editcomment (Request $request,$id){
        $owner_id=auth()->user()->id;
        $idDb = Post::where('id', '=', $id)->first();
        $comment=new Comment();
        if($id < 1 || $idDb === null || $comment['user_id'!=$owner_id])
        {
            return response()->json([
                'status'=>false,
                'message'=>'you can not edit this comment'
            ],500);
        }
        $comment=Comment::find($id);
        $comment->description= isset($request->description)?$request->description:$comment->description;
        $comment->save(); 
        return response()->json([
            'status'=>false,
            'message'=>'you edit this comment',
            'data'=>$comment
        ],200);
    }

    public function deletecomment (Request $request,$post_id,$comment_id){
        $owner_id=auth()->user()->id;
        $idDb = Post::where('id', '=', $post_id)->first();
        if($post_id < 1 || $idDb === null )
        {
            return response()->json([
                'status'=>false,
                'message'=>'this post does not exist'
            ],500);
        }
          if (Comment::where([
              ['id','=',$comment_id],
              ['post_id','=',$post_id],
              ['user_id','=',$owner_id]
          ])->exists()){
            $post=Post::find($post_id);
            $count=$post['num_comments'];
            $post->update(['num_comments'=>$count-1]);
            $post->save();
            $comment=Comment::find($comment_id);
            $comment->delete();
            return response()->json([
                'status'=>true,
                'message'=>'you delete this comment'
            ],500);
        }
        return response()->json([
            'status'=>false,
            'message'=>'you can not delete this comment'
        ],500);
       
}
 public function comments($id){
    $post=Comment::where('post_id','=',$id)->get();
    return response()->json([
        'status'=>true,
        'message'=>'successfully',
        'comments'=>$post
    ]);
 }
}