<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class LoginController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $creds = $request->only(['email', 'password']);
        if (!$token = auth()->attempt($creds)) {
            return response()->json(['error' => true, 'message' => 'Incorrect Login/Password'], 401);
        }
        return response()->json(['token' => $token]);
    }

    public function refresh()
    {
        try {
            $token = auth()->refresh();
        } catch (TokenInvalidException $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 401);
        }
        return response()->json(['token' => $token], 200);
    }
}
