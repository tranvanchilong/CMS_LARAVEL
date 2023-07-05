@extends('frontend.norda.account.layout.app')
@section('user_content')
<h3>Notifications</h3>
<div class="myaccount-table table-responsive text-center">
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <tr>
				<th><i class="fa fa-image"></i></th>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Description') }}</th>
			</tr>
        </thead>
        <tbody>
            @foreach($notification as $notice)
			<tr>
                <td><img src="{{asset($notice->image ?? 'uploads/default.png') }}" height="50"></td>
                <td>{{ $notice->title }}</td>
                <td>{{ $notice->description }}</td>
			</tr>
			@endforeach
        </tbody>
    </table>
</div>

@endsection