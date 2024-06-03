<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function __construct(
        private readonly Request $request
    )
    {
    }

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            /* 'profile_picture_path' => ['required', 'file', 'mimes:jpg, png'], */
        ])->validate();

        /** @var UploadedFile $pictureCollection */
        /* $pictureCollection = $input['profile_picture_path'];
        $input['profile_picture_path'] = $pictureCollection->storeAs("Profiles-pictures", $this->request->file('profile_picture_path')->getClientOriginalName(), "public"); */
        /* $picturePath = 'public/' . $input['profile_picture_path'];
        if (Storage::exists(path : $picturePath))  Storage::delete('public/' . $input['profile_picture_path']); */

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            /* 'profile_picture_path' => $input['profile_picture_path'] */
        ]);
        $user->roles()->sync([\App\Models\Role::where('name', 'user')->first()->id]);
        return $user;
    }
}
