<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
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
     * Store the generated error ID for access in render().
     *
     * @var string|null
     */
    protected ?string $errorId = null;

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Report or log an exception.
     */
    public function report(Throwable $exception)
    {
        // Generate and store error ID
        $this->errorId = $this->generateExceptionHash($exception);

        // Log the error with the error ID
        Log::error("[$this->errorId] " . $exception->getMessage(), [
            'exception' => $exception,
        ]);

        // Report to Sentry if available
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            \Sentry\captureException($exception);
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     */
    // public function render($request, Throwable $exception)
    // {
    //     if (true) {
    //         // Use the stored error ID if available
    //         $errorId = $this->errorId ?? $this->generateExceptionHash($exception);

    //         return response()->json([
    //             'message' => 'Something went wrong, please try again later.',
    //             'error_id' => $errorId,
    //         ], 400);
    //     }

    //     return parent::render($request, $exception);
    // }
    public function render($request, Throwable $exception)
    {
        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        $errorId = $this->errorId ?? $this->generateExceptionHash($exception);

        return response()->json([
            'message' => 'Something went wrong, please try again later.',
            'error_id' => $errorId,
        ], 400);
    }


    /**
     * Generate a short hash to identify the exception.
     */
    private function generateExceptionHash(Throwable $exception): string
    {
        $string = get_class($exception) .
                  '|' . $exception->getMessage() .
                  '|' . $exception->getFile() .
                  '|' . $exception->getLine();

        return substr(hash('sha256', $string), 0, 16);
    }
}
