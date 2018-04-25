<?php

namespace App\Services\AuthToken;

use App\User;
use Firebase\JWT\JWT;

class JwtTokenService implements AuthToken
{
    private $iss = 'lumen-jwt-auth';

    public function generate(User $user)
    {
        $payload = [
            'iss' => $this->iss,        // Issuer of the token
            'sub' => $user->id,         // Subject of the token
            'iat' => time(),            // Time when JWT was issued.
            'exp' => time() + 60*60     // Expiration time
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    }

    /**
     * @param string $token
     * @return object
     */
    public function decode(string $token)
    {
        return JWT::decode($token, env('JWT_SECRET'), [env('JWT_ALGORITHM')]);
    }
}