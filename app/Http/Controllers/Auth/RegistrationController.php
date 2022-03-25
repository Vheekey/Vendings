<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\TokenResource;
use App\Models\Buyer;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * User registration
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        $user = new User([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'username' => $request->username,
            'role' => strtolower($request->role)
        ]);

        $role_class = $user->getRoleClass($request->role);
        $class = new $role_class;
        $class->save();

        $class->user()->save($user);

        $user->token = $user->createToken('MyApp')->plainTextToken;

        return $this->jsonResponse(HTTP_SUCCESS, 'Registration Successful', new TokenResource($user));
    }
}
