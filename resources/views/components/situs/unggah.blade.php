{{--
  Unggahan berkas Livewire dengan bidang seret-lepas. Input file transparan menutupi seluruh bidang, jadi
  klik membuka pemilih berkas dan berkas yang diseret ke bidang diterima peramban tanpa skrip tambahan.
  Kemajuan unggah dari event livewire-upload-* (Alpine bawaan Livewire).
  `berkas` = TemporaryUploadedFile yang sudah terunggah (atau null).
--}}
@props(['model', 'label', 'berkas' => null, 'jenis' => [], 'wajib' => true, 'bantuan' => null, 'maksMb' => 10])
@use('App\Support\Situs')
@php
  $id = 'unggah-' . $model;
  $galat = $errors->has($model);
  $accept = collect($jenis)->map(fn ($e) => ".{$e}")->implode(',');
  $namaJenis = collect($jenis)->reject(fn ($e) => $e === 'jpeg')->map(fn ($e) => strtoupper($e))->implode(', ');
@endphp

<div {{ $attributes->class(['min-w-0']) }}
  x-data="{ unggah: false, kemajuan: 0 }"
  x-on:livewire-upload-start="unggah = true; kemajuan = 0"
  x-on:livewire-upload-finish="unggah = false"
  x-on:livewire-upload-cancel="unggah = false"
  x-on:livewire-upload-error="unggah = false"
  x-on:livewire-upload-progress="kemajuan = $event.detail.progress"
>
  <p id="{{ $id }}-label" class="mb-1.5 block text-sm font-semibold text-biru-950 dark:text-white">
    {{ $label }}
    {{-- Tanda * cukup visual; pembaca layar sudah mendapat "wajib" dari atribut required/aria-required. --}}
    @if ($wajib)
      <span class="text-darurat dark:text-[#ff8a80]" aria-hidden="true">*</span>
    @else
      <span class="font-normal text-teks-lembut">(opsional)</span>
    @endif
  </p>

  @if ($berkas)
    <div class="flex items-center gap-3 rounded border border-biru-200 bg-langit p-3 dark:border-biru-800">
      <span class="flex h-11 w-9 shrink-0 items-center justify-center rounded bg-biru-600 text-[10px] font-bold text-white uppercase">{{ $berkas->getClientOriginalExtension() }}</span>
      <div class="min-w-0 flex-1">
        <p class="truncate text-sm font-semibold text-biru-950 dark:text-white">{{ $berkas->getClientOriginalName() }}</p>
        <p class="text-xs text-teks-lembut">{{ Situs::ukuran($berkas->getSize()) }} · siap dikirim</p>
      </div>
      <button type="button" wire:click="$set('{{ $model }}', null)" class="flex size-9 shrink-0 items-center justify-center rounded text-teks-lembut transition hover:bg-white hover:text-darurat dark:hover:bg-white/10" aria-label="Hapus {{ $berkas->getClientOriginalName() }}">
        <x-situs.ikon nama="silang" class="size-4" />
      </button>
    </div>
  @else
    <div @class([
      'group relative flex flex-col items-center justify-center gap-2 rounded border-2 border-dashed px-4 py-7 text-center transition has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-biru-300',
      $galat ? 'border-darurat/60 bg-darurat/5' : 'border-biru-200 bg-biru-50/40 hover:border-biru-400 hover:bg-langit dark:border-biru-800 dark:bg-white/5 dark:hover:border-biru-600',
    ])>
      <input
        id="{{ $id }}"
        type="file"
        wire:model="{{ $model }}"
        accept="{{ $accept }}"
        aria-labelledby="{{ $id }}-label"
        aria-describedby="{{ $id }}-bantuan"
        @if ($wajib) aria-required="true" @endif
        @if ($galat) aria-invalid="true" @endif
        class="absolute inset-0 size-full cursor-pointer opacity-0"
      />
      <span class="flex size-11 items-center justify-center rounded bg-white text-biru-600 shadow-sm transition group-hover:-translate-y-0.5 dark:bg-biru-900 dark:text-biru-300">
        <x-situs.ikon nama="unggah" class="size-5" />
      </span>
      <p class="text-sm text-teks">
        <span class="font-semibold text-biru-600 dark:text-biru-300">Pilih berkas</span> atau seret ke sini
      </p>
      <p id="{{ $id }}-bantuan" class="text-xs text-teks-lembut">
        {{ $bantuan ? $bantuan . ' ' : '' }}{{ $namaJenis }}, maksimal {{ $maksMb }} MB.
      </p>

      <div x-show="unggah" x-cloak class="absolute inset-x-4 bottom-3">
        <div class="h-1.5 overflow-hidden rounded bg-biru-100 dark:bg-biru-900">
          <div class="h-full bg-biru-600 transition-[width]" x-bind:style="`width: ${kemajuan}%`"></div>
        </div>
        <p class="mt-1 text-xs text-teks-lembut" aria-live="polite">Mengunggah <span x-text="kemajuan"></span>%</p>
      </div>
    </div>
  @endif

  @error($model)
    <p class="mt-1.5 flex items-start gap-1.5 text-sm font-medium text-darurat dark:text-[#ff8a80]">
      <x-situs.ikon nama="peringatan" class="mt-0.5 size-4 shrink-0" />
      {{ $message }}
    </p>
  @enderror
</div>
