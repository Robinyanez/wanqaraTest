<?php

namespace App\Http\Controllers\Api\Auth;

use Auth;
use Carbon\Carbon;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\Auth\AuthRequest;
use Illuminate\Contracts\Auth\StatefulGuard;

class AuthController extends Controller
{
    public function authToken(AuthRequest $request)
    {
        try
        {
            if(!$this->attemptLogin($request)) {

                return $this->errorResponse('Credenciales incorrectas.', self::FORBIDDEN);
            }

            $tokenResult = auth()->user()->createToken('WANQARA-TOKEN');

            $response = [
                'token_type' => 'Bearer',
                'access_token' => $tokenResult->plainTextToken,
            ];

            return $this->successResponse($response, self::OK);

        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param AuthRequest $request
     * @return bool
     */
    protected function attemptLogin(AuthRequest $request): bool
    {
        return $this->guard()->attempt($this->credentials($request));
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  AuthRequest  $request
     * @return array
     */
    protected function credentials(AuthRequest $request): array
    {
        $field = filter_var($request->get($this->email()), FILTER_VALIDATE_EMAIL) ? $this->email() : null;

        return [
            $field      => $request->get($this->email()),
            'password'  => $request->input('password'),
        ];
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    protected function email(): string
    {
        return 'email';
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return StatefulGuard
     */
    protected function guard(): StatefulGuard
    {
        return Auth::guard();
    }
}
