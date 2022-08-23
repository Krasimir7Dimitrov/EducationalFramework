<?php

namespace App\System\Traits;

use App\Model\Collections\UsersCollection;

trait Auth
{
    public function isLoggedIn(): bool
    {
        return !empty($_SESSION['user'] && !empty($_SESSION['loggedIn']));
    }

    public function isAdmin($user_id): bool
    {
        $authColl = new UsersCollection();
        $user = $authColl->getUserById($user_id);

        return !empty($user['is_admin']);
    }

}