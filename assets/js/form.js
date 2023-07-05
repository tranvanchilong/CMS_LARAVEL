(function ($) {
	"use strict";
  //basicform submit
	if ($('textarea.content').length > 0)
	{
		CKEDITOR.replaceClass="content";
	}

	$("#productform").on('submit', function(e){
		e.preventDefault();
		var instance =$('.content').val();
		if (instance != null) {
			for ( instance in CKEDITOR.instances ) {
				CKEDITOR.instances[instance].updateElement();
			}
		}
		var btnhtml=$('.basicbtn').html();

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: this.action,
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData:false,
			beforeSend: function() {
       			
       			$('.basicbtn').attr('disabled','')
       			$('.basicbtn').html('Please Wait....')

    		},
			
			success: function(response){ 
				$('.basicbtn').removeAttr('disabled')
				Sweet('success',response)
				$('.basicbtn').html(btnhtml)
				success(response)
			},
			error: function(xhr, status, error) 
			{
				$('.basicbtn').removeAttr('disabled');
				$('.basicbtn').html(btnhtml);
				
				$.each(xhr.responseJSON.errors, function (key, item) 
				{
					Sweet('error',item)
					$("#errors").html("<li class='text-danger'>"+item+"</li>")
				});
				errosresponse(xhr, status, error);
			}
		})
	});

	$("#ajaxFormLoad").on('submit', function(e){
        e.preventDefault();
        var instance =$('.content').val();
        if (instance != null) {
            for ( instance in CKEDITOR.instances ) {
                CKEDITOR.instances[instance].updateElement();
            }
        }
        var btnhtml=$('.basicbtn').html();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: this.action,
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function() {
                
                $('.basicbtn').attr('disabled','')
                $('.basicbtn').html('Please Wait....')

            },
            
            success: function(data){ 
                $('.basicbtn').removeAttr('disabled')
                $('.basicbtn').html(btnhtml)
                $(".em").each(function () {
                  $(this).html('');
                })
                
                if (data[0] == "success") {
                  location.reload();
                  Sweet('success',data[1])
                }
                
                // if error occurs
                else if (typeof data.error != 'undefined') {
                  for (let x in data) {
                    console.log(x);
                    if (x == 'error') {
                      continue;
                    }
                    document.getElementById('err' + x).innerHTML = data[x][0];
                  }
                }
            },
            error: function(error) 
            {
                $('.basicbtn').removeAttr('disabled');
                $('.basicbtn').html(btnhtml);
                for (let x in error.responseJSON.errors) {
                  console.log('err'+x);
                  document.getElementById('err' + x).innerHTML = error.responseJSON.errors[x][0];
                }
            }
        })
    });

    $("#ajaxForm").on('submit', function(e){
        e.preventDefault();
        var instance =$('.content').val();
        if (instance != null) {
            for ( instance in CKEDITOR.instances ) {
                CKEDITOR.instances[instance].updateElement();
            }
        }
        var btnhtml=$('.basicbtn').html();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: this.action,
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function() {
                
                $('.basicbtn').attr('disabled','')
                $('.basicbtn').html('Please Wait....')

            },
            
            success: function(data){ 
                $('.basicbtn').removeAttr('disabled')
                $('.basicbtn').html(btnhtml)
                $(".em").each(function () {
                  $(this).html('');
                })
                
                if (data[0] == "success") {
                  Sweet('success',data[1])
                }
                
                // if error occurs
                else if (typeof data.error != 'undefined') {
                  for (let x in data) {
                    console.log(x);
                    if (x == 'error') {
                      continue;
                    }
                    document.getElementById('err' + x).innerHTML = data[x][0];
                  }
                }
            },
            error: function(error) 
            {
                $('.basicbtn').removeAttr('disabled');
                $('.basicbtn').html(btnhtml);
                for (let x in error.responseJSON.errors) {
                  console.log('err'+x);
                  document.getElementById('err' + x).innerHTML = error.responseJSON.errors[x][0];
                }
            }
        })
    });

    $("#ajaxFormUpdate").on('submit', function(e){
        e.preventDefault();
        var instance =$('.content').val();
        if (instance != null) {
            for ( instance in CKEDITOR.instances ) {
                CKEDITOR.instances[instance].updateElement();
            }
        }
        var btnhtml=$('.basicbtn').html();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: this.action,
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function() {
                
                $('.basicbtn').attr('disabled','')
                $('.basicbtn').html('Please Wait....')

            },
            
            success: function(data){ 
                $('.basicbtn').removeAttr('disabled')
                $('.basicbtn').html(btnhtml)
                $(".em").each(function () {
                  $(this).html('');
                })
                
                if (data[0] == "success") {
                  Sweet('success',data[1])
                }
                
                // if error occurs
                else if (typeof data.error != 'undefined') {
                  for (let x in data) {
                    console.log(x);
                    if (x == 'error') {
                      continue;
                    }
                    document.getElementById('eerr' + x).innerHTML = data[x][0];
                  }
                }
            },
            error: function(error) 
            {
                $('.basicbtn').removeAttr('disabled');
                $('.basicbtn').html(btnhtml);
                for (let x in error.responseJSON.errors) {
                  console.log('eerr'+x);
                  document.getElementById('eerr' + x).innerHTML = error.responseJSON.errors[x][0];
                }
            }
        })
    });

	$("#ajaxFormUpdateLoad").on('submit', function(e){
        e.preventDefault();
        var instance =$('.content').val();
        if (instance != null) {
            for ( instance in CKEDITOR.instances ) {
                CKEDITOR.instances[instance].updateElement();
            }
        }
        var btnhtml=$('.basicbtn').html();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: this.action,
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function() {
                
                $('.basicbtn').attr('disabled','')
                $('.basicbtn').html('Please Wait....')

            },
            
            success: function(data){ 
                $('.basicbtn').removeAttr('disabled')
                $('.basicbtn').html(btnhtml)
                $(".em").each(function () {
                  $(this).html('');
                })
                
                if (data[0] == "success") {
				  location.reload();
                  Sweet('success',data[1])
                }
                
                // if error occurs
                else if (typeof data.error != 'undefined') {
                  for (let x in data) {
                    console.log(x);
                    if (x == 'error') {
                      continue;
                    }
                    document.getElementById('eerr' + x).innerHTML = data[x][0];
                  }
                }
            },
            error: function(error) 
            {
                $('.basicbtn').removeAttr('disabled');
                $('.basicbtn').html(btnhtml);
                for (let x in error.responseJSON.errors) {
                  console.log('eerr'+x);
                  document.getElementById('eerr' + x).innerHTML = error.responseJSON.errors[x][0];
                }
            }
        })
    });

	$("#ajaxFormRedirect").on('submit', function(e){
        e.preventDefault();
        var instance =$('.content').val();
        if (instance != null) {
            for ( instance in CKEDITOR.instances ) {
                CKEDITOR.instances[instance].updateElement();
            }
        }
        var btnhtml=$('.basicbtn').html();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: this.action,
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function() {
                
                $('.basicbtn').attr('disabled','')
                $('.basicbtn').html('Please Wait....')

            },
            
            success: function(data){ 
                $('.basicbtn').removeAttr('disabled')
                $('.basicbtn').html(btnhtml)
                $(".em").each(function () {
                  $(this).html('');
                })
                
                if (data[0] == "success") {
				  location.href=data[2];
                  Sweet('success',data[1])
                }
                
                // if error occurs
                else if (typeof data.error != 'undefined') {
                  for (let x in data) {
                    console.log(x);
                    if (x == 'error') {
                      continue;
                    }
                    document.getElementById('err' + x).innerHTML = data[x][0];
                  }
                }
            },
            error: function(error) 
            {
                $('.basicbtn').removeAttr('disabled');
                $('.basicbtn').html(btnhtml);
                for (let x in error.responseJSON.errors) {
                  console.log('err'+x);
                  document.getElementById('err' + x).innerHTML = error.responseJSON.errors[x][0];
                }
            }
        })
    });

	$("#basicform").on('submit', function(e){
		e.preventDefault();
		
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: this.action,
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData:false,
			beforeSend: function() {
				   $('.basicbtn').attr('disabled','');
    		},
			
			success: function(response){ 
				$('.basicbtn').removeAttr('disabled')
				Sweet('success',response)
				
				success(response)
			},
			error: function(xhr, status, error) 
			{
				$('.basicbtn').removeAttr('disabled')
				$('.errorarea').show();
				$.each(xhr.responseJSON.errors, function (key, item) 
				{
					Sweet('error',item)
					$("#errors").html("<li class='text-danger'>"+item+"</li>")
				});
				errosresponse(xhr, status, error);
			}
		})


	});

	$(".basicform").on('submit', function(e){
		e.preventDefault();
		var $form = $(this).closest('form');
		var index = $('.basicform').index($form);
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		var basicbtnhtml=$('.basicbtn').html();
		$.ajax({
			type: 'POST',
			url: this.action,
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData:false,
			beforeSend: function() {
				$('.basicbtn').eq(index).html("Please Wait....");
				$('.basicbtn').eq(index).attr('disabled','')
				// $('.basicbtn').html("Please Wait....");
				// $('.basicbtn').attr('disabled','')

			},
			
			success: function(response){ 
				$('.basicbtn').eq(index).removeAttr('disabled')
				Sweet('success',response);
				$('.basicbtn').eq(index).html(basicbtnhtml);
				success(response);
			},
			error: function(xhr, status, error) 
			{
				$('.basicbtn').eq(index).html(basicbtnhtml);
				$('.basicbtn').eq(index).removeAttr('disabled')
				$('.errorarea').show();
				$.each(xhr.responseJSON.errors, function (key, item) 
				{
					Sweet('error',item)
					$("#errors").html("<li class='text-danger'>"+item+"</li>")
				});
				errosresponse(xhr, status, error);
			}
		})


	});

	$(".basicform_email").on('submit', function(e){
		e.preventDefault();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		var basicbtnhtml=$('.basicbtn').html();
		$.ajax({
			type: 'POST',
			url: this.action,
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData:false,
			beforeSend: function() {

				$('.basicbtn').html("Please Wait....");
				$('.basicbtn').attr('disabled','')

			},
			
			success: function(response){ 
				setTimeout(function() {
					$('.basicbtn').html("Mail sent successfully. Please verify your email !!!");
				}, 0);
				Sweet('success',response);
				$('.basicbtn').html(basicbtnhtml);
				success(response);
			},
			error: function(xhr, status, error) 
			{
				$('.basicbtn').html(basicbtnhtml);
				$('.basicbtn').removeAttr('disabled')
				$('.errorarea').show();
				$.each(xhr.responseJSON.errors, function (key, item) 
				{
					Sweet('error',item)
					$("#errors").html("<li class='text-danger'>"+item+"</li>")
				});
				errosresponse(xhr, status, error);
			}
		})


	});

	$(".basicform_with_reload").on('submit', function(e){
		e.preventDefault();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		var basicbtnhtml=$('.basicbtn').html();
		$.ajax({
			type: 'POST',
			url: this.action,
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData:false,
			beforeSend: function() {
				
				$('.basicbtn').html("Please Wait....");
				$('.basicbtn').attr('disabled','')

			},
			
			success: function(response){ 
				$('.basicbtn').removeAttr('disabled')
				Sweet('success',response);
				$('.basicbtn').html(basicbtnhtml);
				location.reload();
			},
			error: function(xhr, status, error) 
			{
				$('.basicbtn').html(basicbtnhtml);
				$('.basicbtn').removeAttr('disabled')
				$('.errorarea').show();
				$.each(xhr.responseJSON.errors, function (key, item) 
				{
					Sweet('error',item)
					$("#errors").html("<li class='text-danger'>"+item+"</li>")
				});
				errosresponse(xhr, status, error);
			}
		})


	});

	$(".basicform_with_reloadpage").on('submit', function(e){
		e.preventDefault();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		var basicbtnhtml=$('.basicbtn').html();
		$.ajax({
			type: 'POST',
			url: this.action,
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData:false,
			beforeSend: function() {
				$('#blockDiv').removeClass('hidden');
			},
			
			success: function(response){ 
				Sweet('success',response);
				location.reload();
			},
			error: function(xhr, status, error) 
			{
				$('.errorarea').show();
				$.each(xhr.responseJSON.errors, function (key, item) 
				{
					Sweet('error',item)
					$("#errors").html("<li class='text-danger'>"+item+"</li>")
				});
				errosresponse(xhr, status, error);
			}
		})


	});

	$(".basicform_with_reset").on('submit', function(e){
		e.preventDefault();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		var basicbtnhtml=$('.basicbtn').html();
		$.ajax({
			type: 'POST',
			url: this.action,
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData:false,
			beforeSend: function() {
				
				$('.basicbtn').html("Please Wait....");
				$('.basicbtn').attr('disabled','')

			},
			
			success: function(response){ 
				$('.basicbtn').removeAttr('disabled')
				Sweet('success',response);
				$('.basicbtn').html(basicbtnhtml);
				$('.basicform_with_reset').trigger('reset');
			},
			error: function(xhr, status, error) 
			{
				$('.basicbtn').html(basicbtnhtml);
				$('.basicbtn').removeAttr('disabled')
				$('.errorarea').show();
				$.each(xhr.responseJSON.errors, function (key, item) 
				{
					Sweet('error',item)
					$("#errors").html("<li class='text-danger'>"+item+"</li>")
				});
				errosresponse(xhr, status, error);
			}
		})


	});
	$(".basicform_with_remove").on('submit', function(e){
		e.preventDefault();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		var basicbtnhtml=$('.basicbtn').html();
		$.ajax({
			type: 'POST',
			url: this.action,
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData:false,
			beforeSend: function() {
				
				$('.basicbtn').html("Please Wait....");
				$('.basicbtn').attr('disabled','')

			},
			
			success: function(response){ 
				$('.basicbtn').removeAttr('disabled')
				Sweet('success',response);
				$('.basicbtn').html(basicbtnhtml);
				$('input[name="ids[]"]:checked').each(function(i){
					var ids = $(this).val();
					$('#row'+ids).remove();
				});

			},
			error: function(xhr, status, error) 
			{
				$('.basicbtn').html(basicbtnhtml);
				$('.basicbtn').removeAttr('disabled')
				$('.errorarea').show();
				$.each(xhr.responseJSON.errors, function (key, item) 
				{
					Sweet('error',item)
					$("#errors").html("<li class='text-danger'>"+item+"</li>")
				});
				errosresponse(xhr, status, error);
			}
		})


	});

	$(".loginform").on('submit', function(e){
		e.preventDefault();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		var basicbtnhtml=$('.basicbtn').html();
		$.ajax({
			type: 'POST',
			url: this.action,
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData:false,
			beforeSend: function() {
       			$('.basicbtn').html("Please Wait....");
       			$('.basicbtn').attr('disabled','')
    		},
			
			success: function(response){ 
				$('.basicbtn').removeAttr('disabled')
				$('.basicbtn').html(basicbtnhtml);
				location.reload();
			},
			error: function(xhr, status, error) 
			{
				$('.basicbtn').html(basicbtnhtml);
				$('.basicbtn').removeAttr('disabled')
				
				$.each(xhr.responseJSON.errors, function (key, item) 
				{
					Sweet('error',item)
					$("#errors").html("<li class='text-danger'>"+item+"</li>")
				});
				errosresponse(xhr, status, error);
			}
		})
	});

	//id basicform1 when submit 
	$("#basicform1").on('submit', function(e){
		e.preventDefault();

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: this.action,
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData:false,
			success: function(response){ 
				success(response)
			},
			error: function(xhr, status, error) 
			{
				$('.errorarea').show();

				$.each(xhr.responseJSON.errors, function (key, item) 
				{
					Sweet('error',item)
					$("#errors").html("<li class='text-danger'>"+item+"</li>")
				});
				errosresponse(xhr, status, error);
			}
		})
	});	
	
	$(".checkAll").on('click',function(){
		$('input:checkbox').not(this).prop('checked', this.checked);
	});

	$(".cancel").on('click',function(e) {
		e.preventDefault();
		var link = $(this).attr("href");
		
		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Do It!'
		}).then((result) => {
			if (result.value == true) {
				window.location.href = link;
			}
		})
	});
	
	function Sweet(icon,title,time=3000){
		
		const Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: time,
			timerProgressBar: true,
			onOpen: (toast) => {
				toast.addEventListener('mouseenter', Swal.stopTimer)
				toast.addEventListener('mouseleave', Swal.resumeTimer)
			}
		})
		
		Toast.fire({
			icon: icon,
			title: title,
		})
	}

})(jQuery);	

function copyUrl(id){
	var copyText = document.getElementById("myUrl"+id);
	copyText.select();
	copyText.setSelectionRange(0, 99999)
	document.execCommand("copy");
	Sweet('success','Link copied to clipboard.');
}
function checkPermissionByGroup(className, checkThis){
    const groupIdName = $("#"+checkThis.id);
    const classCheckBox = $('.'+className+' input');
    if(groupIdName.is(':checked')){
            classCheckBox.prop('checked', true);
    }else{
        classCheckBox.prop('checked', false);
    }
}