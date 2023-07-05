@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Product Price'])
@endsection
@section('content')

<div class="row">
	<div class="col-lg-12">

		<div class="card">
			<div class="card-body">

				<div class="row">
					<div class="col-sm-2">
						<ul class="nav nav-pills flex-column">
							<li class="nav-item">
								<a class="nav-link" href="{{ route('seller.product.edit',$info->id) }}"><i class="fas fa-cogs"></i> {{ __('Item') }}</a>
                            </li>

							<li class="nav-item">
								<a class="nav-link active" href="{{ url('seller/product/'.$info->id.'/price') }}"><i class="fas fa-money-bill-alt"></i> {{ __('Price') }}</a>
                            </li>
                            <li class="nav-item">
								<a class="nav-link " href="{{ url('seller/product/'.$info->id.'/option') }}"><i class="fas fa-tags"></i> {{ __('Options') }}</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{ url('seller/product/'.$info->id.'/varient') }}"><i class="fas fa-expand-arrows-alt"></i> {{ __('Variants') }}</a>
							</li>

							<li class="nav-item">
								<a class="nav-link" href="{{ url('seller/product/'.$info->id.'/image') }}"><i class="far fa-images"></i> {{ __('Images') }}</a>
							</li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('seller/product/'.$info->id.'/inventory') }}"><i class="fa fa-cubes"></i> {{ __('Inventory') }}</a>
                            </li>
							<li class="nav-item">
								<a class="nav-link" href="{{ url('seller/product/'.$info->id.'/files') }}"><i class="fas fa-file"></i> {{ __('Files') }}</a>
							</li>

							<li class="nav-item">
								<a class="nav-link" href="{{ url('seller/product/'.$info->id.'/seo') }}"><i class="fas fa-chart-line"></i> {{ __('SEO') }}</a>
							</li>
							<li class="nav-item">
									<a class="nav-link" href="{{ url('seller/product/'.$info->id.'/express-checkout') }}"><i class="fas fa-cart-arrow-down"></i> {{ __('Express checkout') }}</a>
								</li>
						</ul>
					</div>
					<div class="col-sm-10">
                        <form class="basicform_with_reload" method="post" action="{{ route('seller.products.price_single',$info->id) }}" class="basicform_with_reload">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="price" class="mt-2">{{ __('Current Price') }}</label>
                                </div>
                                <div class="form-group col-md-9 col-12">
                                    <input type="number" disabled value="{{ $info->price_single->price ?? null }}" step="any" class="form-control" id="price" placeholder="Enter Price"  required="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="price" class="mt-2">{{ __('Regular Price') }}</label>
                                </div>
                                <div class="form-group col-md-9 col-12">
                                    <input  type="number" value="{{ $info->price_single->regular_price ?? null }}" step="any" class="form-control" id="price" placeholder="Enter Price"  name="price" required="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="special_price" class="mt-2">{{ __('Special Price') }}</label>
                                </div>
                                <div class="form-group col-md-9 col-12">
                                    <input type="number" value="{{ $info->price_single->special_price ?? null }}" step="any" class="form-control" id="special_price" placeholder=""  name="special_price" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="special_price_type" class="mt-2">{{ __('Special Price Type') }}</label>
                                </div>
                                <div class="form-group col-md-9 col-12">
                                    <select name="price_type" id="special_price_type" class="form-control selectric">
                                    <option value="1" @if(($info->price_single->price_type ?? 1) === 1) selected @endif>{{ __('Fixed') }}</option>
                                    <option value="0" @if(($info->price_single->price_type ?? 1) === 0) selected @endif>{{ __('Percent') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="special_price_start" class="mt-2">{{ __('Special Price Start') }}</label>
                                </div>
                                <div class="form-group col-md-9 col-12">
                                    <input type="date" class="form-control" value="{{ $info->price_single->starting_date ?? null }}" id="special_price_start" placeholder=""  name="special_price_start" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="special_price_end" class="mt-2">{{ __('Special Price End') }}</label>
                                </div>
                                <div class="form-group col-md-9 col-12">
                                    <input type="date" class="form-control" id="special_price_end" value="{{ $info->price_single->ending_date ?? null }}" placeholder=""  name="special_price_end" >
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                        </form>
                        @if ($countVariation)
                        <form class="basicform_with_reload mt-4" method="post" action="{{ route('seller.products.price',$info->id) }}" class="basicform_with_reload">
                            @csrf
                            @method('PUT')
                            <table class="table table-hover table-border table-responsive">
    							<thead >
    								<tr>
    									<th class="text-left" >{{ __('Variants') }}</th>
    									<th>{{ __('Current Price') }}</th>
    									<th >{{ __('Regular Price')  }}</th>
    									<th>{{ __('Special Price') }}</th>
    									<th >{{ __('Special Price Type')  }}</th>
                                        <th >{{ __('Special Price Start')  }}</th>
                                        <th >{{ __('Special Price End')  }}</th>
    								</tr>
    							</thead>
    							<tbody>
                                    @foreach ($info->prices as $in)
                                    @php
                                        $variation_name='';
                                        if(!empty($in->variation_id_code)){
                                            foreach($in->variation_id_code as $v){
                                                $variation = \App\Category::find($v);
                                                $variation_name .= $variation->name . '-';
                                            }
                                        }
                                        $variation_name = substr($variation_name, 0, -1);
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="badge badge-success">{{$variation_name}}</span>
                                        </td>
                                        <td >
                                            <input type="number" disabled value="{{ $in->price }}" step="any" class="form-control" id="price" placeholder="Enter Price"  required="">
                                        </td>
                                        <td>
                                            <input  type="number" value="{{ $in->regular_price }}" step="any" class="form-control" id="price" placeholder="Enter Price"  name="prices[{{ $in->id }}][price]" required="">
                                        </td>
                                        <td>
                                            <input type="number" value="{{ $in->special_price }}" step="any" class="form-control" id="special_price" placeholder=""  name="prices[{{ $in->id }}][special_price]" >
                                        </td>
                                        <td >
                                            <select name="prices[{{ $in->id }}][price_type]" id="special_price_type" class="form-control selectric">
                                                <option value="1" @if($in->price_type === 1) selected @endif>{{ __('Fixed') }}</option>
                                                <option value="0" @if($in->price_type === 0) selected @endif>{{ __('Percent') }}</option>
                                            </select>
                                        </td>
                                        <td>
                                        <input type="date" class="form-control" value="{{ $in->starting_date }}" id="special_price_start" placeholder=""  name="prices[{{ $in->id }}][special_price_start]" >
                                        </td>
                                        <td>
                                        <input type="date" class="form-control" id="special_price_end" value="{{ $in->ending_date }}" placeholder=""  name="prices[{{ $in->id }}][special_price_end]" >
                                        </td>
                                    </tr>
                                    @endforeach
    							</tbody>
    						</table>
                            <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                        </form>
                        @endif

    				</div>
    			</div>
    		</div>
    	</div>
    </div>
</div>
@endsection
@push('style')
<style type="text/css">
    .table-responsive input{
        min-width: 8vw;
    }
</style>
@endpush
@push('js')

<script type="text/javascript" src="{{ asset('assets/js/form.js') }}"></script>

@endpush
