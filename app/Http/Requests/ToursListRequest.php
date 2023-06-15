<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ToursListRequest extends FormRequest
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
            'priceFrom' => ['numeric'],
            'priceTo' => ['numeric'],
            'dateFrom' => ['date'],
            'dateTo' => ['date'],
            'sortBy' => [Rule::in(['price'])],
            'sortOrder' => [Rule::in(['asc', 'desc'])],
        ];
    }

    public function messages(): array
    {
        return [
            'sortBy' => "'Sortby' only accepts 'price' as value",
            'sortOrder' => "'sortOrder' only accepts 'asc' or 'desc' as value",
        ];
    }

}
