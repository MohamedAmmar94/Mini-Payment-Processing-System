<?php
namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest {
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
            "name"    => "required|string|max:255",
            "email"   => "required|email|unique:customers,email",
            "phone"   => "required|unique:customers,phone",
            "address" => "string|max:255",
        ];
    }

    public function messages() {
        return [
            "name.required"  => "الاسم مطلوب",
            "name.string"    => "الاسم يجب أن يكون نصاً",
            "name.max"       => "الاسم يجب أن يكون 255 حرفاً كحد أقصى",
            "email.required" => "البريد الإلكتروني مطلوب",
            "email.email"    => "البريد الإلكتروني يجب أن يكون صالحاً",
            "email.unique"   => "البريد الإلكتروني موجود بالفعل",
            "phone.required" => "رقم الهاتف مطلوب",
            "phone.unique"   => "رقم الهاتف موجود بالفعل",
            "address.string" => "العنوان يجب أن يكون نصاً",
            "address.max"    => "العنوان يجب أن يكون 255 حرفاً كحد أقصى",
        ];
    }

}
