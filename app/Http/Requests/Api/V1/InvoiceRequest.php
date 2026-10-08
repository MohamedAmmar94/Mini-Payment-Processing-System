<?php
namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'customer_id'             => [
                'required',
                'integer',
                'exists:customers,id',
            ],

            'issue_date'              => [
                'required',
                'date',
                'date_format:Y-m-d',
            ],

            'due_date'                => [
                'nullable',
                'date',
                'date_format:Y-m-d',
                'after_or_equal:issue_date',
            ],

            'notes'                   => [
                'nullable',
                'string',
            ],

            'items'                   => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.description'     => [
                'required',
                'string',
                'max:255',
            ],

            'items.*.quantity'        => [
                'required',
                'numeric',
                'gt:0',
            ],

            'items.*.unit_price'      => [
                'required',
                'numeric',
                'gte:0',
            ],

            'items.*.discount_amount' => [
                'nullable',
                'numeric',
                'gte:0',
            ],

            'items.*.tax_amount'      => [
                'nullable',
                'numeric',
                'gte:0',
            ],
        ];

    }
}
