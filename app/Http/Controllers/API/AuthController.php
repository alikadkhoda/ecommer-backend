<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator= Validator::make($request->all(),[
            'name'=>'required|max:191',
            'email'=>'required|email|max:191|unique:users,email',
            'password'=>'required|min:8'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'validation_errors'=>$validator->errors()
            ]);
        }
        else{
            $user=User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password)
            ]);
            $token= $user->createToken($user->email.'_Token')->plainTextToken;
            return response()->json([
                'status'=>200,
                'username'=>$user->name,
                'message'=>'ثبت نام با موفقیت انجام شد',
                'token'=>$token
            ]);
        }
    }

    public function login(Request $request){
        $validator= Validator::make($request->all(),[
            'email'=>'required|max:191',
            'password'=>'required'
        ]); 
        
        if ($validator->fails()) {
            return response()->json([
                'validation_errors'=>$validator->errors()
            ]);
        }
        else{
            $user = User::where('email', $request->email)->first();
 
            if (! $user || ! Hash::check($request->password, $user->password)) {
               
                return response()->json([
                    'status'=>401,
                    'message'=>'ایمیل یا رمزعبور شما نامعتبر است'
                ]);
            }
            else{
                if ($user->role_as == 1)//1=Admin
                 {  $role='admin';
                    $token= $user->createToken($user->email.'_AdminToken',['server:admin'])->plainTextToken;
                }
                else{
                    $role='';
                    $token= $user->createToken($user->email.'_Token',[''])->plainTextToken;
                }
                return response()->json([
                    'status'=>200,
                    'username'=>$user->name,
                    'message'=>'ورود شما با موفقیت انجام شد',
                    'token'=>$token,
                    'role'=>$role
                ]);
            }
        }
    }

    public function logout(Request $request){
      $user=$request->user();
      $user->tokens()->delete();
        return response()->json([
                'status'=>200,
                'message'=>'خروج با موفقیت انجام شد'
        ]);
    }
}
