<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Friend;
use App\Models\Owner;
use App\Models\Profile;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function addfriend(Request $request,$id){

    $check=$id;
    $user_id=auth()->user()->id;
    if(friend::where( [ ['friend_id','=',$check],['user_id','=',$user_id]])->exists()){
        $bool=false;
        $frienddelete=Friend::where([[ 'user_id' , '=', $user_id],['friend_id','=',$check]]);
        $frienddelete->delete();
        return response()->json([
            'status'=>true,
            'status_friend'=>$bool,
            'message'=>'you deleted your freind'
        ]);
    }
    $dataprofile=Profile::find($id)['Image'];
    $datauser=Owner::find($id)['name'];
    $friend=new Friend();
    $friend->friend_name = $datauser;
    $friend->friend_Image =$dataprofile;
    $friend->user_id = $user_id;
    $friend->friend_id = $id;
    $bool=true;
    $friend->save();

    return response()->json([
        'status'=>true,
        'message'=>'friend added successfully',
        'stauts_friend'=>$bool,
        'data'=>$friend
    ]);
}
    public function showlistfriend(){
        $owner_id=auth()->user()->id;
        $friend=Friend::query()->where('user_id','=',$owner_id)->first();
        if ($friend==null){
            return response()->json([
                'message'=>'You did not have a friend',
            ]); 
        }
        $friends=Friend::query()->where('user_id','=',$owner_id)->get();
        return response()->json([
            'message'=>'successfully',
            'friend_data'=>$friends
        ]);
    }
}
