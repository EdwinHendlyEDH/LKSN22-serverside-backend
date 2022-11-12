<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            "name" => "required|min:3|max:255",
            "email" => "required|email:dns|unique:users",
            "password" => "required|min:5|max:255",
        ]);

        if($validator->fails()){
            return response([
                "message" => "Wrong Credentials",
                "errors" => $validator->errors()
            ], 422);
        }

        $val = $validator->validated();
        $val['password'] = bcrypt($val['password']);
        $val['email_verified_at'] = now();

        User::create($val);

        return response([
            "message" => "Register success"
        ], 200);

    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            "email" => "required|email:dns",
            "password" => "required|min:5|max:255",
        ]);

        if($validator->fails()){
            return response([
                "message" => "Invalid field",
                "errors" => $validator->errors()
            ], 422);
        }

        if(!Auth::attempt($validator->validated())){
            return response([
                "message" => "Email or password is incorrect",
            ], 401);
        }


        $user = request()->user();
        $token = $user->createToken(uniqid())->plainTextToken;
        return response([
            "message" => "Login success",
            "user" => [
                "id" => $user['id'],
                "name" => $user['name'],
                "email" => $user['email'],
                "token" => $token
            ]
        ]);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response([
            "message" => "Logout success"
        ], 200);
    }

    public function me(){
        $user = auth()->user();
        return response([
            'message' => "Get user success",
            "user" => [
                "id" => $user['id'],
                "name" => $user['name'],
                "email" => $user['email'],
            ]
        ]);
    }

    public function showForms($id){
        $user = User::with('forms')->get()->where('id', $id)->first();
        if(!$user){
            return response([
                'message' => 'User not found',
            ], 404);
        }
        $forms = $user->forms;
        return response([
            "message" => "Get user and all forms success",
            "forms" => $forms
        ]);
    }

}
