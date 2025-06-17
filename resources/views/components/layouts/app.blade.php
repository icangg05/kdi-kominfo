<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	{{-- Load Style --}}
	@include('components.partials.head')
	{{-- Load Style --}}
</head>

<body class="font-public-sans antialiassed">
	{{-- Navbar --}}
	<x-partials.navbar />
	{{-- Navbar --}}

	{{-- Dynamis Content --}}
	{{ $slot }}
	{{-- Dynamis Content --}}

	{{-- Footer --}}
	<x-partials.footer />
	{{-- Footer --}}

	{{-- Load Scripts --}}
	@include('components.partials.script')
	{{-- Load Scripts --}}
</body>

</html>
