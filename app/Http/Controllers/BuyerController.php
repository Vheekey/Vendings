<?php

namespace App\Http\Controllers;

use App\Http\Requests\Products\BuyRequest;
use App\Http\Requests\Users\DepositRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    public function deposit(DepositRequest $request)
    {
        $user = auth()->user();
        $user->deposit += $request->amount;
        $user->save();

        return $this->jsonResponse(HTTP_SUCCESS, 'Deposit Successful');
    }

    public function buy(BuyRequest $request, Product $product)
    {
        $total = $request->quantity * $product->cost;
        $balance = auth()->user()->deposit;

        if($balance < $total){
            return $this->jsonResponse(HTTP_BAD_REQUEST, 'Insufficient Funds');
        }

        $change = $balance - $total;

        auth()->user()->update(['deposit' => $change]);

        return $this->jsonResponse(HTTP_SUCCESS, 'Purchase Successful', collect([
            'total_spent' => $total,
            'change' => $this->roundToAmount($change),
        ]));
    }

    public function resetDeposit()
    {
        auth()->user()->update(['deposit' => 0]);

        return $this->jsonResponse(HTTP_SUCCESS, 'Deposit reset to 0');
    }

    private function roundToAmount(int $amount) {
        $change = [];
        $possible_values = [5, 10, 20, 50, 100];

        if($amount <= min($possible_values) || $amount == max($possible_values)){
            $change[] = $amount ?? 0;
        }

        $remainder = $amount;

        if($amount > max($possible_values)){
            while($remainder > max($possible_values)){
                $change[] = max($possible_values);
                $remainder = $remainder - max($possible_values);
            }

            if(! in_array($remainder, $possible_values)){
                while($remainder > min($possible_values)){
                    $change[] = min($possible_values);
                    $remainder = $remainder - min($possible_values);
                }
            }else{
                $change[] = $remainder;
            }

        }else{
            while($remainder > min($possible_values)){
                $change[] = min($possible_values);
                $remainder = $remainder - min($possible_values);
            }
        }

        return $change;
    }
}
