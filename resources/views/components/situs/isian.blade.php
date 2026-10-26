{{--
  Satu isian formulir Livewire: label, kontrol, teks bantuan, dan pesan galat dari validasi.
  `model` = properti Livewire (mis. "email" atau "rincian.subdomain"); disinkronkan saat kontrol ditinggalkan
  (wire:model.blur) supaya galat muncul per isian tanpa menunggu tombol kirim.
  `jenis`: text, email, tel, date, number, textarea, select, subdomain (teks berakhiran .kendarikota.go.id).
--}}
@props([
  'model',
  'label',
  'jenis' => 'text',
  'wajib' => true,
  'bantuan' => null,
  'placeholder' => null,
  'pilihan' => [],
  'autocomplete' => null,
])
@php
  $id = 'isian-' . str_replace('.', '-', $model);
  $galat = $errors->has($model);
  $kontrol = 'w-full rounded border bg-white px-4 py-2.5 text-sm text-teks outline-none transition placeholder:text-teks-lembut/70 focus:ring-2 dark:bg-malam-kartu '
    . ($galat
      ? 'border-darurat focus:border-darurat focus:ring-darurat/25'
      : 'border-biru-200 focus:border-biru-500 focus:ring-biru-200 dark:border-biru-800 dark:focus:ring-biru-800');
  $terkait = trim(($bantuan ? "{$id}-bantuan " : '') . ($galat ? "{$id}-galat" : ''));
@endphp

<div {{ $attributes->class(['min-w-0']) }}>
  <label for="{{ $id }}" class="mb-1.5 block text-sm font-semibold text-biru-950 dark:text-white">
    {{ $label }}
    @unless ($wajib)
      <span class="font-normal text-teks-lembut">(opsional)</span>
    @endunless
  </label>

  @if ($jenis === 'textarea')
    <textarea id="{{ $id }}" wire:model.blur="{{ $model }}" rows="4" placeholder="{{ $placeholder }}" @if ($wajib) required @endif @if ($terkait) aria-describedby="{{ $terkait }}" @endif @if ($galat) aria-invalid="true" @endif class="{{ $kontrol }} resize-y leading-relaxed"></textarea>
  @elseif ($jenis === 'select')
    <select id="{{ $id }}" wire:model.live="{{ $model }}" @if ($wajib) required @endif @if ($terkait) aria-describedby="{{ $terkait }}" @endif @if ($galat) aria-invalid="true" @endif class="{{ $kontrol }}">
      @foreach ($pilihan as $nilai => $teks)
        <option value="{{ $nilai }}">{{ $teks }}</option>
      @endforeach
    </select>
  @elseif ($jenis === 'subdomain')
    <div @class(['flex overflow-hidden rounded border transition focus-within:ring-2', $galat ? 'border-darurat focus-within:ring-darurat/25' : 'border-biru-200 focus-within:border-biru-500 focus-within:ring-biru-200 dark:border-biru-800 dark:focus-within:ring-biru-800'])>
      <input id="{{ $id }}" type="text" wire:model.blur="{{ $model }}" placeholder="{{ $placeholder }}" autocomplete="off" autocapitalize="none" spellcheck="false" @if ($wajib) required @endif @if ($terkait) aria-describedby="{{ $terkait }}" @endif @if ($galat) aria-invalid="true" @endif class="min-w-0 flex-1 bg-white px-4 py-2.5 text-sm text-teks outline-none placeholder:text-teks-lembut/70 dark:bg-malam-kartu" />
      <span class="flex items-center border-l border-biru-200 bg-langit px-3 text-sm whitespace-nowrap text-teks-lembut dark:border-biru-800">.kendarikota.go.id</span>
    </div>
  @else
    <input
      id="{{ $id }}"
      type="{{ $jenis }}"
      wire:model.blur="{{ $model }}"
      placeholder="{{ $placeholder }}"
      @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
      @if ($jenis === 'number') min="1" inputmode="numeric" @endif
      @if ($jenis === 'date') max="{{ now()->toDateString() }}" @endif
      @if ($wajib) required @endif
      @if ($terkait) aria-describedby="{{ $terkait }}" @endif
      @if ($galat) aria-invalid="true" @endif
      class="{{ $kontrol }}"
    />
  @endif

  @if ($bantuan)
    <p id="{{ $id }}-bantuan" class="mt-1.5 text-xs text-teks-lembut">{{ $bantuan }}</p>
  @endif
  @error($model)
    <p id="{{ $id }}-galat" class="mt-1.5 flex items-start gap-1.5 text-sm font-medium text-darurat dark:text-[#ff8a80]">
      <x-situs.ikon nama="peringatan" class="mt-0.5 size-4 shrink-0" />
      {{ $message }}
    </p>
  @enderror
</div>
