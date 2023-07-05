(function ($) {
"use strict";

$('#type').on('change',()=>{
	var value =$('#type').val();
	if (value=='id') {
		$('#src').attr('type','number');
	}
	else{
		$('#src').attr('type','text');
	}
});

$('#product_create').on('submit', function (){
	$('#submit_btn').attr('disabled', 'disabled');
	$('#submit_btn').html('Please Wait...');
});


$('.express_form').on('submit',function(e){
	e.preventDefault();
	var required=false;
	if($('.req').length > 0){
		$('.req:checked').each(function () {
			if(this.checked == true){
				required=true;
			}
			else{
				required=false;
			}
			
	   });
	  if(required == false){
		  $('.required_option').show();
	  }
	  else{
		$('.required_option').hide(); 
	  }
	}
	else{
		required=true;
	}
	if(required == true){
	var base_url=$('#base_url').val()
	var form_data=$(this).serialize();
	var url= base_url+"/express?"+form_data;
	$('.express_url').text(url);
	$('.exp_area').show()
	}
});

})(jQuery);

function success(res){
	$('input[name="ids[]"]:checked').each(function(i){
		var ids = $(this).val();
		$('#row'+ids).remove();
	});

	var numberOfChecked = $('input:checkbox:checked').length;
	if (numberOfChecked == 0 ) {
		location.reload();
	}

}

$(document).ready(function() {
    // Listen for changes to any checkbox
    $('.custom-control-input').on('change', function() {
        // Check if all checkboxes are checked
        var allChecked = $('.custom-control-input:checked').length == $('.custom-control-input').length;
        // Update the "check all" checkbox
        $('#selectAll').prop('checked', allChecked);
    });

    // Listen for changes to the "check all" checkbox
    $('#selectAll').on('change', function() {
        // Update all checkboxes to match the "check all" checkbox
        $('.custom-control-input').prop('checked', $(this).prop('checked'));
    });

    // Listen for changes to any checkbox con
    $('.custom-control-input:checkbox').not('#selectAll').on('change', function(){
        if($('.custom-control-input:checkbox').not('#selectAll').length == $('.custom-control-input:checkbox:checked').not('#selectAll:checked').length){
            $('#selectAll').prop('checked', true);
        } else {
            $('#selectAll').prop('checked', false);
        }
    });
});
