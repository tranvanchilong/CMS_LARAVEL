@extends('frontend.norda.account.layout.app')
@section('user_content')
<section>
	<div class="section-body">
		<div class="invoice">
			<div class="invoice-print">
				<div class="row">
					<div class="col-lg-12">
						<div class="invoice-title d-flex justify-content-between align-items-center">
							<h2>{{ __('Booking Information') }}</h2>
							<div class="invoice-number"><strong>{{ __('Booking Id') }}:</strong> {{ $info->booking_no }}</div>
						</div>
						<hr>
						<div class="row">
							
							<div class="col-md-6">
								<address>
									<strong>{{ __('Name') }}:</strong>
									{{ $info->name }}
									<br>
                                    <strong>{{ __('Phone') }}:</strong>
									{{ $info->phone }}
									<br>
                                    <strong>{{ __('Location') }}:</strong>
									{{ $info->locations->name ?? '' }}
									<br>
								</address>
							</div>
                            <div class="col-md-6 text-md-right">
								<address>
                                <strong>{{ __('Booking Date') }}:</strong>
                                    {{ $info->booking_date }}<br>
                                    <strong>Booking Status:</strong>
									@if($info->status==2)
                                        <span class="badge badge-warning">{{ __('Confirmed') }}</span>

                                        @elseif($info->status==1)
                                        <span class="badge badge-primary">{{ __('New') }}</span>

                                        @elseif($info->status==3)
                                        <span class="badge badge-success">{{ __('Completed') }}</span>

                                        @else
                                        <span class="badge badge-danger">{{ __('Canceled') }}</span>

									@endif
                                    <br>
                                    <strong>{{ __('Category') }}:</strong>
									{{ $info->category_services->name ?? '' }}
									<br>
                                    <strong>{{ __('Service') }}:</strong>
									{{ $info->services->name ?? '' }}
									<br>
								</address><br>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection