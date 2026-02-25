<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        // add items you don't want to report here
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
        $this->reportable(function (Throwable $e) {
            // you can log or send to external service here
        });

        // handle our service exceptions in one place
        $this->renderable(function (UserServiceException $e, $request) {
            $message = $e->getMessage() ?: 'Something went wrong';

            if ($request->wantsJson() || $request->expectsJson()) {
                $status = $e instanceof UserNotFoundException ? 404 : 400;
                return response()->json(['message' => $message], $status);
            }

            // non-JSON behaviour falls back to default Laravel response
            if ($e instanceof UserNotFoundException) {
                abort(404, $message);
            }

            return redirect()->back()->withErrors($message);
        });

        // convert missing models or http 404 errors to a JSON response or toast
        $this->renderable(function (ModelNotFoundException $e, $request) {
            $message = 'Resource not found.';

            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['message' => $message], 404);
            }

            abort(404, $message);
        });

        // handle generic HTTP 404s (e.g. missing route or translated model not found)
        $this->renderable(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            $message = 'Resource not found.';

            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['message' => $message], 404);
            }

            abort(404, $message);
        });
    }
}
