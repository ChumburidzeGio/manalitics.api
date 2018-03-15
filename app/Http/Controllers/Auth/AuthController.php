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
            'message' => 'user_authenicated',
            'data' => [
                'token' => $token,
                'expiresIn' => 100
            ]
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
     * Refresh a token.
     *
     * @return \Illuminate\Http\Response
     */
    public function patchRefresh()
    {
        $token = JWTAuth::parseToken();

        $newToken = $token->refresh();

        return $this->response->array([
            'message' => 'token_refreshed',
            'data' => [
                'token' => $newToken
            ]
        ]);
    }

    /**
     * Get authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUser()
    {
        return $this->response->array([
            'message' => 'authenticated_user',
            'data' => JWTAuth::parseToken()->authenticate()
        ]);
    }
}