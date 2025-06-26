<div class="app-title">
	<div>
		<h1><i class="{{ $icon }}"></i> {{ $title }}</h1>
		<p>{{ $desc }}</p>
	</div>
	<ul class="app-breadcrumb breadcrumb">
		<li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
		@foreach ($navMenu as $item)
			@if (isset($item[1]))
				<li class="breadcrumb-item"><a href="{{ $item[1] }}">{{ $item[0] }}</a></li>
			@else
				<li class="breadcrumb-item">{{ $item[0] }}</li>
			@endif
		@endforeach
	</ul>
</div>
