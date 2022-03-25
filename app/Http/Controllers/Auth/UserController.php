<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of all users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(20);

        $users = UserResource::collection($users);

        return $this->wrapJsonResponse($users->response(), 'Users Retrieved');
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->jsonResponse(HTTP_SUCCESS, 'User Retrieved', new UserResource($user));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if($request->filled('username')) $user->username = $request->username;

        if($request->filled('role')){
            $this->swapRole($request, $user);
        }

        return $this->jsonResponse(HTTP_SUCCESS, 'Details Updated');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->userable()->delete();
        $user->delete();

        return $this->jsonResponse(HTTP_SUCCESS, 'User Deleted');
    }

    private function swapRole(Request $request, User $user)
    {
        $user->role = strtolower($request->role);
        $user->userable()->product()->delete();

        $role_class = $user->getRoleClass($request->role);
        $class = new $role_class;
        $class->save();

        $class->user()->save($user);
    }
}
