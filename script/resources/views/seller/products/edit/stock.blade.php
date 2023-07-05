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
								<a class="nav-link" href="{{ url('seller/product/'.$info->id.'/price') }}"><i class="fas fa-money-bill-alt"></i> {{ __('Price') }}</a>
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
                                <a class="nav-link active" href="{{ url('seller/product/'.$info->id.'/inventory') }}"><i class="fa fa-cubes"></i> {{ __('Inventory') }}</a>
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
						<form class="basicform_with_reload" method="post" action="{{ route('seller.products.stock_single',$info->id) }}">
							@csrf
	                        @method('PUT')
	                        <div class="form-group">
	                            <label for="sku">{{ __('SKU') }}</label>
	                            <input type="text" name="sku"  value="{{ $info->stock->sku }}" class="form-control">
	                        </div>

	                        <div class="form-group">
	                            <label for="stock_manage">{{ __('Manage Stock') }}</label>
	                           <select name="stock_manage" id="stock_manage" class="form-control target1">
	                               <option value="1" @if(($info->stock_single->stock_manage ?? 1) == 1) selected @endif>{{ __('Manage Stock') }}</option>
	                               <option value="0" @if(($info->stock_single->stock_manage ?? 1) == 0) selected @endif>{{ __('Dont Need To Manage Stock') }}</option>
	                           </select>
	                        </div>

	                        <div class="form-group stock_status"  @if(($info->stock_single->stock_manage ?? 1) == 0) style="display:none" @endif>
	                            <label for="stock_status">{{ __('Stock Status') }}</label>
	                           <select name="stock_status" id="stock_status" class="form-control">
	                               <option value="1" @if(($info->stock_single->stock_status ?? 1) == 1) selected @endif>{{ __('In Stock') }}</option>
	                               <option value="0" @if(($info->stock_single->stock_status ?? 1) == 0) selected @endif>{{ __('Out Of Stock') }}</option>
	                           </select>
	                        </div>
	                        <div class="form-group stock_quantity" @if(($info->stock_single->stock_manage ?? 1) == 0) style="display:none" @endif >
	                            <label for="stock_qty">{{ __('Stock Quantity') }}</label>
	                            <input type="text" name="stock_qty"  value="{{ $info->stock_single->stock_qty ?? null }}" class="form-control" required="">
	                        </div>

	                        <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
	                    </form>
                        @if ($countVariation)
                        <form class="basicform_with_reload mt-4 variants_status" method="post" action="{{ route('seller.products.stock',$info->id) }}">
							@csrf
	                        @method('PUT')
							<table class="table table-hover table-border table-responsive">
								<thead >
									<tr>
										<th class="text-left" >{{ __('Variants') }}</th>
										<th>{{ __('SKU') }}</th>
										<th >{{ __('Manage Stock')  }}</th>
										<th>{{ __('Stock Status') }}</th>
										<th >{{ __('Stock Quantity')  }}</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($info->stocks as $in)
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
										<td><span class="badge badge-success">{{$variation_name}}</span></td>
										<td >
											<input type="text" name="stocks[{{ $in->id }}][sku]"  value="{{ $in->sku }}" class="form-control">
										</td>
										<td>
											<select name="stocks[{{ $in->id }}][stock_manage]" id="stock_manage" class="form-control">
												<option value="1" @if($in->stock_manage == 1) selected @endif>{{ __('Manage Stock') }}</option>
												<option value="0" @if($in->stock_manage == 0) selected @endif>{{ __('Dont Need To Manage Stock') }}</option>
											</select>
										</td>
										<td>
											<select name="stocks[{{ $in->id }}][stock_status]" id="stock_status" class="form-control">
												<option value="1" @if($in->stock_status == 1) selected @endif>{{ __('In Stock') }}</option>
												<option value="0" @if($in->stock_status == 0) selected @endif>{{ __('Out Of Stock') }}</option>
											</select>
										</td>
										<td>
											<input type="text" name="stocks[{{ $in->id }}][stock_qty]"  value="{{ $in->stock_qty }}" class="form-control" required="">
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
@push('js')

<script type="text/javascript" src="{{ asset('assets/js/form.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/stock.js') }}"></script>
<script>
$( ".target1" ).change(function() {
	if(this.value == 0)
	{
		$(".stock_status").css({ display: "none" });
		$(".stock_quantity").css({ display: "none" });
	}
	else {
		$(".stock_status").css({ display: "" });
		$(".stock_quantity").css({ display: "" });
	}

});
</script>

@endpush
