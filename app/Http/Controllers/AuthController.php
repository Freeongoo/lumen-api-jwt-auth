<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidAuthParamsException;
use App\Services\AuthToken\AuthToken;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    /**
     * @param Request $request
     * @param User $user
     * @param AuthToken $authMechanism
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(Request $request, User $user, AuthToken $authMechanism)
    {
        $this->validateRequest($request);

        // Find the user by email
        $user = User::where('email', $request->input('email'))->firstOrFail();

        // Verify the password and generate the token
        if (!Hash::check($request->input('password'), $user->password)) {
            throw new InvalidAuthParamsException('Email or password is wrong', 401);
        }

        return response()->json(['token' => $authMechanism->generate($user)], 200);
    }

    /**
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validateRequest(Request $request)
    {
        $this->validate($request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);
    }
}