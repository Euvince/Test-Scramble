<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
{

    /* public function __construct(
        public readonly Request $request
    )
    {
    } */

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

        $nameRules = request()->routeIs('register') ? 'required' : '';
        return [
            'email' => ['required', 'email',
                request()->routeIs('register')
                ? Rule::unique('users', 'email')->ignore(request()->route()->parameter('user'))
                : ''
            ],
            'password' => ['required', 'min:8', request()->routeIs('register') ? 'confirmed' : ''],
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

    public function messages () : array {
        return [
            'email.required' => "L'email est requis",
            'email.email' => "L'email doit être de format valide",
            'password.required' => "Le mot de passe est requis",
            'password.min' => "Le mot de passe doit faire au moins 8 caractères",
        ];
    }

}
