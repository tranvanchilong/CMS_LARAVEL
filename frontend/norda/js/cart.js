"use strict";

function currncy_format(price) {
    var currency_position= $('#currency_position').val();
    var currency_name= $('#currency_name').val();
    var currency_icon= $('#currency_icon').val();
    
    if (currency_position == 'left'){
      var currency=currency_icon + price;
    }
    else{
        price = parseInt(price);
      var currency= format_vnd(price, currency_icon);
    }
    return currency;
  }
  
  function format_vnd(n, currency) {
  return n.toFixed(0).replace(/./g, function(c, i, a) {
    return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
  }) + ' ' + currency;
}

  function image_size(url,size) {
    var new_string = url.substring(0, url.lastIndexOf(".")) + size + url.substring(url.lastIndexOf("."));
    return new_string;
  }

  function run_lazy() {
    $(".lazy").unveil(100, function() {
      $(this).on('load',function(){
         this.style.opacity = 1;
      });
    }); 
  }


  function str_limit(text, count, insertDots){
    return text.slice(0, count) + (((text.length > count) && insertDots) ? "..." : "");
  }

  function add_to_cart(id){
    var base_url=$('#base_url').val();
    var dom = $('.cart_'+id).html();
    $('.cart_'+id).html('<div class="spinner-border spinner-border-sm text-white" role="status"><span class="sr-only">Loading...</span></div>');

    $.ajax({
      type: 'get',
      url: base_url+'/add_to_cart/'+id,
      dataType: 'json',          
      success: function(response){ 
        render_cart(response);
        var checkout=base_url+'/cart'
        $('.cart_'+id).attr('href',checkout);
        $('.cart_'+id).removeAttr('onclick');
        $('.cart_'+id).html('<i class="fas fa-check"></i>');
      }
    });    
  }

  function remove_cart(id){
   var base_url=$('#base_url').val();
   var id=$('#rowid'+id).val();
   console.log(id);
   $('#cart-row'+id).remove();
   $.ajax({
    type: 'get',
    url: base_url+'/remove_cart/',
    data:{id:id},
    dataType: 'json',          
    success: function(response){ 
      render_cart(response);
    }
  }); 
   
 }

 function render_cart(data){
   var base_url=$('#base_url').val();
   $('.cart_sub_total').html(currncy_format(data.subtotal));
   $('.cart_total').html(currncy_format(data.total));
   $('.cart_count').html(data.count);

   $('.cart-item').remove();
   $.each(data.cart_add, function(index, value){
    var rowId=value.rowId;
    var term_id=value.id;
   
    // var html='<li class="cart-item cart-row'+value.rowId+'"><div class="cart-img"><a href="'+base_url+'/product/'+value.name+'/'+term_id+'"><img src="'+value.options.preview+'" alt=""></a></div><div class="cart-info"><a href="'+base_url+'/product/'+value.name+'/'+term_id+'">'+value.name+'</a><p>'+value.qty+' x <span>'+value.price+'</span></p></div><div class="cart-remove"><a href="javascript:void(0)" onclick="remove_cart('+value.id+')"><i class="fas fa-times"></i></a></div><input type="hidden" value="'+rowId+'" id="rowid'+value.id+'"></li>';
    
    var html = '<li class="single-product-cart cart-item" id="cart-row{{$row->rowId}}"><div class="cart-img"><a href="'+base_url+'/product/'+value.name+'/'+term_id+'"><img src="'+value.options.preview+'" alt=""></a></div><div class="cart-title"><h4><a href="'+base_url+'/product/'+value.name+'/'+term_id+'">'+value.name+'</a></h4><span> '+value.qty+' × '+value.price+'	</span></div><div class="cart-delete"><a href="javascript:void(0)" onclick="remove_cart('+value.id+')">×</a></div><input type="hidden" value="'+rowId+'" id="rowid'+value.id+'"></li>';

    $('.cart-list').append(html);

  });
 }

