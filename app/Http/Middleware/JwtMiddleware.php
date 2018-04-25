<?php

namespace App\Http\Middleware;

use App\Services\AuthToken\AuthToken;
use Closure;
use Exception;
use App\User;
use Firebase\JWT\ExpiredException;

class JwtMiddleware
{
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
        $token = $request->get('token');

        if(!$token) {
            // Unauthorized response if token not there
            return response()->json([
                'error' => 'Token not provided.'
            ], 401);
        }
        try {
            $credentials = $this->authToken->decode($token);
        } catch(ExpiredException $e) {
            return response()->json([
                'error' => 'Provided token is expired.'
            ], 400);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'An error while decoding token.'
            ], 400);
        }

        $user = User::find($credentials->sub);

        // Now let's put the user in the request class so that you can grab it from there
        $request->auth = $user;
        return $next($request);
    }
}