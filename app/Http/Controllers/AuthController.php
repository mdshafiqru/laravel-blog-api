<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        //validate fields
        $attr = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);
        // create user
        $user = User::create([
            'name' => $attr['name'],
            'email' => $attr['email'],
            'password' => Hash::make($attr['password']),
        ]);

        // return user and token in response
        return response([
            'user' => $user,
            'token' => $user->createToken('secret')->plainTextToken 
        ], 200);
    }

    // Login user
    public function login(Request $request){
        //validate fields
        $attr = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        
        // attempt login
        if (!Auth::attempt($attr)) {
            return response([
                'message' => 'Invalid Credentials'
            ], 403);
        } 

        // return user and token in response
        return response([
            'user' => auth()->user(),
            'token' => auth()->user()->createToken('secret')->plainTextToken 
        ], 200);
    }

    // logout user
    public function logout(){
        auth()->user()->tokens()->delete();
        return response(['message' => 'logout success'], 200);
    }

    // user details
    public function user(){
        return response([
            'user' => auth()->user()
        ], 200);
    }

    // update user profile
    public function update(Request $request){
        $attrs = $request->validate([
            'name' => 'required|string'
        ]);

        $image = $this->saveImage($request->image, 'profiles');

        auth()->user()->update([
            'name' => $attrs['name'],
            'image' => $image
        ]);
        return response([
            'message' => 'user profile updated',
            'user' => auth()->user()
        ], 200);
    }

}
