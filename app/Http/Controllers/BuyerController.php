<?php

namespace App\Http\Controllers;

use App\Http\Requests\Products\BuyRequest;
use App\Http\Requests\Users\DepositRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    /**
     * Deposit amount
     *
     * @param DepositRequest $request
     * @return \Illuminate\Http\Response
     */
    public function deposit(DepositRequest $request)
    {
        $user = auth()->user();
        $user->deposit += $request->amount;
        $user->save();

        return $this->jsonResponse(HTTP_SUCCESS, 'Deposit Successful');
    }

    /**
     * Make a buy request
     *
     * @param BuyRequest $request
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function buy(BuyRequest $request, Product $product)
    {
        $total = $request->quantity * $product->cost;
        $balance = auth()->user()->deposit;

        if($balance < $total){
            return $this->jsonResponse(HTTP_BAD_REQUEST, 'Insufficient Funds');
        }

        $change = $balance - $total;

        auth()->user()->update(['deposit' => $change]);
        $product->decrement('amountAvailable', $request->quantity);

        return $this->jsonResponse(HTTP_SUCCESS, 'Purchase Successful', collect([
            'total_spent' => $total,
            'change' => $this->roundToAmount($change),
        ]));
    }

    /**
     * Reset deposit amount to zero
     *
     * @return \Illuminate\Http\Response
     */
    public function resetDeposit()
    {
        auth()->user()->update(['deposit' => 0]);

        return $this->jsonResponse(HTTP_SUCCESS, 'Deposit reset to 0');
    }

    private function roundToAmount(int $amount) {
        $change = [];

        $possible_values = [100, 50, 20, 10, 5];

        foreach($possible_values as $value)
        {
            if($amount >= $value){
                $count = $amount / $value;
                $balance = floor($amount % $value);

                for($i=1; $i<=$count; $i++)
                {
                    $change[] = $value;
                }

                $amount = $balance;

                if($amount > 5){
                    $this->roundToAmount($amount);
                }
            }
        }

        return $change;
    }
}
