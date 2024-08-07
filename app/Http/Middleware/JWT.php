<?php

namespace App\Http\Middleware;

use App\Helpers\AuthHelper;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class JWT
{
    public function handle(Request $request, Closure $next)
    {
        $accessToken = $request->header('Authorization');

        $parts = explode(' ', $accessToken);
        if (count($parts) !== 2 || $parts[0] !== 'Bearer') {
            return response()->json(['message' => 'Error: Invalid Token Structure'], 401);
        }

        $accessToken = $parts[1];

        if (!$accessToken)
            return response()->json(['message' => 'Error Invalid Token'], 401);

        $decodeResult = AuthHelper::decodeToken($accessToken);

        if ($decodeResult['success'] == false)
            return response()->json(['message' => 'Error Invalid Token'], 401);
        
        $decodedToken = $decodeResult['payload'];
        $user = User::find($decodedToken->sub);
        
        if (!$user)
            return response()->json(['message' => 'Error Invalid Token'], 401);

        $request->attributes->add(['user' => $user]);

        return $next($request);
    }
}