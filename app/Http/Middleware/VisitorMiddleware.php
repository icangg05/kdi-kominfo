<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VisitorMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $ip    = request()->ip();
    $today = now()->toDateString();

    Visitor::firstOrCreate([
      'ip'   => $ip,
      'date' => $today,
    ]);

    return $next($request);
  }
}
