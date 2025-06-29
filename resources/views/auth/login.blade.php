<x-layouts.auth>
	<section class="login-content">
		<div class="logo" style="cursor: pointer">
			<h1 onclick="window.location.href='{{ route('beranda') }}'">Diskominfo Kota Kendari</h1>
		</div>
		<div class="login-box">
			<form class="login-form" action="{{ route('authenticate') }}" method="post">
				@csrf
				<h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>SIGN IN</h3>
				<div class="form-group">
					<label class="control-label">USERNAME</label>
					<input value="{{ old('username') }}" name="username" class="form-control" type="text" placeholder="Email" autofocus>
					@error('username')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>
				<div class="form-group">
					<label class="control-label">PASSWORD</label>
					<input name="password" class="form-control" type="password" placeholder="Password">
					@error('password')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>
				<div class="form-group">
					<div class="utility">
						<div class="animated-checkbox">
							<label>
								<input type="checkbox"><span class="label-text">Stay Signed in</span>
							</label>
						</div>
						<p class="semibold-text mb-2"><a href="#" data-toggle="flip">Forgot Password ?</a></p>
					</div>
				</div>
				<div class="form-group btn-container">
					<button type="submit" class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>
				</div>
			</form>
			<form class="forget-form" action="index.html">
				<h3 class="login-head"><i class="fa fa-lg fa-fw fa-lock"></i>Forgot Password ?</h3>
				<div class="form-group">
					<label class="control-label">EMAIL</label>
					<input class="form-control" type="text" placeholder="Email">
				</div>
				<div class="form-group btn-container">
					<button class="btn btn-primary btn-block"><i class="fa fa-unlock fa-lg fa-fw"></i>RESET</button>
				</div>
				<div class="form-group mt-3">
					<p class="semibold-text mb-0"><a href="#" data-toggle="flip"><i class="fa fa-angle-left fa-fw"></i> Back to
							Login</a></p>
				</div>
			</form>
		</div>
	</section>
</x-layouts.auth>
