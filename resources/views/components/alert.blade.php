<div class="col-md-5 order-1 order-md-2 alert-wrapper">
	<div class="tile">
		<div class="alert alert-{{ $type }} d-flex align-items-center" role="alert">
			<i class="fa fa-check-circle me-2"></i>&nbsp;&nbsp;
			<span>{{ $message }}</span>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		setTimeout(function() {
			$('.alert-wrapper').fadeOut('slow');
		}, 3000);
	});
</script>
