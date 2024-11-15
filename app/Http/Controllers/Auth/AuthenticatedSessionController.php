<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $token = $request->user()->createToken('authToken')->plainTextToken;

        return $this->respondWithSuccess([
            'token' => $token
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        auth()->user()->tokens()->delete();

        return $this->respondWithSuccess(
            ['message' => 'Logged out successfully']
        );
    }
}
