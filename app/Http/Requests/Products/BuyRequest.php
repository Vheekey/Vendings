<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class BuyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        abort_if($this->product->amountAvailable == 0, 400, 'Out of Stock!');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'productId' => 'required|int|exists:products,id',
            'quantity' => 'required|numeric|min:1|max:'.$this->product->amountAvailable,
        ];
    }
}
