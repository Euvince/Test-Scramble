<?php

namespace App\Actions\Profile;

use App\Contarcts\DeleteUserPicture;

class DeleteProfilePicture implements DeleteUserPicture
{
    public function delete($user) {
        $user->deleteProfilePicture();
    }
}
