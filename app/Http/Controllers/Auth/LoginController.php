<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\TokenResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if (! Auth::attempt($request->only('username', 'password'))) {
            return $this->jsonResponse(HTTP_UNAUTHORIZED, 'Invalid Credentials');
        }

        if($request->user()->tokens()){
            return $this->jsonResponse(HTTP_UNAUTHORIZED, 'There is already an active session using your account');
        }

        $user = User::where('username', $request->username)->sole();
        $user->token = $user->createToken('MyApp')->plainTextToken;

        return $this->jsonResponse(HTTP_SUCCESS, 'Login Successful', new TokenResource($user));
    }

    public function logout(Request $request)
    {
        Auth::guard('api')->logout();

        Auth::guard('web')->logout();

        auth()->user()->tokens()->delete();

        return $this->jsonResponse(HTTP_SUCCESS, 'Logout Successful');
    }
}
