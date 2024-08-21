<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Save;
use App\Models\Owner;
use App\Models\Post;
use App\Models\Profile;
use Illuminate\Http\Request;

class SaveController extends Controller
{
    public function save ($id){
        $user_id=auth()->user()->id;
        $iddb= Post::where('id','=',$id)->first();
        if ($iddb==null){
            return response()->json([
                'status'=>false,
                'message'=>'this post dose not exist'
            ]);
        }
        if (Save::where([['post_id','=',$id] , ['user_id','=',$user_id]])->exists()){
            return response()->json([
                'status'=>false,
                'message'=>'already saved',
            ]);
        }
        $post=Post::find($id);
        $post_user=$post['user_id'];
        $name=Owner::find($post_user)['name'];
        $image=Profile::find($post_user)['Image'];
        $save= new Save();
        $save->user_id = $user_id;
        $save->post_id = $id;
        $save ->save();
        return response()->json([
            'status'=>true,
            'message'=>'successfully',
            'name'=>$name,
            'image'=>$image,
            'save_data'=>$save
        ]);
    }
    public function deletefromSave($post_id){
        $owner_id=auth()->user()->id;
        $idDb = Save::where('post_id', '=', $post_id)->first();
        if($post_id < 1 || $idDb === null )
        {
            return response()->json([
                'status'=>false,
                'message'=>'this post does not exist'
            ],500);
        }
          if (Save::where([
              ['post_id','=',$post_id],
              ['user_id','=',$owner_id]
          ])->exists()){
            $save=Save::where('post_id','=',$post_id);
            $save->delete();
            return response()->json([
                'status'=>true,
                'message'=>'you delete this post from History'
            ],500);
        }
        return response()->json([
            'status'=>false,
            'message'=>'you can not delete this post'
        ],500);

    }
    public function savepage(){
        $user_id = auth()->user()->id;
        $save = Save::where('user_id','=',$user_id)->first();
        if ($save == null){
            return response ()->json([
                'status'=>false,
                'message'=>'is Empty',
            ]);
        }
        $saves= Save::where('user_id','=',$user_id)->get();
        foreach ($saves as $posts){
            $post[]=$posts['post_id'];
        }
        foreach ($post as $a){
            $owner=Post::find($a)['user_id'];
            $array[]=[
                'name'=>Owner::find($owner)['name'],
                'image'=>Profile::find($owner)['Image'],
                'post'=>Post::find($a),
            ];
        }
        return response ()->json([
            'status'=>true,
            'message'=>'successfully',
            'History'=>$array
        ]);
    }
}
