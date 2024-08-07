<?php

namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// use Validator;

class AuthController extends Controller
{
    public function _construct(){
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|min:4|max:100',
            'password' => 'required|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid Input', 'validations' => $validator->errors()], 400);
        }

        $user = User::where('username', $request->username)->first();

        if (!$user)
            return response()->json(['message' => 'Error username not found'], 401);
        if (!$user->password || !Hash::check($request->password, $user->password))
            return response()->json(['message' => 'Error incorrect password'], 401);

        $tokens = AuthHelper::generateTokens($user);

        return response()->json(['data' => ['token' => $tokens['access_token']]], 200);
    }
}

