@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Review And Rattings')])
@endsection
@section('content')
<div class="">
    <div class="row justify-content-center">
        <div class="col-12">

            <div class="card">

                <div class="card-body">
                	<form method="post" action="{{ route('seller.review_course.destroys') }}" class="basicform_with_reload">
                		@csrf
                		<div class="float-left mb-2">
                			<div class="input-group">
                				<select class="form-control selectric" name="method">
                					<option disabled selected="">{{ __('Select Action') }}</option>

                					<option value="delete" class="text-danger">{{ __('Delete Permanently') }}</option>

                				</select>
                				<div class="input-group-append">
                					<button class="btn btn-primary basicbtn" type="submit">{{ __('Submit') }}</button>
                				</div>
                			</div>
                		</div>
                    <div class="table-responsive">
                        <table class="table table-hover table-nowrap card-table text-center">
                            <thead>
                                <tr>
                                	<th class="am-select">
                                		<div class="custom-control custom-checkbox">
                                			<input type="checkbox" class="custom-control-input checkAll" id="selectAll">
                                			<label class="custom-control-label checkAll" for="selectAll"></label>
                                		</div>
                                	</th>
                                    <th class="text-left" >{{ __('Rattings') }}</th>
                                    <th class="text-left" >{{ __('Comment') }}</th>
                                    <th >{{ __('Course') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Created At') }}</th>

                                </tr>
                            </thead>
                            <tbody class="list font-size-base rowlink" data-link="row">
                               @foreach($reviews as $review)
                               <tr>
                               	<td>
                               		<div class="custom-control custom-checkbox">
                               			<input type="checkbox" name="ids[]" class="custom-control-input" id="customCheck{{ $review->id }}" value="{{ $review->id }}">
                               			<label class="custom-control-label" for="customCheck{{ $review->id }}"></label>
                               		</div>
                               	</td>
                               	<td>{{ $review->rating }}</td>
                               	<td>{{ $review->comment }}</td>
                               	<td><a href="{{ url('/course/'.$review->course->slug) }}" target="_blank">{{ Str::limit($review->course->title,10) }}</a></td>
                               	<td>{{ $review->name }}</td>
                               	<td>{{ $review->email }}</td>
                               	<td>{{ $review->created_at->format('d-F-Y') }}</td>
                               </tr>
                               @endforeach
                            </tbody>
                        </table>
                    </form>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">

                    {{ $reviews->links('vendor.pagination.bootstrap-4') }}

                </div>
            </div>
        </div>
    </div>
</div>





@endsection
@push('js')

<script type="text/javascript" src="{{ asset('assets/js/form.js') }}"></script>
@endpush

