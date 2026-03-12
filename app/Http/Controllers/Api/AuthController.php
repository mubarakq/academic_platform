<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class AuthController
{
   public function register(Request $request){
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|email|unique:users',
            'password'=>'required|string|min:8',
        ]);
        $user = User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
        ]);

        $role = Role::firstOrCreate(['name' => 'user']);
        $user->roles()->syncWithoutDetaching([$role->id]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user'=>$user->load('roles'),
            'token'=>$token,
        ]);
   }
   public function login(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);
        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'message'=> 'invalid email or paasword'
            ], 401);
        }
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'user'=> $user->load('roles'),
            'token' => $token
        ]);
   }
}
