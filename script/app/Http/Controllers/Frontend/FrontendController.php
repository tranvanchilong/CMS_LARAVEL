<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Term;
use App\Meta;
use App\Category;
use App\Attribute;
use App\Getway;
use App\Models\Review;
use Cache;
use Session;
use Artesaos\SEOTools\Facades\JsonLdMulti;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\JsonLd;
use Illuminate\Support\Facades\Mail;
use App\Useroption;
use URL;
use App\Option;
use App\Plan;
use Auth;
use App\ProductFeature;
use App\ProductFeatureDetail;
use App\Models\Price;
use App\Testimonial;
use App\Service;
use App\Faq;
use App\Portfolio;
use App\Course;
use App\Module;
use App\Team;
use App\Career;
use App\Partner;
use App\Package;
use Config;
use App\Domain;
use App\Mail\ContactSendEmail;
use App\Models\Requestdomain;
use App\Location;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Booking;
use App\Permalink;
use App\Post;
use Captcha;
use App\Http\Controllers\Frontend\AffiliateController;
use Illuminate\Support\Facades\Cookie;
use App\Models\Customer;
use App\AffiliateConfig;

//LMS
use App\Http\Controllers\LMS\Web\HomeController;

class FrontendController extends Controller
{
    public $cats;
    public $attrs;
    public function __construct(Request $request)
    {
      $host=$request->getHost();
      $domain = Domain::where('domain', $host)->first();
      $custom_domain = Requestdomain::where('domain', $host)->first();
      if($domain == null && $custom_domain == null){
        abort(404);
      }else{
        $google_captcha = Useroption::where('user_id', $domain->user_id ?? $custom_domain->user_id)->where('key','google-captcha')->first();
        $info = json_decode($google_captcha->value ?? '');
        Config::set('captcha.sitekey', $info->site_key ?? '');
        Config::set('captcha.secret', $info->secret_key ?? '');
      }

    }

