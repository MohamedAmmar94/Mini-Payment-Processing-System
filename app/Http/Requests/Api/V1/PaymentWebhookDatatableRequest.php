<?php
namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentWebhookDatatableRequest extends FormRequest {
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
            'search_term' => ['nullable', 'string', 'max:100'],
            'from'        => ['nullable', 'date_format:Y-m-d'],
            'to'          => [
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:from',
            ],
            'start'       => ['sometimes', 'integer', 'min:0'],
            'length'      => ['sometimes', 'integer', Rule::in([10, 25, 50])],
            'draw'        => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
