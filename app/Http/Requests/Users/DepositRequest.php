<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class DepositRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }


    protected function prepareForValidation()
    {
        abort_if(! $this->amount%5 == 0, 422, 'Deposits should be in multiples of 5!');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
