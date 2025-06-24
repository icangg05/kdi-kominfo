<div class="container">
	<x-section-title
		title="Sambutan Kepala Diskominfo<br>Kota Kendari"
		desc="Selamat datang di website resmi Diskominfo Kota kendari. Kami berkomitmen mewujudkan Smart City yang maju, inovatif, dan berdaya saing." />

	{{-- Left Content --}}
	<div class="grid grid-cols-5 gap-x-0 lg:gap-x-9 gap-y-4.5 lg:gap-y-0">
		<div class="col-span-5 lg:col-span-3 order-2 lg:order-1">
			<h6 class="font-bold text-xl lg:text-2xl text-gray-800">Sahuriyanto Meronda, S.P</h6>
			<div class="mt-4 lg:mt-6 prose prose-sm lg:prose [&_*]:text-cText">
				{!! $sambutanKadis !!}
			</div>
			<div class="mt-7 lg:mt-8 grid grid-cols-2">
				@foreach ($taglineSambutan as $item)
					<p class="font-sen text-cText text-sm lg:text-base inline-flex items-center space-x-3">
						<i class="fa-solid fa-caret-right text-primary"></i>
						<span>{{ $item }}</span>
					</p>
				@endforeach
			</div>
		</div>
		{{-- Left Content --}}

		{{-- Right Content --}}
		<div class="col-span-5 lg:col-span-2 order-1 lg:order-2">
			<img class="aspect-3/4 object-cover object-center w-[90%] lg:w-full mx-auto"
				src="{{ asset("storage/$fotoKadis") }}"
				alt="kadis">
		</div>
		{{-- Right Content --}}
	</div>
</div>
