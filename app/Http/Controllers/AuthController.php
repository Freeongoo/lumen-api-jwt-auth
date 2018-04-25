<?php

namespace App\Http\Controllers;

use App\Services\AuthToken\AuthToken;
use App\User;
use Dotenv\Exception\InvalidPathException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @param  \App\User $user
     * @param  AuthToken $authMechanism
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(User $user, AuthToken $authMechanism) {
        $this->validate($this->request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        // Find the user by email
        $user = User::where('email', $this->request->input('email'))->first();
        if (!$user) {
            throw new InvalidPathException("Email does not exist", 404);
        }

        // Verify the password and generate the token
        if (!Hash::check($this->request->input('password'), $user->password)) {
            throw new UnauthorizedException('Email or password is wrong', 401);
        }

        return response()->json([
            'token' => $authMechanism->generate($user)
        ], 200);
    }
}