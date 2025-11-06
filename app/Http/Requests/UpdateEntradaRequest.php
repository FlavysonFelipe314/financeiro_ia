<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEntradaRequest extends FormRequest
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
            'conta_id' => ['required'],
            'title' => ['required','max:255'],
            'category' => ['required','max:255'],
            'payment_method' => ['required'],
            'amount' => ['required','numeric','min:0'],
        ];
    }
}