function render_products(data,target,badge_status=false) {
    var preloader= $('#preloader').val();
    var base_url= $('#base_url').val();
    $.each(data, function(index, value){  
        var name=value.title;
        var name=str_limit(name,22,true);
        var url = base_url+'/product/'+value.slug+'/'+value.id;
        if (value.preview != null) {
          var image=image_size(value.preview.media.url,'medium');  
        }
        else{
            var image=base_url+'/uploads/default.png';
        }
         
        if(value.price){
          // if(value.price.starting_date == null || value.price.ending_date == null){
          //   var price=currncy_format(value.price.price);
          // }
          // else
          if(value.price.price == value.price.regular_price){
            var price=currncy_format(value.price.price);
          }
          else{
            var price='<span class="new-price">'+currncy_format(value.price.price)+'</span> <span class="old-price">'+currncy_format(value.price.regular_price)+'</span>';
          }
        }
        else{
          var price=currncy_format(0);
        }
        
        if (value.category != null) {
          var category=value.category.category.name;  
       }
       else{
           var category='';
       }
       
       var next=false;

       if(value.attributes.length > 0){
        next=true;
       }
       if(value.options.length > 0){
        next=true;
       }
       if(value.affiliate != null){
        next=true;
       }

       
       if(next == false && value.stock.stock_manage != 1){
        var cart_url=''
        if(value.stock.stock_status == 0)
        {
          cart_url='<button title="Add to cart" class="cart_'+value.id+'" "><i class="icon-basket-loaded"></i></button>';
        }
        else
        {
          cart_url='<button title="Add to cart" class="cart_'+value.id+'" onclick="add_to_cart('+value.id+')"><i class="icon-basket-loaded"></i></button>';
        }
       
       }
       else{
        var cart_url='<a href="'+url+'"><i class="fas fa-shopping-basket"></i></a>';
       }

       if(badge_status == true){
         if(value.featured == 1){
          var badge='<span class="pro-badge left bg-red">Trending</span>';
         }
         else if(value.featured == 2){
          var badge='<span class="pro-badge left bg-red">Best selling</span>';
         }
         else{
           badge='';
         }
        
       }
      
       else{
         badge='';
       }
  
       if(value.stock && value.stock.stock_status == 0){
        var badge='<span class="pro-badge left bg-red">Stock Out</span>';
       }

       

        // var html ='<div class="product-card">';
        //     html +='<div class="product-img">';
        //     html +='<img src="'+image+'" alt="product-4">';
        //     html +='<ul class="product-widget">';
        //     html +='<li>'+cart_url+'</li>';
        //     html +='<li><a href="'+url+'"><i class="fas fa-search"></i></a></li>';
        //     html +='<li><a class="wishlist_'+value.id+'" onclick="add_to_wishlist('+value.id+')"><i class="fas fa-heart"></i></a></li>';
        //     html +='</ul></div>';
        //     html +='<div class="product-content">';
        //     html +=' <div class="product-cate">';
        //     html +=' <p>'+category+'</p>';
        //     html +='</div>';
        //     html +='</div>';
        //     html +='<div class="product-name">';
        //     html +='<a href="'+url+'"><h3>'+name+'</h3></a></div>';
        //     html +='<div class="product-price">';
        //     html +='<p>'+price+'</p>';
        //     html +='<ul class="product-rating">';
        //     html +='<li>';
        //     html +='<i class="fas fa-star"></i>';
        //     html +=' <span>('+value.reviews_count+')</span>';
        //     html +='</li></ul></div></div>';

        var html = '<div class="product-plr-2">';
    		html += '<div class="single-product-wrap-2 mb-25 px-0 px-lg-3">';
    		html += '<div class="product-img-2 mb-3">';
    		html += '<a href="'+url+'"><img src="'+image+'" alt=""></a>';
    		html += badge;
    		html += '</div>';
    		html += '<div class="product-content-3">';
    		html += '<span>'+category+'</span>';
    		html += '<h4><a href="'+url+'">'+name+'</a></h4>';
    		html += '<div class="product-rating-wrap-2">';
    		html += '<div class="product-rating-2">';
    		html += '<i class="icon_star"></i>';
    		html += '<i class="icon_star"></i>';
    		html += '<i class="icon_star"></i>';
    		html += '<i class="icon_star"></i>';
    		html += '<i class="icon_star "></i>';
    		html += '</div>';
    		html += '<span>('+value.reviews_count+')</span>';
    		html += '</div>';
    		html += '<div class="pro-price-action-wrap">';
    		html += '<div class="product-price-3">';
    		html += price;
    		html += '</div>';
    		html += '<div class="product-action-3">';
    		html += '<button title="Wishlist" class="wishlist_'+value.id+'" onclick="add_to_wishlist('+value.id+')"><i class="icon-heart"></i></button>';
        if(value.stock.stock_status == 0)
        {
    		html += '<button title="Out Stock" class="cart_'+value.id+'"><i class="icon-basket-loaded"></i></button>';
        }
        else
        {
          html += '<button title="Add to cart" onclick="add_to_cart('+value.id+')" class="cart_'+value.id+'"><i class="icon-basket-loaded"></i></button>';
        }
    		html += '</div>';
    		html += '</div>';
    		html += '</div>';
    		html += '</div>';
    		html += '</div>';
       
        $(target).append(html);
    });
  }



  function render_shop_products(data,target) {
    var preloader= $('#preloader').val();
    var base_url= $('#base_url').val();
    $('.product-card').remove();

   $.each(data, function(index, value){  
    var name=value.title;
    var hide_prices = value.hide_price_product;
    if(hide_prices == null){
      var hide_price = 0;
    }else{
      var hide_price = value.hide_price_product.value;
    }
    var name=str_limit(name,22,true);
    var url = base_url+'/product/'+value.slug+'/'+value.id;
    if (value.preview != null) {
      var image=image_size(value.preview.media.url,'medium');  
    }
    else{
        var image=base_url+'/uploads/default.png';
    }
     
    
    if(value.price){
      // if(value.price.starting_date == null || value.price.ending_date == null ){
      //   var price=currncy_format(value.price.price);
      // }
      if(value.price.price == value.price.regular_price){
        var price=currncy_format(value.price.price);
      }
      else{
            var price='<span class="new-price">'+currncy_format(value.price.price)+'</span> <span class="old-price">'+currncy_format(value.price.regular_price)+'</span>';
      }
    }
    else{
      var price=currncy_format(0);
    }
    if (value.category != null) {
      var category=value.category.category.name;  
   }
   else{
       var category='';
   }
   
   var next=false;

   if(value.attributes.length > 0){
    next=true;
   }
   if(value.options.length > 0){
    next=true;
   }
   if(value.affiliate != null){
        next=true;
   }

   
   if(next == false && value.stock.stock_manage != 1){
    var cart_url='<a href="javascript:void(0)" onclick="add_to_cart('+value.id+')" class="cart_'+value.id+'"><i class="fas fa-shopping-basket"></i></a>';
   
   }
   else{
    var cart_url='<a href="'+url+'"><i class="fas fa-shopping-basket"></i></a>';
   }
   
     if(value.featured == 1){
      var badge='<span class="pro-badge left bg-red">Trending</span>';
     }
     else if(value.featured == 2){
      var badge='<span class="pro-badge left bg-red">Best selling</span>';
     }
     else if(value.featured == 3){
      var badge='<span class="pro-badge left bg-red">Top rate</span>';
     }
     else{
       badge='';
     }
    
   

   if(value.stock.stock_status == 0){
    var badge='<span class="pro-badge left bg-red">Stock Out</span>';
   }

   

    // var html ='<div class="product-card">';
    //     html +='<div class="product-img">';
    //     html +='<img src="'+image+'" alt="product-4">';
    //     html +=badge;
    //     html +='<ul class="product-widget">';
    //     html +='<li>'+cart_url+'</li>';
    //     html +='<li><a href="'+url+'"><i class="fas fa-search"></i></a></li>';
    //     html +='<li><a class="wishlist_'+value.id+'" onclick="add_to_wishlist('+value.id+')"><i class="fas fa-heart"></i></a></li>';
    //     html +='</ul></div>';
    //     html +='<div class="product-content">';
    //     html +=' <div class="product-cate">';
    //     html +=' <p>'+category+'</p>';
    //     html +='</div>';
    //     html +='</div>';
    //     html +='<div class="product-name">';
    //     html +='<a href="'+url+'"><h3>'+name+'</h3></a><p> </p></div>';
    //     html +='<div class="product-price">';
    //     html +='<p>'+price+'</p>';
    //     html +='<ul class="product-rating">';
    //     html +='<li>';
    //     html +='<i class="fas fa-star"></i>';
    //     html +=' <span>('+value.reviews_count+')</span>';
    //     html +='</li></ul></div></div>';
    
    var html = '<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-6 product-card">';
    html += '<div class="single-product-wrap mb-35">';
    html += '<div class="product-img product-img-zoom mb-15">';
    html += '<a href="'+url+'">';
    html += '<img src="'+image+'" alt="">';
    html += '</a>';
    html += badge;
    html += '<div class="product-action-2 tooltip-style-2">';
    if(hide_price == 0)
    {
      html += '<button title="Wishlist" class="wishlist_'+value.id+'" onclick="add_to_wishlist('+value.id+')"><i class="icon-heart"></i></button>';
    }
    // html += '<button title="Quick View" data-toggle="modal" data-target="#exampleModal"><i class="icon-size-fullscreen icons"></i></button>';
    // html += '<button title="Compare"><i class="icon-refresh"></i></button>';
    html += '</div>';
    html += '</div>';
    html += '<div class="product-content-wrap-2 text-center">';
    html += '<div class="product-rating-wrap">';
    html += '<div class="product-rating">';
    html += '<i class="icon_star"></i>';
    html += '<i class="icon_star"></i>';
    html += '<i class="icon_star"></i>';
    html += '<i class="icon_star"></i>';
    html += '<i class="icon_star"></i>';
    html += '</div>';
    html += '<span>('+value.reviews_count+')</span>';
    html += '</div>';
    html += '<h3><a href="'+url+'">'+name+'</a></h3>';
    if(hide_price == 0)
    {
      html += '<div class="product-price-2">';
      html += '<span>'+price+'</span>';
      html += '</div>';
    }
    html += '</div>';
    html += '<div class="product-content-wrap-2 product-content-position text-center">';
    html += '<div class="product-rating-wrap">';
    html += '<div class="product-rating">';
    html += '<i class="icon_star"></i>';
    html += '<i class="icon_star"></i>';
    html += '<i class="icon_star"></i>';
    html += '<i class="icon_star"></i>';
    html += '<i class="icon_star"></i>';
    html += '</div>';
    html += '<span>('+value.reviews_count+')</span>';
    html += '</div>';
    html += '<h3><a href="'+url+'">'+name+'</a></h3>';
    if(hide_price == 0)
    {
      html += '<div class="product-price-2">';
      html += '<span>'+price+'</span>';
      html += '</div>';
      html += '<div class="pro-add-to-cart">';
      if(value.stock.stock_status == 0)
      {
        html += '<button class="cart_'+value.id+'"  title="Out Stock">Out Stock</button>';
      }
      else
      {
        html += '<button class="cart_'+value.id+'" onclick="add_to_cart('+value.id+')" title="Add to Cart">Add To Cart</button>';
      }
      html += '</div>';
    }
    
    html += '</div>';
    html += '</div>';
    html += '</div>';

   
    $(target).append(html);
          
   });
  }


