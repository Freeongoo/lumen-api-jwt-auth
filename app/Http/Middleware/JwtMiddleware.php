<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiExceptionHandlerTrait;
use App\Services\AuthToken\AuthToken;
use Closure;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;

class JwtMiddleware
{
    use ApiExceptionHandlerTrait;

    /**
     * @var AuthToken
     */
    private $authToken;

    public function __construct(AuthToken $authToken)
    {
        $this->authToken = $authToken;
    }

    public function handle($request, Closure $next, $guard = null)
    {
        try {
            $token = $request->get('token');
            if (!$token)
                throw new AuthorizationException("Token not passed");

            $credentials = $this->authToken->decode($token);
        } catch (\Exception $e) {
            return $this->handleException($e, 401);
        }

        $user = User::find($credentials->sub);

        // Now let's put the user in the request class so that you can grab it from there
        $request->auth = $user;
        return $next($request);
    }
}