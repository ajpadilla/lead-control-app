<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreLeadRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'source' => 'required|string|max:255',
            'owner' => 'required|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'source.required' => 'The source field is required.',
            'owner.exists' => 'The selected owner is invalid.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     *
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'meta' => [
                'success' => false,
                'errors' => $validator->errors()->all(),
            ],
        ], 422);

        throw new ValidationException($validator, $response);
    }
}
