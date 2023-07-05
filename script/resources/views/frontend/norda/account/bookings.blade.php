@extends('frontend.norda.account.layout.app')
@section('user_content')
<h3>{{ __('Bookings') }}</h3>
<div class="myaccount-table table-responsive text-center">
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
				<th>{{ __('Booking Id') }}</th>
				<th>{{ __('Booking Date') }}</th>
				<th>{{ __('Location') }}</th>
				<th>{{ __('Service') }}</th>
				<th>{{ __('Category') }}</th> 
                <th>{{ __('View') }}</th>
			</tr>
        </thead>
        <tbody>
            @foreach($bookings as $row)
			<tr>
				<td><a href="{{ url('/user/booking/view',$row->id) }}">{{ $row->booking_no }}</a></td>
				<td>{{ $row->booking_date}}</td>
				<td>{{ $row->locations->name ?? ''}}</td>
				<td>{{ $row->services->name ?? ''}}</td>
				<td>{{ $row->category_services->name ?? ''}}</td>
                <td ><a href="{{ url('/user/booking/view',$row->id) }}"><i class="fa fa-eye"></i></a></td>
			</tr>
			@endforeach
        </tbody>
    </table>
</div>
{{ $bookings->links('vendor.pagination.bootstrap-4') }}
@endsection