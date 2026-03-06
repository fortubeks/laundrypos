<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {
        //
    })

    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->report(function (Throwable $e) {

            $statusCode = 500;

            if ($e instanceof HttpExceptionInterface) {
                $statusCode = $e->getStatusCode();
            }

            // Only report server errors
            if ($statusCode < 500) {
                return;
            }

            $request = request();

            $body = "
                Server Error ({$statusCode})

                Message: {$e->getMessage()}

                File: {$e->getFile()}
                Line: {$e->getLine()}

                URL: {$request->fullUrl()}
                Method: {$request->method()}

                User ID: " . optional($request->user())->id . "

                Payload:
                " . json_encode($request->all(), JSON_PRETTY_PRINT);

            Mail::raw($body, function ($mail) {
                $mail->to([
                    'david@fortranhouse.com',
                    'info@fortranhouse.com',
                ])->subject('Laravel Server Error');
            });

        });

    })->create();
