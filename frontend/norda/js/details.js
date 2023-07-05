(function($) {
    "use strict";
    var preloader = $('#preloader').val();
    var base_url = $('#base_url').val();
    var inputs = $(".cat_id");
    var category=[];
    var data_currency = $('#product_price').attr("data-currency");
    var price_status = $('#product_price').attr("data-price-status");
    var data_price= JSON.parse($('#product_price').attr("data-price"));
    var data_stock= JSON.parse($('#product_stock').attr("data-stock"));
    
    for(var i = 0; i < inputs.length; i++){
        var cat=$(inputs[i]).val();
        category.push(parseInt(cat));
    }


     $('.attribute').on('click',function(){
        $('.attribute').removeClass('active');
        $(this).addClass('active');
       
        $('input.variation:radio:checked').each(function () {
            var val= $(this).val()
            $('.attr'+val).addClass('active');
            
        });
        var variation_id_code=[];
        if($('.variation:checked').length === $('.count_var').length){
            variation_id_code = $('.variation:checked').map(function(){
                return Number($(this).val());
            }).get();
            
            if(price_status==1){
                data_price.forEach(function (val,key) {
                    if(JSON.stringify(val.variation_id_code)==JSON.stringify(variation_id_code)){
                        var dateFrom = val.starting_date;
                        var dateTo = val.ending_date;
                        var dateCheck = new Date().toISOString().slice(0, 10);

                        var from = Date.parse(dateFrom);
                        var to   = Date.parse(dateTo);
                        var check = Date.parse(dateCheck );

                        if((check <= to && check >= from)){
                            $('#product_price').html('<span class="new-price">'+number_format(val.price)+data_currency
                                +'</span><span class="old-price">'+number_format(val.regular_price)+data_currency+'</span>');
                        }else{
                            $('#product_price').html('<span>'+number_format(val.price)+data_currency+'</span>');
                        }      
                    }
                });
            }
            
            data_stock.forEach(function (val,key) {  
                if(JSON.stringify(val.variation_id_code)==JSON.stringify(variation_id_code)){
                    $('#product_stock span').text(val.stock_qty);
                }
            });
            
        }
    });
    const number_format = amount => {
      return amount.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    };

    $('.option').on('click',function(){
        $('.option').removeClass('active');
        $(this).addClass('active');
       var main_amount=$('#main_amount').val();
       var main_amount=parseFloat(main_amount);
        var calculate_amount=$('#main_amount').val();
        var calculate_amount=parseFloat(calculate_amount);
        $('.options:checked').each(function () {
            var val= $(this).val()
            var amounttype= $(this).data('amounttype')
            var amounttype= parseInt($(this).data('amounttype'));
            var amount= $(this).data('amount');
            var amount= parseFloat(amount);
            $('.option'+val).addClass('active');
            
            if(amounttype == 1){
               var final_amount= calculate_amount+amount; 
            }
            else{
                var percent= calculate_amount * amount / 100;
                var final_amount= calculate_amount+percent;
               
            }
            calculate_amount=final_amount;
    
        });
        $('#amount').html(currncy_format(calculate_amount));
    }); 
    


    var term=$('#term').val();
    var term=parseInt(term);
    var rev_url=base_url+'/get_reviews/'+term;
    get_data();
    render_review(rev_url);


    $("#cart-form").on('submit', function(e) {
       
        var btn_content = $('.submit_btn').html();
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var required=true;
       
        if($("input[name=stock_manage]").val() == true)
        {
            required=false;
            if($("input[name=qty]").val() <= Number($('#product_stock span').text())){
                required = true;
                $('.required_option').hide();
                if($("input[name=have_variation]").val() == 'true')
                {
                    if($('.variation:checked').length !== $('.count_var').length)
                    {
                        required = false;
                        $('.required_option').show();
                    }
                }
               
            }
            else{
                required = false;
                $('.required_option').show();
            }
        }

        if(required == true){
        $.ajax({
            type: 'POST',
            url: this.action,
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $('.submit_btn').attr('disabled', '');
                $('.submit_btn').html('Please wait...');
            },
            success: function(response) {
                $('.submit_btn').html("Cart Added");
               
                render_cart(response)
            }
        })

       }


    });


    $("#wishlist").on('click', function(e) {
        var btn_content = $('.wishlist-icon').html();
        var id = $(this).data('id');       
        
              
        $.ajax({
            type: 'get',
            url: base_url+'/add_to_wishlist/'+id,
            dataType: 'html',
            beforeSend: function() {
                $('#wishlist').attr('disabled', '');
                $('#wishlist').html('<div class="spinner-border spinner-border-sm text-danger" role="status"><span class="sr-only">Loading...</span></div>');
            },
            success: function(response) {
                $('#wishlist').html(btn_content);
               
                $('.heart').addClass('active');
                $('.wishlist_count').html(response)
            }
        })


    });




    $(".review-form").on('submit', function(e) {
        var btn_content = $('.review_btn').html();
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
            processData: false,
            beforeSend: function() {
                $('.review_btn').attr('disabled', '');
                $('.review_btn').html('Please wait...');
            },
            success: function(response) {
                $('.review_btn').html(response);
            }
        })


    });


    function get_data() {
        var term=$('#term').val();
        $.ajax({
            type: 'get',
            url: base_url + '/get_ralated_product_with_latest_post',
            data:{categories:category,term:term},
            dataType: 'json',
            beforeSend: function() {
                $('.product-card').remove();
                for (var i = 1; i < 5; i++) {
                    var img='<div class="content-placeholder product_preload"></div>';
                    var html='<div class="product-card content-placeholder"><div class="product-img"> <a href="#" class="text-dark">'+img+'</div><div class="product-content"><div class="product-name"><h3></h3></a><p></p></div><div class="product-price"><h4></h4><p></p></div><div class="product-cart"></div></div></div>';
                    $('#related_product_area').append(html);
                    $('#latest_product_area').append(html);
                   
                }
            },  
            success: function(response) {
               
                if(response.ratting_count > 0){
                    // for(var i = 0; i < response.ratting_avg; i++){
                    //     $('.single-product-review').append('<li><i class="fas fa-star"></i></li>');
                    // }
                    // $('.single-product-review').append('<li><span>('+response.ratting_avg+'/5)</span></li>');
                    $('#review_count').html('('+response.ratting_count+')');
                }
              

            },
            error: function() {
               get_data();
            }
        })
    }


    

})(jQuery);    

    function render_review(url){
      
        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            success: function(response) {
               $.each(response.data, function(key, value) {
                var avatar="https://ui-avatars.com/api/?background=random&name="+value.name;
                var html = '<div class="single-review">';
                    html = '<div class="review-img">';
                    html = '<img src="'+avatar+'" alt="'+value.name+'">';
                    html = '</div>';
                    html = '<div class="review-content">';
                    html = '<div class="review-top-wrap">';
                    html = '<div class="review-name">';
                    html = '<h5><span>'+value.name+'</span> - '+value.created_at+'</h5>';
                    html = '</div>';
                    html = '<div class="review-rating">';
                    html = '<i class="yellow icon_star"></i>';
                    html = '<i class="yellow icon_star"></i>';
                    html = '<i class="yellow icon_star"></i>';
                    html = '<i class="yellow icon_star"></i>';
                    html = '<i class="yellow icon_star"></i>';
                    html = '</div>';
                    html = '</div>';
                    html = '<p>'+escapeHtml(value.comment)+'</p>';
                    html = '</div>';
                    html = '</div>';
                
                $('.review-list').append(html);
                // render_star(key,value.rating);
               });

               if(response.links.links.length > 3) {
                
                 render_pagination('.pagination',response.links.links);
               }
              
            }
        })
    }
    function getFormData(dom_query){
        var out = {};
        var s_data = $(dom_query).serializeArray();
        //transform into simple data/value object
        for(var i = 0; i<s_data.length; i++){
            var record = s_data[i];
            out[record.name] = record.value;
        }
        return out;
        }
        
        var base_url = $('#base_url').val();
        $('input:radio[class=variation]').change(function () {
            if($('.variation:checked').length == $('.count_var').length)
            {            
                $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
                $.ajax({
                type: 'get',
                url: base_url + '/getforVariation',
                data:getFormData('#cart-form'),
                dataType: 'json',
                success: function(response) {
                    if(response.regular_price == response.price) {
                        $("#change_price_regular").css("display", "none");
                    }
                    else {
                        $("#change_price_regular").css("display", "");
                    }
                    $("#change_price_regular").text(response.regular_price);
                    $("#change_price").text(response.price);
                    $("#product_stock").css("display","");
                   
                }
            })
    
            }
            });

    function render_star(key,rating){

        for(var i = 0; i < 5; i++){
            if(i < rating){
                var cl="fas fa-star active";
            }
            else{
                var cl="fas fa-star";
            }
            var html='<li><i class="'+cl+'"></i>';

            $('.rev_ar'+key).append(html);
        }

    }


  var entityMap = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;',
      '/': '&#x2F;',
      '`': '&#x60;',
      '=': '&#x3D;'
  };

  function escapeHtml(string) {
      return String(string).replace(/[&<>"'`=\/]/g, function (s) {
        return entityMap[s];
    });
  }

function PaginationClicked(key){
    var url =$('.page-link-no'+key).data('url');
    render_review(url)
}