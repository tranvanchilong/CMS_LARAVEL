@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>__('Affiliate Configurations')])
@endsection
@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>{{__('User Registration Affiliate')}}</h4>
            </div>
            <div class="card-body">
                <form class="basicform form-horizontal" action="{{route('seller.affiliate.store')}}" method="POST">
                    @csrf                  
                    <div class="form-group row">
                        <input type="hidden" name="type" value="user_registration">
                        <div class="col-lg-4">
                            <label class="control-label">{{__('User Registration')}}</label>
                        </div>
                        <div class="col-lg-6">
                            <input type="number" min="0" step="0.01" max="100" class="form-control" name="percentage" value="{{$user_registration->percentage ?? 0}}" placeholder="Percentage of Order Amount" required="">
                        </div>
                        <div class="col-lg-2">
                            <label class="control-label">$</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="control-label">{{__('Status')}}</label>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input id="enabled_user_registration" class="custom-control-input" name="status" type="checkbox" value="1" {{data_get($user_registration,'status')==1 ? 'checked' : ''}}>
                                    <label class="custom-control-label" for="enabled_user_registration">{{__('Enable')}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary basicbtn">{{__('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>{{__('First Purchase Affiliate')}}</h4>
            </div>
            <div class="card-body">
                <form class="basicform form-horizontal" action="{{route('seller.affiliate.store')}}" method="POST">
                    @csrf                  
                    <div class="form-group row">
                        <input type="hidden" name="type" value="user_registration_first_purchase">
                        <div class="col-lg-4">
                            <label class="control-label">{{__('First Purchase')}}</label>
                        </div>
                        <div class="col-lg-6">
                            <input type="number" min="0" step="0.01" max="100" class="form-control" name="percentage" value="{{$first_purchase->percentage ?? 0}}" placeholder="Order Amount" required="">
                        </div>
                        <div class="col-lg-2">
                            <label class="control-label">%</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="control-label">{{__('Status')}}</label>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input id="enabled_user_registration_first_purchase" class="custom-control-input" name="status" type="checkbox" value="1" {{data_get($first_purchase,'status')==1 ? 'checked' : ''}}>
                                    <label class="custom-control-label" for="enabled_user_registration_first_purchase">{{__('Enable')}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary basicbtn">{{__('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>{{__('Next Time Purchase Affiliate')}}</h4>
            </div>
            <div class="card-body">
                <form class="basicform form-horizontal" action="{{route('seller.affiliate.store')}}" method="POST">
                    @csrf                  
                    <div class="form-group row">
                        <input type="hidden" name="type" value="user_registration_next_time_purchase">
                        <div class="col-lg-4">
                            <label class="control-label">{{__('Next Time Purchase')}}</label>
                        </div>
                        <div class="col-lg-6">
                            <input type="number" min="0" step="0.01" max="100" class="form-control" name="percentage" value="{{$next_time_purchase->percentage ?? 0}}" placeholder="Order Amount" required="">
                        </div>
                        <div class="col-lg-2">
                            <label class="control-label">%</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="control-label">{{__('Status')}}</label>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input id="enabled_user_registration_next_time_purchase" class="custom-control-input" name="status" type="checkbox" value="1" {{data_get($next_time_purchase,'status')==1 ? 'checked' : ''}}>
                                    <label class="custom-control-label" for="enabled_user_registration_next_time_purchase">{{__('Enable')}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary basicbtn">{{__('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>{{__('Product Sharing Affiliate')}}</h4>
            </div>
            <div class="card-body">
                <form class="basicform" action="{{route('seller.affiliate.store')}}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <input type="hidden" name="type" value="product_sharing">
                        <label class="col-lg-3 col-from-label">{{__('Product Sharing and Purchasing')}}</label>
                        <div class="col-lg-6">
                            <input type="number" min="0" step="0.01" max="100" class="form-control" name="amount" value="{{ !empty($product_sharing->details) ? data_get(json_decode($product_sharing->details),'commission') : '' }}" placeholder="Percentage of Order Amount" required="">
                        </div>
                        <div class="col-md-3">
                            <select class="form-control aiz-selectpicker" name="amount_type" tabindex="-98">
                                <option value="amount" {{!empty($product_sharing->details) && data_get(json_decode($product_sharing->details),'commission_type')=='amount' ? 'selected' : ''}}>$</option>
                                <option value="percent" {{!empty($product_sharing->details) && data_get(json_decode($product_sharing->details),'commission_type')=='percent' ? 'selected' : ''}}>%</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="control-label">{{__('Status')}}</label>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input id="enabled_product_sharing" class="custom-control-input" name="status" type="checkbox" value="1" {{data_get($product_sharing,'status')==1 ? 'checked' : ''}}>
                                    <label class="custom-control-label" for="enabled_product_sharing">{{__('Enable')}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary basicbtn">{{__('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<div class="row">
    {{-- <div class="col-md-6">
        <div class="card bg-gray-light">
            <div class="card-header">
                <h4>Affiliate Link Validatin Time (Days)</h4>
            </div>
            <div class="card-body">
                <form class="form-horizontal basicform" action="{{route('seller.affiliate.configs.store')}}" method="POST">
                    @csrf                                       
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <input type="hidden" class="form-control" name="type" value="validation_time">
                            <label class="control-label">Validation Time</label>
                        </div>
                        <div class="col-lg-8">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="No of Days" name="validation_time" value="{{$validation_time->value ?? ''}}">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">Days</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary basicbtn">{{__('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
    <div class="col-md-6">
        <div class="card bg-gray-light">
            <div class="card-header">
                <h4>{{__('Affiliate Customers Level')}} (Level)</h4>
            </div>
            <div class="card-body">
                <form class="form-horizontal basicform_with_reload" action="{{route('seller.affiliate.configs.store')}}" method="POST">
                    @csrf                                       
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <input type="hidden" class="form-control" name="type" value="total_level">
                            <label class="control-label">{{__('Customers Level')}}</label>
                        </div>
                        <div class="col-lg-8">
                            <div class="input-group mb-3">
                                <input type="number" class="form-control" placeholder="Min 1 Max 5" min="1" max="5" name="total_level" value="{{$total_level->value ?? ''}}">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">Level</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @foreach($levels as $level)
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="control-label">{{$level->type}}</label>
                        </div>
                        <div class="col-lg-6">
                            <input type="number" min="0" step="0.01" max="100" class="form-control" name="percentage[{{$level->type}}]" value="{{$level->percentage ?? 0}}" placeholder="Order Amount" required="">
                        </div>
                        <div class="col-lg-2">
                            <label class="control-label">%</label>
                        </div>
                    </div>
                    @endforeach
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary basicbtn">{{__('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush