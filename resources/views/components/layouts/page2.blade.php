<div>
	{{-- OVerlay Background --}}
	<div class="h-[22rem] lg:h-[27rem] absolute w-full bg-cover bg-center bg-no-repeat"
		style="background-image: url('{{ asset('img/bg-earth.jpg') }}');">
		<div class="absolute inset-0 bg-black/40 backdrop-brightness-90"></div>
	</div>
	{{-- Overlay Background --}}

	<div class="relative z-10 container pt-25 lg:pt-38">
		{{-- Breadcrumb --}}
		<ol class="mb-10 lg:mb-12 flex items-center whitespace-nowrap">
			<li class="inline-flex items-center">
				<a
					class="flex items-center text-xs lg:text-sm text-white/85 hover:text-white focus:outline-hidden focus:text-white"
					href="{{ route('beranda') }}" wire:navigate>
					<i class="fa-solid fa-house me-2 lg:me-3 text-[12px] lg:text-sm text-white/80"></i>
					Beranda
				</a>
				<i class="fa-solid fa-chevron-right mx-3 text-white/85 text-xs"></i>
			</li>

			@foreach ($breadcrumb as $i => $item)
				@if (count($breadcrumb) - 1 != $i)
					<li class="inline-flex items-center">
						<a
							class="flex items-center text-xs lg:text-sm text-white/85 hover:text-white focus:outline-hidden focus:text-white"
							href="{{ $item[2] }}" wire:navigate>
							<i class="{{ $item[0] }} me-2 lg:me-3 text-[12px] lg:text-sm text-white/80"></i>
							{{ $item[1] }}
						</a>
						<i class="fa-solid fa-chevron-right mx-3 text-white/85 text-xs"></i>
					</li>
				@else
					<li class="inline-flex items-center text-xs lg:text-sm font-semibold text-white/85 truncate" aria-current="page">
						{{ $item[0] }}
					</li>
				@endif
			@endforeach
		</ol>
		{{-- Breadcrumb --}}

		{{-- Title --}}
		<div class="mb-14 text-white/85">
			<h1 class="mb-6 text-3xl lg:text-5xl font-bold"> {{ $title }} </h1>
			<p class="max-w-4xl text-sm lg:text-base"> {{ $desc }} </p>
		</div>
		{{-- Title --}}

		<div class="mt-[9rem] lg:mt-[11rem]">
			{{ $slot }}
		</div>
	</div>
</div>
