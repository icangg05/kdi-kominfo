<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VisitorMiddleware
{
  public function handle(Request $request, Closure $next): Response
  {
    // Hanya hitung kunjungan halaman publik, bukan aset atau panel admin.
    if ($request->is('api/*') && ! $request->is('api/pengaturan')) {
      Visitor::firstOrCreate([
        'ip' => $request->ip(),
        'date' => now()->toDateString(),
      ]);
    }

    return $next($request);
  }
}
