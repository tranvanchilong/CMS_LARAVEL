(function ($) {
  "use strict";	
	$('.location').on('change',function(){
		var option=$(this).find('option:selected').data('method')
		console.log(option)
	
		$('.payment_mode').remove();
		if(option.length == 1){
		
			$.each(option, function(index, value){
				var price=parseFloat(value.slug);
				var html = '<div class="pay-top sin-payment payment_mode">';
				   html +='<input type="hidden"  name="required_shipping_mode" class="form-control" value="1">';
				   html += '<input id="payment_method_'+value.id+'" class="input-radio shipping_mode" checked type="radio" name="shipping_mode" required data-price="'+price+'"  value="'+value.id+'">';
				   html += '<label for="payment_method_'+value.id+'">'+ value.name+' </label>';
				   html += '</div>';
				// var html='<li class="wc_payment_method payment_method_bacs payment_mode"><input id="payment_method_'+value.id+'" type="radio" class="input-radio shipping_mode" name="shipping_mode" data-price="'+price+'" value="'+value.id+'"><label for="payment_method_'+value.id+'"> &nbsp&nbsp'+ value.name+'</label></li>';
				$('.shipping_methods').append(html);
				$('.bigbag-checkout-payment').show();
				$(".shipping_mode").click();
		});
		}
		else if(option.length > 1){
			$.each(option, function(index, value){
				var price=parseFloat(value.slug);
				var html = '<div class="pay-top sin-payment payment_mode">';
				   html +='<input type="hidden"  name="required_shipping_mode" class="form-control" value="1">';
				   html += '<input id="payment_method_'+value.id+'" class="input-radio shipping_mode" type="radio" name="shipping_mode" required data-price="'+price+'"  value="'+value.id+'">';
				   html += '<label for="payment_method_'+value.id+'">'+ value.name+' </label>';
				   html += '</div>';
				   $('.shipping_methods').append(html);
				   $('.bigbag-checkout-payment').show();
			});
		
		}
		
	
		
	})

	$('.checkout_form').on('submit',function(){
		var html=$('.checkout_submit_btn').html();
		$('.checkout_submit_btn').attr('disabled','disabled');
		$('.checkout_submit_btn').html('<div class="spinner-border text-light spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>&nbsp&nbsp Please Wait...</span>');
	});

	$(document).on('click','.shipping_mode',function(e) {
		var price=$(this).data('price');
		$('#shipping_charge').html(currncy_format(price));
		$('.shipping_charge').show();
		var price=parseFloat(price);
		var total_amount=parseFloat($('#total_amount').val());
		var calculate=total_amount+price;
		$('.total_cost_amount').html(currncy_format(calculate));

	});

})(jQuery); 