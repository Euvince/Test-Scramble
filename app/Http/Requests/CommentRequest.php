<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CommentRequest extends FormRequest
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
            'content' => ['required', 'min:10', 'max:500']
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
            'content.required' => "Le contenu du commentaire est requis",
            'content.min' => "Le contenu doit faire au moins 10 caractères",
            'content.max' => "Le contenu ne peut dépasser 500 caractères",
        ];
    }

}
