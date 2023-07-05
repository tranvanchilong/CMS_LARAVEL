@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Permalinks'])
@endsection
@section('content')
@if(Session::has('success'))
<div class="row">
	<div class="col-sm-12">
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<strong>{{ Session::get('success') }}</strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	</div>
</div>
@endif
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>{{ __('Update Permalinks') }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-10 offset-lg-1">
                        <form class="ajaxFormRedirect" method="post" action="{{ route('seller.permalinks.update') }}">
                            @csrf
                            <div class="row">
                                @foreach($data as $d)
                                    @php
                                        $link = $d['default'];
                                        $value = '';
                                        if($permalinks && isset($permalinks[$d['name']]) && $permalinks[$d['name']]){
                                            $link = $permalinks[$d['name']];
                                            if($permalinks[$d['name']] != $d['default']){
                                                $value = $permalinks[$d['name']];
                                            }
                                        }
                                    @endphp
                                    <div class="form-group col-lg-6">
                                        <div class="form-divider text-capitalize">
                                            {{$d['text']}} ** <br>
                                        </div>
                                        <div class="input-group mb-2">
                                            <input type="text" name="permalinks[{{$d['name']}}]" value="{{$value}}" class="form-control">
                                        </div>
                                        <span class="text-warning"><strong class="text-info">Preview: </strong>{{url('/'.$link)}}{{$d['slug'] ? '/{slug}' : ''}} </span>
                                    </div>
                                @endforeach
                                <div class="form-group col-lg-12">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                    <div class="col-sm-12 text-center">
                                        <button class="btn btn-primary basicbtn" type="submit">{{ __('Update') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
