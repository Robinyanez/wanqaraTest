<?php

namespace App\Exceptions;

use Log;
use Throwable;
//use Fruitcake\Cors\CorsService;
use Illuminate\Http\Request;
use App\Interfaces\HttpCodeInterface;
use App\Traits\Utilities\ApiResponse;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler implements HttpCodeInterface
{
    use ApiResponse;
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $exception, Request $request) {

            if ($request->is('api/*')) {

                $response = $this->handleExceptionApi($request, $exception);
                return $response;
            }
        });
    }

    public function handleExceptionApi($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {

            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if ($exception instanceof ModelNotFoundException) {

            $model = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("No existe ninguna instancia de {$model} con el id especificado", self::NOT_FOUND);
        }

        if ($exception instanceof AuthenticationException) {

            return $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof AuthorizationException) {

            return $this->errorResponse('No posee permisos para ejecutar esta acción', self::FORBIDDEN);
        }

        if ($exception instanceof NotFoundHttpException) {

            return $this->errorResponse('No se encontró la URL especificada', self::NOT_FOUND);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {

            return $this->errorResponse('El método especificado en la petición no es válido', self::METHOD_NOT_ALLOWED);
        }

        if ($exception instanceof HttpException) {

            return $this->errorResponse($exception->getMessage(), $exception->getCode());
        }

        if ($exception instanceof QueryException) {

            $code = $exception->errorInfo[1];

            if ($code == 1451) {

                return $this->errorResponse('No se puede eliminar de forma permamente el recurso porque está relacionado con algún otro.', self::CONFLICT);
            }
        }

        if ($exception instanceof TokenMismatchException) {

            return redirect()->back()->withInput($request->input());
        }

        if (config('app.debug')) {

            return parent::render($request, $exception);
        }

        Log::error($exception->getCode());
        Log::error($exception->getMessage());

        return $this->errorResponse('Falla inesperada. Intente luego', self::INTERNAL_SERVER_ERROR);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($this->isFrontend($request)) {

            return redirect()->guest('login');
        }

        return $this->errorResponse('Token expirado - No autenticado.', self::UNAUTHORIZED);
    }

    protected function convertValidationExceptionToResponse(ValidationException $exception, $request)
    {
        $errors = $exception->validator->errors()->getMessages();

        if ($this->isFrontend($request)) {

            return $request->ajax() ? response()->json($errors, self::UNPROCESSABLE_ENTITY) : redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors($errors);
        }

        return $this->errorResponse($errors, self::UNPROCESSABLE_ENTITY);
    }

    private function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
