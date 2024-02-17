<?php

namespace App\Exceptions;

use GuzzleHttp\Psr7\Response;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use PhpParser\ErrorHandler\Throwing;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {

        });


    }

    public function render($request, Throwable $e){

        if($request->expectsJson()){
            if($e instanceof UniqueConstraintViolationException){
                $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR();
                return new JsonResponse([
                    'message'=> 'could not execute query',
                    'success' => false,
                    'exception'=> $e,
                    'code' => $statusCode
                ]);
            }
        }
        return parent::render($request,$e);
    }
}
