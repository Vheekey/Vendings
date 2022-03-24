<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\TokenResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if (! Auth::attempt($request->only('username', 'password'))) {
            return $this->jsonResponse(HTTP_UNAUTHORIZED, 'Invalid Credentials');
        }

        $user = User::where('username', $request->username)->sole();
        $user->token = $user->createToken($user->username)->plainTextToken;

        return $this->jsonResponse(HTTP_SUCCESS, 'Login Successful', new TokenResource($user));
    }
}
