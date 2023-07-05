(function ($) {
  "use strict";
  $(document).on('click','.attributes',()=> {
    var arr = [];
    $('.attributes:checkbox:checked').each(function () {
        var val=parseInt($(this).val());
        arr.push(val);
    });
    attributes = arr;
    get_data(base_url+'/get_shop_products');
  });

  $(document).on('click','.categories',()=> {
    var arr = [];
    $('.categories:checkbox:checked').each(function () {
        var v=parseInt($(this).val());
        arr.push(v);
    });
    categories = arr;
    get_data(base_url+'/get_shop_products');
  });
  $(document).on('click','.label-filter',()=> {
    var arr = [];
    $('.categories2:checkbox:checked').each(function () {
        var v=parseInt($(this).val());
        arr.push(v);
    });
    categories = arr;
    get_data(base_url+'/get_shop_products');
  });

  $(document).on('click','.filter_btn',()=> {
    get_data(base_url+'/get_shop_products');
  });

})(jQuery);  
  var preloader= $('#preloader').val();
  var base_url= $('#base_url').val();
  var order_by=$('.order_by').val();
  var src= $('.src').val();
  var attributes=[];
  var categories=[];
    
  if($('#category').val() != ''){
    categories.push($('#category').val());
  }

  get_data(base_url+'/get_shop_products');

  $('.order_by').on('change',function(){
     order_by= $(this).val();
     get_data(base_url+'/get_shop_products'); 
  });

  
      $.ajax({
        type: 'get',
        url: base_url+'/get_shop_attributes',
        dataType: 'json',
        data:{order: order_by},
                  
        success: function(response){ 
            $('.cat-item').remove();
            
            var cat =$('#category').val();
            
            $.each(response.categories, function(index, value){
                if(value.preview){
                  var image=value.preview.content;
                }
                else{
                  var image='uploads/default.png';
                }
                if(cat == value.id){
                  var selected="checked";
                }
                else{
                  var selected=null;
                }
                var html='<li><div class="sidebar-widget-list-left"><input class="categories" '+selected+' id="category-'+index+'" type="checkbox" value="'+value.id+'"> <a href="javascript:void(0);">'+value.name+' <span>'+value.posts_count+'</span> </a><span class="checkmark"></span></div></li>';
                $('.category_area').append(html);

                var html_mobile='<li class="d-inline-block mx-1"><label class="label-filter" for="category-'+value.id+'"><input hidden class="categories2" '+selected+' id="category-'+value.id+'" type="checkbox" value="'+value.id+'"><img class="img-filter-mobile" width="50" height="50" src="'+image+'"><p class="name">'+value.name+'</p></label></li>';
                $('.category_area_mobile').append(html_mobile);
                
                if(value.children_categories){
                    $.each(value.children_categories, function(index, value){
                      if(value.preview){
                      var image=value.preview.content;
                    }
                    else{
                      var image='uploads/default.png';
                    }
                      if(cat == value.id){
                        var selected="checked";
                      }
                      else{
                        var selected=null;
                      }
                      var html='<li class="ml-3"><div class="sidebar-widget-list-left"><input class="categories" '+selected+' id="category-'+index+'" type="checkbox" value="'+value.id+'"> <a href="javascript:void(0);">'+value.name+' <span>'+value.posts_count+'</span> </a><span class="checkmark"></span></div></li>';
                      $('.category_area').append(html);
                      var html_mobile='<li class="d-inline-block mx-1"><label class="label-filter" for="category-'+value.id+'"><input hidden class="categories2" '+selected+' id="category-'+value.id+'" type="checkbox" value="'+value.id+'"><img class="img-filter-mobile" width="50" height="50" src="'+image+'"><p class="name">'+value.name+'</p></label></li>';
                      $('.category_area_mobile').append(html_mobile);
                  });
                }

            });
          
            if(response.brands.length > 0)
            {
              $("#show_brand_area").css("display","");
            }
            if(!response.brands.length>0){
              $("#section_brand_area").remove();
            }
            $.each(response.brands, function(index, value){
                if(value.preview){
                  var image=value.preview.content;
                }
                else{
                  var image='uploads/default.png';
                }
                if(cat == value.id){
                  var selected="checked";
                }
                else{
                  var selected=null;
                }

                var html='<li><div class="sidebar-widget-list-left"><input class="categories" '+selected+' id="category-'+value.id+'" type="checkbox" value="'+value.id+'"> <a href="javascript:void(0);">'+value.name+' <span>'+value.posts_count+'</span> </a><span class="checkmark"></span></div></li>';
                $('.brand_area').append(html);
                var html_mobile='<li class="d-inline-block mx-1"><label class="label-filter" for="category-'+value.id+'"><input hidden class="categories2" '+selected+' id="category-'+value.id+'" type="checkbox" value="'+value.id+'"><img class="img-filter-mobile" width="50" height="50" src="'+image+'"><p class="name">'+value.name+'</p></label></li>';
                $('.brand_area_mobile').append(html_mobile);

            });
            

            $.each(response.attributes, function(index, value){
               var html = '<div class="sidebar-widget shop-sidebar-border mb-40 pt-40"><h4 class="sidebar-widget-title">Select by '+value.name+'</h4><div class="sidebar-widget-list"><ul class="product-size-ul'+index+'"></ul></div></div>';
               $('#left_sidebar').append(html);

               $.each(value.featured_child_with_post_count_attribute, function(i, v){

                var li='<li><div class="sidebar-widget-list-left"><input class="attributes" id="attribute-'+v.id+'" type="checkbox" value="'+v.id+'"> <a href="javascript:void(0);">'+v.name+' <span>'+v.variations_count+'</span> </a><span class="checkmark"></span></div></li>';
                $('.product-size-ul'+index+'').append(li);
               });
            });   
        },
        error: function() 
        {
            location.reload();
        }
    })

  function get_data(url) {
     
      $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            data:{order: order_by,categories: categories,attrs:attributes,term:src},
            beforeSend: function() {
                $('.product-card').remove();
                // for (var i = 1; i < 10; i++) {
                //     var img='<div class="content-placeholder product_preload"></div>';
                //     var html='<div class="product-card content-placeholder"><div class="product-img"> <a href="#" class="text-dark">'+img+'</div><div class="product-content"><div class="product-name"><h3></h3></a><p></p></div><div class="product-price"><h4></h4><p></p></div><div class="product-cart"></div></div></div>';
                //     $('.preload_area').append(html);
                // }
            },           
            success: function(response){ 
                if(response.from == null){
                  var from=0;
                }
                else{
                  var from=response.from;
                }

                if(response.total == null){
                  var total=0;
                }
                else{
                  var total=response.total;
                }

                // $('.grid-verti').click();
                // $('.content-placeholder').remove();
                $('#from').html(from);
                $('#to').html(response.to);
                $('#total').html(total);
                render_shop_products(response.data,'.product-parent');             
                
                // product_slider();
                // run_lazy();
                if(response.links.length > 3) {
                  $('.pagination-render').show();      
               render_pagination('.pagination-render',response.links);
             }
             else{
              render_pagination('.pagination-render',response.links);
              $('.page-item').remove();
              $('.pagination-render').hide();
             }
               
            },
            error: function() 
            {
               get_data(base_url+'/get_shop_products');
            }
        })
  }



function PaginationClicked(key){
    var url =$('.page-link-no'+key).data('url');
  //  get_data(url)
}

$(document).on('click','.render-page-link',function() {
 var url = $(this).data('url');
   //console.log(url)
   if(url != null){
     get_data(url);
     $('html, body').animate({
        scrollTop: $(".shop-area").offset().top
    }, 500);
   }

});