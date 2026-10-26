<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menghitung satu kunjungan per IP per hari. Hanya dipasang di rute halaman situs publik
 * (routes/web.php), jadi panel admin, aset, unduhan, dan permintaan Livewire tidak ikut terhitung.
 */
class VisitorMiddleware
{
  public function handle(Request $request, Closure $next): Response
  {
    // whereDate, bukan firstOrCreate: cast `date` menyimpan "Y-m-d 00:00:00", jadi pencocokan dengan
    // string "Y-m-d" gagal di SQLite (pengujian) dan kunjungan yang sama tercatat berulang.
    if ($request->isMethod('GET') && ! Visitor::where('ip', $request->ip())->whereDate('date', today())->exists()) {
      Visitor::create(['ip' => $request->ip(), 'date' => today()]);
    }

    return $next($request);
  }
}
