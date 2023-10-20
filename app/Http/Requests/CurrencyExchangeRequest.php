<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CurrencyExchangeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'source' => 'required|string',
            'target' => 'required|string',
            'amount' => array(
                'required',
                'regex:/^(?!,)(?:(?:\d{1,3}(?:,\d{3})*|\d+)$)/i',
                'min:0',
            ),
        ];
    }

    /**
     * 若驗證失敗，則回傳錯誤訊息，並且回傳422狀態碼
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['msg' => 'error', 'errors' => $validator->errors()], 422));
    }
}
