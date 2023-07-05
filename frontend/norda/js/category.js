(function ($) {
    "use strict";
    var base_url= $('#base_url').val();
    var request_count = 0;
    get_data();
  
      $('.cat_search').on('change',function(){
          var value_cat = $(this).val();
          $('#form_search_header').attr('action', value_cat);
      });
      
    function get_data() {
        $.ajax({
              type: 'get',
              url: base_url+'/get_home_page_products',
              data:{latest_product:1,random_product:1,trending_products:1,best_selling_product:1,sliders:1,menu_category:1,bump_adds:1,brand_adds:1,banner_adds:1,banner_adds_2:1,banner_adds_3:1,get_offerable_products:1},
              dataType: 'json',
                      
              success: function(response){ 
                    if (response.get_menu_category.length > 0) {
                        $.each(response.get_menu_category, function(index, value){
                          var html='<li class="cr-dropdown"><a href="'+base_url+'/category/'+value.slug+'/'+value.id+'">'+value.name+'</a></li>';
                          $('.cat-menu').append(html);
                          $('.cat-mobile-menu').append(html);
                        
                          var html2 = '<option value="'+base_url+'/category/'+value.slug+'/'+value.id+'">'+value.name+'</option>';
                          $('.cat_search').append(html2);
                        });  
                        $('.nice-select').niceSelect();
                    }
    
                 
              },
              error: function() 
              {
                  if(request_count == 0){
                    get_data();  
                  }
                  request_count+1;
                  
              }
          })
    }
  
  })(jQuery);
      