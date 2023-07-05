(function ($) {
  "use strict";
  var preloader= $('#preloader').val();
  var base_url= $('#base_url').val();
  var request_count = 0;
  get_data();

    $('.cat_search').on('change',function(){
        var value_cat = $(this).val();
        $('#form_search_header').attr('action', value_cat);
    });
    $('.hero-slider-active').slick({
        autoplay: true,
        autoplaySpeed: 3000,
        dots: true,
        fade: true,
        cssEase: 'linear',
        arrows: false,
        prevArrow: '<span class="prev"><i class="fas fa-arrow-left"></i></span>',
        nextArrow: '<span class="next"><i class="fas fa-arrow-right"></i></span>',
        slidesToShow: 1,
        slidesToScroll: 1,
    }); 
    $('.hero-slider-active-mid').slick({
        autoplay: true,
        autoplaySpeed: 3000,
        slidesToShow: 1,
        slidesToScroll: 1,
        fade: true,
        loop: true,
        dots: true,
        arrows: true,
        prevArrow: '<span class="slider-icon-1-prev"><i class="icon-arrow-left"></i></span>',
        nextArrow: '<span class="slider-icon-1-next"><i class="icon-arrow-right"></i></span>',
    });  
    $('.slider-image-active').slick({
      slidesToShow: 3,
      slidesToScroll: 1,
      fade: false,
      loop: true,
      dots: true,
      rows: 1,
      arrows: false,
      responsive: [
          {
              breakpoint: 1199,
              settings: {
                  slidesToShow: 2,
              }
          },
          {
              breakpoint: 991,
              settings: {
                  slidesToShow: 2,
              }
          },
          {
              breakpoint: 767,
              settings: {
                  slidesToShow: 1,
              }
          },
          {
              breakpoint: 575,
              settings: {
                  slidesToShow: 1,
              }
          }
      ]
  });
  $('.slider-image-active-4').slick({
      slidesToShow: 4,
      slidesToScroll: 1,
      fade: false,
      loop: true,
      dots: true,
      rows: 1,
      arrows: false,
      responsive: [
          {
              breakpoint: 1199,
              settings: {
                  slidesToShow: 3,
              }
          },
          {
              breakpoint: 991,
              settings: {
                  slidesToShow: 2,
              }
          },
          {
              breakpoint: 767,
              settings: {
                  slidesToShow: 1,
              }
          },
          {
              breakpoint: 575,
              settings: {
                  slidesToShow: 1,
              }
          }
      ]
  });
  $('.portfolio-slider-active').slick({
      slidesToShow: 3,
      slidesToScroll: 1,
      fade: false,
      loop: true,
      dots: true,
      rows: 1,
      arrows: false,
      prevArrow: '<span class="slider-icon-1-prev"><i class="fas fa-arrow-left"></i></span>',
      nextArrow: '<span class="slider-icon-1-next"><i class="fas fa-arrow-right"></i></span>',
      responsive: [
          {
              breakpoint: 1199,
              settings: {
                  slidesToShow: 2,
              }
          },
          {
              breakpoint: 991,
              settings: {
                  slidesToShow: 2,
              }
          },
          {
              breakpoint: 767,
              settings: {
                  slidesToShow: 1,
              }
          },
          {
              breakpoint: 575,
              settings: {
                  slidesToShow: 1,
              }
          }
      ]
  });
  $('.portfolio-slider-active-4').slick({
      slidesToShow: 4,
      slidesToScroll: 1,
      fade: false,
      loop: true,
      dots: true,
      rows: 1,
      arrows: false,
      prevArrow: '<span class="slider-icon-1-prev"><i class="fas fa-arrow-left"></i></span>',
      nextArrow: '<span class="slider-icon-1-next"><i class="fas fa-arrow-right"></i></span>',
      responsive: [
          {
              breakpoint: 1199,
              settings: {
                  slidesToShow: 3,
              }
          },
          {
              breakpoint: 991,
              settings: {
                  slidesToShow: 2,
              }
          },
          {
              breakpoint: 767,
              settings: {
                  slidesToShow: 1,
              }
          },
          {
              breakpoint: 575,
              settings: {
                  slidesToShow: 1,
              }
          }
      ]
  });
  $('.team-slider-active-5').slick({
      slidesToShow: 5,
      slidesToScroll: 1,
      fade: false,
      loop: true,
      dots: true,
      rows: 1,
      arrows: false,
      responsive: [
          {
              breakpoint: 1099,
              settings: {
                  slidesToShow: 4,
              }
          },
          {
              breakpoint: 991,
              settings: {
                  slidesToShow: 3,
              }
          },
          {
              breakpoint: 767,
              settings: {
                  slidesToShow: 2,
              }
          },
          {
              breakpoint: 575,
              settings: {
                  slidesToShow: 1,
              }
          }
      ]
  });
  $('.team-slider-active-4').slick({
      slidesToShow: 4,
      slidesToScroll: 1,
      fade: false,
      loop: true,
      dots: true,
      rows: 1,
      arrows: false,
      responsive: [
          {
              breakpoint: 1099,
              settings: {
                  slidesToShow: 3,
              }
          },
          {
              breakpoint: 991,
              settings: {
                  slidesToShow: 2,
              }
          },
          {
              breakpoint: 767,
              settings: {
                  slidesToShow: 1,
              }
          },
          {
              breakpoint: 575,
              settings: {
                  slidesToShow: 1,
              }
          }
      ]
  });
  $('.team-slider-active-3').slick({
      slidesToShow: 3,
      slidesToScroll: 1,
      fade: false,
      loop: true,
      dots: true,
      rows: 1,
      arrows: false,
      responsive: [
          {
              breakpoint: 1099,
              settings: {
                  slidesToShow: 2,
              }
          },
          {
              breakpoint: 991,
              settings: {
                  slidesToShow: 2,
              }
          },
          {
              breakpoint: 767,
              settings: {
                  slidesToShow: 1,
              }
          },
          {
              breakpoint: 575,
              settings: {
                  slidesToShow: 1,
              }
          }
      ]
  });
  $('.testimonial-wrap-2').slick({
      slidesToShow: 3,
      slidesToScroll: 1,
      infinite: true,
      dots: true,
      arrows: false,
      autoplay: true,
      autoplaySpeed: 6000,
      responsive: [{
              breakpoint: 1024,
              settings: {
                  slidesToShow: 2,
                  slidesToScroll: 1,
                  infinite: true,
                  dots: true
              }
          },
          {
              breakpoint: 900,
              settings: {
                  slidesToShow: 2,
                  slidesToScroll: 1
              }
          }, {
              breakpoint: 600,
              settings: {
                  slidesToShow: 1,
                  slidesToScroll: 1
              }
          },
          {
              breakpoint: 480,
              settings: {
                  slidesToShow: 1,
                  slidesToScroll: 1
              }
          }

      ]
  });
  $('.partner-active-5').slick({
      slidesToShow: 5,
      slidesToScroll: 1,
      infinite: true,
      dots: true,
      arrows: false,
      autoplay: true,
      rows: 2,
      autoplaySpeed: 6000,
      responsive: [{
              breakpoint: 1024,
              settings: {
                  slidesToShow: 4,
                  slidesToScroll: 1,
                  infinite: true,
                  dots: true
              }
          },
          {
              breakpoint: 900,
              settings: {
                  slidesToShow: 3,
                  slidesToScroll: 1
              }
          }, {
              breakpoint: 600,
              settings: {
                  slidesToShow: 2,
                  slidesToScroll: 1
              }
          },
          {
              breakpoint: 480,
              settings: {
                  slidesToShow: 1,
                  slidesToScroll: 1
              }
          }

      ]
  });
  $('.partner-active-6').slick({
      slidesToShow: 6,
      slidesToScroll: 2,
      infinite: true,
      dots: true,
      arrows: false,
      autoplay: true,
      rows: 2,
      autoplaySpeed: 6000,
      responsive: [{
              breakpoint: 1024,
              settings: {
                  slidesToShow: 4,
              }
          },
          {
              breakpoint: 900,
              settings: {
                  slidesToShow: 3,
              }
          }, {
              breakpoint: 600,
              settings: {
                  slidesToShow: 2,
              }
          },
          {
              breakpoint: 480,
              settings: {
                  slidesToShow: 2,
              }
          }

      ]
  });
  $('.partner-active-6-1').slick({
      slidesToShow: 6,
      slidesToScroll: 2,
      infinite: true,
      dots: true,
      arrows: false,
      autoplay: true,
      autoplaySpeed: 6000,
      responsive: [{
              breakpoint: 1024,
              settings: {
                  slidesToShow: 4,
              }
          },
          {
              breakpoint: 900,
              settings: {
                  slidesToShow: 3,
              }
          }, {
              breakpoint: 600,
              settings: {
                  slidesToShow: 2,
              }
          },
          {
              breakpoint: 480,
              settings: {
                  slidesToShow: 2,
              }
          }

      ]
  });
  $('.booking-active-5').slick({
    slidesToShow: 5,
    slidesToScroll: 2,
    infinite: true,
    dots: true,
    arrows: true,
    autoplay: true,
    prevArrow: '<span class="slider-icon-1-prev"><i class="icon-arrow-left"></i></span>',
    nextArrow: '<span class="slider-icon-1-next"><i class="icon-arrow-right"></i></span>',
    autoplaySpeed: 6000,
    responsive: [{
            breakpoint: 1024,
            settings: {
                slidesToShow: 4,
            }
        },
        {
            breakpoint: 900,
            settings: {
                slidesToShow: 3,
            }
        }, {
            breakpoint: 600,
            settings: {
                slidesToShow: 2,
            }
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
            }
        }

    ]
});

    $('.product-slider-active-2').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        fade: false,
        loop: true,
        dots: true,
        rows: 2,
        arrows: false,
        responsive: [
            {
                breakpoint: 1099,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 2,
                }
            }
        ]
    });

  function get_data() {
      $.ajax({
            type: 'get',
            url: base_url+'/get_home_page_products',
            data:{latest_product:1,random_product:1,trending_products:1,top_rate_products:1,best_selling_product:1,sliders:1,menu_category:1,bump_adds:1,brand_adds:1,banner_adds:1,banner_adds_2:1,banner_adds_3:1,get_offerable_products:1},
            dataType: 'json',
            
            beforeSend: function() {
                // $('.product-card').remove();
                // for (var i = 1; i < 5; i++) {
                //     var img='<div class="content-placeholder product_preload"></div>';
                //     var html='<div class="product-card content-placeholder"><div class="product-img"> <a href="#" class="text-dark">'+img+'</div><div class="product-content"><div class="product-name"><h3></h3></a><p></p></div><div class="product-price"><h4></h4><p></p></div><div class="product-cart"></div></div></div>';
                //     $('#bast_selling_product_area').append(html);
                //     $('#trending_product_area').append(html);
                //     $('#latest_product_area').append(html);

                // }
                
            },            
            success: function(response){ 
                $('.slider_preload').removeClass('content-placeholder');
                // $('.slider_preload').removeClass('slider_preload');
                // $('.content-placeholder').remove();
                
                // $('.cat-item').remove();
                if (response.get_random_products.length > 0) {
                  render_products(response.get_random_products,'#random_product_area'); 
                }
                else{
                    $('#random-product-area').remove();
                }
                if (response.get_best_selling_product.length > 0) {
                  render_products(response.get_best_selling_product,'#bast_selling_product_area'); 
                }
                else{
                    $('#best-product-area').remove();
                }
                if (response.get_trending_products.length > 0) {
                  render_products(response.get_trending_products,'#trending_product_area'); 
                }
                else{
                    $('#trending-product-area').remove();
                }
                if (response.get_top_rate_products.length > 0) {
                  render_products(response.get_top_rate_products,'#toprate_product_area'); 
                }
                else{
                    $('#toprate-product-area').remove();
                }
                if (response.get_offerable_products.length > 0) {                 
                //   render_products(response.get_offerable_products,'#get_offerable_products');
                //   $('.get_offerable_products').show(); 
                }
                
                else{
                    // $('.get_offerable_products').remove();
                }
                if (response.get_latest_products.length > 0) {
                  render_products(response.get_latest_products,'#latest_product_area',true); 
                }else{
                    $('#latest-product-area').remove();
                }
                
                if (response.sliders.length > 0) {
                     $.each(response.sliders, function(index, value){
                      var html='<a href="'+value.url+'"><img src="'+value.slider+'" alt=""></a>';
                      if (value.meta.btn_text){
                        var add_class = 'animated btn-1-padding-1';
                      }else{
                        add_class = ''
                      }
                      
                      var html='<div class="single-hero-slider single-animation-wrap slider-height-2 custom-d-flex custom-align-item-center bg-img hm2-slider-bg res-white-overly-xs" style="background-image:url('+value.slider+');"><div class="container"><div class="row"><div class="col-12"><div class="hero-slider-content-4 slider-animated-1"><div class="section-title"><h1 class="animated">'+value.meta.title+'</h1><span class="animated">'+value.meta.title_2+'</span></div><p class="animated">'+value.meta.title_3+'</p><div class="btn-style-1"><a class="'+add_class+'" href="'+value.url+'">'+value.meta.btn_text+'</a></div></div></div></div></div></div>';
                      
                      $('.hero-slider-active-1').append(html);
                    });  
                    hero_slider_active_1();
                }

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

                if (response.bump_adds.length > 0) {
                     $.each(response.bump_adds, function(index, value){
                      var html='<a href="'+value.url+'"><img src="'+value.image+'" alt=""></a>';
                        var add_class = 'service-border-1';
                        if(index + 1 == response.bump_adds.length){
                          add_class = ''
                        }
                        
                        if(index == 1){
                          add_class += ' service-border-1-none-md';
                        }
                        
                        var html = '<div class="col-lg-3 col-md-6 col-sm-6 col-6 '+add_class+'"><div class="single-service-wrap-2 mb-30"><div class="service-icon-2"><img src="'+value.image+'" /></div><div class="service-content-2"><h3>'+value.meta.title+'</h3><p>'+value.meta.title_2+'</p></div></div></div>';
                      $('#service-area').append(html);
                    }); 
                }
                
                if (response.brand_adds.length > 0) {
                    $.each(response.brand_adds, function(index, value){
                        var html = '<div class="single-brand-logo-2 mb-30"><a href="'+value.url+'"><img src="'+value.image+'" alt="brand-logo"></a></div>';
                        $('#brand_adds').append(html);
                    });
                }else{
                    $('#brand-logo-area').remove();
                }

                if (response.banner_adds.length > 0) {
                     $.each(response.banner_adds, function(index, value){
                        var add_class = 'col-12';
                        if(index > 0){
                            add_class = 'col-6';
                        }
                        if (value.meta.btn_text){
                          var i_class = 'icon-arrow-right';
                        }else{
                          i_class = ''
                        }
                        var html = '<div class="col-lg-4 col-md-6 col-12"><div class="banner-wrap mb-10"><div class="banner-img banner-img-border banner-img-zoom"><a href="'+value.url+'"><img src="'+value.image+'" alt=""></a></div><div class="banner-content-5"><span>'+value.meta.title_2+'</span><h2>'+value.meta.title+'</h2><p>'+value.meta.title_3+'</p><div class="btn-style-4"><a href="'+value.url+'">'+value.meta.btn_text+' <i class="'+i_class+'"></i></a></div></div></div></div>';
                      $('.banner_ad').append(html);
                    });  
                }
                
                if (response.banner_adds_2.length > 0) {
                     $.each(response.banner_adds_2, function(index, value){
                        var add_class = 'col-12';
                        if(index > 0){
                            add_class = 'col-6';
                        }
                        if (value.meta.btn_text){
                          var i_class = 'icon-arrow-right';
                        }else{
                          i_class = ''
                        }
                        var html = '<div class="col-lg-4 col-md-6 col-12"><div class="banner-wrap mb-10"><div class="banner-img banner-img-border banner-img-zoom"><a href="'+value.url+'"><img src="'+value.image+'" alt=""></a></div><div class="banner-content-5"><span>'+value.meta.title_2+'</span><h2>'+value.meta.title+'</h2><p>'+value.meta.title_3+'</p><div class="btn-style-4"><a href="'+value.url+'">'+value.meta.btn_text+' <i class="'+i_class+'"></i></a></div></div></div></div>';
                      $('.banner_ads_2').append(html);
                    });  
                }
                
                if (response.banner_adds_3.length > 0) {
                     $.each(response.banner_adds_3, function(index, value){
                        var add_class = 'col-12';
                        if(index > 0){
                            add_class = 'col-6';
                        }
                        if (value.meta.btn_text){
                          var i_class = 'icon-arrow-right';
                        }else{
                          i_class = ''
                        }
                        var html = '<div class="col-lg-4 col-md-6 col-12"><div class="banner-wrap mb-10"><div class="banner-img banner-img-border banner-img-zoom"><a href="'+value.url+'"><img src="'+value.image+'" alt=""></a></div><div class="banner-content-5"><span>'+value.meta.title_2+'</span><h2>'+value.meta.title+'</h2><p>'+value.meta.title_3+'</p><div class="btn-style-4"><a href="'+value.url+'">'+value.meta.btn_text+' <i class="'+i_class+'"></i></a></div></div></div></div>';
                      $('.banner_ads_3').append(html);
                    });  
                }
                
                
            //   product_slider_active_2();


            //   product_slider();
            //   run_lazy();
               
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
$(window).on('load', function (event) {
    
    $('#masonry-package').imagesLoaded( function() {
        // items on button click
        $('.filter-btn').on('click', 'li', function () {
          var filterValue = $(this).attr('data-filter');
          $grid.isotope({
            filter: filterValue
          });
        });
        // menu active class
        $('.filter-btn li').on('click', function (e) {
          $(this).siblings('.active').removeClass('active');
          $(this).addClass('active');
          e.preventDefault();
        });
        var $grid = $('.masonry-row').isotope({
          itemSelector: '.package-column',
          percentPosition: true,
          masonry: {
            columnWidth: 1
            }
        });
    });

    $('#masonry-gallery').imagesLoaded( function() {
        // items on button click
        $('.filter-btn').on('click', 'li', function () {
          var filterValue = $(this).attr('data-filter');
          $grid.isotope({
            filter: filterValue
          });
        });
        // menu active class
        $('.filter-btn li').on('click', function (e) {
          $(this).siblings('.active').removeClass('active');
          $(this).addClass('active');
          e.preventDefault();
        });
        var $grid = $('.gallery-row').isotope({
          itemSelector: '.gallery-column',
          percentPosition: true,
          masonry: {
            columnWidth: 1
            }
        });
    });
});
    