<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\{Exceptions, Middleware};

use Symfony\Component\HttpKernel\Exception\{AccessDeniedHttpException, NotFoundHttpException}; // 403, 404

use Illuminate\Http\Request;

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

        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            return redirect()
                ->back() // Fallback caso não tenha página anterior
                ->with('error', 'Você não tem permissão para acessar esta área.');
        });

        // Trata Erro 404 (Não Encontrado)
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            // Verifica se existe uma URL anterior para voltar, senão vai para tela anterior
            $urlAnterior = url()->previous();
            $urlAtual = url()->current();

            // Se a URL anterior for diferente da atual, volta. Se for igual, vai para o dashboard
            if ($urlAnterior && $urlAnterior !== $urlAtual) {
                return redirect()->back()->with('error', 'A página ou recurso solicitado não foi encontrado.');
            }

            return redirect()->to('/dashboard')->with('error', 'A página solicitada não existe.');
        });
    })->create();
