<?php

/**
 * app/Http/Middleware/InjectNoindexMeta.php
 * 
 * Middleware que inyecta automáticamente <meta name="robots" content="noindex, nofollow">
 * en rutas protegidas (admin, login, checkout) para evitar indexación.
 * 
 * INSTALACIÓN:
 * 1. Crear este archivo en app/Http/Middleware/
 * 2. Registrar en app/Http/Kernel.php:
 *    protected $routeMiddleware = [
 *        'noindex' => \App\Http\Middleware\InjectNoindexMeta::class,
 *    ];
 * 
 * 3. Usar en routes/web.php:
 *    Route::middleware('noindex')->group(function () {
 *        Route::get('/login', ...);
 *        Route::post('/login', ...);
 *        Route::get('/admin', ...);
 *    });
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InjectNoindexMeta
{
    /**
     * Inyecta meta tags de noindex en la respuesta HTML
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Solo inyectar si es respuesta HTML
        if ($this->isHtmlResponse($response)) {
            $content = $response->getContent();
            
            // Meta tags a inyectar
            $noindexMeta = $this->buildNoindexMeta();
            
            // Inyectar antes de </head>
            // Inyectar antes de </head>
            if (strpos($content, '</head>') !== false) {
                $content = str_replace(
                    '</head>',
                    $noindexMeta . "\n</head>",
                    $content
                );

                $response->setContent($content);
            }
        }

        return $response;
    }

    /**
     * Verifica si la respuesta es HTML
     *
     * @param Response $response
     * @return bool
     */
    private function isHtmlResponse(Response $response): bool
    {
        $contentType = $response->headers->get('content-type', '');
        return strpos($contentType, 'text/html') !== false;
    }

    /**
     * Construye los meta tags de noindex
     *
     * @return string
     */
    private function buildNoindexMeta(): string
    {
        return <<<'HTML'
    <!-- Meta tags de SEO para proteger rutas sensibles -->
    <meta name="robots" content="noindex, nofollow, noarchive, nocache">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="googlebot-mobile" content="noindex, nofollow">
    <meta name="robots" content="noimageindex">
HTML;
    }
}
