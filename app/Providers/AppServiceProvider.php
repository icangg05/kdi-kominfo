<?php

namespace App\Providers;

use App\Models\ProfilDinas;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    View::composer(['components.layouts.app', 'components.*', 'livewire.*'], function ($view) {
      $pengaturan = ProfilDinas::where('jenis', 'pengaturan')->first();
      $konten     = $pengaturan->konten;

      $view->with([
        'global_telp'  => $konten[0]['value'],
        'global_email' => $konten[1]['value'],
        'global_fb'    => $konten[2]['value'],
        'global_ig'    => $konten[3]['value'],
        'global_tt'    => $konten[4]['value'],
        'global_yt'    => $konten[5]['value'],
      ]);
    });
  }
}
