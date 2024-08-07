<?php

namespace App\Helpers;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthHelper
{
    public static function generateTokens(User $user)
    {
        $now = now();

        $payload = [
            'sub' => $user->id,
            'iat' => strtotime($now),
            'exp' => strtotime($now->addMinutes(60)),
        ];
        $accessToken = JWT::encode($payload, ENV('JWT_SECRET'), 'HS256');
        
        return [
            'access_token' => $accessToken,
        ];
    }

    public static function decodeToken($token)
    {
        try {
            $payload = JWT::decode($token, new Key(ENV('JWT_SECRET'), 'HS256'));

            return [
                'success' => true,
                'payload' => $payload
            ];
        } catch (\Exception $th) {
            return [
                'success' => false,
                'message' => $th->getMessage()
            ];
        }
    }
}