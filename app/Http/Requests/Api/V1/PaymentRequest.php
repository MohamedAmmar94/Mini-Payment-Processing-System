<?php
namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest {
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
            'amount'         => [
                'required',
                'numeric',
                'gt:0',
            ],

            'currency'       => [
                'required',
                'string',
                'size:3',
            ],

            'payment_method' => [
                'required',
                'string',
                'in:card',
            ],
        ];
    }
    public function validateResolved(): void {
        parent::validateResolved();

        if (! $this->header('Idempotency-Key')) {
            abort(422, 'Idempotency-Key header is required.');
        }
    }
}