function add_to_wishlist(id) {
  var base_url=$('#base_url').val();
  var dom = $('.wishlist_'+id).html();
  $('.wishlist_'+id).html('<div class="spinner-border spinner-border-sm text-white" role="status"><span class="sr-only">Loading...</span></div>');

  $.ajax({
    type: 'get',
    url: base_url+'/add_to_wishlist/'+id,
    dataType: 'json',          
    success: function(response){ 
      var wishlist=base_url+'/wishlist'
      $('.wishlist_'+id).attr('href',wishlist);
      $('.wishlist_'+id).removeAttr('onclick');
      $('.wishlist_'+id).html('<i class="fas fa-check"></i>');
      $('.wishlist_count').html(response)
    }
  });  
}

function render_pagination(target,data){
        $('.render-page-item').remove();
       $.each(data, function(key,value){
            if(value.label === '&laquo; Previous'){
                if(value.url === null){
                    var is_disabled="disabled"; 
                    var is_active=null;
                }
                else{
                    var is_active='render-page-link-no'+key;
                    var is_disabled='onClick="PaginationClicked('+key+')"';
                }
                var html='<li  class="render-page-item"><a '+is_disabled+' class="render-page-link '+is_active+'" href="javascript:void(0)" data-url="'+value.url+'"><i class="fas fa-long-arrow-alt-left"></i></a></li>';
            }
            else if(value.label === 'Next &raquo;'){
                if(value.url === null){
                    var is_disabled="disabled"; 
                    var is_active=null;
                }
                else{
                    var is_active='render-page-link-no'+key;
                   var is_disabled='onClick="PaginationClicked('+key+')"';
                }
                var html='<li class="render-page-item"><a '+is_disabled+'  class="render-page-link '+is_active+'" href="javascript:void(0)" data-url="'+value.url+'"><i class="fas fa-long-arrow-alt-right"></i></a></li>';
            }
            else{
                if(value.active==true){
                    var is_active="active";
                    var is_disabled="disabled";
                    var url=null;

                }
                else{
                    var is_active='render-page-link-no'+key;
                    var is_disabled='onClick="PaginationClicked('+key+')"';
                    var url=value.url;
                }
                var html='<li class="render-page-item"><a class="render-page-link '+is_active+'" '+is_disabled+' href="javascript:void(0)" data-url="'+url+'">'+value.label+'</a></li>';
            }
            if(value.url !== null){
              $(target).append(html);
            }
            
       });
    }