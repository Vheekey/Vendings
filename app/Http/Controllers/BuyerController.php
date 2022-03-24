<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\DepositRequest;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    public function deposit(DepositRequest $request)
    {
        auth()->user()->deposit += $request->amount;
        auth()->user()->save();

        return $this->jsonResponse(HTTP_SUCCESS, 'Deposit Successful');
    }

    public function buy(Request $request)
    {

    }
}
