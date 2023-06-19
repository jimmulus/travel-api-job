<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TravelRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {

        return [
            'is_public' => ['required', 'boolean'],
            'name' => [
                'required',
                'string',
                Rule::unique('travel')->ignore($this->travel),
                'max:255'],
            'description' => ['required', 'string'],
            'number_of_days' => ['required', 'numeric'],
        ];
    }
}
