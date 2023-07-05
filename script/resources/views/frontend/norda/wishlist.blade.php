@extends('frontend.norda.layouts.app')
@section('content')
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="active">{{ __('Wishlist') }}</li>
            </ul>
        </div>
    </div>
</div>

<div class="cart-main-area pt-60 pb-60">
    <div class="container">
        <h3 class="cart-page-title">{{ __('Wishlist') }}</h3>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <form action="#">
                    <div class="table-content table-responsive cart-table-content">
                        <table>
                            <thead>
                                <tr>
    								<th>{{ __('Image') }}</th>
    								<th>{{ __('Name') }}</th>
    								<th>{{ __('Price') }}</th>
    								<th>{{ __('Action') }}</th>
    							</tr>
                            </thead>
                            <tbody>
                                @foreach(Cart::instance('wishlist')->content() as $row)
                                <tr>
                                    <td class="product-thumbnail">
                                        <a href="#"><img src="{{ $row->options->preview }}" height="100" alt=""></a>
                                    </td>
                                    <td class="product-name">
                                        <a href="{{ url('/product/'.$row->name.'/'.$row->id) }}">{{ $row->name }} @foreach ($row->options->attribute as $attribute)

                                           <p><b>{{ $attribute->attribute->name }}</b> : {{ $attribute->variation->name }}</p>
                                           @endforeach
                                           @foreach ($row->options->options as $op)
                                           <small>{{ $op->name }}</small>,
                                           @endforeach
                                       </a>
                                    </td>
                                    <td class="product-price-cart"><span class="amount">{{ amount_format($row->price) }}</span></td>
                                    <td class="product-wishlist-cart">
                                        <a href="{{ url('/wishlist/remove',$row->rowId) }}">{{__('Remove')}}</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@push('js')
{{--<script src="{{ asset('frontend/norda/js/category.js')}}"></script>--}}
@endpush
