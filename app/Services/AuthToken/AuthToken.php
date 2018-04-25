<?php

namespace App\Services\AuthToken;

use App\User;

interface AuthToken
{
    public function generate(User $user);

    public function decode(string $token);
}