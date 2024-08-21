<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use App\Models\Profile;
use App\Models\Friend;
use App\Models\Post;
use App\Models\Save;
use App\Models\Comment;
use App\Models\HistoryPosts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class OwnerController extends Controller
{
    //register 
    public function register (Request $request){
        $validator=$request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required|confirmed'
        ]);

        if (auth()->attempt($validator) || Owner::where('email','=',$request->email)->first()){
            return response()->json([
                'status'=>false,
            'message'=>'The email has already been taken'
            ]);    
        }
        
        $user=new Owner();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=bcrypt($request->password);
       
        //save in database
         $user->save();
         //make token
         $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);
        //VALIDATE DATA
        if(!auth()->attempt($loginData))
        {
            return response()->json([
                'status'=>false,
                'message'=>'invalid data'
            ],500);
        }
        //MAKE TOKEN
        $token=auth()->user()->createToken('authToken')->accessToken;
        //response
        return response()->json([
            'message'=>'register successfully',
            'status'=>true,
            'data'=>$user,
            'token'=>$token
        ],200);
    }
    // login 
        public function login(Request $request)
        {
            //VALIDATION
            $loginData = $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);
            //VALIDATE DATA
            if(!auth()->attempt($loginData))
            {
                return response()->json([
                    'status'=>false,
                    'message'=>'invalid data'
                ],500);
            }
            //MAKE TOKEN
            $token=auth()->user()->createToken('authToken')->accessToken;
            //SEND RESPONSE
            $data=Owner::where('email','=',$request->email)->first();
            return response()->json([
                'status'=>true,
                'message'=>'logged in successfully',
                'id'=>$data,
                'access_token'=>$token
            ],200);
    
        }
        //edit email or password
        public function editregister (Request $request){
            $data=$request->validate([
                'name',
                'email',
                'password'=>'confirmed'
            ]);
            $id=auth()->user()->id;
            $user=Owner::find($id);
            if (Owner::where('email','=',$request->email)->first() && $user['email']!=$request->email){
                return response()->json([
                    'status'=>false,
                    'message'=>'The email has already been taken'
                ]);
            }

      
            $user=Owner::find($id);
            $user->name=isset($request->name) ? $request->name : $user->name;
            $user->password=isset($request->password) ? bcrypt($request->password) : $user->password;
            $user->email=isset($request->email) ? $request->email : $user->email ; 
            //save in datebase
            $user->save();
            //response
            return response()->json([
              'status'=>true,
              'message'=>'edit register data successfull',
              'new_data'=>$user
            ],200);
        }

        public function profile (Request $request){
            $loginData = $request->validate([
                'Image',
                'Image_cover',
                'lived',
                'birthdate',
                'gender',
                'Job',
                'studing',
                'bio'

            ]); 
            $owner_id=auth()->user()->id;
            $user=new profile();
            if(profile::where([
                ['user_id','=',$owner_id],
            ])->exists()){
                return response()->json([
                    'message'=>'you already have a profile, edit it',
                ]);
            }
            $user->user_id=$owner_id;
            $user->birthdate=isset($request->birthdate) ? $request->birthdate : $user->birthdate;
            $user->gender=isset($request->gender) ? $request->gender: $user->gender;
            $user->lived=isset($request->lived) ? $request ->lived : $user->lived;
            $user->Job=isset($request->Job) ? $request->Job : $user->Job;
            $user->studing=isset($request->studing) ? $request->studing : $user->studing;
            $user->bio=isset($request->bio) ? $request->bio: $user->bio;
            //image
            if($request->hasFile('Image'))
        {
                $allowedFileExtension=['jpg','jpeg','png','bmp'];
                $file=$request->file('Image');
                $extension=$file->getClientOriginalExtension();
                $check=in_array($extension,$allowedFileExtension);
                if ($check){
                  $Imagename=rand() . '.'. $file->getClientOriginalExtension();
                  $file->move(public_path('uploads/profiles/Images'),$Imagename);
                  $path="public/uploads/profiles/images/$Imagename";
                  $user->Image=$path;
                }
              else {
                return response()->json([
                    'status'=>false,
                    'message'=>'invalid video format'
                ],500);
              }
    }
             if($request->hasFile('Image_cover'))
        {
                $allowedFileExtension=['jpg','jpeg','png','bmp'];
                $file=$request->file('Image_cover');
                $extension=$file->getClientOriginalExtension();
                $check=in_array($extension,$allowedFileExtension);
                if ($check){
                  $Imagename=rand() . '.'. $file->getClientOriginalExtension();
                  $file->move(public_path('uploads/profiles/Images_cover'),$Imagename);
                  $path="public/uploads/profiles/images_cover/$Imagename";
                  $user->Image_cover=$path;
                }
              else {
                return response()->json([
                    'status'=>false,
                    'message'=>'invalid video format'
                ],500);
              }
    }
            //save in database
            $user->save();
            //response date
            return response()->json([
                'status'=>true,
                "message"=>'successfully',
                'data'=>$user
            ],200);
        }
        //edit profile
        public function editprofile(Request $request){
            $Data = $request->validate([
                'Image',
                'Image_cover',
                'lived',
                'birthdate',
                'gender',
                'Job',
                'studing',
                'bio'
            ]); 
            $owner_id=auth()->user()->id;
            $user=Profile::find($owner_id);
            $user->birthdate=isset($request->birthdate) ? $request->birthdate : $user->birthdate;
            $user->gender=isset($request->gender) ? $request->gender: $user->gender;
            $user->lived=isset($request->lived) ? $request ->lived : $user->lived;
            $user->Job=isset($request->Job) ? $request->Job : $user->Job;
            $user->studing=isset($request->studing) ? $request->studing : $user->studing;
            $user->bio=isset($request->bio) ? $request->bio: $user->bio;
            //image
             //image
             if($request->hasFile('Image'))
             {
                $allowedFileExtension=['jpg','jpeg','png','bmp'];
                $file=$request->file('Image');
                $extension=$file->getClientOriginalExtension();
                $check=in_array($extension,$allowedFileExtension);
                if ($check){
                  $Imagename=rand() . '.'. $file->getClientOriginalExtension();
                  $file->move(public_path('uploads/profiles/Images'),$Imagename);
                  $path="public/uploads/profiles/Images/$Imagename";
                  $user->Image=$path;
                }
              else {
                return response()->json([
                    'status'=>false,
                    'message'=>'invalid Image format'
                ],500);
              }
         }

         if($request->hasFile('Image_cover'))
         {
                 $allowedFileExtension=['jpg','jpeg','png','bmp'];
                 $file=$request->file('Image_cover');
                 $extension=$file->getClientOriginalExtension();
                 $check=in_array($extension,$allowedFileExtension);
                 if ($check){
                   $Imagename=rand() . '.'. $file->getClientOriginalExtension();
                   $file->move(public_path('uploads/profiles/Images_cover'),$Imagename);
                   $path="public/uploads/profiles/images_cover/$Imagename";
                   $user->Image_cover=$path;
                 }
               else {
                 return response()->json([
                     'status'=>false,
                     'message'=>'invalid video format'
                 ],500);
               }
     }
            //save in database
            $user->save();
            //response date
            return response()->json([
                'status'=>true,
                "message"=>'profile updated successful',
                'data'=>$user
            ],200);
        }
        //logout 
        public function logout(Request $request){
            $token=$request->user()->token();
            //revoke token
            $token->revoke();
            //send response
            return response()->json([
                'status'=>true,
                'message'=> 'successfully'
            ]);
        }
        public function showprofile ($id){
           
            $idDb = Profile::where('id', '=', $id)->first();

            if ($id < 1 || $idDb == null || $id>count(Profile::all()))
                {
                    return response()->json([
                        'status'=>false,
                        'message'=>'this is not exist'
                    ],500);
                }
            $post=Post::where('user_id','=',$id)->get();
            $postnum=count($post);
            $following=count(Friend::where('user_id','=',$id)->get());
            $followers=count(Friend::where('friend_id','=',$id)->get());
            $Profile=Profile::where('id', '=', $id)->first();
            return response ()->json([
                'message'=>'successfully',
                'number_of_posts'=>$postnum,
                'following'=>$following,
                'follweres'=>$followers,
                'Profile'=>$Profile
            ]);
    }
    public function showregisterdate(){
        $owner_id=auth()->user()->id;
        $owner_data=Owner::where('id','=',$owner_id)->first();
        return response()->json([
            'message'=>'successfully',
            'Owner_data'=>$owner_data
        ]);
    }
   public function allprofile($id){
    $owner=auth()->user()->id;
    $friend_id=Profile::find($id)['user_id'];
    $Profile_data=profile::where('id','=',$id)->get();
    $owner_name=Owner::find($friend_id)['name'];
    if (Friend::where([['user_id','=',$owner],['friend_id','=',$friend_id]])->exists()){
        $bool=true;
    }
    else $bool=false;

    $post=Post::where('user_id','=',$friend_id)->get();
    $postnum=count($post);
    $following=count(Friend::where('user_id','=',$friend_id)->get());
    $followers=count(Friend::where('friend_id','=',$friend_id)->get());
    return response()->json([
        'name'=>$owner_name,
        'friend_status'=>$bool,
        'number_of_posts'=>$postnum,
        'following'=>$following,
        'follweres'=>$followers,
        'Profile'=>$Profile_data,
        'post'=>$post->sortBy('id')->reverse()->values()
    ]);
   }
   public function deleteaccount(){
    $user_id=auth()->user()->id;
    
    $owner=Owner::find($user_id);
    $owner->delete();

    $profile=Profile::find($user_id);
    $profile->delete();

    $friend=Friend::where('friend_id','=',$user_id)->get();
    foreach ($friend as $delete){
        $delete->delete();
    }

    $friend2=Friend::where('user_id','=',$user_id)->get();
    foreach ($friend2 as $delete3){
        $delete3->delete();
    }

    $comment=Comment::where('user_id','=',$user_id)->get();
    foreach ($comment as $delete1){
        $delete1->delete();
    }

    $post=Post::where('user_id','=',$user_id)->get();
    foreach ($post as $delete2){
        $delete2->delete();
    }
    $save=Save::where('user_id','=',$user_id)->get();
    foreach ($save as $delete3){
        $delete3->delete();
    }
    $history=HistoryPosts::where('user_id','=',$user_id)->get();
    foreach ($history as $delete4){
        $delete4->delete();
    }

    return response()->json([
        'status'=>true,
        'message'=>'you delete this account',
        'owner_data'=>$owner,
        'Profile_data'=>$profile
    ]);
   }

}
