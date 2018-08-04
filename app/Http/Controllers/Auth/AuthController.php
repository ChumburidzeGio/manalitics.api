<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Consts\AllowedCurrencyCodes;
use App\Rules\Currency;
use App\Rules\CurrentPassword;

class AuthController extends Controller
{
    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email|max:255',
                'password' => 'required',
            ]);

            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->response->errorUnauthorized('Invalid credentials');
            }
        }

        catch (ValidationException $e) {
            return $e->getResponse();
        }

        catch (JWTException $e) {
            return $this->response->errorInternal('Could not create token');
        }

        return $this->response->array([
            'token' => $token,
            'user' => $this->getUser(),
        ]);
    }

    /**
     * Handle a register request to the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);

            $user = User::create([
                'main_currency' => AllowedCurrencyCodes::EUR,
                'name' => $request->name,
                'email' => $request->email,
                'password' => app('hash')->make($request->password),
            ]);
            
            $token = JWTAuth::fromUser($user);
        }

        catch (ValidationException $e) {
            return $e->getResponse();
        }

        catch (JWTException $e) {
            return $this->response->errorInternal('Could not create token');
        }

        return $this->response->array([
            'token' => $token,
            'user' => $this->getUser($user),
        ]);
    }

    /**
     * Invalidate a token.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteInvalidate()
    {
        $token = JWTAuth::parseToken();

        $token->invalidate();

        return $this->response->array([
            'message' => 'token_invalidated'
        ]);
    }

    /**
     * Get authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    private function getUser($user = null)
    {
        $user = $user ?? app('auth')->user();

        return [
            'name' => $user->name,
            'email' => $user->email,
            'currency' => $user->currency,
        ];
    }

    /**
     * Update user
     *
     * @return \Illuminate\Http\Response
     */
    public function postUpdate(Request $request)
    {
        if($request->has('currency')) {
            $this->validate($request, [
                'currency' => ['string', new Currency],
            ]);

            $update = app('auth')->user()->update([
                'main_currency' => $request->currency
            ]);
        }

        if($request->has('email')) {
            $this->validate($request, [
                'email' => ['string', 'email'],
            ]);

            $update = app('auth')->user()->update([
                'email' => $request->email
            ]);
        }

        if($request->has('curentPassword')) {
            $this->validate($request, [
                'curentPassword' => ['string', new CurrentPassword],
                'newPassword' => 'string',
            ]);

            $update = app('auth')->user()->update([
                'password' => app('hash')->make($request->newPassword)
            ]);
        }

        if($request->has('name')) {
            $this->validate($request, [
                'name' => 'string',
            ]);

            $update = app('auth')->user()->update([
                'name' => $request->name
            ]);
        }

        return [
            'updated' => $update,
        ];
    }
}