    public function index(Request $request)
    {
      //LMS
      if(domain_info('shop_type')==2)
      {
        return HomeController::index();
      }

         $url=$request->getHost();
         $url=str_replace('www.','',$url);

        if (url('/') == env('APP_URL') || $url == 'localhost') {
           $seo=Option::where('key','seo')->first();
        $seo=json_decode($seo->value);

       JsonLdMulti::setTitle($seo->title ?? env('APP_NAME'));
       JsonLdMulti::setDescription($seo->description ?? null);
       JsonLdMulti::addImage(asset('uploads/logo.png'));

       SEOMeta::setTitle($seo->title ?? env('APP_NAME'));
       SEOMeta::setDescription($seo->description ?? null);
       SEOMeta::addKeyword($seo->tags ?? null);

       SEOTools::setTitle($seo->title ?? env('APP_NAME'));
       SEOTools::setDescription($seo->description ?? null);
       SEOTools::setCanonical($seo->canonical ?? url('/'));
       SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
       SEOTools::opengraph()->addProperty('image', asset('uploads/logo.png'));
       SEOTools::twitter()->setTitle($seo->title ?? env('APP_NAME'));
       SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
       SEOTools::jsonLd()->addImage(asset('uploads/logo.png'));


      $latest_gallery=Category::where('type','gallery')->with('preview')->where('is_admin',1)->latest()->take(15)->get();
      $features=Category::where('type','features')->with('preview','excerpt')->where('is_admin',1)->latest()->get();

      $testimonials=Category::where('type','testimonial')->with('excerpt')->where('is_admin',1)->latest()->get();

      $brands=Category::where('type','brand')->with('preview')->where('is_admin',1)->latest()->get();

      $plans=Plan::where('status',1)->get();
      $header=Option::where('key','header')->first();
      $header=json_decode($header->value ?? '');

      $about_1=Option::where('key','about_1')->first();
      $about_1=json_decode($about_1->value ?? '');

      $about_2=Option::where('key','about_2')->first();
      $about_2=json_decode($about_2->value ?? '');

      $about_3=Option::where('key','about_3')->first();
      $about_3=json_decode($about_3->value ?? '');

      $ecom_features=Option::where('key','ecom_features')->first();
      $ecom_features=json_decode($ecom_features->value ?? '');

      $counter_area=Option::where('key','counter_area')->first();
      $counter_area=json_decode($counter_area->value ?? '');

      return view('welcome',compact('latest_gallery','plans','features','header','about_1','about_3','about_2','testimonials','brands','ecom_features','counter_area'));
        }

        if($url==env('APP_PROTOCOLESS_URL')){
          return redirect('/check');
        }

        $latest_products= $this->get_latest_products();
        $best_selling_products = $this->get_best_selling_product();
        $trending_products = $this->get_trending_products();
        $blogs = $this->get_blogs(3);

      $page = ProductFeature::where('user_id', domain_info('user_id'))->where('status', 1)->with('user')->where('is_home_page', 1)->language(language_active())->first();

      if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
       }
       else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
       }
       JsonLdMulti::setTitle($page->title ?? $seo->title ?? null);
       JsonLdMulti::setDescription($page->meta_description ?? $seo->description ?? null);
       JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

       SEOMeta::setTitle($page->title ?? $seo->title ?? null);
       SEOMeta::setDescription($page->meta_description ?? $seo->description ?? null);
       SEOMeta::addKeyword($page->meta_keyword ?? $seo->tags ?? null);

       SEOTools::setTitle($page->title ?? $seo->title ?? null);
       SEOTools::setDescription($page->meta_description ?? $seo->description ?? null);
       SEOTools::setCanonical($seo->canonical ?? url('/'));
       SEOTools::opengraph()->addProperty('keywords', $page->meta_keyword ?? $seo->tags ?? null);
       SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
       SEOTools::twitter()->setTitle($page->title ?? $seo->title ?? null);
       SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
       SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

      if($page){
        // $page = ProductFeature::where('slug', $slug)->first();
        $feature = ProductFeatureDetail::where('feature_page_id', $page->id)->where('feature_status',1)->orderBy('serial_number','asc')->get();
        $blogs=Post::where('user_id',$page->user_id)->where('status',1)->blog()->language(language_active())->with('bcategory')->latest()->limit(6)->get();
        $trending_blogs=Post::where('user_id',$page->user_id)->where('status',1)->where('featured',1)->blog()->with('bcategory')->get();
        $trending_blogs_old=Post::where('user_id',$page->user_id)->where('status',1)->where('featured',1)->blog()->with('bcategory')->orderBy('id','ASC')->limit(2)->get();
        $trending_blogs_new=Post::where('user_id',$page->user_id)->where('status',1)->where('featured',1)->blog()->with('bcategory')->orderBy('id','DESC')->limit(2)->get();
        $blogs_new=Post::where('user_id',$page->user_id)->where('status',1)->blog()->with(['bcategory','user'])->orderBy('id','DESC');
        $testimonials=Testimonial::where('user_id',$page->user_id)->where('featured',1)->language(language_active())->orderBy('serial_number','asc')->limit(6)->get();
        $services=Service::where('user_id',$page->user_id)->where('featured',1)->where('type','')->language(language_active())->orderBy('serial_number','asc')->limit(6)->get();
        $portfolios=Portfolio::where('user_id',$page->user_id)->where('featured',1)->language(language_active())->orderBy('serial_number','asc')->limit(6)->get();
        $teams=Team::where('user_id',$page->user_id)->where('type','team')->where('featured',1)->language(language_active())->orderBy('serial_number','asc')->limit(6)->get();
        $faqs=Faq::where('user_id',$page->user_id)->where('featured',1)->language(language_active())->orderBy('serial_number','asc')->limit(6)->get();
        $partners=Partner::where('user_id',$page->user_id)->where('featured',1)->language(language_active())->orderBy('serial_number','asc')->get();
        $courses=Course::where('user_id',$page->user_id)->where('featured',1)->language(language_active())->orderBy('serial_number','asc')->limit(9)->get();
        $packages=Package::where('user_id',$page->user_id)->where('featured',1)->language(language_active())->orderBy('serial_number','asc')->limit(3)->get();
        $packages_category = Category::where('type','package_category')->where('user_id', $page->user_id)->language(language_active())->orderBy('serial_number', 'ASC')->get();
        $banner_ads = $this->get_banner_adds();
        $banner_ads_3 = $this->get_banner_adds('banner_ads_3');
        $bump_ads = $this->get_bump_adds();
        $brand_ads = $this->get_brand_adds();
        $best_selling_products = $this->get_best_selling_product();
        $trending_products = $this->get_trending_products();
        $latest_products = $this->get_latest_products();
        $random_products = $this->get_random_products();
        $top_rate_products = $this->get_top_rate_products();
        $categories = $this->get_category();
        $sliders = $this->get_slider();
        $menu_fp = json_decode($page->menu->data ?? '');
        $get_booking = $this->get_booking();
        $locations = $get_booking['locations'];
        $booking_category = $get_booking['booking_category'];
        $booking_dates = $get_booking['booking_dates'];
        $booking_setting = $get_booking['booking_setting'];
        $booking_service = $get_booking['booking_service'];

        $blogs_new = $blogs_new->paginate(6);

        return view(base_view().'.feature_page', compact('page', 'feature','blogs','testimonials',
          'services','faqs','portfolios','teams','partners','packages','banner_ads','banner_ads_3',
          'bump_ads','brand_ads','best_selling_products','trending_products','latest_products','random_products',
          'top_rate_products','categories','sliders','menu_fp','packages_category','locations','booking_category',
          'booking_dates','booking_setting','booking_service','trending_blogs','trending_blogs_old','trending_blogs_new',
          'blogs_new','courses')
        );
      }
	    return view(base_view().'.index', compact('latest_products','best_selling_products','trending_products', 'blogs'));
    }

    public function feature_page(Request $request,$domain='',$slug)
    {
      $page = ProductFeature::where('user_id', domain_info('user_id'))->with('user')->where('status', 1)->where('slug', $slug)->first();
      if(!$page){
        abort(404);
      }
      $feature = ProductFeatureDetail::where('feature_page_id', $page->id)->where('feature_status',1)->orderBy('serial_number','asc')->get();
      $blogs=Post::where('user_id',$page->user_id)->where('status',1)->blog()->with('bcategory')->latest()->limit(6)->get();
      $trending_blogs=Post::where('user_id',$page->user_id)->where('status',1)->where('featured',1)->blog()->with('bcategory')->get();
      $trending_blogs_old=Post::where('user_id',$page->user_id)->where('status',1)->where('featured',1)->blog()->with('bcategory')->orderBy('id','ASC')->limit(2)->get();
      $trending_blogs_new=Post::where('user_id',$page->user_id)->where('status',1)->where('featured',1)->blog()->with('bcategory')->orderBy('id','DESC')->limit(2)->get();
      $blogs_new=Post::where('user_id',$page->user_id)->where('status',1)->blog()->with(['bcategory','user'])->orderBy('id','DESC');
      $testimonials=Testimonial::where('user_id',$page->user_id)->where('featured',1)->orderBy('serial_number','asc')->limit(6)->get();
      $services=Service::where('user_id',$page->user_id)->where('featured',1)->where('type','')->orderBy('serial_number','asc')->limit(6)->get();
      $portfolios=Portfolio::where('user_id',$page->user_id)->where('featured',1)->orderBy('serial_number','asc')->limit(6)->get();
      $courses=Course::where('user_id',$page->user_id)->where('featured',1)->language(language_active())->orderBy('serial_number','asc')->limit(9)->get();
      $teams=Team::where('user_id',$page->user_id)->where('type','team')->where('featured',1)->orderBy('serial_number','asc')->limit(6)->get();
      $faqs=Faq::where('user_id',$page->user_id)->where('featured',1)->orderBy('serial_number','asc')->limit(6)->get();
      $partners=Partner::where('user_id',$page->user_id)->where('featured',1)->orderBy('serial_number','asc')->get();

      $slug_category = $request->category;
      $packages_category_id = Category::where('type','package_category')->where('slug', $slug_category)->where('user_id', $page->user_id)->language(language_active())->orderBy('serial_number', 'ASC')->first();
      if(!$slug_category){
        $packages=Package::where('user_id',$page->user_id)->where('featured',1)->language(language_active())->orderBy('serial_number','asc')->get();
      }else{
        if(empty($packages_category_id)){
          abort(404);
        }
        $packages=Package::where('user_id',$page->user_id)->where('category_id', $packages_category_id->id)->where('featured',1)->language(language_active())->orderBy('serial_number','asc')->get();
      }
      $packages_category = Category::where('type','package_category')->where('user_id', $page->user_id)->orderBy('serial_number', 'ASC')->get();
      $banner_ads = $this->get_banner_adds();
      $banner_ads_3 = $this->get_banner_adds('banner_ads_3');
      $bump_ads = $this->get_bump_adds();
      $brand_ads = $this->get_brand_adds();
      $best_selling_products = $this->get_best_selling_product();
      $trending_products = $this->get_trending_products();
      $latest_products = $this->get_latest_products();
      $random_products = $this->get_random_products();
      $top_rate_products = $this->get_top_rate_products();
      $categories = $this->get_category();
      $sliders = $this->get_slider();
      $menu_fp = json_decode($page->menu->data ?? '');
      $get_booking = $this->get_booking();
      $locations = $get_booking['locations'];
      $booking_category = $get_booking['booking_category'];
      $booking_dates = $get_booking['booking_dates'];
      $booking_setting = $get_booking['booking_setting'];
      $booking_service = $get_booking['booking_service'];
      $blogs_new = $blogs_new->paginate(6);

      if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
       }
      else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
      }
      if(!empty($seo)){
        JsonLdMulti::setTitle($page->title ?? $seo->title ?? null);
        JsonLdMulti::setDescription($page->meta_description ?? $seo->description ?? null);
        JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

        SEOMeta::setTitle($page->title ?? $seo->title ?? null);
        SEOMeta::setDescription($page->meta_description ?? $seo->description ?? null);

        SEOTools::setTitle($page->title ?? $seo->title ?? null);
        SEOTools::setDescription($page->meta_description ?? $seo->description ?? null);
        SEOTools::setCanonical(url('/'));
        SEOTools::opengraph()->addProperty('keywords', $page->meta_keyword ?? $seo->tags ?? null);
        SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
        SEOTools::twitter()->setTitle($page->title ?? $seo->title ?? null);
        SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
      }
      return view(base_view().'.feature_page', compact('page', 'feature','blogs',
        'testimonials','services','faqs','portfolios','teams','partners','packages','banner_ads',
        'banner_ads_3','bump_ads','brand_ads','best_selling_products','trending_products',
        'latest_products','random_products','top_rate_products','categories','sliders','menu_fp',
        'packages_category','locations','booking_category','booking_dates','booking_setting',
        'booking_service','trending_blogs','trending_blogs_old','trending_blogs_new','blogs_new','courses'));
    }

    public function page($domain='',$slug)
    {
      $id=request()->route()->parameter('id');
      $info=Post::where('user_id',domain_info('user_id'))->page()->where('slug', $slug)->first();
      JsonLdMulti::setTitle($info->title ?? env('APP_NAME'));
      JsonLdMulti::setDescription($info->excerpt->value ?? null);
      JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

      SEOMeta::setTitle($info->title ?? env('APP_NAME'));
      SEOMeta::setDescription($info->excerpt->value ?? null);

      SEOTools::setTitle($info->title ?? env('APP_NAME'));
      SEOTools::setDescription($info->excerpt->value ?? null);
      SEOTools::setCanonical(url('/'));
      SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
      SEOTools::twitter()->setTitle($info->title ?? env('APP_NAME'));
      SEOTools::twitter()->setSite($info->title ?? null);
      SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));



      return view(base_view().'.page',compact('info'));
    }

    public function tracking()
    {
        $seo=Option::where('key','seo')->first();
        $seo=json_decode($seo->value);

       JsonLdMulti::setTitle("Order Tracking");
       JsonLdMulti::setDescription($seo->description ?? null);
       JsonLdMulti::addImage(asset('uploads/logo.png'));

       SEOMeta::setTitle("Order Tracking");
       SEOMeta::setDescription($seo->description ?? null);
       SEOMeta::addKeyword($seo->tags ?? null);

       SEOTools::setTitle("Order Tracking");
       SEOTools::setDescription($seo->description ?? null);

       SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
       SEOTools::opengraph()->addProperty('image', asset('uploads/logo.png'));
       SEOTools::twitter()->setTitle("Order Tracking");
       SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
       SEOTools::jsonLd()->addImage(asset('uploads/logo.png'));


       return view(base_view().'.tracking');
    }

    public function sitemap(){
      if(!file_exists('uploads/'.domain_info('user_id').'/sitemap.xml')){
        abort(404);
      }
      return response(file_get_contents('uploads/'.domain_info('user_id').'/sitemap.xml'), 200, [
        'Content-Type' => 'application/xml'
      ]);
    }

    public function blog_list(Request $request)
    {
       if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
       }
       else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
       }
       if(!empty($seo)){
         JsonLdMulti::setTitle('Blog - '.$seo->title ?? env('APP_NAME'));
         JsonLdMulti::setDescription($seo->description ?? null);
         JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

         SEOMeta::setTitle('Blog - '.$seo->title ?? env('APP_NAME'));
         SEOMeta::setDescription($seo->description ?? null);
         SEOMeta::addKeyword($seo->tags ?? null);

         SEOTools::setTitle('Blog - '.$seo->title ?? env('APP_NAME'));
         SEOTools::setDescription($seo->description ?? null);
         SEOTools::setCanonical($seo->canonical ?? url('/'));
         SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
         SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
         SEOTools::twitter()->setTitle('Blog - '.$seo->title ?? env('APP_NAME'));
         SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
         SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
       }

        $user_id=domain_info('user_id');

        $blogs_category = Category::where('user_id',Auth::id())->where('type','bcategory')->language(language_active())->with('preview')->get();

        $blogs_random=Post::where('user_id',$user_id)->where('status',1)->blog()->language(language_active())->with('bcategory')->inRandomOrder()->limit(5)->get();
        $blogs=Post::where('user_id',$user_id)->where('status',1)->blog()->language(language_active())->with('bcategory')->orderBy('id','desc');

        if($request->keyword){
            $blogs->where('title', 'like', '%'.$request->keyword.'%');
        }
        if($request->category_id){
          $blogs->where('category_id',$request->category_id);
        }
        $blogs = $blogs->paginate(6);
    	return view(base_view().'.blog',compact('blogs', 'blogs_category', 'blogs_random'));

    }

    public function blog_detail(Request $request){
        $user_id=domain_info('user_id');
        $blog=Post::where('user_id',$user_id)->where('status',1)->blog()->with('bcategory')->where('slug', $request->slug)->first();
        if(!$blog){
            abort(404);
        }

        if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
       }
       else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
       }

        $keyword = explode(',', $blog->meta_keyword ? $seo->tags : '');
        $meta_description = $blog->meta_description ? $blog->meta_description : $seo->description;
        $meta_keyword = $blog->meta_keyword ?  $blog->meta_keyword : $seo->tags;

       if(!empty($seo)){
         JsonLdMulti::setTitle($blog->title . ' - '.$seo->title ?? env('APP_NAME'));
         JsonLdMulti::setDescription($meta_description ?? null);
         JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

         SEOMeta::setTitle($blog->title . ' - '.$seo->title ?? env('APP_NAME'));
         SEOMeta::setDescription($meta_description ?? null);
         SEOMeta::addKeyword($meta_keyword ?? null);

         SEOTools::setTitle($blog->title . ' - '.$seo->title ?? env('APP_NAME'));
         SEOTools::setDescription($meta_description ?? null);
         SEOTools::setCanonical($seo->canonical ?? url('/'));
         SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
         SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
         SEOTools::twitter()->setTitle($blog->title . ' - '.$seo->title ?? env('APP_NAME'));
         SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
         SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
       }

        $blogs_category = Category::where('user_id',Auth::id())->where('type','bcategory')->language(language_active())->with('preview')->get();

        $blogs_random=Post::where('user_id',$user_id)->where('status',1)->blog()->language(language_active())->with('bcategory')->inRandomOrder()->limit(5)->get();

        $next = Post::where('user_id',$user_id)->blog()->where('status',1)->where('id', '>', $blog->id)->first();
        $previous = Post::where('user_id',$user_id)->blog()->where('status',1)->where('id', '<', $blog->id)->first();

   	    return view(base_view().'.blog-detail',compact('blog', 'keyword', 'blogs_category', 'blogs_random', 'next', 'previous'));


    }

    public function guide_list(Request $request)
    {
       if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
       }
       else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
       }
       if(!empty($seo)){
         JsonLdMulti::setTitle('Guide - '.$seo->title ?? env('APP_NAME'));
         JsonLdMulti::setDescription($seo->description ?? null);
         JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

         SEOMeta::setTitle('Guide - '.$seo->title ?? env('APP_NAME'));
         SEOMeta::setDescription($seo->description ?? null);
         SEOMeta::addKeyword($seo->tags ?? null);

         SEOTools::setTitle('Guide - '.$seo->title ?? env('APP_NAME'));
         SEOTools::setDescription($seo->description ?? null);
         SEOTools::setCanonical($seo->canonical ?? url('/'));
         SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
         SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
         SEOTools::twitter()->setTitle('Guide - '.$seo->title ?? env('APP_NAME'));
         SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
         SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
       }

        $user_id=domain_info('user_id');
        $guides_category = Category::where('user_id', domain_info('user_id'))->where('type','guide_category')->language(language_active())->with('preview')->get();

        $guides=Post::where('user_id',$user_id)->guide()->language(language_active())->with('guide_category')->orderBy('id','desc');

        if($request->keyword){
            $guides->where('title', 'like', '%'.$request->keyword.'%');
        }
        if($request->category){
          $guides->where('category_id',$request->category);
        }
        $guides = $guides->paginate(30);
    	return view(base_view().'.guide',compact('guides', 'guides_category'));

    }

    public function guide_detail(Request $request){
        $user_id=domain_info('user_id');
        $guide=Post::where('user_id',$user_id)->guide()->with('guide_category')->where('slug', $request->slug)->first();
        if(!$guide){
            abort(404);
        }

        if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
       }
       else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
       }

        $keyword = explode(',', $guide->meta_keyword ? $seo->tags : '');
        $meta_description = $guide->meta_description ? $guide->meta_description : $seo->description;
        $meta_keyword = $guide->meta_keyword ?  $guide->meta_keyword : $seo->tags;

       if(!empty($seo)){
         JsonLdMulti::setTitle($guide->title . ' - '.$seo->title ?? env('APP_NAME'));
         JsonLdMulti::setDescription($meta_description ?? null);
         JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

         SEOMeta::setTitle($guide->title . ' - '.$seo->title ?? env('APP_NAME'));
         SEOMeta::setDescription($meta_description ?? null);
         SEOMeta::addKeyword($meta_keyword ?? null);

         SEOTools::setTitle($guide->title . ' - '.$seo->title ?? env('APP_NAME'));
         SEOTools::setDescription($meta_description ?? null);
         SEOTools::setCanonical($seo->canonical ?? url('/'));
         SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
         SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
         SEOTools::twitter()->setTitle($guide->title . ' - '.$seo->title ?? env('APP_NAME'));
         SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
         SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
       }

        $guides_category = Category::where('user_id',Auth::id())->where('type','guide_category')->language(language_active())->with('preview')->get();

        $next = Post::where('user_id',$user_id)->guide()->where('id', '>', $guide->id)->first();
        $previous = Post::where('user_id',$user_id)->guide()->where('id', '<', $guide->id)->first();

   	    return view(base_view().'.guide-detail',compact('guide', 'keyword', 'guides_category', 'next', 'previous'));

    }

    public function service_list(Request $request)
    {
       if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
       }
       else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
       }
       if(!empty($seo)){
         JsonLdMulti::setTitle('Service - '.$seo->title ?? env('APP_NAME'));
         JsonLdMulti::setDescription($seo->description ?? null);
         JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

         SEOMeta::setTitle('Service - '.$seo->title ?? env('APP_NAME'));
         SEOMeta::setDescription($seo->description ?? null);
         SEOMeta::addKeyword($seo->tags ?? null);

         SEOTools::setTitle('Service - '.$seo->title ?? env('APP_NAME'));
         SEOTools::setDescription($seo->description ?? null);
         SEOTools::setCanonical($seo->canonical ?? url('/'));
         SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
         SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
         SEOTools::twitter()->setTitle('Service - '.$seo->title ?? env('APP_NAME'));
         SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
         SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
       }

        $user_id=domain_info('user_id');

        $services_random=Service::where('user_id',$user_id)->where('featured',1)->where('type','')->language(language_active())->inRandomOrder()->limit(5)->get();
        $services=Service::where('user_id',$user_id)->where('featured',1)->where('type','')->language(language_active())->orderBy('serial_number','asc');

        if($request->keyword){
            $services->where('name', 'like', '%'.$request->keyword.'%');
        }

        $services = $services->paginate(6);
      return view(base_view().'.service',compact('services', 'services_random'));

    }

    public function service_detail(Request $request){
        $user_id=domain_info('user_id');
        $service=Service::where('user_id',$user_id)->where('featured',1)->where('type','')->where('slug', $request->slug)->first();
        if(!$service){
            abort(404);
        }

        if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
       }
       else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
       }
       if(!empty($seo)){
         JsonLdMulti::setTitle($service->name . ' - '.$seo->title ?? env('APP_NAME'));
         JsonLdMulti::setDescription($seo->description ?? null);
         JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

         SEOMeta::setTitle($service->name . ' - '.$seo->title ?? env('APP_NAME'));
         SEOMeta::setDescription($seo->description ?? null);
         SEOMeta::addKeyword($seo->tags ?? null);

         SEOTools::setTitle($service->name . ' - '.$seo->title ?? env('APP_NAME'));
         SEOTools::setDescription($seo->description ?? null);
         SEOTools::setCanonical($seo->canonical ?? url('/'));
         SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
         SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
         SEOTools::twitter()->setTitle($service->name . ' - '.$seo->title ?? env('APP_NAME'));
         SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
         SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
       }

        $services_random=Service::where('user_id',$user_id)->where('featured',1)->where('type','')->language(language_active())->inRandomOrder()->limit(5)->get();

        return view(base_view().'.service-detail',compact('service','services_random'));

    }

    public function portfolio_list(Request $request)
    {
       if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
       }
       else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
       }
       if(!empty($seo)){
         JsonLdMulti::setTitle('Portfolio - '.$seo->title ?? env('APP_NAME'));
         JsonLdMulti::setDescription($seo->description ?? null);
         JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

         SEOMeta::setTitle('Portfolio - '.$seo->title ?? env('APP_NAME'));
         SEOMeta::setDescription($seo->description ?? null);
         SEOMeta::addKeyword($seo->tags ?? null);

         SEOTools::setTitle('Portfolio - '.$seo->title ?? env('APP_NAME'));
         SEOTools::setDescription($seo->description ?? null);
         SEOTools::setCanonical($seo->canonical ?? url('/'));
         SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
         SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
         SEOTools::twitter()->setTitle('Portfolio - '.$seo->title ?? env('APP_NAME'));
         SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
         SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
       }

        $user_id=domain_info('user_id');

        $portfolios_category = Category::where('type','portfolio_category')->where('user_id', $user_id)->language(language_active())->orderBy('id', 'DESC')->get();

        $portfolios_random=Portfolio::where('user_id',$user_id)->where('featured',1)->language(language_active())->inRandomOrder()->limit(5)->get();
        $portfolios=Portfolio::where('user_id',$user_id)->where('featured',1)->language(language_active())->orderBy('serial_number','asc');

        if($request->keyword){
            $portfolios->where('name', 'like', '%'.$request->keyword.'%');
        }
        if($request->category_id){
            $portfolios->where('category_id',$request->category_id);
        }

        $portfolios = $portfolios->paginate(6);
      return view(base_view().'.portfolios',compact('portfolios', 'portfolios_category', 'portfolios_random'));

    }

    public function portfolio_detail(Request $request){
        $user_id=domain_info('user_id');
        $portfolio=Portfolio::where('user_id',$user_id)->where('featured',1)->where('slug', $request->slug)->first();
        if(!$portfolio){
            abort(404);
        }

        if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
       }
       else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
       }
       if(!empty($seo)){
         JsonLdMulti::setTitle($portfolio->name . ' - '.$seo->title ?? env('APP_NAME'));
         JsonLdMulti::setDescription($seo->description ?? null);
         JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

         SEOMeta::setTitle($portfolio->name . ' - '.$seo->title ?? env('APP_NAME'));
         SEOMeta::setDescription($seo->description ?? null);
         SEOMeta::addKeyword($seo->tags ?? null);

         SEOTools::setTitle($portfolio->name . ' - '.$seo->title ?? env('APP_NAME'));
         SEOTools::setDescription($seo->description ?? null);
         SEOTools::setCanonical($seo->canonical ?? url('/'));
         SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
         SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
         SEOTools::twitter()->setTitle($portfolio->name . ' - '.$seo->title ?? env('APP_NAME'));
         SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
         SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
       }

        $portfolios_category = Category::where('type','portfolio_category')->where('user_id', $user_id)->language(language_active())->orderBy('id', 'DESC')->get();

        $portfolios_random=Portfolio::where('user_id',$user_id)->where('featured',1)->language(language_active())->inRandomOrder()->limit(5)->get();

        return view(base_view().'.portfolios-detail',compact('portfolio','portfolios_category','portfolios_random'));

    }
    public function course_list(Request $request)
    {
       if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
       }
       else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
       }
       if(!empty($seo)){
         JsonLdMulti::setTitle('Course - '.$seo->title ?? env('APP_NAME'));
         JsonLdMulti::setDescription($seo->description ?? null);
         JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

         SEOMeta::setTitle('Course - '.$seo->title ?? env('APP_NAME'));
         SEOMeta::setDescription($seo->description ?? null);
         SEOMeta::addKeyword($seo->tags ?? null);

         SEOTools::setTitle('Course - '.$seo->title ?? env('APP_NAME'));
         SEOTools::setDescription($seo->description ?? null);
         SEOTools::setCanonical($seo->canonical ?? url('/'));
         SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
         SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
         SEOTools::twitter()->setTitle('Course - '.$seo->title ?? env('APP_NAME'));
         SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
         SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
       }

        $user_id=domain_info('user_id');
        $src=$request->src ?? null;
        $courses_category = Category::where('type','course_category')->where('user_id', $user_id)->language(language_active())->orderBy('id', 'DESC')->get();
        $courses = Course::where('user_id',$user_id)->where('featured',1)->language(language_active())
        ->when($src,function ($query, $src)
        {
            return $query->where('title', 'like', '%'.$src.'%');
        })
        ->paginate(9);
      return view(base_view().'.courses',compact('src','courses', 'courses_category'));

    }
    public function course_detail(Request $request){
      $user_id=domain_info('user_id');
      $course=Course::where('user_id',$user_id)->where('featured',1)->where('slug', $request->slug)->first();
      if(!$course){
          abort(404);
      }

      if(Cache::has(domain_info('user_id').'seo')){
      $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
     }
     else{
      $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
      $seo=json_decode($data->value ?? '');
     }
     if(!empty($seo)){
       JsonLdMulti::setTitle($course->title . ' - '.$seo->title ?? env('APP_NAME'));
       JsonLdMulti::setDescription($seo->description ?? null);
       JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

       SEOMeta::setTitle($course->title . ' - '.$seo->title ?? env('APP_NAME'));
       SEOMeta::setDescription($seo->description ?? null);
       SEOMeta::addKeyword($seo->tags ?? null);

       SEOTools::setTitle($course->name . ' - '.$seo->title ?? env('APP_NAME'));
       SEOTools::setDescription($seo->description ?? null);
       SEOTools::setCanonical($seo->canonical ?? url('/'));
       SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
       SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
       SEOTools::twitter()->setTitle($course->name . ' - '.$seo->title ?? env('APP_NAME'));
       SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
       SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
     }

      $courses_category = Category::where('type','course_category')->where('user_id', $user_id)->language(language_active())->orderBy('id', 'DESC')->get();

      $courses_random=Course::where('user_id',$user_id)->where('featured',1)->language(language_active())->inRandomOrder()->limit(5)->get();
      $modules = Module::where('course_id', $course->id)->get();
      return view(base_view().'.courses-detail',compact('course','courses_category','courses_random','modules'));

  }
    public function career_list(Request $request)
    {
       if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
       }
       else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
       }
       if(!empty($seo)){
         JsonLdMulti::setTitle('Career - '.$seo->title ?? env('APP_NAME'));
         JsonLdMulti::setDescription($seo->description ?? null);
         JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

         SEOMeta::setTitle('Career - '.$seo->title ?? env('APP_NAME'));
         SEOMeta::setDescription($seo->description ?? null);
         SEOMeta::addKeyword($seo->tags ?? null);

         SEOTools::setTitle('Career - '.$seo->title ?? env('APP_NAME'));
         SEOTools::setDescription($seo->description ?? null);
         SEOTools::setCanonical($seo->canonical ?? url('/'));
         SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
         SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
         SEOTools::twitter()->setTitle('Career - '.$seo->title ?? env('APP_NAME'));
         SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
         SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
       }

        $user_id=domain_info('user_id');

        $careers_category = Category::where('type','career_category')->where('user_id', $user_id)->language(language_active())->orderBy('id', 'DESC')->get();

        $careers_random=Career::where('user_id',$user_id)->where('featured',1)->language(language_active())->inRandomOrder()->limit(5)->get();
        $careers=Career::where('user_id',$user_id)->where('featured',1)->language(language_active())->orderBy('serial_number','asc');

        if($request->keyword){
            $careers->where('name', 'like', '%'.$request->keyword.'%');
        }
        if($request->category_id){
            $careers->where('category_id',$request->category_id);
        }

        $careers = $careers->paginate(6);
      return view(base_view().'.career',compact('careers', 'careers_category', 'careers_random'));

    }

    public function career_detail(Request $request){
        $user_id=domain_info('user_id');
        $career=Career::where('user_id',$user_id)->where('featured',1)->where('slug', $request->slug)->first();
        if(!$career){
            abort(404);
        }

        if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
       }
       else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
       }
       if(!empty($seo)){
         JsonLdMulti::setTitle($career->name . ' - '.$seo->title ?? env('APP_NAME'));
         JsonLdMulti::setDescription($seo->description ?? null);
         JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

         SEOMeta::setTitle($career->name . ' - '.$seo->title ?? env('APP_NAME'));
         SEOMeta::setDescription($seo->description ?? null);
         SEOMeta::addKeyword($seo->tags ?? null);

         SEOTools::setTitle($career->name . ' - '.$seo->title ?? env('APP_NAME'));
         SEOTools::setDescription($seo->description ?? null);
         SEOTools::setCanonical($seo->canonical ?? url('/'));
         SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
         SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
         SEOTools::twitter()->setTitle($career->name . ' - '.$seo->title ?? env('APP_NAME'));
         SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
         SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
       }

        $careers_category = Category::where('type','career_category')->where('user_id', $user_id)->language(language_active())->orderBy('id', 'DESC')->get();

        $careers_random=Career::where('user_id',$user_id)->where('featured',1)->language(language_active())->inRandomOrder()->limit(5)->get();

        return view(base_view().'.career-detail',compact('career','careers_category','careers_random'));

    }

    public function team_list(Request $request)
    {
       if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
       }
       else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
       }
       if(!empty($seo)){
         JsonLdMulti::setTitle('Team - '.$seo->title ?? env('APP_NAME'));
         JsonLdMulti::setDescription($seo->description ?? null);
         JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

         SEOMeta::setTitle('Team - '.$seo->title ?? env('APP_NAME'));
         SEOMeta::setDescription($seo->description ?? null);
         SEOMeta::addKeyword($seo->tags ?? null);

         SEOTools::setTitle('Team - '.$seo->title ?? env('APP_NAME'));
         SEOTools::setDescription($seo->description ?? null);
         SEOTools::setCanonical($seo->canonical ?? url('/'));
         SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
         SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
         SEOTools::twitter()->setTitle('Team - '.$seo->title ?? env('APP_NAME'));
         SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
         SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
       }

        $user_id=domain_info('user_id');

        $teams=Team::where('user_id',$user_id)->where('type','team')->where('featured',1)->language(language_active())->orderBy('serial_number','asc');

        if($request->keyword){
            $teams->where('name', 'like', '%'.$request->keyword.'%');
        }

        $teams = $teams->paginate(6);
      return view(base_view().'.team',compact('teams'));

    }
    public function team_detail(Request $request){
      $user_id=domain_info('user_id');
      $team=Team::find($request->id);
      if(!$team){
          abort(404);
      }

      if(Cache::has(domain_info('user_id').'seo')){
      $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
     }
     else{
      $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
      $seo=json_decode($data->value ?? '');
     }
     if(!empty($seo)){
       JsonLdMulti::setTitle($team->name . ' - '.$seo->title ?? env('APP_NAME'));
       JsonLdMulti::setDescription($seo->description ?? null);
       JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

       SEOMeta::setTitle($team->name . ' - '.$seo->title ?? env('APP_NAME'));
       SEOMeta::setDescription($seo->description ?? null);
       SEOMeta::addKeyword($seo->tags ?? null);

       SEOTools::setTitle($team->name . ' - '.$seo->title ?? env('APP_NAME'));
       SEOTools::setDescription($seo->description ?? null);
       SEOTools::setCanonical($seo->canonical ?? url('/'));
       SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
       SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
       SEOTools::twitter()->setTitle($team->name . ' - '.$seo->title ?? env('APP_NAME'));
       SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
       SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
     }

      return view(base_view().'.team-detail',compact('team'));

  }
    public function instructor_list(Request $request)
    {
       if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
       }
       else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
       }
       if(!empty($seo)){
         JsonLdMulti::setTitle('Instructor - '.$seo->title ?? env('APP_NAME'));
         JsonLdMulti::setDescription($seo->description ?? null);
         JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

         SEOMeta::setTitle('Instructor - '.$seo->title ?? env('APP_NAME'));
         SEOMeta::setDescription($seo->description ?? null);
         SEOMeta::addKeyword($seo->tags ?? null);

         SEOTools::setTitle('Instructor - '.$seo->title ?? env('APP_NAME'));
         SEOTools::setDescription($seo->description ?? null);
         SEOTools::setCanonical($seo->canonical ?? url('/'));
         SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
         SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
         SEOTools::twitter()->setTitle('Instructor - '.$seo->title ?? env('APP_NAME'));
         SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
         SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
       }

        $user_id=domain_info('user_id');

        $instructors=Team::where('user_id',$user_id)->where('type','instructor')->where('featured',1)->language(language_active())->orderBy('serial_number','asc');

        if($request->keyword){
            $instructors->where('name', 'like', '%'.$request->keyword.'%');
        }

        $instructors = $instructors->paginate(6);
      return view(base_view().'.instructor',compact('instructors'));

    }


    public function instructor_detail(Request $request){
      $user_id=domain_info('user_id');
      $instructors=Team::find($request->id);
      if(!$instructors){
          abort(404);
      }

      if(Cache::has(domain_info('user_id').'seo')){
      $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
     }
     else{
      $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
      $seo=json_decode($data->value ?? '');
     }
     if(!empty($seo)){
       JsonLdMulti::setTitle($instructors->name . ' - '.$seo->title ?? env('APP_NAME'));
       JsonLdMulti::setDescription($seo->description ?? null);
       JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

       SEOMeta::setTitle($instructors->name . ' - '.$seo->title ?? env('APP_NAME'));
       SEOMeta::setDescription($seo->description ?? null);
       SEOMeta::addKeyword($seo->tags ?? null);

       SEOTools::setTitle($instructors->name . ' - '.$seo->title ?? env('APP_NAME'));
       SEOTools::setDescription($seo->description ?? null);
       SEOTools::setCanonical($seo->canonical ?? url('/'));
       SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
       SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
       SEOTools::twitter()->setTitle($instructors->name . ' - '.$seo->title ?? env('APP_NAME'));
       SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
       SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
     }

      return view(base_view().'.instructor-detail',compact('instructors'));

  }

    public function package_list(Request $request)
    {
       if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
       }
       else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
       }
       if(!empty($seo)){
         JsonLdMulti::setTitle('Package - '.$seo->title ?? env('APP_NAME'));
         JsonLdMulti::setDescription($seo->description ?? null);
         JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

         SEOMeta::setTitle('Package - '.$seo->title ?? env('APP_NAME'));
         SEOMeta::setDescription($seo->description ?? null);
         SEOMeta::addKeyword($seo->tags ?? null);

         SEOTools::setTitle('Package - '.$seo->title ?? env('APP_NAME'));
         SEOTools::setDescription($seo->description ?? null);
         SEOTools::setCanonical($seo->canonical ?? url('/'));
         SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
         SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
         SEOTools::twitter()->setTitle('Package - '.$seo->title ?? env('APP_NAME'));
         SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
         SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
       }

        $user_id=domain_info('user_id');

        $slug_category = $request->category ?? '';

        $packages_category_id = Category::where('type','package_category')->where('slug', $slug_category)->where('user_id', $user_id)->language(language_active())->orderBy('serial_number', 'ASC')->first();

        if(!$slug_category){
          $packages=Package::where('user_id',$user_id)->where('featured',1)->language(language_active())->orderBy('serial_number','asc')->get();
        }else{
          if(empty($packages_category_id)){
            abort(404);
          }
          $packages=Package::where('user_id',$user_id)->where('category_id', $packages_category_id->id)->where('featured',1)->language(language_active())->orderBy('serial_number','asc')->get();
        }



        $packages_category = Category::where('type','package_category')->where('user_id', $user_id)->language(language_active())->orderBy('serial_number', 'ASC')->get();
      return view(base_view().'.package',compact('packages','packages_category'));

    }

    public function faqs(Request $request)
    {
       if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
       }
       else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
       }
       if(!empty($seo)){
         JsonLdMulti::setTitle('Faq - '.$seo->title ?? env('APP_NAME'));
         JsonLdMulti::setDescription($seo->description ?? null);
         JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

         SEOMeta::setTitle('Faq - '.$seo->title ?? env('APP_NAME'));
         SEOMeta::setDescription($seo->description ?? null);
         SEOMeta::addKeyword($seo->tags ?? null);

         SEOTools::setTitle('Faq - '.$seo->title ?? env('APP_NAME'));
         SEOTools::setDescription($seo->description ?? null);
         SEOTools::setCanonical($seo->canonical ?? url('/'));
         SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
         SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
         SEOTools::twitter()->setTitle('Faq - '.$seo->title ?? env('APP_NAME'));
         SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
         SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
       }

        $user_id=domain_info('user_id');

        $faqs=Faq::where('user_id',$user_id)->where('featured',1)->language(language_active())->orderBy('serial_number','asc')->get();

      return view(base_view().'.faq',compact('faqs'));

    }

    public function testimonial(Request $request)
    {
       if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
       }
       else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
       }
       if(!empty($seo)){
         JsonLdMulti::setTitle('Testimonial - '.$seo->title ?? env('APP_NAME'));
         JsonLdMulti::setDescription($seo->description ?? null);
         JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

         SEOMeta::setTitle('Testimonial - '.$seo->title ?? env('APP_NAME'));
         SEOMeta::setDescription($seo->description ?? null);
         SEOMeta::addKeyword($seo->tags ?? null);

         SEOTools::setTitle('Testimonial - '.$seo->title ?? env('APP_NAME'));
         SEOTools::setDescription($seo->description ?? null);
         SEOTools::setCanonical($seo->canonical ?? url('/'));
         SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
         SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
         SEOTools::twitter()->setTitle('Testimonial - '.$seo->title ?? env('APP_NAME'));
         SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
         SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
       }

        $user_id=domain_info('user_id');

        $testimonials=Testimonial::where('user_id',$user_id)->where('featured',1)->language(language_active())->orderBy('serial_number','asc')->get();

      return view(base_view().'.testimonial',compact('testimonials'));

    }

    public function partner(Request $request)
    {
       if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
       }
       else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
       }
       if(!empty($seo)){
         JsonLdMulti::setTitle('Partner - '.$seo->title ?? env('APP_NAME'));
         JsonLdMulti::setDescription($seo->description ?? null);
         JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

         SEOMeta::setTitle('Partner - '.$seo->title ?? env('APP_NAME'));
         SEOMeta::setDescription($seo->description ?? null);
         SEOMeta::addKeyword($seo->tags ?? null);

         SEOTools::setTitle('Partner - '.$seo->title ?? env('APP_NAME'));
         SEOTools::setDescription($seo->description ?? null);
         SEOTools::setCanonical($seo->canonical ?? url('/'));
         SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
         SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
         SEOTools::twitter()->setTitle('Partner - '.$seo->title ?? env('APP_NAME'));
         SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
         SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
       }

        $user_id=domain_info('user_id');

        $partners=Partner::where('user_id',$user_id)->where('featured',1)->language(language_active())->orderBy('serial_number','asc')->get();

      return view(base_view().'.partner',compact('partners'));

    }

    public function shop(Request $request)
    {
       if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
       }
       else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
       }
       if(!empty($seo)){
         JsonLdMulti::setTitle('Shop - '.$seo->title ?? env('APP_NAME'));
         JsonLdMulti::setDescription($seo->description ?? null);
         JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

         SEOMeta::setTitle('Shop - '.$seo->title ?? env('APP_NAME'));
         SEOMeta::setDescription($seo->description ?? null);
         SEOMeta::addKeyword($seo->tags ?? null);

         SEOTools::setTitle('Shop - '.$seo->title ?? env('APP_NAME'));
         SEOTools::setDescription($seo->description ?? null);
         SEOTools::setCanonical($seo->canonical ?? url('/'));
         SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
         SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
         SEOTools::twitter()->setTitle('Shop - '.$seo->title ?? env('APP_NAME'));
         SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
         SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
       }


      $src=$request->src ?? null;
    	return view(base_view().'.shop',compact('src'));
    }

    public function cart(){
       \Cart::setGlobalTax(tax());
        if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
       }
       else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
       }
       if(!empty($seo)){
        JsonLdMulti::setTitle('Cart - '.$seo->title ?? env('APP_NAME'));
        JsonLdMulti::setDescription($seo->description ?? null);
        JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

        SEOMeta::setTitle('Cart - '.$seo->title ?? env('APP_NAME'));
        SEOMeta::setDescription($seo->description ?? null);
        SEOMeta::addKeyword($seo->tags ?? null);

        SEOTools::setTitle('Cart - '.$seo->title ?? env('APP_NAME'));
        SEOTools::setDescription($seo->description ?? null);
        SEOTools::setCanonical($seo->canonical ?? url('/'));
        SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
        SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
        SEOTools::twitter()->setTitle('Cart - '.$seo->title ?? env('APP_NAME'));
        SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
        SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
       }

      return view(base_view().'.cart');
    }

    public function wishlist(){
       if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
       }
       else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
       }
       if(!empty($seo)){
        JsonLdMulti::setTitle('Wishlist - '.$seo->title ?? env('APP_NAME'));
        JsonLdMulti::setDescription($seo->description ?? null);
        JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

        SEOMeta::setTitle('Wishlist - '.$seo->title ?? env('APP_NAME'));
        SEOMeta::setDescription($seo->description ?? null);
        SEOMeta::addKeyword($seo->tags ?? null);

        SEOTools::setTitle('Wishlist - '.$seo->title ?? env('APP_NAME'));
        SEOTools::setDescription($seo->description ?? null);
        SEOTools::setCanonical($seo->canonical ?? url('/'));
        SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
        SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
        SEOTools::twitter()->setTitle('Wishlist - '.$seo->title ?? env('APP_NAME'));
        SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
        SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
       }


      return view(base_view().'.wishlist');
    }

    public function thanks(){
       if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
       }
       else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
       }
        if(!empty($seo)){
       JsonLdMulti::setTitle('Thank you - '.$seo->title ?? env('APP_NAME'));
       JsonLdMulti::setDescription($seo->description ?? null);
       JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

       SEOMeta::setTitle('Thank you - '.$seo->title ?? env('APP_NAME'));
       SEOMeta::setDescription($seo->description ?? null);
       SEOMeta::addKeyword($seo->tags ?? null);

       SEOTools::setTitle('Thank you - '.$seo->title ?? env('APP_NAME'));
       SEOTools::setDescription($seo->description ?? null);
       SEOTools::setCanonical($seo->canonical ?? url('/'));
       SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
       SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
       SEOTools::twitter()->setTitle('Thank you - '.$seo->title ?? env('APP_NAME'));
       SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
       SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
       }
      return view(base_view().'.thanks');
    }
    public function make_local(Request $request){

         Session::put('locale',$request->lang);
        \App::setlocale($request->lang);
        return redirect('/');
    }

    public function checkout(){
      if(Auth::check() == true){
        Auth::logout();
      }
      $cart = \Cart::content();
      foreach ($cart as $key => $value) {
        if(!empty(count($value->options->attribute_full)) && empty(count($value->options->attribute))){
          return redirect('/cart');
        }
      }
       \Cart::setGlobalTax(tax());


        if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
        }
       else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
        }
         if(!empty($seo)){
       JsonLdMulti::setTitle('Checkout - '.$seo->title ?? env('APP_NAME'));
       JsonLdMulti::setDescription($seo->description ?? null);
       JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

       SEOMeta::setTitle('Checkout - '.$seo->title ?? env('APP_NAME'));
       SEOMeta::setDescription($seo->description ?? null);
       SEOMeta::addKeyword($seo->tags ?? null);

       SEOTools::setTitle('Checkout - '.$seo->title ?? env('APP_NAME'));
       SEOTools::setDescription($seo->description ?? null);
       SEOTools::setCanonical($seo->canonical ?? url('/'));
       SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
       SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
       SEOTools::twitter()->setTitle('Checkout - '.$seo->title ?? env('APP_NAME'));
       SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
       SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
       }

      $shop_type=domain_info('shop_type');
      $user_id=domain_info('user_id');
      if($shop_type==1){
        $locations= Category::where('user_id',$user_id)->where('type','city')->with('child_relation')->get();
      }
      else{
        $locations=[];
      }


      $getways=  Getway::where('user_id',$user_id)->where('status',1)->whereHas('method', function ($method) {
            $method->where('featured', 1);
        })->get();

      return view(base_view().'.checkout',compact('locations','getways'));
    }

    public function wishlist_remove(){
      $id=request()->route()->parameter('id');
    }

    public function detail(Request $request, $slug, $id)
    {
      $id=request()->route()->parameter('id');
      $user_id=domain_info('user_id');


      $info=Term::where('user_id',$user_id)->where('type','product')->where('status',1)->with('affiliate','medias','content','categories','brands','seo','price','options','stock')->findorFail($id);
      $next = Term::where('user_id',$user_id)->where('type','product')->where('status',1)->where('id', '>', $id)->first();
      $previous = Term::where('user_id',$user_id)->where('type','product')->where('status',1)->where('id', '<', $id)->first();

     $variations = collect($info->attributes)->groupBy(function($q){
      return $q->attribute->name;
     });



     $content=json_decode($info->content->value);
     $seo=json_decode($info->seo->value ?? '');
     $keyword = explode(',', $seo->meta_keyword);

     SEOMeta::setTitle($seo->meta_title ?? $info->title);
     SEOMeta::setDescription($seo->meta_description ?? $content->excerpt ?? null);
     SEOMeta::addMeta('article:published_time', $info->updated_at->format('Y-m-d'), 'property');
     SEOMeta::addKeyword([$seo->meta_keyword ?? null ]);

     OpenGraph::setDescription($seo->meta_description ?? $content->excerpt ?? null);
     OpenGraph::setTitle($seo->meta_title ?? $info->title);
     OpenGraph::addProperty('type', 'product');

     foreach($info->medias as $row){
      OpenGraph::addImage(asset($row->url));
      JsonLdMulti::addImage(asset($row->url));
      JsonLd::addImage(asset($row->url));
     }


     JsonLd::setTitle($seo->meta_title ?? $info->title);
     JsonLd::setDescription($seo->meta_description ?? $content->excerpt ?? null);
     JsonLd::setType('Product');

     JsonLdMulti::setTitle($seo->meta_title ?? $info->title);
     JsonLdMulti::setDescription($seo->meta_description ?? $content->excerpt ?? null);
     JsonLdMulti::setType('Product');


    $latest_products= $this->get_latest_products();
    $hide_price_product = Useroption::where('user_id',domain_info('user_id'))->where('key','hide_price_product')->first();
    $hide_price_product = !empty($hide_price_product) ? $hide_price_product->value : null;

    if($request->has('product_referral_code') && feature_is_activated('affiliate_status', domain_info('user_id'))) {
      $affiliate_validation_time = AffiliateConfig::where('user_id',domain_info('user_id'))->where('type', 'validation_time')->first();
      $cookie_minute = 30 * 24;
      if ($affiliate_validation_time) {
          $cookie_minute = $affiliate_validation_time->value * 60 * 24;
      }

      Cookie::queue('product_referral_code', $request->product_referral_code, $cookie_minute);
      Cookie::queue('referred_product_id', $id, $cookie_minute);

      $referred_by_customer = Customer::where('created_by',domain_info('user_id'))->where('referral_code', $request->product_referral_code)->first();

      $affiliateController = new AffiliateController;
      $affiliateController->processAffiliateStats($referred_by_customer->id, domain_info('user_id'), 1, 0, 0, 0);
    }

     return view(base_view().'.details',compact('info','next','previous','variations','content', 'keyword', 'latest_products','hide_price_product'));
    }

    public function category($id)
    {
    	$id=request()->route()->parameter('id');
      $user_id=domain_info('user_id');
      $info=Category::where('user_id',$user_id)->where('type','category')->with('preview')->findorFail($id);

      if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
      }
      else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
      }

      JsonLdMulti::setTitle($info->name ?? env('APP_NAME'));
      JsonLdMulti::setDescription($seo->description ?? null);
      JsonLdMulti::addImage(asset($info->preview->content ?? 'uploads/'.domain_info('user_id').'/logo.png'));

      SEOMeta::setTitle($info->name ?? env('APP_NAME'));
      SEOMeta::setDescription($seo->description ?? null);
      SEOMeta::addKeyword($seo->tags ?? null);

      SEOTools::setTitle($info->name ?? env('APP_NAME'));
      SEOTools::setDescription($seo->description ?? null);
      SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
      SEOTools::opengraph()->addProperty('image', asset($info->preview->content ?? 'uploads/'.domain_info('user_id').'/logo.png'));
      SEOTools::twitter()->setTitle($info->name ?? env('APP_NAME'));
      SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
      SEOTools::jsonLd()->addImage(asset($info->preview->content ?? 'uploads/'.domain_info('user_id').'/logo.png'));



      return view(base_view().'.shop',compact('info'));
    }


    public function home_page_products(Request $request)
    {
      if($request->latest_product){
        if($request->latest_product == 1){
          $data['get_latest_products']= $this->get_latest_products();
        }
        else{
          $data['get_latest_products']= $this->get_latest_products($request->latest_product);
        }
      }

      if($request->random_product){
        if ($request->random_product == 1) {
           $data['get_random_products']= $this->get_random_products();
        }
        else{
           $data['get_random_products']= $this->get_random_products($request->random_product);
        }

      }
      if($request->get_offerable_products){
        if ($request->get_offerable_products == 1) {
           $data['get_offerable_products']= $this->get_offerable_products();
        }
        else{
           $data['get_offerable_products']= $this->get_offerable_products($request->random_product);
        }

      }

      if($request->trending_products){
        if($request->trending_products == 1){
          $data['get_trending_products'] = $this->get_trending_products();
        }
        else{
          $data['get_trending_products'] = $this->get_trending_products($request->trending_products);
        }

      }

      if($request->top_rate_products){
        if($request->top_rate_products == 1){
          $data['get_top_rate_products'] = $this->get_top_rate_products();
        }
        else{
          $data['get_top_rate_products'] = $this->get_top_rate_products($request->top_rate_products);
        }

      }

      if($request->best_selling_product){
        if($request->best_selling_product == 1){
         $data['get_best_selling_product']= $this->get_best_selling_product();
        }
        else{
          $data['get_best_selling_product']= $this->get_best_selling_product($request->best_selling_product);
        }
      }

      if($request->sliders){
        $data['sliders'] = $this->get_slider();
      }

      if($request->menu_category){
        $data['get_menu_category'] = $this->get_menu_category();
      }

      if($request->bump_adds){
        $data['bump_adds']=$this->get_bump_adds();
      }

      if($request->banner_adds){
        $data['banner_adds']=$this->get_banner_adds();
      }

      if($request->banner_adds_2){
        $data['banner_adds_2']=$this->get_banner_adds('banner_ads_2');
      }

      if($request->banner_adds_3){
        $data['banner_adds_3']=$this->get_banner_adds('banner_ads_3');
      }

      if($request->brand_adds){
        $data['brand_adds']=$this->get_brand_adds();
      }

      if($request->featured_category){
        $data['featured_category']=$this->get_featured_category();
      }

      if($request->featured_brand){
        $data['featured_brand']=$this->get_featured_brand();
      }

      if($request->category_with_product){
        $data['category_with_product']=$this->get_category_with_product();
      }

      if($request->brand_with_product){
        $data['brand_with_product']=$this->get_brand_with_product();
      }




      return response()->json($data);

    }

    public  function get_slider(){
       $user_id=domain_info('user_id');
     return  Category::where('type','slider')->with('excerpt')->where('user_id',$user_id)->orderBy('serial_number', 'ASC')->get()->map(function($q){
         $data['slider']=asset($q->name);
         $data['url']=$q->slug;
         $data['meta']=json_decode($q->excerpt->content ?? '');

        return $data;
       });
    }

    public function get_menu_category(){
       $user_id=domain_info('user_id');
      return $data=Category::where('type','category')->where('user_id',$user_id)->where('menu_status',1)->language(language_active())->select('id','name','slug')->get();
    }


    public function brand($id)
    {
      $id=request()->route()->parameter('id');
      $user_id=domain_info('user_id');
      $info=Category::where('user_id',$user_id)->where('type','brand')->with('preview')->findorFail($id);

      if(Cache::has(domain_info('user_id').'seo')){
        $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
      }
      else{
        $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
        $seo=json_decode($data->value ?? '');
      }

      JsonLdMulti::setTitle($info->name ?? env('APP_NAME'));
      JsonLdMulti::setDescription($seo->description ?? null);
      JsonLdMulti::addImage(asset($info->preview->content ?? 'uploads/'.domain_info('user_id').'/logo.png'));

      SEOMeta::setTitle($info->name ?? env('APP_NAME'));
      SEOMeta::setDescription($seo->description ?? null);
      SEOMeta::addKeyword($seo->tags ?? null);

      SEOTools::setTitle($info->name ?? env('APP_NAME'));
      SEOTools::setDescription($seo->description ?? null);
      SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
      SEOTools::opengraph()->addProperty('image', asset($info->preview->content ?? 'uploads/'.domain_info('user_id').'/logo.png'));
      SEOTools::twitter()->setTitle($info->name ?? env('APP_NAME'));
      SEOTools::twitter()->setSite($info->name ?? null);
      SEOTools::jsonLd()->addImage(asset($info->preview->content ?? 'uploads/'.domain_info('user_id').'/logo.png'));

      return view(base_view().'.shop',compact('info'));

    }

    public function get_featured_attributes()
    {
      $user_id=domain_info('user_id');
      $posts=Category::where('user_id',$user_id)->where('type','parent_attribute')->where('featured',1)->with('featured_child_with_post_count_attribute')->get();

      return $posts;
    }

    public function get_ralated_product_with_latest_post(Request $request){
    	$user_id=domain_info('user_id');

    	$this->cats=$request->categories ?? [];
    	$avg=Review::where('term_id',$request->term)->avg('rating');
    	$ratting_count=Review::where('term_id',$request->term)->count();
    	$avg=(int)$avg;
    	$related=Term::where('user_id',$user_id)->where('status',1)->where('type','product')->whereHas('post_categories',function($q){
            $q->whereIn('category_id',$this->cats);
        })->with('preview','attributes','category','price','options','stock','affiliate')->withCount('reviews')->latest()->take(20)->get();

    	 $get_latest_products=  $this->get_latest_products();
    	 $data['get_latest_products']=$get_latest_products;
    	 $data['get_related_products']=$related;
    	 $data['ratting_count']=$ratting_count;
    	 $data['ratting_avg']=$avg;

    	 return response()->json($data);
    }

    public function get_reviews($id){
    	$user_id=domain_info('user_id');
    	$id=request()->route()->parameter('id');
    	$reviews=Review::where('term_id',$id)->where('user_id',$user_id)->latest()->paginate(12);
    	$data=[];
    	foreach($reviews as $review){
    		$dta['rating']=$review->rating;
    		$dta['name']=$review->name;
    		$dta['comment']=$review->comment;
    		$dta['created_at']=$review->created_at->diffForHumans();
    		array_push($data,$dta);
    	}
    	$revi['data']=$data;
    	$revi['links']=$reviews;

    	return response()->json($revi);
    }


    public function get_ralated_products(Request $request)
    {
      $user_id=domain_info('user_id');

      $this->cats=$request->cats;

      $posts=Term::where('user_id',$user_id)->where('status',1)->where('type','product')->whereHas('post_categories',function($q){
        $q->whereIn('category_id',$this->cats);
      })->with('preview','attributes','category','price','options','stock','affiliate')->latest()->paginate(30);

      return response()->json($posts);
    }

    public function product_search(Request $request)
    {
      $user_id=domain_info('user_id');
      $posts=Term::where('user_id',$user_id)->where('status',1)->where('type','product')->where('title','LIKE','%'.$request->src.'%')->with('preview','attributes','category','price','options','stock','affiliate')->latest()->paginate(30);
      return response()->json($posts);
    }

    public function get_featured_category()
    {
      $user_id=domain_info('user_id');
      $posts=Category::where('user_id',$user_id)->where('type','category')->with('preview')->where('featured',1)->latest()->get()->map(function($q){
        $data['id']=$q->id;
        $data['name']=$q->name;
        $data['slug']=$q->slug;
        $data['type']=$q->type;
        $data['preview']=asset($q->preview->content ?? 'uploads/default.png');
        return $data;
      });

      return $posts;
    }

    public function get_featured_brand()
    {
      $user_id=domain_info('user_id');
      $posts=Category::where('user_id',$user_id)->where('type','brand')->with('preview')->where('featured',1)->latest()->get()->map(function($q){
        $data['id']=$q->id;
        $data['name']=$q->name;
        $data['slug']=$q->slug;
        $data['type']=$q->type;
        $data['preview']=asset($q->preview->content ?? 'uploads/default.png');
        return $data;
      });
      return $posts;
    }

    public function get_category_parent()
    {
      $user_id=domain_info('user_id');
      return $posts=Category::where('user_id',$user_id)->where('type','category')->where('p_id',null)->language(language_active())->withCount('posts')->with('childrenCategories')->latest()->get();

    }

    public function get_category()
    {
      $user_id=domain_info('user_id');
      return $posts=Category::where('user_id',$user_id)->where('type','category')->language(language_active())->latest()->get();

    }

    public function get_brand()
    {
      $user_id=domain_info('user_id');
      return $posts=Category::where('user_id',$user_id)->where('type','brand')->language(language_active())->withCount('posts')->latest()->get();


    }

    public function get_products(Request $request)
    {
      $user_id=domain_info('user_id');
      $posts=Term::where('user_id',$user_id)->where('status',1)->where('type','product')->with('preview','attributes','category','price','options','stock','affiliate')->withCount('reviews')->latest()->paginate(30);
       return response()->json($posts);
    }
    public function get_offerable_products($limit=20)
    {
      $user_id=domain_info('user_id');
      $posts=Term::where('user_id',$user_id)->where('status',1)->where('type','product')->with('preview','attributes','category','price','options','stock','affiliate')->whereHas('price',function($q){
        return $q->where('ending_date','>=',date('Y-m-d'))->where('starting_date','<=',date('Y-m-d'));
      })->withCount('reviews')->inRandomOrder()->take(20)->get();
       return $posts;
    }


    public function get_latest_products($limit=10)
    {
       $user_id=domain_info('user_id');
       $posts=Term::where('user_id',$user_id)->where('status',1)->where('type','product')->language(language_active())->with('preview','attributes','category','price','options','stock','affiliate')->withCount('reviews')->latest()->take($limit)->get();
       return $posts;

    }

    public function get_blogs($limit=20)
    {
       $user_id=domain_info('user_id');
       $posts=Post::where('user_id',$user_id)->where('status',1)->blog()->with('bcategory')->latest()->take($limit)->get();
       return $posts;

    }

    public function max_price(){
      $user_id=domain_info('user_id');
     return Attribute::where('user_id',$user_id)->max('price');

    }

    public function min_price(){
      $user_id=domain_info('user_id');
     return Attribute::where('user_id',$user_id)->min('price');

    }

    public function get_bump_adds($limit=6){
      $user_id=domain_info('user_id');
      return Category::where('user_id',$user_id)->where('type','offer_ads')->language(language_active())->with('excerpt')->latest()->take($limit)->get()->map(function($q){
        $data['image']=asset($q->name);
        $data['url']=$q->slug;
        $data['meta']=json_decode($q->excerpt->content ?? '');
        return $data;
      });

    }
    public function get_banner_adds($type = 'banner_ads'){
      $user_id=domain_info('user_id');
      return Category::where('user_id',$user_id)->where('type',$type)->language(language_active())->with('excerpt')->get()->map(function($q){
        $data['image']=asset($q->name);
        $data['url']=$q->slug;
        $data['meta']=json_decode($q->excerpt->content ?? '');
        return $data;
      });
    }
    public function get_brand_adds(){
      $user_id=domain_info('user_id');
      return Category::where('user_id',$user_id)->where('type','brand_ads')->language(language_active())->with('excerpt')->get()->map(function($q){
        $data['image']=asset($q->name);
        $data['url']=$q->slug;
        $data['meta']=json_decode($q->excerpt->content ?? '');
        return $data;
      });
    }


    public function get_shop_attributes(){
      $data['categories']=$this->get_category_parent();
      $data['brands']=$this->get_brand();
      $data['attributes']=$this->get_featured_attributes();
      return $data;
    }


    public function get_shop_products(Request $request)
    {

        if($request->order=='DESC' || $request->order=='ASC'){
          $order=$request->order;
        }
        else{
          $order='DESC';
        }
        if($request->order=='best_sell'){
          $featured=2;
        }
        elseif($request->order=='trending'){
          $featured=1;
        }
        elseif($request->order=='top_rate'){
          $featured=3;
        }
        else{
          $featured=0;
        }

       $user_id=domain_info('user_id');
       $this->attrs = $request->attrs ?? [];
       $this->cats=$request->categories ?? [];

       $posts=Term::where('user_id',$user_id)->where('status',1)->where('type','product')->language(language_active())->with('preview','attributes','category','price','options','stock','affiliate','hide_price_product')->withCount('reviews');

       if(!empty($request->term)){
        $data= $posts->where('title','LIKE','%'.$request->term.'%');
       }

       if(count($this->attrs) > 0){
        $data= $posts->whereHas('attributes_relation',function($q){
             return $q->whereIn('variation_id',$this->attrs);
           });
       }

       if(!empty($request->min_price)){
         $min_price=$request->min_price;
        $data=$posts->whereHas('price',function($q) use ($min_price){
          return $q->where('price','>=',$min_price);
        });

       }

       if(!empty($request->max_price)){
        $max_price=$request->max_price;
        $data=$posts->whereHas('price',function($q) use ($max_price){
         return $q->where('price','<=',$max_price);
       });
      }

       if(count($this->cats) > 0){
        $data= $posts->whereHas('post_categories',function($q){
             return $q->whereIn('category_id',$this->cats);
           });
       }

       if($featured != 0){
        $data= $posts->where('featured',$featured);
       }
       else{
        $data= $posts->orderBy('id',$order);
       }

       $data= $data ?? $posts;
       $data=$data->paginate($request->limit ?? 18);
       return response()->json($data);

    }

    public function get_random_products($limit=20)
    {
       $limit=request()->route()->parameter('limit') ?? 20;
       $user_id=domain_info('user_id');
       $posts=Term::where('user_id',$user_id)->where('status',1)->where('type','product')->language(language_active())->with('preview','attributes','category','price','options','stock','affiliate')->withCount('reviews')->inRandomOrder()->take($limit)->get();
       return $posts;
    }

    public function get_trending_products($limit=10)
    {
       $user_id=domain_info('user_id');
       $posts=Term::where('user_id',$user_id)->where('status',1)->where('type','product')->where('featured',1)->language(language_active())->with('preview','attributes','category','price','options','stock','affiliate')->withCount('reviews')->latest()->take($limit)->get();
       return $posts;
    }

    public function get_best_selling_product($limit=10)
    {
       $user_id=domain_info('user_id');
       $posts=Term::where('user_id',$user_id)->where('status',1)->where('type','product')->where('featured',2)->language(language_active())->with('preview','attributes','category','price','options','stock','affiliate')->withCount('reviews')->latest()->take($limit)->get();
       return $posts;
    }

    public function get_top_rate_products($limit=20)
    {
       $user_id=domain_info('user_id');
       $posts=Term::where('user_id',$user_id)->where('status',1)->where('type','product')->where('featured',3)->language(language_active())->with('preview','attributes','category','price','options','stock','affiliate')->withCount('reviews')->latest()->take($limit)->get();
       return $posts;
    }

    public function get_category_with_product($limit=10)
    {
      $limit=request()->route()->parameter('limit');
      $user_id=domain_info('user_id');
      $posts=Category::where('user_id',$user_id)->where('type','category')->with('take_20_product')->take($limit)->get();

      return $posts;
    }

    public function get_brand_with_product($limit=10)
    {

      $limit=request()->route()->parameter('limit');

      $user_id=domain_info('user_id');
      $posts=Category::where('user_id',$user_id)->where('type','brand')->with('take_20_product')->take($limit)->get();

      return $posts;
    }

    public function contact(){
        $user_id=domain_info('user_id');
        $location = Useroption::where('key', 'location')->where('user_id', $user_id)->first();
        $location = json_decode($location->value ?? '');
        $work_times = Location::where('user_id', $user_id)->where('is_default', 1)->first();
        return view(base_view().'.contact', compact('location','work_times'));
    }

    public function refreshCaptcha()
    {
        return response()->json([
            'captcha' => Captcha::img()
        ]);
    }

    public function newsletter(Request $request){
        $user_id=domain_info('user_id');

        $post = new Category;
        $post->name = $request->email;
        $post->type= 'newsletter';
        $post->user_id=$user_id;
        $post->save();

        Session::flash('success', 'Subscribe successfully');
        return back();
    }
    public function sendMailContact(Request $request)
  {
    $google_captcha = Useroption::where('key','google-captcha')->first();
    $info = json_decode($google_captcha->value ?? '');
    if(data_get($info,'status') == 1){
      $messages = [
        'g-recaptcha-response.required' => __('You must check the reCAPTCHA.'),
        'g-recaptcha-response.nocaptcha' => __('Captcha error! try again later or contact site admin.'),
      ];

      $validator = \Validator::make($request->all(), [
          'g-recaptcha-response' => 'required|nocaptcha'
      ], $messages);

      if ($validator->fails())
      {
          \Session::flash('error',$validator->errors()->first());
          return back();
      }
    }
    else{
      $messages = [
        'captcha.required' => __('You must check the reCAPTCHA.'),
        'captcha.captcha' => __('Captcha error! try again later or contact site admin.'),
      ];

      $validator = \Validator::make($request->all(), [
          'captcha' => 'required|captcha'
      ], $messages);

      if ($validator->fails())
      {
          \Session::flash('error',$validator->errors()->first());
          return back();
      }
    }
    $shop_name=Useroption::where('key','shop_name')->where('user_id',domain_info('user_id'))->first();
    $store_email=Useroption::where('key','store_email')->where('user_id',domain_info('user_id'))->first();
    $validated = $request->validate([
      'name' => 'required|string|max:100',
      'email' => 'required|email|max:100',
      'message' => 'required|max:300',
    ]);

    $data = [
      'name' => $request->name,
      'email' => $request->email,
      'subject' => $request->subject,
      'message' => $request->message,
      'shop_name' =>$shop_name->value
    ];
    Mail::to($store_email->value)->send(new ContactSendEmail($data));
    Session::flash('success', 'Your message submitted successfully !!');
    return redirect('/contact-us');
  }

  public function gallery(Request $request){
    if(Cache::has(domain_info('user_id').'seo')){
      $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
     }
     else{
      $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
      $seo=json_decode($data->value ?? '');
     }
     if(!empty($seo)){
       JsonLdMulti::setTitle('Gallery' . ' - '.$seo->title ?? env('APP_NAME'));
       JsonLdMulti::setDescription($seo->description ?? null);
       JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

       SEOMeta::setTitle('Gallery' . ' - '.$seo->title ?? env('APP_NAME'));
       SEOMeta::setDescription($seo->description ?? null);
       SEOMeta::addKeyword($seo->tags ?? null);

       SEOTools::setTitle('Gallery' . ' - '.$seo->title ?? env('APP_NAME'));
       SEOTools::setDescription($seo->description ?? null);
       SEOTools::setCanonical($seo->canonical ?? url('/'));
       SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
       SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
       SEOTools::twitter()->setTitle('Gallery' . ' - '.$seo->title ?? env('APP_NAME'));
       SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
       SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
     }

      $slug_category = $request->category;

      $category_id = Category::where('type','gallery_category')->where('user_id', domain_info('user_id'))->where('slug', $slug_category)->language(Session::get('locale'))->first();
      if(!$slug_category){
        $templates = Category::where('type', 'gallery')->where('user_id', domain_info('user_id'))->language(Session::get('locale'))->with('excerpt')->orderBy('serial_number', 'asc')->paginate(24);
      }else{
        if(empty($category_id)){
          abort(404);
        }
        $templates = Category::where('type', 'gallery')->where('user_id', domain_info('user_id'))->where('p_id', $category_id->id)->language(Session::get('locale'))->with('excerpt')->orderBy('serial_number', 'asc')->paginate(24);
      }



      $gallery_category = Category::where('type','gallery_category')->where('user_id', domain_info('user_id'))->language(Session::get('locale'))->orderBy('serial_number', 'ASC')->get();

    return view(base_view().'.gallery',compact('templates','gallery_category'));
  }
  public function booking(Request $request){
    if(Cache::has(domain_info('user_id').'seo')){
      $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
     }
     else{
      $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
      $seo=json_decode($data->value ?? '');
     }
     if(!empty($seo)){
       JsonLdMulti::setTitle('Booking' . ' - '.$seo->title ?? env('APP_NAME'));
       JsonLdMulti::setDescription($seo->description ?? null);
       JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

       SEOMeta::setTitle('Booking' . ' - '.$seo->title ?? env('APP_NAME'));
       SEOMeta::setDescription($seo->description ?? null);
       SEOMeta::addKeyword($seo->tags ?? null);

       SEOTools::setTitle('Booking' . ' - '.$seo->title ?? env('APP_NAME'));
       SEOTools::setDescription($seo->description ?? null);
       SEOTools::setCanonical($seo->canonical ?? url('/'));
       SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
       SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
       SEOTools::twitter()->setTitle('Booking' . ' - '.$seo->title ?? env('APP_NAME'));
       SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
       SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
     }
     $data = $this->get_booking();

    return view(base_view().'.booking',$data);
  }
  public function store_booking(Request $request){
    $user_id=domain_info('user_id');
    $customer_id = Auth::guard('customer')->user()->id ?? '';
    $prefix = Useroption::where('user_id', $user_id)->where('key', 'order_prefix')->first();
    $max_id = Booking::max('id');
    $prefix = empty($prefix) ? $max_id + 1 : $prefix->value . $max_id;

    $booking = new Booking;
    $booking->booking_no = $prefix;
    $booking->name = $request->name;
    $booking->phone = $request->phone;
    $booking->booking_date = $request->booking_date;
    if($customer_id){
      $booking->customer_id =  $customer_id;
    }
    $booking->status = 1;
    $booking->user_id =  $user_id;
    $booking->category_service_id =  $request->category_service_id;
    $booking->service_id =  $request->service_id;
    $booking->location_id =  $request->location_id;
    $booking->save();

    Session::flash('success', 'Booking created successfully');
    return back();
  }
  public function get_booking()
  {
    $user_id=domain_info('user_id');
    $locations = Location::where('user_id', $user_id)->where('status', 1)->get();
    $booking_category = Category::where('type','booking')->where('user_id', $user_id)->language(language_active())->orderBy('serial_number', 'ASC')->get();
    $booking_setting = Useroption::where('user_id', Auth::id())->where('key', 'booking_setting')->first();
    $booking_service = Service::where('user_id', $user_id)->where('type', 'service_booking')->language(language_active())->orderBy('serial_number', 'ASC')->get();

    $date_start = Carbon::now()->timezone('Asia/Ho_Chi_Minh');
    $hour_equal=$date_start->hour + $date_start->minute/60;
    if($hour_equal<5){
      $date_start = $date_start->subDay(1);
    }
    $date_end = clone $date_start;
    $date_end = $date_end->addDay(7);

    $bookings = Booking::where('user_id', $user_id)
      ->whereBetween('booking_date', [$date_start, $date_end])
      ->select('booking_date', \DB::raw('count(*) as count'))
      ->groupBy('booking_date')
      ->get()->toArray();

    $bookings_arr = [];
    foreach($bookings as $v){
        $bookings_arr[$v['booking_date']] = $v['count'];
    }
    $start_time = strtotime(date('09:00:00'));
    $end_time = strtotime(date('17:00:00'));
    $booking_dates = array();
    for($i=0;$i<7;$i++){
        $date_start = $date_start->addDay(1);
        $time_dates = array();
        for ($j=$start_time;$j<=$end_time;$j = $j + 30*60){
            $hour = $date_start->format('Y-m-d').' '.date('H:i:s',$j);
            $data_hour = [
              'hour' => $hour,
              'count'=> $bookings_arr[$hour] ?? 0,
            ];
            array_push($time_dates,$data_hour);
        }
        $booking_dates[$date_start->format('Y-m-d H:i:s')]=$time_dates;
    }
    $data=[
      'locations' => $locations,
      'booking_category' => $booking_category,
      'booking_setting' => $booking_setting,
      'booking_dates' => $booking_dates,
      'booking_service' => $booking_service,
    ];

    return $data;
  }
  public function maintenance(Request $request)
  {
    if($request->method() == "GET"){
      if(request()->session()->has('maintenance')){
        return redirect('/');
      }
      return view('frontend.maintenance');
    }

    $test = Domain::where('user_id',domain_info('user_id'))->first();
    if(Hash::check($request->password, $test->maintainance_mode_password)){
      request()->session()->put('maintenance', 1);
    }

    return redirect()->back();
  }

  public function blogCategory(Request $request, $domain, $slug)
  {
    {
      if(Cache::has(domain_info('user_id').'seo')){
       $seo=json_decode(Cache::get(domain_info('user_id').'seo'));
      }
      else{
       $data=Useroption::where('user_id',domain_info('user_id'))->where('key','seo')->first();
       $seo=json_decode($data->value ?? '');
      }
      if(!empty($seo)){
        JsonLdMulti::setTitle('Blog - '.$seo->title ?? env('APP_NAME'));
        JsonLdMulti::setDescription($seo->description ?? null);
        JsonLdMulti::addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));

        SEOMeta::setTitle('Blog - '.$seo->title ?? env('APP_NAME'));
        SEOMeta::setDescription($seo->description ?? null);
        SEOMeta::addKeyword($seo->tags ?? null);

        SEOTools::setTitle('Blog - '.$seo->title ?? env('APP_NAME'));
        SEOTools::setDescription($seo->description ?? null);
        SEOTools::setCanonical($seo->canonical ?? url('/'));
        SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
        SEOTools::opengraph()->addProperty('image', asset('uploads/'.domain_info('user_id').'/logo.png'));
        SEOTools::twitter()->setTitle('Blog - '.$seo->title ?? env('APP_NAME'));
        SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
        SEOTools::jsonLd()->addImage(asset('uploads/'.domain_info('user_id').'/logo.png'));
      }

       $user_id=domain_info('user_id');

       $blogs_category = Category::where('user_id',Auth::id())->where('type','bcategory')->language(language_active())->with('preview')->get();

       $blogs_random=Post::where('user_id',$user_id)->where('status',1)->blog()->language(language_active())->with('bcategory')->inRandomOrder()->limit(5)->get();

       $blogs=Post::where('user_id',$user_id)->where('status',1)->blog()->language(language_active())->whereHas('bcategory', function($q) use ($slug){
        $q->where('slug',$slug);
      })->with('bcategory')->orderBy('id','desc');

       if($request->keyword){
           $blogs->where('title', 'like', '%'.$request->keyword.'%');
       }

       $blogs = $blogs->paginate(6);
     return view(base_view().'.blog',compact('blogs', 'blogs_category', 'blogs_random'));

   }
  }
  public function get_blog_new(Request $request)
  {
    if($request->ajax())
    {
      $user_id=domain_info('user_id');
      $blogs_new=Post::where('user_id', $user_id)->where('status',1)->blog()->with('bcategory')->orderBy('id','DESC');
      $blogs_new = $blogs_new->paginate(6);
      return view('frontend.norda.load_blogs_data', compact('blogs_new'))->render();
    }
  }
}
