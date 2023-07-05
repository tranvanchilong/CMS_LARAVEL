@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Support')])
@endsection
@section('content')
<div class="container w-75">
    <div class="w-100 row">
    @if ($contact_list->count())
        @foreach ($contact_list as $contact)
        <div class="mb-4 col-3 text-center">
            <a href="{{$contact->url}}" target="_blank" rel="noopener noreferrer">
                <img class="rounded img-fluid img-thumbnail w-50" src="{{asset($contact->image ?? '') }}" class="chat" alt="Contact">
            </a>
        </div>
        @endforeach
    @endif
    </div>
</div>
@endsection
@push('js')
<script type="text/javascript" src="{{ asset('uploads/support.js') }}"></script>
@endpush