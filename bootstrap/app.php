<?php

use App\Http\Middleware\VisitorMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware): void {
    // Caddy -> Astro -> Octane: IP asli pengunjung hanya bisa dipercaya
    // dari dalam jaringan Docker, karena itu proxy-nya dipercaya penuh di sini.
    $middleware->trustProxies(at: '*');
    $middleware->append(VisitorMiddleware::class);
  })
  ->withExceptions(function (Exceptions $exceptions): void {
    //
  })->create();
