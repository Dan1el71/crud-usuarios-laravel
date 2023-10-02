<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function create(Request $request){
        try{
            $validar = Validator::make($request->all(), 
            [
                'name' => 'required|string|max:100',
                'email' => 'required|string|email|max:50|unique:users',
                'password' => 'required|string|min:8'
            ]);

            if($validar->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validar->errors()->all()
                ], 400);
            }
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                    'status' => true,
                    'message' => 'User Created Sucessfully',
                    'token' => $user->createToken('API TOKEN')->plainTextToken
                ], 200);

        }  catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function login (Request $request) {
        try{
            $validar = Validator::make($request->all(), 
            [
                'email' => 'required|string|email|max:50',
                'password' => 'required|string'
            ]);

            if($validar->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validar->errors()->all()
                ], 400);
            }
            
            if(!Auth::attempt($request->only('email','password'))){
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                    'errors' => ['Unauthorized']
                ], 401);
            }
            $user = User::where('email',$request->email)->first();

            return response()->json([
                    'status' => true,
                    'message' => 'User logged in sucessfully',
                    'data' => $user,
                    'token' => $user->createToken('API TOKEN')->plainTextToken
                ], 200);
            
        } catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'Logged out sucessfully'
        ], 200);
    }
}
