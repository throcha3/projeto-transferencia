<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'payee_id' => ['required', 'integer', 'exists:accounts,id'],
            'payer_id' => ['required', 'integer', 'exists:accounts,id'],
            'value' => ['required', 'numeric', 'min:0', 'max: 99999']
        ];
    }
}
