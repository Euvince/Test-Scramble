<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RoleRequest extends FormRequest
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
            'name' => [
                'required',
                Rule::unique(table : 'roles', column : 'name')
                ->ignore($this->route()->parameter('role'))
                ->withoutTrashed()
            ],
        ];
    }

    public function failedValidation (Validator $validator) : HttpResponseException {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'error' => true,
            'message' => "Erreurs de validations",
            'status_code' => 422,
            'errorsList' => $validator->errors()
        ]));
    }

    public function messages() : array {
        return [
            'name.required' => "Le nom du rôle est requis",
            'name.unique' => "Ce nom est déjà utilisé",
        ];
    }

}
