<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('process', $this->product);
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
            'name' => ['sometimes',
                Rule::unique('products','productName')->ignore($this->product->id),
                'string',
                'min:3',
                'max:50'
            ],
            'quantity' => 'sometimes|int|min:1|max:10000000',
            'price' => 'sometimes|numeric|min:5',
        ];
    }
}
