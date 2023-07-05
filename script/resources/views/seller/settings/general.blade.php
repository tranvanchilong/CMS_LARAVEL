@extends('layouts.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-colorpicker.min.css') }}">
<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
@endpush
@section('head')
@include('layouts.partials.headersection',['title'=>__('Shop Settings')])
@endsection
@section('content')
<div class="row">
    <div class="col-12 col-sm-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4>{{ __('Settings') }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-4">
                        <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" id="home-tab4" data-toggle="tab" href="#home4" role="tab" aria-controls="home" aria-selected="true">{{ __('General') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab4" data-toggle="tab" href="#profile4" role="tab" aria-controls="profile" aria-selected="false">{{ __('Location') }}</a>
                            </li>
                            @php
                            $plan=user_limit();
                            $plan_check=$plan;
                            $plan=filter_var($plan['pwa']);
                            @endphp
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab4" data-toggle="tab" @if($plan==true) href="#pwa" @endif role="tab" aria-controls="profile" aria-selected="false">{{ __('PWA Settings') }}@if($plan != true) <i class="fa fa-lock text-danger"></i> @endif</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab22" data-toggle="tab" @if(filter_var($plan_check['custom_css'])==true) href="#css_area" @endif role="tab" aria-controls="profile" aria-selected="false">{{ __('Additional Css') }} @if(filter_var($plan_check['custom_css']) != true) <i class="fa fa-lock text-danger"></i> @endif</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab33" data-toggle="tab" @if(filter_var($plan_check['custom_js'])==true) href="#js_area" @endif role="tab" aria-controls="profile" aria-selected="false">{{ __('Additional Js') }} @if(filter_var($plan_check['custom_js']) != true) <i class="fa fa-lock text-danger"></i> @endif</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab4" data-toggle="tab" href="#contact4" role="tab" aria-controls="contact" aria-selected="false">{{ __('Theme Settings') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab4" data-toggle="tab" href="#feature_settings" role="tab" aria-controls="contact" aria-selected="false">{{ __('Feature Settings') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab4" data-toggle="tab" href="#text_settings" role="tab" aria-controls="contact" aria-selected="false">{{ __('Text Settings') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab4" data-toggle="tab" href="#certificate_settings" role="tab" aria-controls="contact" aria-selected="false">{{ __('Certificate Settings') }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-sm-12 col-md-8">
                        <div class="tab-content no-padding" id="myTab2Content">
                            <div class="tab-pane fade active show" id="home4" role="tabpanel" aria-labelledby="home-tab4">
                                <form method="post" action="{{ route('seller.settings.store') }}" class="basicform">
                                    @csrf
                                    <input type="hidden" name="type" value="general">
                                    <div class="form-group">
                                        <label>{{  __('Business Name') }}</label>
                                        <input type="text" name="shop_name" class="form-control" required="" value="{{ $shop_name->value ?? '' }}">
                                    </div>

                                    <div class="form-group">
                                        <label>{{  __('Business Description') }}</label>
                                        <textarea class="form-control" required="" name="shop_description">{{ $shop_description->value ?? '' }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Notification & Reply-to Email') }}</label>
                                        <input type="email" name="store_email" class="form-control" required="" placeholder="reply@example.com" value="{{ $store_email->value ?? '' }}">
                                    </div>

                                    <div class="form-group">
                                        <label>{{ __('Order ID Format (Prefix)') }}</label>
                                        <input type="text" name="order_prefix" class="form-control" required="" placeholder="#ABC" value="{{ $order_prefix->value ?? ''  }}">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Currency Position') }}</label>
                                        <select class="form-control" name="currency_position">
                                            @if(!empty($currency))
                                            <option value="left" @if($currency->currency_position  == 'left') selected="" @endif>Left</option>
                                            <option value="right" @if($currency->currency_position  == 'right') selected="" @endif>Right</option>
                                            @else
                                            <option value="left" >{{ __('Left') }}</option>
                                            <option value="right" >{{ __('Right') }}</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Currency Name') }}</label>
                                        <input type="text" name="currency_name" class="form-control" required="" placeholder="USD" value="{{ $currency->currency_name ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Currency Icon') }}</label>
                                        <input type="text" name="currency_icon" class="form-control" required="" placeholder="$" value="{{ $currency->currency_icon ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Tax') }}</label>
                                    <input type="number" name="tax" class="form-control" required="" placeholder="0.00" value="{{ $tax->value ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('I will sale (shop type)') }}</label>
                                        <select class="form-control" name="shop_type">
                                            <option value="1" @if($domain->shop_type == 1) selected="selected" @endif>{{ __('I will sale physical products') }}</option>
                                            <option value="0" @if($domain->shop_type == 0) selected="selected" @endif>{{ __('I will sale digital products') }}</option>
                                            <option value="2" @if($domain->shop_type == 2) selected="selected" @endif>{{ __('I will sale courses (LMS)') }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Order Receive Method') }}</label>

                                        <select class="form-control" name="order_receive_method">
                                            <option value="whatsapp" @if($order_receive_method == 'whatsapp') selected="selected" @endif>{{ __('I will Receive My Order Via Whatsapp') }}</option>
                                            <option value="email" @if($order_receive_method == 'email') selected="selected" @endif>{{ __('I will Receive My Order Via Email') }}</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>{{ __('Languages') }}</label>

                                        <select class="form-control select2 col-sm-12" name="lanugage[]" multiple="">
                                            @foreach($langlist ?? [] as $key => $row)

                                            <option value="{{ $row }},{{ $key }}" @if(in_array($key, $my_languages)) selected="" @endif>{{ $key }}</option>

                                            @endforeach

                                        </select>
                                        @foreach($active_languages ?? [] as $key => $row)
                                            <span>{{$key}}</span>
                                            <input type="file" class="form-control" value="{{$key}}" name="flag[]">
                                        @endforeach

                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Default Language') }}</label>

                                        <select class="form-control col-sm-12" name="local">
                                            @foreach($langlist ?? [] as $key => $row)

                                            <option value="{{ $key }}" @if($key == $local) selected="" @endif>{{ $row }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Server Key Firebase') }}</label>
                                        <textarea name="push_firebase" class="form-control">{{$key_firebase->value ?? ''}}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <button class="btn btn-primary  col-3 basicbtn" type="submit">{{ __('Save') }}</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="profile4" role="tabpanel" aria-labelledby="profile-tab4">
                                <form method="post" action="{{ route('seller.settings.store') }}" class="basicform">
                                    @csrf
                                    <input type="hidden" name="type" value="location">
                                    <div class="form-group">
                                        <label>{{ __('Company') }}</label>
                                        <input class="form-control" name="company_name" value="{{ $location->company_name ?? '' }}" type="text" value="" required="">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Address') }}</label>
                                        <input class="form-control" name="address" value="{{ $location->address ?? '' }}" type="text" value="" required="">
                                    </div>

                                    <div class="form-group">
                                        <label>{{ __('City') }}</label>
                                        <input class="form-control" name="city" value="{{ $location->city ?? '' }}" type="text" value="" required="">
                                    </div>
                                    <div class="form-row">
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('State') }}</label>
                                                <input class="form-control" name="state" value="{{ $location->state ?? '' }}" type="text" required="">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('Postal / Zip Code') }}</label>
                                                <input class="form-control" name="zip_code" value="{{ $location->zip_code ?? '' }}" type="text" required="" placeholder="1234">
                                            </div>
                                        </div>
                                    </div>
                            
                                    <div class="form-group">
                                        <label>{{ __('Email') }}</label>
                                        <input class="form-control" name="email" type="email" value="{{ $location->email ?? '' }}" value="" required="">
                                    </div>

                                    <div class="form-group">
                                        <label>{{ __('Phone') }}</label>
                                        <input class="form-control" name="phone" type="text" value="{{ $location->phone ?? '' }}" value="" required="">
                                    </div>

                                    <div class="form-group">
                                        <label>{{ __('Invoice Description') }}</label>
                                        <textarea class="form-control" name="invoice_description">{{  $location->invoice_description ?? '' }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <button class="btn btn-primary float-right col-3 basicbtn" type="submit">Save</button>
                                    </div>

                                </form>
                            </div>
                            @if($plan==true)
                            <div class="tab-pane fade" id="pwa" role="tabpanel" aria-labelledby="profile-tab4">
                                <form method="post" action="{{ route('seller.settings.store') }}" enctype="multipart/form-data" class="basicform">
                                    @csrf
                                    <input type="hidden" name="type" value="pwa_settings">
                                    <div class="form-group">
                                        <label>{{ __('APP Title') }}</label>
                                        <input class="form-control" name="pwa_app_title" value="{{ $pwa->name ?? '' }}" type="text" value="" required="">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('App Name (Short Name)') }}</label>
                                        <input class="form-control" name="pwa_app_name" value="{{ $pwa->short_name ?? '' }}" type="text" value="" required="">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('APP Background Color (Dont use color code)') }}</label>
                                        <input class="form-control" name="pwa_app_background_color" value="{{ $pwa->background_color ?? '' }}" type="text" value="" required="">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('APP Theme Color') }}</label>
                                        <input class="form-control" name="pwa_app_theme_color" value="{{ $pwa->theme_color ?? '' }}" type="text" value="" required="">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('APP Main Language') }}</label>
                                        <input class="form-control" name="app_lang" value="{{ $pwa->theme_color ?? '' }}" type="text" value="" required="" placeholder="en-US">
                                        <small>{{ __('Example: en-US') }}</small>
                                    </div>

                                    <div class="form-group">
                                        <label>{{ __('App Icon 128x128') }}</label>
                                        <input class="form-control" name="app_icon_128x128"  type="file"  required="" accept="image/.png">
                                    </div>

                                    <div class="form-group">
                                        <label>{{ __('App Icon 144x144') }}</label>
                                        <input class="form-control" name="app_icon_144x144"  type="file"  required="" accept="image/.png">
                                    </div>

                                    <div class="form-group">
                                        <label>{{ __('App Icon 152x152') }}</label>
                                        <input class="form-control" name="app_icon_152x152"  type="file"  required="" accept="image/.png">
                                    </div>

                                    <div class="form-group">
                                        <label>{{ __('App Icon 192x192') }}</label>
                                        <input class="form-control" name="app_icon_192x192"  type="file"  required="" accept="image/.png">
                                    </div>

                                    <div class="form-group">
                                        <label>{{ __('App Icon 512x512') }}</label>
                                        <input class="form-control" name="app_icon_512x512"  type="file"  required="" accept="image/.png">
                                    </div>

                                    <div class="form-group">
                                        <label>{{ __('App Icon 256x256') }}</label>
                                        <input class="form-control" name="app_icon_256x256"  type="file"  required="" accept="image/.png">
                                    </div>

                                    <div class="form-group">
                                        <button class="btn btn-primary float-right col-3 basicbtn" type="submit">{{ __('Save') }}</button>
                                    </div>
                                </form>
                            </div>
                            @endif

                            <div class="tab-pane fade" id="css_area" role="tabpanel" aria-labelledby="contact-tab4">
                                <form method="post" action="{{ route('seller.settings.store') }}" class="basicform">
                                    <input type="hidden" name="type" value="css">
                                    @csrf
                                    <div class="form-group">
                                        <label>Css</label>
                                        <textarea class="form-control" name="css" required="">{{ $css }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <button class="btn btn-primary float-right col-3 basicbtn" type="submit">{{ __('Save') }}</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="js_area" role="tabpanel" aria-labelledby="contact-tab4">
                                <form method="post" action="{{ route('seller.settings.store') }}" class="basicform">
                                    <input type="hidden" name="type" value="js">
                                    @csrf
                                    <div class="form-group">
                                        <label>Js</label>
                                        <textarea class="form-control" name="js" required="">{{ $js }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <button class="btn btn-primary float-right col-3 basicbtn" type="submit">{{ __('Save') }}</button>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="contact4" role="tabpanel" aria-labelledby="contact-tab4">
                                <form method="post" action="{{ route('seller.settings.store') }}" class="basicform" enctype="multipart/form-data">
                                    <input type="hidden" name="type" value="theme_settings">
                                    @csrf
                                    <div class="form-row">
                                        <div class="col-6 col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('Theme Color') }}</label>
                                                <input type="text" name="theme_color" class="form-control rgcolorpicker" required="" value="{{ $theme_color->value ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('Footer Background') }}</label>
                                                <input type="text" name="footer_background" class="form-control rgcolorpicker" value="{{ $footer_background->value ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('Title Color') }}</label>
                                                <input type="text" name="title_color" class="form-control rgcolorpicker" required="" value="{{ $title_color->value ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('Title Background') }}</label>
                                                <input type="text" name="title_background" class="form-control rgcolorpicker" required="" value="{{ $title_background }}">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('Text Color') }}</label>
                                                <input type="text" name="text_color" class="form-control rgcolorpicker" required="" value="{{ $text_color->value ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="col-6 col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('Menu Size') }}</label>
                                                <input type="number" name="menu_size" class="form-control" value="{{ $menu_size }}" placeholder="Default 16px">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('Title Size') }}</label>
                                                <input type="number" name="title_size" class="form-control" value="{{ $title_size }}" placeholder="Default 30px">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('Subtitle Size') }}</label>
                                                <input type="number" name="subtitle_size" class="form-control" value="{{ $subtitle_size }}" placeholder="Default 22px">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('Text Size') }}</label>
                                                <input type="number" name="text_size" class="form-control" value="{{ $text_size }}" placeholder="Default 16px">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('Hero Title Size') }}</label>
                                                <input type="number" name="hero_title_size" class="form-control" value="{{ $hero_title_size }}" placeholder="Default 34px">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('Hero Subtitle Size') }}</label>
                                                <input type="number" name="hero_subtitle_size" class="form-control" value="{{ $hero_subtitle_size }}" placeholder="Default 26px">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-6 col-md-4">
                                            <div class="form-group">
                                                <label>{{ __('Config Font ') }}</label>
                                                <select class="form-control" name="font">
                                                    <option value="Manrope" {{$font == 'Manrope' ? 'selected' : ''}}>{{ __('Manrope') }}</option>
                                                    <option value="Nunito" {{$font == 'Nunito' ? 'selected' : ''}}>{{ __('Nunito') }}</option>
                                                    <option value="Poppins" {{$font == 'Poppins' ? 'selected' : ''}}>{{ __('Poppins') }}</option>
                                                    <option value="Roboto" {{$font == 'Roboto' ? 'selected' : ''}}>{{ __('Roboto') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-4">
                                            <div class="form-group">
                                                <label>{{ __('Fill Theme') }}</label>
                                                <select class="form-control" name="fill_theme">
                                                    <option value="disable" {{$fill_theme == 'disable' ? 'selected' : ''}}>{{ __('Disable') }}</option>
                                                    <option value="enable" {{$fill_theme == 'enable' ? 'selected' : ''}}>{{ __('Enable') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-4">
                                            <div class="form-group">
                                                <label>{{ __('Fill Title') }}</label>
                                                <select class="form-control" name="fill_title">
                                                    <option value="disable" {{$fill_title == 'disable' ? 'selected' : ''}}>{{ __('Disable') }}</option>
                                                    <option value="enable" {{$fill_title == 'enable' ? 'selected' : ''}}>{{ __('Enable') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-6 col-md-4">
                                            <div class="form-group">
                                                <label>{{ __('Account Status') }}</label>
                                                <select class="form-control" name="account_status">
                                                    <option value="1" @if($account_status == 1) selected="selected" @endif>{{ __('Active') }}</option>
                                                    <option value="0" @if($account_status == 0) selected="selected" @endif>{{ __('Deactive') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-4">
                                            <div class="form-group">
                                                <label>{{ __('Cart Status') }}</label>
                                                <select class="form-control" name="cart_status">
                                                    <option value="1" @if($cart_status == 1) selected="selected" @endif>{{ __('Active') }}</option>
                                                    <option value="0" @if($cart_status == 0) selected="selected" @endif>{{ __('Deactive') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-4">
                                            <div class="form-group">
                                                <label>{{ __('Wishlist Status') }}</label>
                                                <select class="form-control" name="wishlist_status">
                                                    <option value="1" @if($wishlist_status == 1) selected="selected" @endif>{{ __('Active') }}</option>
                                                    <option value="0" @if($wishlist_status == 0) selected="selected" @endif>{{ __('Deactive') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-6 col-md-4">
                                            <div class="form-group">
                                                <label>{{ __('Slide Type') }}</label>
                                                <select class="form-control" name="slide_type">
                                                    <option value="1" {{$slide_type == 1 ? 'selected' : ''}}>{{ __('Full Screen') }}</option>
                                                    <option value="0" {{$slide_type == 0 ? 'selected' : ''}}>{{ __('None') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-4">
                                            <div class="form-group">
                                                <label>{{ __('Menu Type') }}</label>
                                                <select class="form-control" name="menu_type">
                                                    <option value="1" {{$domain->menu_type == 1 ? 'selected' : ''}}>{{ __('Menu Shop') }}</option>
                                                    <option value="0" {{$domain->menu_type == 0 ? 'selected' : ''}}>{{ __('Menu Business') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                    <label>{{ __('Social Links') }}</label>
                                    <table class="table table-bordered table-striped" id="user_table">
                                        <thead>
                                            <tr>
                                                <th width="35%">{{ __('Url') }}</th>
                                                <th width="35%">{{ __('Icon Class') }} (<a href="https://fontawesome.com/" target="_blank">fontawesome</a>)</th>
                                                <th width="30%"><button  type="button" name="add" id="add" class="btn btn-success btn-sm">{{ __('Add New') }}</button></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($socials ?? [] as $key => $row)

                                            <tr>
                                                <td><input type="text" name="url[]" class="form-control" required value="{{ $row->url }}" /></td>
                                                <td><input type="text" name="icon[]" class="form-control" placeholder="fa fa-facebook" required value="{{ $row->icon }}" /></td>
                                                <td><button type="button" name="remove" id="" class="btn btn-danger remove">{{ __('Remove') }}</button></td>
                                            </tr>

                                            @endforeach

                                        </tbody>

                                    </table>
                                    <div class="form-group">
                                        <button class="btn btn-primary float-right col-3 basicbtn" type="submit">{{ __('Save') }}</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="feature_settings" role="tabpanel" aria-labelledby="contact-tab4">
                                <form method="post" action="{{ route('seller.settings.store') }}" class="basicform" enctype="multipart/form-data">
                                    <input type="hidden" name="type" value="feature_settings">
                                    @csrf
                                    <div class="form-row">
                                        <div class="col-6 col-md-4">
                                            <div class="form-group">
                                                <label>{{ __('Wallet Status') }}</label>
                                                <select class="form-control" name="wallet_status">
                                                    <option value="1" @if($wallet_status == 1) selected="selected" @endif>{{ __('Active') }}</option>
                                                    <option value="0" @if($wallet_status == 0) selected="selected" @endif>{{ __('Deactive') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-4">
                                            <div class="form-group">
                                                <label>{{ __('Booking Status') }}</label>
                                                <select class="form-control" name="booking_status">
                                                    <option value="1" @if($booking_status == 1) selected="selected" @endif>{{ __('Enable') }}</option>
                                                    <option value="0" @if($booking_status == 0) selected="selected" @endif>{{ __('Disable') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-4">
                                            <div class="form-group">
                                                <label>{{ __('Affiliate Status') }}</label>
                                                <select class="form-control" name="affiliate_status">
                                                    <option value="1" @if($affiliate_status == 1) selected="selected" @endif>{{ __('Enable') }}</option>
                                                    <option value="0" @if($affiliate_status == 0) selected="selected" @endif>{{ __('Disable') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-4">
                                            <div class="form-group">
                                                <label>{{ __('Show/Hide Product Price') }}</label>
                                                <select class="form-control" name="hide_price_product">
                                                    <option value="1" @if($hide_price_product == 1) selected="selected" @endif>{{ __('Hide') }}</option>
                                                    <option value="0" @if($hide_price_product == 0) selected="selected" @endif>{{ __('Show') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary float-right col-3 basicbtn" type="submit">{{ __('Save') }}</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="text_settings" role="tabpanel" aria-labelledby="contact-tab4">
                                <form method="post" action="{{ route('seller.settings.store') }}" class="basicform" enctype="multipart/form-data">
                                    <input type="hidden" name="type" value="text_settings">
                                    @csrf
                                    <div class="form-group">
                                        <label>{{ __('Booking Note') }}</label>
                                        <textarea class="form-control" name="booking_setting"placeholder="Enter Content">{{$booking_setting}}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Footer Text') }}</label>
                                        <textarea class="form-control" name="footer_text"placeholder="Enter Content">{{$footer_text}}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary float-right col-3 basicbtn" type="submit">{{ __('Save') }}</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="certificate_settings" role="tabpanel" aria-labelledby="contact-tab4">
                                <form method="post" action="{{ route('seller.settings.store') }}" class="basicform" enctype="multipart/form-data">
                                    <input type="hidden" name="type" value="certificate_settings">
                                    @csrf
                                    <div class="form-group">
                                        <label>{{ __('Certificate Status') }}</label>
                                        <input type="checkbox" name="certificate_status" value="active" @if(data_get($certificate,'certificate_status') == 'active') checked="checked" @endif />
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Certificate Id') }}</label>
                                        <input class="form-control" name="certificate_id"  type="number" value="{{ $certificate->certificate_id ?? '' }}" required="">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Certificate Image') }}</label>
                                        <input class="form-control" name="certificate_image"  type="text" value="{{ $certificate->certificate_image ?? '' }}" required="">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary float-right col-3 basicbtn" type="submit">{{ __('Save') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('js')
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/form.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('assets/js/color.js') }}"></script>
<script>
    $(document).ready(function() {
    $('.test').select2();
});
</script>
@endpush
