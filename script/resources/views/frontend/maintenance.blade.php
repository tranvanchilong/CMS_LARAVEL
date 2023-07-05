<!DOCTYPE html>
<html class="no-js" lang="{{ App::getlocale() }}" >
<head>
        <title>Maintainance Mode</title>
		<!-- favicon -->
		<link rel="shortcut icon" href="{{ asset('uploads/'.domain_info('user_id').'/favicon.ico') }}" type="image/x-icon">
		<link href="https://fonts.googleapis.com/css?family=Lato:400" rel="stylesheet" type="text/css">
		<!-- bootstrap css -->
		<link rel="stylesheet" href="{{ asset('assets/frontend/css/bootstrap.min.css') }}">

		<style>
			body {
				margin: 0;
				padding: 0;
				width: 100%;
				height: 100%;
				color: #B0BEC5;
				display: table;
				font-weight: 400;
				font-family: 'Lato';
			}

			.container {
				text-align: center;
				display: table-cell;
				vertical-align: middle;
			}

			.content {
				text-align: center;
				display: block;
			}

			.title {
				font-size: 72px;
				margin-bottom: 40px;
			}

			h3.maintain-txt {
				line-height: 40px;
			}
			.maintain-img-wrapper img {
				width: 100%;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="content">
				<div class="row">
					<div class="col-lg-4 offset-lg-4">
						<div class="maintain-img-wrapper">
							<img src="{{ asset('uploads/maintainance.png') }}" alt="">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-8 offset-lg-2">
						<h3 class="maintain-txt">
						{{__('Notice')}} 
						<br>
						{{__('Website is under maintenance')}} !!! <br>
						</h3>
					</div>
				</div>
			</div>
			@php
				$checkPass = App\Domain::where('user_id', domain_info('user_id'))->first()->maintainance_mode_password ?? '';
			@endphp
			@if(!empty($checkPass))
			<a href="#" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i>{{__('You are shop admin')}} ?</a>
			@endif
		</div>


	<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<form id="ajaxFormLoad" method="post" class="modal-form">
					@csrf
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle">{{__('Maintenance Password')}}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">      
						<div class="form-group">
							<label for="">Password **</label>
							<input min="1" type="password" class="form-control ltr" name="password" placeholder="Enter Password">
							<p id="password" class="mb-0 text-danger em"></p>
							
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
						<button type="submit" id="basicbtn" class="btn btn-primary">{{ __('Submit') }}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script src="{{asset('frontend/norda/js/vendor/jquery-v3.6.0.min.js')}}"></script>
	<script src="{{asset('frontend/norda/js/vendor/bootstrap.bundle.min.js')}}"></script>
	</body>
</html>
