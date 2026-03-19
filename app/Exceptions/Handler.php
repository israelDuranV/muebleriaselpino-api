<?php
namespace App\Exceptions;
use Illuminate\Auth\AuthenticationException;


use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;



class Handler extends ExceptionHandler
{
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json(['message' => 'No autenticado'], 401);
    }

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}

