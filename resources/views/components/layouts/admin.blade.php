<!DOCTYPE html>
<html lang="en">

<head>
	@include('components.partials-admin.head')

	<style>
		.custom-alert {
			position: fixed;
			top: 20px;
			right: -100%;
			background-color: #4CAF50;
			color: white;
			padding: 15px 20px;
			border-radius: 8px;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
			display: flex;
			align-items: center;
			gap: 10px;
			font-size: 16px;
			z-index: 9999;
			opacity: 0;
			transition: all 0.5s ease;
		}

		.custom-alert.show {
			right: 20px;
			opacity: 1;
		}

		@media (max-width: 480px) {
			.custom-alert {
				font-size: 15px;
				padding: 12px 16px;
				right: -100%;
				top: 15px;
			}

			.custom-alert.show {
				right: 10px;
			}
		}
	</style>

</head>

<body class="app sidebar-mini rtl">
	<!-- Navbar-->
	@include('components.partials-admin.header')
	<!-- Sidebar menu-->
	@include('components.partials-admin.sidebar')

	<main class="app-content">
		<x-partials-admin.breadcrumb
			:icon="$icon"
			:title="$title"
			:desc="$desc"
			:nav-menu="$navMenu" />

		{{ $slot }}
	</main>

	{{-- @if (session('success'))
		<div class="custom-alert alert-wrapper" role="alert">
			<i class="fa fa-check-circle"></i>
			<span>{{ session('success') }}</span>
		</div>
	@endif --}}

	<div class="custom-alert" role="alert">
		<i class="fa fa-check-circle"></i>
		<span></span>
	</div>

	<!-- Essential javascripts for application to work-->
	@include('components.partials-admin.script')

</body>

</html>
