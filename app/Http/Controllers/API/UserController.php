<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
        $users = User::all();
        return response()->json([
            'status' => 200,
            'users' => $users
        ]);
        
    }
    public function edit($id){
        $user=User::find($id);
        if($user){
            return response()->json([
                'status'=>200,
                'user'=>$user
            ]);
        }
        else{
            return response()->json([
            'status'=>404,
            'message'=>'کاربر مورد نظر یافت نشد'
        ]);
        }
        
    }
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'email' => 'required|email|max:191',
            'role_as' => 'required|max:191',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ]);
        }
        else{
            $user=User::find($id);
            if($user){
                $user->name=$request->name;
                $user->email=$request->email;
                $user->role_as=$request->role_as;
                $user->update();
                
                return response()->json([
                    'status'=>200,
                    'message'=>'کاربر با موفقیت آپدیت شد'
                ]);
                
            }
            else{
                return response()->json([
                    'status'=>404,
                    'message'=>'کاربر مورد نظر یافت نشد'
                ]);
            }
        }
    }
    public function destroy($id){
        $user=User::find($id);
        if($user){
            $user->delete();
            return response()->json([
                'status'=>200,
                'message'=>' کاربر مورد نظر حذف شد'
            ]);
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'کاربری باآیدی مورد نظر یافت نشد'
            ]);
        }
    }
}
