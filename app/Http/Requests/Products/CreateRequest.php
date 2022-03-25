<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
        abort_unless($this->price % 5 == 0, 422, 'Prices should be in multiples of 5!');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:products,productName|string|min:3|max:50',
            'quantity' => 'required|int|min:1|max:10000000',
            'price' => 'required|numeric|min:5',
        ];
    }
}
