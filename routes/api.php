<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\OwnerController;
use App\Http\Controllers\api\PostController;
use App\Http\Controllers\api\LikeController;
use App\Http\Controllers\api\CommentController;
use App\Http\Controllers\api\FriendController;
use App\Http\Controllers\api\SaveController;
use App\Http\Controllers\api\HistoryController;
use App\Http\Controllers\api\PostHistoryController;
use App\Http\Controllers\API\MultipleUploadController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('owner/')->group(function (){

Route::post('register',[OwnerController::class,'register']);
Route::post('login',[OwnerController::class,'login']);
Route::get('showprofile/{id}',[OwnerController::class,'showprofile']);



Route::group(["middleware"=>["auth:api"]],function(){
Route::post('profile',[OwnerController::class,'profile']);
Route::post('editprofile',[OwnerController::class,'editprofile']);
Route::post('editregister',[OwnerController::class,'editregister']);

Route::get('deleteaccount',[OwnerController::class,'deleteaccount']);


Route::get('showregisterdate',[OwnerController::class,'showregisterdate']);
Route::get('allprofile/{id}',[OwnerController::class,'allprofile']);

Route::get('logout',[OwnerController::class,'logout']);
});

});
Route::prefix('post/')->group(function (){
    
Route::group(["middleware"=>["auth:api"]],function(){
Route::post('addpost',[PostController::class,'addpost']);
Route::post('editpost/{id}',[PostController::class,'editpost']);
Route::get('deletepost/{id}',[PostController::class,'deletepost']);
Route::get('showMyposts',[PostController::class,'showMyposts']);
Route::get('homepage',[PostController::class,'homepage']);

Route::post('like/{id}',[LikeController::class,'like']);
Route::post('addcomment/{id}',[CommentController::class,'addcomment']);
Route::post('editcomment/{id}',[CommentController::class,'editcomment']);
Route::get('deletecomment/{id_post}/{id_comment}',[CommentController::class,'deletecomment']);
Route::get('comments/{id}',[CommentController::class,'comments']);

});
});
Route::prefix('friend/')->group(function (){
    Route::group(["middleware"=>["auth:api"]],function(){

    Route::post('addfriend/{id}',[FriendController::class,'addfriend']);
    Route::get('showlistfriend',[FriendController::class,'showlistfriend']);

    });
    });

  Route::prefix('save/')->group(function (){
        Route::group(["middleware"=>["auth:api"]],function(){
    
        Route::post('save/{id}',[SaveController::class,'save']);
        Route::get('savepage',[SaveController::class,'savepage']);
        Route::get('deletefromSave/{post_id}',[SaveController::class,'deletefromSave']);
    
        });
        });
        Route::prefix('history/')->group(function (){
            Route::group(["middleware"=>["auth:api"]],function(){

            Route::post('search/{var}',[HistoryController::class,'search']);
            Route::post('deletefromhistory/{id}',[ HistoryController::class,'deletefromhistory']);
            Route::get('showhisory',[ HistoryController::class,'showhisory']);
            });
            });
            Route::prefix('historypost/')->group(function (){
                Route::group(["middleware"=>["auth:api"]],function(){
    
                Route::post('historypost/{id}',[PostHistoryController::class,'historypost']);
                Route::post('deleteposthis/{id}',[PostHistoryController::class,'deleteposthis']);
                Route::get('Historypostlist',[ PostHistoryController::class,'Historypostlist']);
                
                });
                });