<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $user_id = auth('sanctum')->user()->id;
        $user = User::find($user_id);
        if ($user) {
            $profile = Profile::where('user_id', $user_id)->first();
            if ($profile) {
                return response()->json([
                    'status' => 200,
                    'user' => $user,
                    'profile' => $profile

                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'user' => $user,
                    'profile'=>''

                ]);
            }
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'پروفایلی با آیدی مورد نظر پیدا نشد',
            ]);
        }
    }

    public function edit()
    {
        $user_id = auth('sanctum')->user()->id;
        $user = User::find($user_id);
        if ($user) {
            $profile = Profile::where('user_id', $user_id)->first();
            if ($profile) {
                return response()->json([
                    'status' => 200,
                    'profile' => $profile,
                    'user' => $user

                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'user' => $user

                ]);
            }
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'پروفایلی با آیدی مورد نظر پیدا نشد',
            ]);
        }
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'email' => 'required|email|max:191',
            'birthday' => 'date'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ]);
        } else {
            $user_id = auth('sanctum')->user()->id;
            $hasProfile = Profile::where('user_id', $user_id)->first();
            if ($hasProfile) {

                $hasProfile->birthday = $request->birthday;
                if ($request->hasFile('image')) {
                    $path = $hasProfile->image;
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->move('uploads/profile/', $filename);
                    $hasProfile->image = 'uploads/profile/' . $filename;
                }
                $user = User::find($user_id);
                $user->name = $request->name;
                $user->email = $request->email;
                $user->save();
                $hasProfile->save();
            } else {
                $profile = new Profile;
                $profile->user_id = $user_id;
                $profile->birthday = $request->birthday;
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->move('uploads/profile/', $filename);
                    $profile->image = 'uploads/profile/' . $filename;
                }
                $user = User::find($user_id);
                $user->name = $request->name;
                $user->email = $request->email;
                $user->save();
                $profile->save();
            }
            return response()->json([
                'status' => 200,
                'message' => 'آپدیت انجام شد'
            ]);
        }
    }
}
