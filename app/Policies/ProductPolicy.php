<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if a given product can be accessed by the user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return bool
     */
    public function process(User $user, Product $product)
    {
        return $user->role == User::SELLER && $user->userable->id === $product->sellerId
                ? Response::allow()
                : Response::deny('Unauthorized!');;
    }
}
