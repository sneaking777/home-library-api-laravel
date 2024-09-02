<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if (
                $request->routeIs('book.show')
                || $request->routeIs('book.update')
                || $request->routeIs('book.destroy')
            ) {

                return response()->json([
                    'message' => __('exceptions.not_found.book')
                ], Response::HTTP_NOT_FOUND);
            }
            if (
                $request->routeIs('author.show')
                || $request->routeIs('author.update')
                || $request->routeIs('author.destroy')
            ) {
                return response()->json([
                    'message' => __('exceptions.not_found.author')
                ], Response::HTTP_NOT_FOUND);
            }
            return response()->json([
                'message' => __('exceptions.not_found.resource')
            ], Response::HTTP_NOT_FOUND);
        });
    })->create();
