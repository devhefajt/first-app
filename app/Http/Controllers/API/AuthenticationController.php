<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Nowakowskir\JWT\JWT;
use Nowakowskir\JWT\TokenDecoded;
use Nowakowskir\JWT\TokenEncoded;

class AuthenticationController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $this->createToken($user);

        return $this->respondWithToken($token);
    }

    // User login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = $this->createToken($user);

        return $this->respondWithToken($token);
    }

    // Generate JWT token for a user
    protected function createToken($user)
    {
        $payload = [
            'sub' => $user->id,
            'exp' => time() + 3600, // 1 hour expiration
        ];
        
        $tokenDecoded = new TokenDecoded($payload);
        $privateKey = env('JWT_PRIVATE_KEY'); // Use JWT_SECRET if using HS256

        return $tokenDecoded->encode($privateKey, JWT::ALGORITHM_RS256);
    }

    // Format response with JWT token information
    protected function respondWithToken(TokenEncoded $token)
    {
        return response()->json([
            'access_token' => $token->toString(),
            'token_type' => 'bearer',
            'expires_in' => 3600,
        ]);
    }
}
