<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\Owner;
use App\Models\Profile;
use Illuminate\Http\Request;



class HistoryController extends Controller
{
    public function search($var)
    {
            $h=$var;
            $var='%' . $var . '%';
            $namenull=Owner::where('name','like',$var)->first();
            if ($namenull==null){
            return response()->json([
               []
            ]);
           }
            $owner_id=auth()->user()->id;
            $history=new History();
            $history->user_id=$owner_id;
            $history->name = $h;
            $history->save();
            $name=Owner::where('name','like',$var)->get();
            foreach($name as $names){
               $array1[]=$names['id'];
            }
            foreach($array1 as $pro){
               $array11[$pro]=Owner::find($pro)['name'];
               $array2[$pro]=Profile::find($pro)['Image'];
               $array[] =[
                   'id'=>$pro,
                   'name'=>$array11[$pro],
                   'Image'=>$array2[$pro]
              ];
            }
            return response()->json([
               'status'=>true,
               'people'=>$array
            ]);
   }

   public function deletefromhistory($id){
        $idDb = History::where('id', '=', $id)->first();
        if($id < 1 || $idDb === null )
        {
            return response()->json([
                'status'=>false,
                'message'=>'this name does not exist'
            ],500);
        }
    $owner_id=auth()->user()->id;
    if (History::where([
        ['id','=',$id],
        ['user_id','=',$owner_id]
    ])->exists()){
      $hisotry=History::where('id','=',$id);
      $hisotry->delete();
      return response()->json([
          'status'=>true,
          'message'=>'you delete this from History'
      ],500);
  }
   }

   public function showhisory(){
    $owner_id=auth()->user()->id;
    $data=History::where('user_id','=',$owner_id)->get();
    return response ()->json([
        'status'=>true,
        'history'=>$data
    ]);
   }

}
