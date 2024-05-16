<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PostRequest extends FormRequest
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
            'title' => [
                'required', 'min:5', 'max:255',
                Rule::unique(table : 'posts', column : 'title')
                ->ignore(request()->route()->parameter('post'))
                ->withoutTrashed()
            ],
            'excerpt' => ['required', 'min:12', 'max:50'],
            'content' => ['required', 'min:10', 'max:500'],
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
            'title.required' => "Le titre est requis",
            'title.min' => "Le titre doit faire au moins 5 carctères",
            'title.max' => "Le titre ne peut dépasser 255 caractères",
            'title.unique' => "Ce titre est déjà utilisé",
            'excerpt.min' => "Le contenu abrégé doit faire au moins 12 carctères",
            'excerpt.max' => "Le contenu abrégé ne peut dépasser 50 carctères",
            'content.min' => "Le contenu doit faire au moins 10 carctères",
            'content.max' => "Le contenu ne peut dépasser 500 carctères"
        ];
    }

}
