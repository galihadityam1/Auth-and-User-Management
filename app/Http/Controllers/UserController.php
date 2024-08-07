<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// use Validator;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        $user = $request->get('user');

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:4|max:100',
            'username' => 'required|string|unique:users|min:4|max:100',
            'password' => 'required|string|confirmed|min:8|max:100',
            'password_confirmation' => 'required|string|min:8|max:100',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        User::create(
            [
                'username' => $request->username,
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'created_by' => $user->id,
            ]
        );

        return response()->json(['message' => 'username berhasil disimpan'], 200);
    }

    public function updateUser(Request $request, $id)
    {
        $user = $request->get('user');

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:4|max:100',
            'username' => 'required|string|min:4|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        $updatedUser = User::find($id);

        if (!$updatedUser) {
            return response()->json(['message' => "error user not found"], 401);
        }

        $updatedUser->update([
            'name' => $request->name,
            'username' => $request->username,
            'updated_by' => $user->id
        ]);

        return response()->json(['message' => 'username berhasil diperbarui'], 200);
    }

    public function deleteUser($id)
    {
        $deletedUser = User::find($id);

        if (!$deletedUser) {
            return response()->json(['message' => "error user not found"], 401);
        }

        $deletedUser->delete();

        return response()->json(['message' => 'username berhasil dihapus'], 200);
    }

    public function getAllUser()
    {
        $allUser = User::all();

        return response()->json(['data' => $allUser], 200);
    }

    public function getUserById($id)
    {
        $user = User::where('id', $id)->first();

        return response()->json(['data' => $user], 200);
    }
}
