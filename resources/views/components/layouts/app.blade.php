<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	{{-- Load Style --}}
	@include('components.layouts.partials.head')
	{{-- Load Style --}}
</head>

<body class="font-public-sans antialiased">
	@if (request()->routeIs('beranda'))
		{{-- Preloader --}}
		<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 800)">
			<div
				x-show="show"
				x-transition:enter="transition ease-out duration-500"
				x-transition:enter-start="opacity-0 scale-95"
				x-transition:enter-end="opacity-100 scale-100"
				x-transition:leave="transition ease-in duration-300"
				x-transition:leave-start="opacity-100 scale-100"
				x-transition:leave-end="opacity-0"
				class="fixed inset-0 z-[9999] bg-primary flex items-center justify-center">
				<div class="flex flex-col items-center text-white">
					<i class="fas fa-spinner fa-spin text-4xl mb-4"></i>
					<p class="text-base font-medium tracking-wide">Memuat halaman...</p>
				</div>
			</div>
		</div>
	@endif



	{{-- Navbar --}}
	<x-layouts.partials.navbar />
	{{-- Navbar --}}

	{{-- Dynamis Content --}}
	{{ $slot }}
	{{-- Dynamis Content --}}

	{{-- Footer --}}
	<x-layouts.partials.footer />
	{{-- Footer --}}


	{{-- Push Modal --}}
	@stack('modal')

	{{-- Push Script --}}
	@stack('scripts')

	{{-- Load Scripts --}}
	@include('components.layouts.partials.script')
	{{-- Load Scripts --}}
</body>

</html>
