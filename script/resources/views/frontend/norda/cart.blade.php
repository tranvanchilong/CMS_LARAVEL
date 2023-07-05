@extends('frontend.norda.layouts.app')
@section('content')
<div id="blockDiv" class="hidden">
    <div class="blockPage"></div>
    <div class="blockUI">
        <img class="height" src="{{ asset('uploads/loading-gif.gif') }}">
    </div>
</div>
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{url('/')}}">{{__('Home')}}</a>
                </li>
                <li class="active">{{__('Cart')}} </li>
            </ul>
        </div>
    </div>
</div>

<div class="cart-main-area pt-60 pb-60">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">

                    <div class="table-content table-responsive cart-table-content">
                        <table>
                            <thead>
                                <tr>
                                    <th class="f-14"><i class="fa fa-image"></i></th>
                                    <th class="f-14">{{ __('Product') }}</th>
                                    <th class="f-14"></th>
                                    <th class="f-14">{{ __('Variations') }}</th>
                                    <th class="f-14">{{ __('Option') }}</th>
                                    <th class="f-14">{{ __('Price') }}</th>
                                    <th class="f-14">{{ __('Quantity') }}</th>
                                    <th class="f-14">{{ __('Total') }}</th>
                                    <th class="f-14"><a href="#"><i class="icon_close"></i></a></th>
                                </tr>
                            </thead>
                            <tbody id="cart-data">
                                @foreach(Cart::content() as $key => $row)

                                <tr>
                                    <form class="basicform_with_reloadpage" id="form-{{$key}}" method="post" action="{{url('/update_cart')}}">
                                    @csrf
                                    <input type="hidden" name="rowId" value="{{ $key }}">
                                        <td class="product-thumbnail">
                                            <a href="{{ url('/product/'.$row->name.'/'.$row->id) }}"><img src="{{ $row->options->preview }}" alt="" height="100"></a>
                                        </td>
                                        <td class="product-name"><a href="{{ url('/product/'.$row->name.'/'.$row->id) }}">{{ $row->name }}</a></td>
                                        <td>
                                            @foreach ($row->options->attribute_full->groupBy('category_id') as $attributes)
                                            <div class="d-flex" style="padding-bottom:10px;">
                                                <label>{{ $attributes->first()->attribute->name }} :</label>
                                            </div>
                                            @endforeach
                                        </td>
                                        @if(!empty(count($row->options->attribute_full)) && empty(count($row->options->attribute)))
                                        <td>
                                            @foreach ($row->options->attribute_full->groupBy('category_id') as $attributes)
                                            <div class="d-flex pb-11">
                                            <select data-id="{{$key}}" class="form-control outline empty-variant" name="variation[{{$attributes->first()->category_id}}]" required="">
                                                <option value="" disabled="" selected="" hidden="">Select</option>
                                                @foreach ($attributes as $attribute)
                                                <option
                                                    {{in_array($attribute->variation->id, $row->options->attribute ? $row->options->attribute->pluck('variation_id')->toArray() : []) ? 'selected' : ''}} value="{{$attribute->variation->id}}">{{$attribute->variation->name}}
                                                </option>
                                                @endforeach
                                            </select>

                                            </div>
                                            <span class="ft-size">{{ __('Please update the item type')}}</span>
                                            @endforeach
                                        </td>
                                        @else
                                        <td>
                                            @foreach ($row->options->attribute_full->groupBy('category_id') as $attributes)
                                            <div class="d-flex" style="padding-bottom:10px;">
                                            <select data-id="{{$key}}" class="form-control variant" name="variation[{{$attributes->first()->category_id}}]" required="">
                                                <option value="" disabled="" selected="" hidden="">Select</option>
                                                @foreach ($attributes as $attribute)
                                                <option
                                                    {{in_array($attribute->variation->id, $row->options->attribute ? $row->options->attribute->pluck('variation_id')->toArray() : []) ? 'selected' : ''}} value="{{$attribute->variation->id}}">{{$attribute->variation->name}}
                                                </option>
                                                @endforeach
                                            </select>
                                            </div>
                                            @endforeach
                                        </td>
                                        @endif
                                        <td >
                                            @foreach ($row->options->options as $op)
                                            <p>{{ $op->name }}</p>
                                            @endforeach
                                        </td>
                                        <td class="product-price-cart"><span class="amount">{{ amount_format($row->price) }}</span></td>
                                        <td class="product-quantity pro-details-quality">
                                            <div class="pro-details-quality">
                                                <div class="cart-plus-minus" data-id="{{$key}}" data-price="{{$row->price}}">
                                                    @isset($update)
                                                    <div class="dec qtybutton">-</div>
                                                    @endisset
                                                    <input class="cart-plus-minus-box quantity" type="number" name="qty"  id="qty"  value="{{ $row->qty }}"   min="1" max="999"  />
                                                    @isset($update)
                                                    <div class="inc qtybutton">+</div>
                                                    @endisset
                                                    <div class="modal fade" id="modal-delete-{{$key}}" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content"  style="width:50% !important; margin-left:15%; margin-top: 30%;">
                                                                <div class="modal-body">
                                                                    <h3>Bạn chắc chắn muốn bỏ sản phẩm này?</h3>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Không</button>
                                                                    <button type="button" class="modal-submit-delete btn btn-danger" data-dismiss="modal" data-id="{{$key}}">Đồng ý</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="product-subtotal" id="price-{{$key}}">{{ amount_format($row->price*$row->qty) }}</td>
                                        <td class="product-remove">
                                            <!-- <button class="btn btn-primary basicbtn" type="submit"><i class="fa fa-edit"></i></button> -->
                                            <!-- <a href="{{ url('/cart_remove',$row->rowId) }}"><i class="icon_close delete-full-product"></i></a> -->
                                            <a href="#" data-toggle="modal" data-target="#modal-delete-confirm-{{$key}}" data-row-id="{{$row->rowId}}"><i class="icon_close delete-full-product"></i></a>
                                        </td>
                                        <div class="modal fade" id="modal-delete-confirm-{{$key}}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content"  style="width:50% !important; margin-left:15%; margin-top: 30%;">
                                                <div class="modal-body">
                                                    <h3>Bạn chắc chắn muốn bỏ sản phẩm này?</h3>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Không</button>
                                                    <button type="button" class="btn btn-danger"> <a href="{{ url('/cart_remove') }}/{{$row->rowId}}"><p style="color: white">Xóa</p></a></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        </form>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                <br/>
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="discount-code-wrapper">
                            <div class="title-wrap">
                                <h4 class="cart-bottom-title section-bg-gray">{{ __('Coupon') }}</h4>
                            </div>
                            <div class="discount-code">
                                <p>{{ __('Enter your coupon code if you have one') }}</p>
                                <form class="basicform_with_reload" enctype="multipart/form-data" action="{{ url('/apply_coupon') }}" method="post">                         <input type="text" name="code" id="coupon_code" value="" placeholder="Coupon code" required="">
                                    <button class="cart-btn-2 basicbtn btn-main" type="submit">{{ __('Apply Coupon') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="grand-totall">
                            <div class="title-wrap">
                                <h4 class="cart-bottom-title section-bg-gary-cart">{{ __('Cart totals') }}</h4>
                            </div>
                            <h5>{{ __('Price Total') }} <span>{{ amount_format(Cart::priceTotal()) }}</span></h5>
                            <div class="total-shipping">
                                <ul>
                                    <li>{{ __('Discount') }}
                                    @if((Cart::discount() == 0))
                                        <span>{{ amount_format(Cart::discount()) }}</span>
                                    @else
                                        <span>- {{ amount_format(Cart::discount()) }}</span>
                                    @endif
                                    </li>
                                </ul>
                            </div>
                            <h5>{{ __('Sub Total') }} <span>{{ amount_format(Cart::subtotal()) }}</span></h5>
                            <div class="total-shipping">
                                <ul>
                                    <li>{{ __('Tax') }} <span>{{ amount_format(Cart::tax()) }}</span></li>
                                </ul>
                            </div>
                            <h4 class="grand-totall-title">{{__('Total')}} <span>{{ amount_format(Cart::total()) }}</span></h4>
                            <a id="submit-checkout" class="btn-main" href="{{ url('/checkout') }}">{{ __('Proceed to checkout') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script type="text/javascript" src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/form.js') }}"></script>
{{--<script src="{{ asset('frontend/norda/js/category.js')}}"></script>--}}
@endpush
