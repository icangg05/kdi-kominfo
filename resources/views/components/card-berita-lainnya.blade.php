<div class="bg-white p-4 rounded-xl c2Shadow">
	@php
		$beritaLainnya = App\Models\Berita::orderBy('total_lihat', 'desc')->limit(3)->get();
	@endphp

	<h4 class="font-semibold text-primary mb-3">Berita Lainnya</h4>
	<div class="space-y-1">
		@foreach ($beritaLainnya as $item)
			<a wire:navigate href="{{ route('berita.show', $item->slug) }}"
				class="flex items-start gap-3 hover:bg-gray-100 p-2 rounded-lg transition">
				<img src="{{ asset($item->thumbnail ? "storage/$item->thumbnail" : 'img/berita.webp') }}"
					class="w-16 h-16 rounded-lg object-cover" />
				<div>
					<h5 class="text-sm font-medium text-cText leading-tight">
						{{ $item->judul }}
					</h5>
					<span class="text-xs text-gray-400">
						<i class="fas fa-calendar-alt mr-1"></i>
						{{ Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
					</span>
				</div>
			</a>
		@endforeach
	</div>
</div>
