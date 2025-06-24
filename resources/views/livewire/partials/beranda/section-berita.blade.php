@php
	$berita = App\Models\Berita::with('kategori')->latest()->limit(6)->get();
@endphp

<div class="container">
	<x-section-title
		title="Berita Terbaru Diskominfo<br>Kota Kendari"
		desc="Informasi terbaru seputar program dan kegiatan Diskominfo Kota Kendari. <a wire:navigate href='{{ route('berita.index') }}' class='text-primary'>Lihat lainnya</a>" />

	<div class="grid grid-cols-3 gap-y-6 lg:gap-x-8">
		@foreach ($berita as $index => $item)
			<a wire:navigate href="{{ route('berita.show', $item->slug) }}" class="col-span-3 lg:col-span-1">
				<div class="flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl">
					<img class="w-full aspect-16/10 object-cover rounded-t-xl"
						src="{{ asset($item->thumbnail ? "storage/$item->thumbnail" : 'img/berita.webp') }}"
						alt="Card Image">
					<div class="p-4 md:p-5">
						<h3 class="text-lg font-bold text-gray-800 line-clamp-1">
							{{ $item->judul }}
						</h3>
						<p class="mt-1 text-sm lg:text-base text-cText line-clamp-3">
							{{ strip_tags($item->konten) }}
						</p>
						<p class="mt-5 text-xs text-gray">
							Terakhir diperbarui
							{{ $item->updated_at->diffForHumans() }}
						</p>
					</div>
				</div>
			</a>
		@endforeach
	</div>
</div>
