<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domain;
use Cache;
use DB;
use Auth;
use App\User;
use App\Menu;
use App\Useroption;
use App\Meta;
use App\Post;
use App\Postcategory;
use App\Media;
use App\Postmedia;
use App\Categorymeta;
use App\Models\Price;
use App\Stock;
use App\Career;
use App\Faq;
use App\Package;
use App\Partner;
use App\Portfolio;
use App\ProductFeature;
use App\ProductFeatureDetail;
use App\ProductFeatureSectionElement;
use App\Service;
use App\Team;
use App\Testimonial;
use App\Models\Template;
use App\Category;
use App\Term;
use App\Models\ContactLists;
use App\Location;

//LMS
use App\Models\LMS\Setting as LMSSetting;
use App\Models\LMS\SettingTranslation as LMSSettingTranslation;
use App\Models\LMS\HomeSection as LMSHomeSection;
use App\Models\LMS\AdvertisingBanner as LMSAdvertisingBanner;
use App\Models\LMS\AdvertisingBannerTranslation as LMSAdvertisingBannerTranslation;
use App\Models\LMS\Badge as LMSBadge;
use App\Models\LMS\BadgeTranslation as LMSBadgeTranslation;
use App\Models\LMS\RegistrationPackage as LMSRegistrationPackage;
use App\Models\LMS\RegistrationPackageTranslation as LMSRegistrationPackageTranslation;
use App\Models\LMS\User as LMSUser;
use App\Models\LMS\Role as LMSRole;
use App\Models\LMS\Permission as LMSPermission;
use App\Models\LMS\Group as LMSGroup;
use App\Models\LMS\GroupUser as LMSGroupUser;
use App\Models\LMS\Category as LMSCategory;
use App\Models\LMS\CategoryTranslation as LMSCategoryTranslation;
use App\Models\LMS\TrendCategory as LMSTrendCategory;
use App\Models\LMS\BecomeInstructor as LMSBecomeInstructor;
use App\Models\LMS\BlogCategory as LMSBlogCategory;
use App\Models\LMS\Blog as LMSBlog;
use App\Models\LMS\BlogTranslation as LMSBlogTranslation;
use App\Models\LMS\Filter as LMSFilter;
use App\Models\LMS\FilterTranslation as LMSFilterTranslation;
use App\Models\LMS\FilterOption as LMSFilterOption;
use App\Models\LMS\FilterOptionTranslation as LMSFilterOptionTranslation;
use App\Models\LMS\Webinar as LMSWebinar;
use App\Models\LMS\WebinarTranslation as LMSWebinarTranslation;
use App\Models\LMS\Bundle as LMSBundle;
use App\Models\LMS\BundleTranslation as LMSBundleTranslation;
use App\Models\LMS\BundleFilterOption as LMSBundleFilterOption;
use App\Models\LMS\BundleWebinar as LMSBundleWebinar;
use App\Models\LMS\WebinarExtraDescription as LMSWebinarExtraDescription;
use App\Models\LMS\WebinarExtraDescriptionTranslation as LMSWebinarExtraDescriptionTranslation;
use App\Models\LMS\WebinarFilterOption as LMSWebinarFilterOption;
use App\Models\LMS\WebinarPartnerTeacher as LMSWebinarPartnerTeacher;
use App\Models\LMS\WebinarReport as LMSWebinarReport;
use App\Models\LMS\WebinarReview as LMSWebinarReview;
use App\Models\LMS\WebinarChapter as LMSWebinarChapter;
use App\Models\LMS\WebinarChapterTranslation as LMSWebinarChapterTranslation;
use App\Models\LMS\WebinarAssignment as LMSWebinarAssignment;
use App\Models\LMS\WebinarAssignmentTranslation as LMSWebinarAssignmentTranslation;
use App\Models\LMS\WebinarAssignmentAttachment as LMSWebinarAssignmentAttachment;
use App\Models\LMS\WebinarAssignmentHistory as LMSWebinarAssignmentHistory;
use App\Models\LMS\WebinarAssignmentHistoryMessage as LMSWebinarAssignmentHistoryMessage;
use App\Models\LMS\File as LMSFile;
use App\Models\LMS\FileTranslation as LMSFileTranslation;
use App\Models\LMS\Session as LMSSession;
use App\Models\LMS\SessionTranslation as LMSSessionTranslation;
use App\Models\LMS\SessionRemind as LMSSessionRemind;
use App\Models\LMS\TextLesson as LMSTextLesson;
use App\Models\LMS\TextLessonTranslation as LMSTextLessonTranslation;
use App\Models\LMS\TextLessonAttachment as LMSTextLessonAttachment;
use App\Models\LMS\Quiz as LMSQuiz;
use App\Models\LMS\QuizTranslation as LMSQuizTranslation;
use App\Models\LMS\QuizzesQuestion as LMSQuizzesQuestion;
use App\Models\LMS\QuizzesQuestionTranslation as LMSQuizzesQuestionTranslation;
use App\Models\LMS\QuizzesQuestionsAnswer as LMSQuizzesQuestionsAnswer;
use App\Models\LMS\QuizzesQuestionsAnswerTranslation as LMSQuizzesQuestionsAnswerTranslation;
use App\Models\LMS\QuizzesResult as LMSQuizzesResult;
use App\Models\LMS\WebinarChapterItem as LMSWebinarChapterItem;
use App\Models\LMS\Certificate as LMSCertificate;
use App\Models\LMS\CertificateTemplate as LMSCertificateTemplate;
use App\Models\LMS\CertificateTemplateTranslation as LMSCertificateTemplateTranslation;
use App\Models\LMS\Comment as LMSComment;
use App\Models\LMS\CommentReport as LMSCommentReport;
use App\Models\LMS\CourseForum as LMSCourseForum;
use App\Models\LMS\CourseForumAnswer as LMSCourseForumAnswer;
use App\Models\LMS\CourseLearning as LMSCourseLearning;
use App\Models\LMS\CourseNoticeboard as LMSCourseNoticeboard;
use App\Models\LMS\CourseNoticeboardStatus as LMSCourseNoticeboardStatus;
use App\Models\LMS\DeleteAccountRequest as LMSDeleteAccountRequest;
use App\Models\LMS\Discount as LMSDiscount;
use App\Models\LMS\DiscountCategory as LMSDiscountCategory;
use App\Models\LMS\Faq as LMSFaq;
use App\Models\LMS\FaqTranslation as LMSFaqTranslation;
use App\Models\LMS\Favorite as LMSFavorite;
use App\Models\LMS\FeatureWebinar as LMSFeatureWebinar;
use App\Models\LMS\FeatureWebinarTranslation as LMSFeatureWebinarTranslation;
use App\Models\LMS\Forum as LMSForum;
use App\Models\LMS\ForumTranslation as LMSForumTranslation;
use App\Models\LMS\ForumTopic as LMSForumTopic;
use App\Models\LMS\ForumTopicAttachment as LMSForumTopicAttachment;
use App\Models\LMS\ForumTopicPost as LMSForumTopicPost;
use App\Models\LMS\ForumTopicReport as LMSForumTopicReport;
use App\Models\LMS\ForumRecommendedTopic as LMSForumRecommendedTopic;
use App\Models\LMS\ForumRecommendedTopicItem as LMSForumRecommendedTopicItem;
use App\Models\LMS\NotificationTemplate as LMSNotificationTemplate;
use App\Models\LMS\Page as LMSPage;
use App\Models\LMS\PageTranslation as LMSPageTranslation;
use App\Models\LMS\Promotion as LMSPromotion;
use App\Models\LMS\PromotionTranslation as LMSPromotionTranslation;
use App\Models\LMS\Region as LMSRegion;
use App\Models\LMS\SpecialOffer as LMSSpecialOffer;
use App\Models\LMS\Subscribe as LMSSubscribe;
use App\Models\LMS\SubscribeTranslation as LMSSubscribeTranslation;
use App\Models\LMS\Tag as LMSTag;
use App\Models\LMS\Testimonial as LMSTestimonial;
use App\Models\LMS\TestimonialTranslation as LMSTestimonialTranslation;
use App\Models\LMS\Ticket as LMSTicket;
use App\Models\LMS\TicketTranslation as LMSTicketTranslation;
use App\Models\LMS\TicketUser as LMSTicketUser;
use App\Models\LMS\UserMeta as LMSUserMeta;
use App\Models\LMS\UserOccupation as LMSUserOccupation;
use App\Models\LMS\UpcomingCourse as LMSUpcomingCourse;
use App\Models\LMS\UpcomingCourseTranslation as LMSUpcomingCourseTranslation;
use App\Models\LMS\UpcomingCourseFilterOption as LMSUpcomingCourseFilterOption;
use App\Models\LMS\UpcomingCourseFollower as LMSUpcomingCourseFollower;
use App\Models\LMS\UpcomingCourseReport as LMSUpcomingCourseReport;
use App\Models\LMS\FloatingBar as LMSFloatingBar;
use App\Models\LMS\FloatingBarTranslation as LMSFloatingBarTranslation;
use App\Models\LMS\Currency as LMSCurrency;
use App\Models\LMS\HomePageStatistic as LMSHomePageStatistic;
use App\Models\LMS\HomePageStatisticTranslation as LMSHomePageStatisticTranslation;
use App\Models\LMS\Waitlist as LMSWaitlist;
use App\Models\LMS\Meeting as LMSMeeting;
use App\Models\LMS\MeetingTime as LMSMeetingTime;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates = User::where('role_id',3)->where('status',1)->whereHas('user_domain',function($q){
            return $q->where('featured',1);
        })->with('user_domain')->get()->sortBy('user_domain.serial_number');
        // dd($templates);
        $active_theme=Domain::where('user_id',Auth::id())->first();
        return view('seller.store.template',compact('templates','active_theme'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $domain_id=Auth::user()->domain_id;
        $domain_template = Domain::where('id', $id)->where('featured', 1)->first();
        if(!$domain_template){
            \Session::flash('error', 'Theme not found !');
            return back();
        }

        DB::beginTransaction();
        try {
            Domain::where('id',$domain_id)->update(['template_id'=>$domain_template->template_id, 'template_domain_id' => $id,'menu_type'=>$domain_template->menu_type,'top_bar_contact_status'=>$domain_template->top_bar_contact_status,'float_contact_status'=>$domain_template->float_contact_status,'shop_type'=>$domain_template->shop_type]);

            if($request->import){
                $this->cloneTemplate($domain_template);
            }else{
                $useroptions = Useroption::whereIn('key', ['font', 'text_color', 'theme_color', 'title_color'])->get();
                foreach($useroptions as $useroption){
                    Useroption::updateOrCreate(['key' => $useroption->key, 'user_id' => Auth::user()->id], ['value' => $useroption->value]);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            \Session::flash('warning', 'Something went wrong !');
            return back();
        }

        Cache::forget(get_host());
        \Session::flash('success', 'Theme activated successfully');
        return back();
    }


    public function cloneTemplate($domain_template)
    {
        $user = Auth::user();
        $user_template = User::find($domain_template->user_id);

        // delete data old
        $this->deleteDataBeforeClone($domain_template);


        //clone data template
        //file
        $path_file_old = 'uploads/'.$user_template->id;
        $path_file_new = 'uploads/'.$user->id;
        \File::copyDirectory($path_file_old,$path_file_new);

        $path_url_old = $user_template->user_domain->domain.'/uploads/'.$user_template->id;
        $path_url_new = $user->user_domain->domain.'/uploads/'.$user->id;

        //check shop not lms
        if($domain_template->shop_type!=2)
        {
          //Store or Business
          //info
          $useroptions = Useroption::where('user_id',$user_template->id)
              ->whereNotIn('key', ['shop_name', 'shop_description', 'store_email', 'contract_address', 'receiver_address', 'order_prefix'])
              ->select('key','value')->get()->toArray();
          foreach($useroptions as $useroption)
          {
              $data_useroptions=[];
              $data_useroptions['user_id']=$user->id;
              $data_useroptions['key']= $useroption['key'];
              $data_useroptions['value']= $useroption['value'];
              Useroption::updateOrCreate($data_useroptions);
          }

          //category
          $categories = Category::where('user_id',$user_template->id)->get();
          $old_category_id = $categories->pluck('id')->toArray();
          $new_category_id = [];
          if($categories){
            foreach($categories as $category){
              $new_category = $category->replicate();
              $new_category->name=str_replace($path_file_old,$path_file_new,$category->name);
              $new_category->user_id = $user->id;
              $new_category->save();
              array_push($new_category_id,$new_category->id);
            }
            $final_category_id = array_combine($old_category_id,$new_category_id);
            //update new category p_id 
            $new_categories = Category::where('user_id',$user->id)->whereNotNull('p_id')->get();
            if($new_categories){
              foreach($new_categories as $new_category){
                $new_category->p_id = $final_category_id[$new_category->p_id] ?? null;
                $new_category->save();
              }
            }
          }

          //category_meta
          $category_metas = Categorymeta::whereIn('category_id',$old_category_id)->get();
          if($category_metas){
            foreach($category_metas as $category_meta){
              $new_category_meta = $category_meta->replicate();
              $new_category_meta->content=str_replace($path_file_old,$path_file_new,$category_meta->content);
              $new_category_meta->category_id = $final_category_id[$category_meta->category_id] ?? null;
              $new_category_meta->save();
            }
          }

          //post
          $posts = Post::where('user_id',$user_template->id)->get();
          if($posts){
            foreach($posts as $post){
              $new_post = $post->replicate();
              $new_post->image=str_replace($path_file_old,$path_file_new,$post->image);
              $new_post->user_id = $user->id;
              $new_post->category_id = $final_category_id[$post->category_id] ?? null;
              $new_post->save();
            }
          }

          //term
          $terms = Term::where('user_id',$user_template->id)->get();
          $old_term_id = $terms->pluck('id')->toArray();
          $new_term_id = [];
          if($terms){
            foreach($terms as $term){
              $new_term = $term->replicate();
              $new_term->user_id = $user->id;
              $new_term->save();
              array_push($new_term_id,$new_term->id);
            }
            $final_term_id = array_combine($old_term_id,$new_term_id);
          }

          //price
          $prices = Price::whereIn('term_id',$old_term_id)->get();
          if($prices){
            foreach($prices as $price){
              $new_price = $price->replicate();
              $new_price->term_id = $final_term_id[$price->term_id] ?? null;
              $new_price->save();
            }
          }

          //stock
          $stocks = Stock::whereIn('term_id',$old_term_id)->get();
          if($stocks){
            foreach($stocks as $stock){
              $new_stock = $stock->replicate();
              $new_stock->term_id = $final_term_id[$stock->term_id] ?? null;
              $new_stock->save();
            }
          }

          //meta
          $metas = Meta::whereIn('term_id',$old_term_id)->get();
          if($metas){
            foreach($metas as $meta){
              $new_meta = $meta->replicate();
              $new_meta->term_id = $final_term_id[$meta->term_id] ?? null;
              $new_meta->save();
            }
          }

          //post_category
          $post_categories = Postcategory::whereIn('term_id',$old_term_id)->get();
          if($post_categories){
            foreach($post_categories as $post_category){
              $new_post_category = $post_category->replicate();
              $new_post_category->term_id = $final_term_id[$post_category->term_id] ?? null;
              $new_post_category->category_id = $final_category_id[$post_category->category_id] ?? null;
              $new_post_category->save();
            }
          }

          //media
          $medias = Media::where('user_id',$user_template->id)->get();
          $old_media_id = $medias->pluck('id')->toArray();
          $new_media_id = [];
          if($medias){
            foreach($medias as $media){
              $new_media = $media->replicate();
              $new_media->name = str_replace($path_file_old,$path_file_new,$media->name);
              $new_media->url = str_replace($path_url_old,$path_url_new,$media->url);
              $new_media->user_id = $user->id;
              $new_media->save();
              array_push($new_media_id,$new_media->id);
            }
            $final_media_id = array_combine($old_media_id,$new_media_id);
          }
          
          //post_media
          $post_medias = Postmedia::whereIn('term_id',$old_term_id)->get();
          if($post_medias){
            foreach($post_medias as $post_media){
              $new_post_media = $post_media->replicate();
              $new_post_media->term_id = $final_term_id[$post_media->term_id] ?? null;
              $new_post_media->media_id = $final_media_id[$post_media->media_id] ?? null;
              $new_post_media->save();
            }
          }
          
          //portfolio
          $portfolios = Portfolio::where('user_id',$user_template->id)->get();
          if($portfolios){
            foreach($portfolios as $portfolio){
              $new_portfolio = $portfolio->replicate();
              $new_portfolio->image = str_replace($path_file_old,$path_file_new,$portfolio->image);
              $new_portfolio->category_id = $final_category_id[$portfolio->category_id] ?? null;
              $new_portfolio->user_id = $user->id;
              $new_portfolio->save();
            }
          }

          //career
          $careers = Career::where('user_id',$user_template->id)->get();
          if($careers){
            foreach($careers as $career){
              $new_career = $career->replicate();
              $new_career->category_id = $final_category_id[$career->category_id] ?? null;
              $new_career->user_id = $user->id;
              $new_career->save();
            }
          }

          //faq
          $faqs = Faq::where('user_id',$user_template->id)->get();
          if($faqs){
            foreach($faqs as $faq){
              $new_faq = $faq->replicate();
              $new_faq->user_id = $user->id;
              $new_faq->save();
            }
          }

          //package
          $packages = Package::where('user_id',$user_template->id)->get();
          if($packages){
            foreach($packages as $package){
              $new_package = $package->replicate();
              $new_package->category_id = $final_category_id[$package->category_id] ?? null;
              $new_package->user_id = $user->id;
              $new_package->save();
            }
          }

          //partner
          $partners = Partner::where('user_id',$user_template->id)->get();
          if($partners){
            foreach($partners as $partner){
              $new_partner = $partner->replicate();
              $new_partner->image=str_replace($path_file_old,$path_file_new,$partner->image);
              $new_partner->user_id = $user->id;
              $new_partner->save();
            }
          }

          //service
          $services = Service::where('user_id',$user_template->id)->get();
          if($services){
            foreach($services as $service){
              $new_service = $service->replicate();
              $new_service->image=str_replace($path_file_old,$path_file_new,$service->image);
              $new_service->user_id = $user->id;
              $new_service->save();
            }
          }

          //team
          $teams = Team::where('user_id',$user_template->id)->get();
          if($teams){
            foreach($teams as $team){
              $new_team = $team->replicate();
              $new_team->image=str_replace($path_file_old,$path_file_new,$team->image);
              $new_team->user_id = $user->id;
              $new_team->save();
            }
          }

          //testimonial
          $testimonials = Testimonial::where('user_id',$user_template->id)->get();
          if($testimonials){
            foreach($testimonials as $testimonial){
              $new_testimonial = $testimonial->replicate();
              $new_testimonial->image=str_replace($path_file_old,$path_file_new,$testimonial->image);
              $new_testimonial->user_id = $user->id;
              $new_testimonial->save();
            }
          }

          //contact_list
          $contact_lists = ContactLists::where('user_id',$user_template->id)->get();
          if($contact_lists){
            foreach($contact_lists as $contact_list){
              $new_contact_list = $contact_list->replicate();
              $new_contact_list->image=str_replace($path_file_old,$path_file_new,$contact_list->image);
              $new_contact_list->user_id = $user->id;
              $new_contact_list->save();
            }
          }
          
          //location
          $locations = Location::where('user_id',$user_template->id)->get();
          if($locations){
            foreach($locations as $location){
              $new_location = $location->replicate();
              $new_location->image=str_replace($path_file_old,$path_file_new,$location->image);
              $new_location->user_id = $user->id;
              $new_location->save();
            }
          }
          
          //feature_page
          $feature_pages = ProductFeature::where('user_id',$user_template->id)->get();
          $old_feature_page_id = $feature_pages->pluck('id')->toArray();
          $new_feature_page_id = [];
          if($feature_pages){
            foreach($feature_pages as $feature_page){
              $new_feature_page = $feature_page->replicate();
              $new_feature_page->user_id = $user->id;
              $new_feature_page->save();
              array_push($new_feature_page_id,$new_feature_page->id);
            }
            $final_feature_page_id = array_combine($old_feature_page_id,$new_feature_page_id);
          }

          //section
          $sections = ProductFeatureDetail::whereIn('feature_page_id',$old_feature_page_id)->get();
          $old_section_id = $sections->pluck('id')->toArray();
          $new_section_id = [];
          if($sections){
            foreach($sections as $section){
              $new_section = $section->replicate();
              $new_section->image=str_replace($path_file_old,$path_file_new,$section->image);
              $new_section->feature_page_id = $final_feature_page_id[$section->feature_page_id] ?? null;
              $new_section->save();
              array_push($new_section_id,$new_section->id);
            }
            $final_section_id = array_combine($old_section_id,$new_section_id);
          }

          //section_element
          $section_elements = ProductFeatureSectionElement::whereIn('feature_page_detail_id',$old_section_id)->get();
          if($section_elements){
            foreach($section_elements as $section_element){
              $new_section_element = $section_element->replicate();
              $new_section_element->image=str_replace($path_file_old,$path_file_new,$section_element->image);
              $new_section_element->feature_page_detail_id = $final_section_id[$section_element->feature_page_detail_id] ?? null;
              $new_section_element->save();
            }
          }

          //menu
          $menus = Menu::where('user_id',$user_template->id)->get();
          if($menus){
            foreach($menus as $menu){
              $new_menu = $menu->replicate();
              $new_menu->user_id = $user->id;
              $new_menu->fp_id = $final_feature_page_id[$menu->fp_id] ?? null;
              $new_menu->save();
            }
          }
         
          //permalinks
          $permalinks = Domain::where('user_id',$user_template->id)->first();
          Domain::updateOrCreate(['user_id' => $user->id], ['permalinks' => $permalinks->permalinks]);
        }
        else {
          //LMS
          //lms_settings 
          $lms_settings = LMSSetting::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_setting_id = $lms_settings->pluck('id')->toArray();
          $new_lms_setting_id = [];
          if($lms_settings){
            foreach($lms_settings as $lms_setting){
              $new_lms_setting = $lms_setting->replicate();
              $new_lms_setting->updated_at = $lms_setting->updated_at;
              $new_lms_setting->save();
              array_push($new_lms_setting_id,$new_lms_setting->id);
            }
            $final_lms_setting_id = array_combine($old_lms_setting_id,$new_lms_setting_id);
          }

          //lms_setting_translations
          $lms_setting_translations = LMSSettingTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_setting_translations){
            foreach($lms_setting_translations as $lms_setting_translation){
              $new_lms_setting_translation = $lms_setting_translation->replicate();
              $new_lms_setting_translation->setting_id = $final_lms_setting_id[$lms_setting_translation->setting_id] ?? null;
              $new_lms_setting_translation->save();
            }
          }

          //lms_home_sections
          $lms_home_sections = LMSHomeSection::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_home_sections){
            foreach($lms_home_sections as $lms_home_section){
              $new_lms_home_section = $lms_home_section->replicate();
              $new_lms_home_section->save();
            }
          }

          //lms_advertising_banners 
          $lms_advertising_banners = LMSAdvertisingBanner::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_advertising_banner_id = $lms_advertising_banners->pluck('id')->toArray();
          $new_lms_advertising_banner_id = [];
          if($lms_advertising_banners){
            foreach($lms_advertising_banners as $lms_advertising_banner){
              $new_lms_advertising_banner = $lms_advertising_banner->replicate();
              $new_lms_advertising_banner->created_at = $lms_advertising_banner->created_at;
              $new_lms_advertising_banner->save();
              array_push($new_lms_advertising_banner_id,$new_lms_advertising_banner->id);
            }
            $final_lms_advertising_banner_id = array_combine($old_lms_advertising_banner_id,$new_lms_advertising_banner_id);
          }

          //lms_advertising_banner_translations
          $lms_advertising_banner_translations = LMSAdvertisingBannerTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_advertising_banner_translations){
            foreach($lms_advertising_banner_translations as $lms_advertising_banner_translation){
              $new_lms_advertising_banner_translation = $lms_advertising_banner_translation->replicate();
              $new_lms_advertising_banner_translation->advertising_banner_id = $final_lms_advertising_banner_id[$lms_advertising_banner_translation->advertising_banner_id] ?? null;
              $new_lms_advertising_banner_translation->save();
            }
          }

          //lms_badges 
          $lms_badges = LMSBadge::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_badge_id = $lms_badges->pluck('id')->toArray();
          $new_lms_badge_id = [];
          if($lms_badges){
            foreach($lms_badges as $lms_badge){
              $new_lms_badge = $lms_badge->replicate();
              $new_lms_badge->created_at = $lms_badge->created_at;
              $new_lms_badge->save();
              array_push($new_lms_badge_id,$new_lms_badge->id);
            }
            $final_lms_badge_id = array_combine($old_lms_badge_id,$new_lms_badge_id);
          }

          //lms_badge_translations
          $lms_badge_translations = LMSBadgeTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_badge_translations){
            foreach($lms_badge_translations as $lms_badge_translation){
              $new_lms_badge_translation = $lms_badge_translation->replicate();
              $new_lms_badge_translation->badge_id = $final_lms_badge_id[$lms_badge_translation->badge_id] ?? null;
              $new_lms_badge_translation->save();
            }
          }

          //lms_registration_packages 
          $lms_registration_packages = LMSRegistrationPackage::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_registration_package_id = $lms_registration_packages->pluck('id')->toArray();
          $new_lms_registration_package_id = [];
          if($lms_registration_packages){
            foreach($lms_registration_packages as $lms_registration_package){
              $new_lms_registration_package = $lms_registration_package->replicate();
              $new_lms_registration_package->created_at = $lms_registration_package->created_at;
              $new_lms_registration_package->save();
              array_push($new_lms_registration_package_id,$new_lms_registration_package->id);
            }
            $final_lms_registration_package_id = array_combine($old_lms_registration_package_id,$new_lms_registration_package_id);
          }

          //lms_registration_package_translations
          $lms_registration_package_translations = LMSRegistrationPackageTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_registration_package_translations){
            foreach($lms_registration_package_translations as $lms_registration_package_translation){
              $new_lms_registration_package_translation = $lms_registration_package_translation->replicate();
              $new_lms_registration_package_translation->registration_package_id = $final_lms_registration_package_id[$lms_registration_package_translation->registration_package_id] ?? null;
              $new_lms_registration_package_translation->save();
            }
          }

          //lms_roles
          $lms_roles = LMSRole::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_role_id = $lms_roles->pluck('id')->toArray();
          $new_lms_role_id = [];
          
          if($lms_roles){
            foreach($lms_roles as $lms_role){
              $new_lms_role = $lms_role->replicate();
              $new_lms_role->created_at = $lms_role->created_at;
              $new_lms_role->save();
              array_push($new_lms_role_id,$new_lms_role->id);
            }
            $final_lms_role_id = array_combine($old_lms_role_id,$new_lms_role_id);
          }
          
          //lms_users
          $lms_users = LMSUser::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_user_id = $lms_users->pluck('id')->toArray();
          $new_lms_user_id = [];

          if($lms_users){
            foreach($lms_users as $lms_user){
              $new_lms_user = $lms_user->replicate();
              $new_lms_user->role_id = $final_lms_role_id[$lms_user->role_id] ?? 0;
              $new_lms_user->created_at = $lms_user->created_at;
              $new_lms_user->updated_at = $lms_user->updated_at;
              $new_lms_user->save();
              array_push($new_lms_user_id,$new_lms_user->id);
            }
            $final_lms_user_id = array_combine($old_lms_user_id,$new_lms_user_id);

            //update account admin
            $account_admin = LMSUser::getAdmin();
            if($account_admin){
              $account_admin->email = $user->email;
              $account_admin->password = $user->password;
              $account_admin->full_name = $user->name;
              $account_admin->save();
            }
          }

          //lms_permissions
          $lms_permissions = LMSPermission::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_permissions){
            foreach($lms_permissions as $lms_permission){
              $new_lms_permission = $lms_permission->replicate();
              $new_lms_permission->role_id = $final_lms_role_id[$lms_permission->role_id] ?? null;
              $new_lms_permission->save();
            }
          }

          //lms_groups 
          $lms_groups = LMSGroup::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_group_id = $lms_groups->pluck('id')->toArray();
          $new_lms_group_id = [];
          if($lms_groups){
            foreach($lms_groups as $lms_group){
              $new_lms_group = $lms_group->replicate();
              $new_lms_group->creator_id = $final_lms_user_id[$lms_group->creator_id] ?? null;
              $new_lms_group->created_at = $lms_group->created_at;
              $new_lms_group->save();
              array_push($new_lms_group_id,$new_lms_group->id);
            }
            $final_lms_group_id = array_combine($old_lms_group_id,$new_lms_group_id);
          }

          //lms_group_users
          $lms_group_users = LMSGroupUser::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_group_users){
            foreach($lms_group_users as $lms_group_user){
              $new_lms_group_user = $lms_group_user->replicate();
              $new_lms_group_user->group_id = $final_lms_group_id[$lms_group_user->group_id] ?? null;
              $new_lms_group_user->created_at = $lms_group_user->created_at;
              $new_lms_group_user->save();
            }
          }

          //lms_categories
          $lms_categories = LMSCategory::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_category_id = $lms_categories->pluck('id')->toArray();
          $new_lms_category_id = [];
          if($lms_categories){
            foreach($lms_categories as $lms_category){
              $new_lms_category = $lms_category->replicate()->makeHidden('locale','title','translations');
              $new_lms_category = LMSCategory::create($new_lms_category->toArray());
              $new_lms_category->save();
              array_push($new_lms_category_id,$new_lms_category->id);
            }
            $final_lms_category_id = array_combine($old_lms_category_id,$new_lms_category_id);
            //update new parent_id 
            $new_lms_categories = LMSCategory::withoutGlobalScopes()->where('domain_id',$user->domain_id)->whereNotNull('parent_id')->get();
            if($new_lms_categories){
              foreach($new_lms_categories as $new_lms_category){
                $new_lms_category->parent_id = $final_lms_category_id[$new_lms_category->parent_id] ?? null;
                $new_lms_category->save();
              }
            }
          }

          //lms_category_translations
          $lms_category_translations = LMSCategoryTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_category_translations){
            foreach($lms_category_translations as $lms_category_translation){
              $new_lms_category_translation = $lms_category_translation->replicate();
              $new_lms_category_translation->category_id = $final_lms_category_id[$lms_category_translation->category_id] ?? null;
              $new_lms_category_translation->save();
            }
          }

          // //lms_trend_categories
          $lms_trend_categories = LMSTrendCategory::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_trend_categories){
            foreach($lms_trend_categories as $lms_trend_category){
              $new_lms_trend_category = $lms_trend_category->replicate();
              $new_lms_trend_category->category_id = $final_lms_category_id[$lms_trend_category->category_id] ?? null;
              $new_lms_trend_category->save();
            }
          }

          //lms_become_instructors
          $lms_become_instructors = LMSBecomeInstructor::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_become_instructors){
            foreach($lms_become_instructors as $lms_become_instructor){
              $new_lms_become_instructor = $lms_become_instructor->replicate();
              $new_lms_become_instructor->user_id = $final_lms_user_id[$lms_become_instructor->user_id] ?? null;
              $new_lms_become_instructor->package_id = $final_lms_registration_package_id[$lms_become_instructor->package_id] ?? null;
              $new_lms_become_instructor->created_at = $lms_become_instructor->created_at;
              $new_lms_become_instructor->save();
            }
          }

          //lms_blog_categories 
          $lms_blog_categories = LMSBlogCategory::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_blog_category_id = $lms_blog_categories->pluck('id')->toArray();
          $new_lms_blog_category_id = [];
          if($lms_blog_categories){
            foreach($lms_blog_categories as $lms_blog_category){
              $new_lms_blog_category = $lms_blog_category->replicate();
              $new_lms_blog_category->save();
              array_push($new_lms_blog_category_id,$new_lms_blog_category->id);
            }
            $final_lms_blog_category_id = array_combine($old_lms_blog_category_id,$new_lms_blog_category_id);
          }

          //lms_blogs 
          $lms_blogs = LMSBlog::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_blog_id = $lms_blogs->pluck('id')->toArray();
          $new_lms_blog_id = [];
          if($lms_blogs){
            foreach($lms_blogs as $lms_blog){
              $new_lms_blog = $lms_blog->replicate()->makeHidden('locale','title','description','meta_description','content','translations');
              $new_lms_blog->category_id = $final_lms_blog_category_id[$lms_blog->category_id] ?? null;
              $new_lms_blog->author_id = $final_lms_user_id[$lms_blog->author_id] ?? null;
              $new_lms_blog->created_at = $lms_blog->created_at;
              $new_lms_blog->updated_at = $lms_blog->updated_at;
              $new_lms_blog = LMSBlog::create($new_lms_blog->toArray());
              $new_lms_blog->save();
              array_push($new_lms_blog_id,$new_lms_blog->id);
            }
            $final_lms_blog_id = array_combine($old_lms_blog_id,$new_lms_blog_id);
          }

          //lms_blog_translations
          $lms_blog_translations = LMSBlogTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_blog_translations){
            foreach($lms_blog_translations as $lms_blog_translation){
              $new_lms_blog_translation = $lms_blog_translation->replicate();
              $new_lms_blog_translation->blog_id = $final_lms_blog_id[$lms_blog_translation->blog_id] ?? null;
              $new_lms_blog_translation->save();
            }
          }

          //lms_filters 
          $lms_filters = LMSFilter::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_filter_id = $lms_filters->pluck('id')->toArray();
          $new_lms_filter_id = [];
          if($lms_filters){
            foreach($lms_filters as $lms_filter){
              $new_lms_filter = $lms_filter->replicate();
              $new_lms_filter->category_id = $final_lms_category_id[$lms_filter->category_id] ?? null;
              $new_lms_filter->save();
              array_push($new_lms_filter_id,$new_lms_filter->id);
            }
            $final_lms_filter_id = array_combine($old_lms_filter_id,$new_lms_filter_id);
          }

          //lms_filter_translations
          $lms_filter_translations = LMSFilterTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_filter_translations){
            foreach($lms_filter_translations as $lms_filter_translation){
              $new_lms_filter_translation = $lms_filter_translation->replicate();
              $new_lms_filter_translation->filter_id = $final_lms_filter_id[$lms_filter_translation->filter_id] ?? null;
              $new_lms_filter_translation->save();
            }
          }

          //lms_filter_options 
          $lms_filter_options = LMSFilterOption::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_filter_option_id = $lms_filter_options->pluck('id')->toArray();
          $new_lms_filter_option_id = [];
          if($lms_filter_options){
            foreach($lms_filter_options as $lms_filter_option){
              $new_lms_filter_option = $lms_filter_option->replicate();
              $new_lms_filter_option->filter_id = $final_lms_filter_id[$lms_filter_option->filter_id] ?? null;
              $new_lms_filter_option->save();
              array_push($new_lms_filter_option_id,$new_lms_filter_option->id);
            }
            $final_lms_filter_option_id = array_combine($old_lms_filter_option_id,$new_lms_filter_option_id);
          }

          //lms_filter_option_translations
          $lms_filter_option_translations = LMSFilterOptionTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_filter_option_translations){
            foreach($lms_filter_option_translations as $lms_filter_option_translation){
              $new_lms_filter_option_translation = $lms_filter_option_translation->replicate();
              $new_lms_filter_option_translation->filter_option_id = $final_lms_filter_option_id[$lms_filter_option_translation->filter_option_id] ?? null;
              $new_lms_filter_option_translation->save();
            }
          }

          //lms_webinars 
          $lms_webinars = LMSWebinar::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_webinar_id = $lms_webinars->pluck('id')->toArray();
          $new_lms_webinar_id = [];
          if($lms_webinars){
            foreach($lms_webinars as $lms_webinar){
              $new_lms_webinar = $lms_webinar->replicate()->makeHidden('locale','title','description','seo_description','translations');
              $new_lms_webinar->teacher_id = $final_lms_user_id[$lms_webinar->teacher_id] ?? null;
              $new_lms_webinar->creator_id = $final_lms_user_id[$lms_webinar->creator_id] ?? null;
              $new_lms_webinar->category_id = $final_lms_category_id[$lms_webinar->category_id] ?? null;
              $new_lms_webinar->created_at = $lms_webinar->created_at;
              $new_lms_webinar->updated_at = $lms_webinar->updated_at;
              $new_lms_webinar = LMSWebinar::create($new_lms_webinar->toArray());
              $new_lms_webinar->save();
              array_push($new_lms_webinar_id,$new_lms_webinar->id);
            }
            $final_lms_webinar_id = array_combine($old_lms_webinar_id,$new_lms_webinar_id);
          }

          //lms_webinar_translations
          $lms_webinar_translations = LMSWebinarTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_webinar_translations){
            foreach($lms_webinar_translations as $lms_webinar_translation){
              $new_lms_webinar_translation = $lms_webinar_translation->replicate();
              $new_lms_webinar_translation->webinar_id = $final_lms_webinar_id[$lms_webinar_translation->webinar_id] ?? null;
              $new_lms_webinar_translation->save();
            }
          }

          //lms_bundles 
          $lms_bundles = LMSBundle::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_bundle_id = $lms_bundles->pluck('id')->toArray();
          $new_lms_bundle_id = [];
          if($lms_bundles){
            foreach($lms_bundles as $lms_bundle){
              $new_lms_bundle = $lms_bundle->replicate()->makeHidden('locale','title','description','seo_description','translations');
              $new_lms_bundle->creator_id = $final_lms_user_id[$lms_bundle->creator_id] ?? null;
              $new_lms_bundle->teacher_id = $final_lms_user_id[$lms_bundle->teacher_id] ?? null;
              $new_lms_bundle->category_id = $final_lms_category_id[$lms_bundle->category_id] ?? null;
              $new_lms_bundle->created_at = $lms_bundle->created_at;
              $new_lms_bundle->updated_at = $lms_bundle->updated_at;
              $new_lms_bundle = LMSBundle::create($new_lms_bundle->toArray());
              $new_lms_bundle->save();
              array_push($new_lms_bundle_id,$new_lms_bundle->id);
            }
            $final_lms_bundle_id = array_combine($old_lms_bundle_id,$new_lms_bundle_id);
          }

          //lms_bundle_translations
          $lms_bundle_translations = LMSBundleTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_bundle_translations){
            foreach($lms_bundle_translations as $lms_bundle_translation){
              $new_lms_bundle_translation = $lms_bundle_translation->replicate();
              $new_lms_bundle_translation->bundle_id = $final_lms_bundle_id[$lms_bundle_translation->bundle_id] ?? null;
              $new_lms_bundle_translation->save();
            }
          }

          //lms_bundle_filter_options
          $lms_bundle_filter_options = LMSBundleFilterOption::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_bundle_filter_options){
            foreach($lms_bundle_filter_options as $lms_bundle_filter_option){
              $new_lms_bundle_filter_option = $lms_bundle_filter_option->replicate();
              $new_lms_bundle_filter_option->bundle_id = $final_lms_bundle_id[$lms_bundle_filter_option->bundle_id] ?? null;
              $new_lms_bundle_filter_option->filter_option_id = $final_lms_filter_option_id[$lms_bundle_filter_option->filter_option_id] ?? null;
              $new_lms_bundle_filter_option->save();
            }
          }

          //lms_bundle_webinars
          $lms_bundle_webinars = LMSBundleWebinar::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_bundle_webinars){
            foreach($lms_bundle_webinars as $lms_bundle_webinar){
              $new_lms_bundle_webinar = $lms_bundle_webinar->replicate();
              $new_lms_bundle_webinar->creator_id = $final_lms_user_id[$lms_bundle_webinar->creator_id] ?? null;
              $new_lms_bundle_webinar->bundle_id = $final_lms_bundle_id[$lms_bundle_webinar->bundle_id] ?? null;
              $new_lms_bundle_webinar->webinar_id = $final_lms_webinar_id[$lms_bundle_webinar->webinar_id] ?? null;
              $new_lms_bundle_webinar->save();
            }
          }

          //lms_webinar_extra_descriptions 
          $lms_webinar_extra_descriptions = LMSWebinarExtraDescription::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_webinar_extra_description_id = $lms_webinar_extra_descriptions->pluck('id')->toArray();
          $new_lms_webinar_extra_description_id = [];
          if($lms_webinar_extra_descriptions){
            foreach($lms_webinar_extra_descriptions as $lms_webinar_extra_description){
              $new_lms_webinar_extra_description = $lms_webinar_extra_description->replicate();
              $new_lms_webinar_extra_description->creator_id = $final_lms_user_id[$lms_webinar_extra_description->creator_id] ?? null;
              $new_lms_webinar_extra_description->webinar_id = $final_lms_webinar_id[$lms_webinar_extra_description->webinar_id] ?? null;
              $new_lms_webinar_extra_description->upcoming_course_id = $final_lms_upcoming_course_id[$lms_webinar_extra_description->upcoming_course_id] ?? null;
              $new_lms_webinar_extra_description->created_at = $lms_webinar_extra_description->created_at;
              $new_lms_webinar_extra_description->save();
              array_push($new_lms_webinar_extra_description_id,$new_lms_webinar_extra_description->id);
            }
            $final_lms_webinar_extra_description_id = array_combine($old_lms_webinar_extra_description_id,$new_lms_webinar_extra_description_id);
          }

          //lms_webinar_extra_description_translations
          $lms_webinar_extra_description_translations = LMSWebinarExtraDescriptionTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_webinar_extra_description_translations){
            foreach($lms_webinar_extra_description_translations as $lms_webinar_extra_description_translation){
              $new_lms_webinar_extra_description_translation = $lms_webinar_extra_description_translation->replicate();
              $new_lms_webinar_extra_description_translation->webinar_extra_description_id = $final_lms_webinar_extra_description_id[$lms_webinar_extra_description_translation->webinar_extra_description_id] ?? null;
              $new_lms_webinar_extra_description_translation->save();
            }
          }

          //lms_webinar_filter_options
          $lms_webinar_filter_options = LMSWebinarFilterOption::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_webinar_filter_options){
            foreach($lms_webinar_filter_options as $lms_webinar_filter_option){
              $new_lms_webinar_filter_option = $lms_webinar_filter_option->replicate();
              $new_lms_webinar_filter_option->webinar_id = $final_lms_webinar_id[$lms_webinar_filter_option->webinar_id] ?? null;
              $new_lms_webinar_filter_option->filter_option_id = $final_lms_filter_option_id[$lms_webinar_filter_option->filter_option_id] ?? null;
              $new_lms_webinar_filter_option->save();
            }
          }

          //lms_webinar_partner_teachers
          $lms_webinar_partner_teachers = LMSWebinarPartnerTeacher::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_webinar_partner_teachers){
            foreach($lms_webinar_partner_teachers as $lms_webinar_partner_teacher){
              $new_lms_webinar_partner_teacher = $lms_webinar_partner_teacher->replicate();
              $new_lms_webinar_partner_teacher->webinar_id = $final_lms_webinar_id[$lms_webinar_partner_teacher->webinar_id] ?? null;
              $new_lms_webinar_partner_teacher->teacher_id = $final_lms_user_id[$lms_webinar_partner_teacher->teacher_id] ?? null;
              $new_lms_webinar_partner_teacher->save();
            }
          }

          //lms_webinar_reports
          $lms_webinar_reports = LMSWebinarReport::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_webinar_reports){
            foreach($lms_webinar_reports as $lms_webinar_report){
              $new_lms_webinar_report = $lms_webinar_report->replicate();
              $new_lms_webinar_report->webinar_id = $final_lms_webinar_id[$lms_webinar_report->webinar_id] ?? null;
              $new_lms_webinar_report->user_id = $final_lms_user_id[$lms_webinar_report->user_id] ?? null;
              $new_lms_webinar_report->created_at = $lms_webinar_report->created_at;
              $new_lms_webinar_report->save();
            }
          }

          //lms_webinar_reviews
          $lms_webinar_reviews = LMSWebinarReview::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_webinar_review_id = $lms_webinar_reviews->pluck('id')->toArray();
          $new_lms_webinar_review_id = [];
          if($lms_webinar_reviews){
            foreach($lms_webinar_reviews as $lms_webinar_review){
              $new_lms_webinar_review = $lms_webinar_review->replicate();
              $new_lms_webinar_review->creator_id = $final_lms_user_id[$lms_webinar_review->creator_id] ?? null;
              $new_lms_webinar_review->webinar_id = $final_lms_webinar_id[$lms_webinar_review->webinar_id] ?? null;
              $new_lms_webinar_review->bundle_id = $final_lms_bundle_id[$lms_webinar_review->bundle_id] ?? null;
              $new_lms_webinar_review->created_at = $lms_webinar_review->created_at;
              $new_lms_webinar_review->save();
              array_push($new_lms_webinar_review_id,$new_lms_webinar_review->id);
            }
            $final_lms_webinar_review_id = array_combine($old_lms_webinar_review_id,$new_lms_webinar_review_id);
          }

          //lms_webinar_chapters 
          $lms_webinar_chapters = LMSWebinarChapter::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_webinar_chapter_id = $lms_webinar_chapters->pluck('id')->toArray();
          $new_lms_webinar_chapter_id = [];
          if($lms_webinar_chapters){
            foreach($lms_webinar_chapters as $lms_webinar_chapter){
              $new_lms_webinar_chapter = $lms_webinar_chapter->replicate();
              $new_lms_webinar_chapter->user_id = $final_lms_user_id[$lms_webinar_chapter->user_id] ?? null;
              $new_lms_webinar_chapter->webinar_id = $final_lms_webinar_id[$lms_webinar_chapter->webinar_id] ?? null;
              $new_lms_webinar_chapter->created_at = $lms_webinar_chapter->created_at;
              $new_lms_webinar_chapter->save();
              array_push($new_lms_webinar_chapter_id,$new_lms_webinar_chapter->id);
            }
            $final_lms_webinar_chapter_id = array_combine($old_lms_webinar_chapter_id,$new_lms_webinar_chapter_id);
          }

          //lms_webinar_chapter_translations
          $lms_webinar_chapter_translations = LMSWebinarChapterTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_webinar_chapter_translations){
            foreach($lms_webinar_chapter_translations as $lms_webinar_chapter_translation){
              $new_lms_webinar_chapter_translation = $lms_webinar_chapter_translation->replicate();
              $new_lms_webinar_chapter_translation->webinar_chapter_id = $final_lms_webinar_chapter_id[$lms_webinar_chapter_translation->webinar_chapter_id] ?? 0;
              $new_lms_webinar_chapter_translation->save();
            }
          }

          //lms_webinar_assignments
          $lms_webinar_assignments = LMSWebinarAssignment::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_webinar_assignment_id = $lms_webinar_assignments->pluck('id')->toArray();
          $new_lms_webinar_assignment_id = [];
          if($lms_webinar_assignments){
            foreach($lms_webinar_assignments as $lms_webinar_assignment){
              $new_lms_webinar_assignment = $lms_webinar_assignment->replicate();
              $new_lms_webinar_assignment->creator_id = $final_lms_user_id[$lms_webinar_assignment->creator_id] ?? null;
              $new_lms_webinar_assignment->webinar_id = $final_lms_webinar_id[$lms_webinar_assignment->webinar_id] ?? null;
              $new_lms_webinar_assignment->chapter_id = $final_lms_webinar_chapter_id[$lms_webinar_assignment->chapter_id] ?? null;
              $new_lms_webinar_assignment->created_at = $lms_webinar_assignment->created_at;
              $new_lms_webinar_assignment->save();
              array_push($new_lms_webinar_assignment_id,$new_lms_webinar_assignment->id);
            }
            $final_lms_webinar_assignment_id = array_combine($old_lms_webinar_assignment_id,$new_lms_webinar_assignment_id);
          }

          //lms_webinar_assignment_translations
          $lms_webinar_assignment_translations = LMSWebinarAssignmentTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_webinar_assignment_translations){
            foreach($lms_webinar_assignment_translations as $lms_webinar_assignment_translation){
              $new_lms_webinar_assignment_translation = $lms_webinar_assignment_translation->replicate();
              $new_lms_webinar_assignment_translation->webinar_assignment_id = $final_lms_webinar_assignment_id[$lms_webinar_assignment_translation->webinar_assignment_id] ?? null;
              $new_lms_webinar_assignment_translation->save();
            }
          }
          
          //lms_webinar_assignment_attachments
          $lms_webinar_assignment_attachments = LMSWebinarAssignmentAttachment::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_webinar_assignment_attachments){
            foreach($lms_webinar_assignment_attachments as $lms_webinar_assignment_attachment){
              $new_lms_webinar_assignment_attachment = $lms_webinar_assignment_attachment->replicate();
              $new_lms_webinar_assignment_attachment->creator_id = $final_lms_user_id[$lms_webinar_assignment_attachment->creator_id] ?? null;
              $new_lms_webinar_assignment_attachment->assignment_id = $final_lms_webinar_assignment_id[$lms_webinar_assignment_attachment->assignment_id] ?? null;
              $new_lms_webinar_assignment_attachment->save();
            }
          }

          //lms_webinar_assignment_histories
          $lms_webinar_assignment_histories = LMSWebinarAssignmentHistory::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_webinar_assignment_history_id = $lms_webinar_assignment_histories->pluck('id')->toArray();
          $new_lms_webinar_assignment_history_id = [];
          if($lms_webinar_assignment_histories){
            foreach($lms_webinar_assignment_histories as $lms_webinar_assignment_history){
              $new_lms_webinar_assignment_history = $lms_webinar_assignment_history->replicate();
              $new_lms_webinar_assignment_history->instructor_id = $final_lms_user_id[$lms_webinar_assignment_history->instructor_id] ?? null;
              $new_lms_webinar_assignment_history->student_id = $final_lms_user_id[$lms_webinar_assignment_history->student_id] ?? null;
              $new_lms_webinar_assignment_history->assignment_id = $final_lms_webinar_assignment_id[$lms_webinar_assignment_history->assignment_id] ?? null;
              $new_lms_webinar_assignment_history->created_at = $lms_webinar_assignment_history->created_at;
              $new_lms_webinar_assignment_history->save();
              array_push($new_lms_webinar_assignment_history_id,$new_lms_webinar_assignment_history->id);
            }
            $final_lms_webinar_assignment_history_id = array_combine($old_lms_webinar_assignment_history_id,$new_lms_webinar_assignment_history_id);
          }

          //lms_webinar_assignment_history_messages
          $lms_webinar_assignment_history_messages = LMSWebinarAssignmentHistoryMessage::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_webinar_assignment_history_messages){
            foreach($lms_webinar_assignment_history_messages as $lms_webinar_assignment_history_message){
              $new_lms_webinar_assignment_history_message = $lms_webinar_assignment_history_message->replicate();
              $new_lms_webinar_assignment_history_message->assignment_history_id = $final_lms_webinar_assignment_history_id[$lms_webinar_assignment_history_message->assignment_history_id] ?? null;
              $new_lms_webinar_assignment_history_message->sender_id = $final_lms_user_id[$lms_webinar_assignment_history_message->sender_id] ?? null;
              $new_lms_webinar_assignment_history_message->created_at = $lms_webinar_assignment_history_message->created_at;
              $new_lms_webinar_assignment_history_message->save();
            }
          }
          
          //lms_files
          $lms_files = LMSFile::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_file_id = $lms_files->pluck('id')->toArray();
          $new_lms_file_id = [];
          if($lms_files){
            foreach($lms_files as $lms_file){
              $new_lms_file = $lms_file->replicate();
              $new_lms_file->creator_id = $final_lms_user_id[$lms_file->creator_id] ?? null;
              $new_lms_file->webinar_id = $final_lms_webinar_id[$lms_file->webinar_id] ?? null;
              $new_lms_file->chapter_id = $final_lms_webinar_chapter_id[$lms_file->chapter_id] ?? null;
              $new_lms_file->created_at = $lms_file->created_at;
              $new_lms_file->updated_at = $lms_file->updated_at;
              $new_lms_file->save();
              array_push($new_lms_file_id,$new_lms_file->id);
            }
            $final_lms_file_id = array_combine($old_lms_file_id,$new_lms_file_id);
          }

          //lms_file_translations
          $lms_file_translations = LMSFileTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_file_translations){
            foreach($lms_file_translations as $lms_file_translation){
              $new_lms_file_translation = $lms_file_translation->replicate();
              $new_lms_file_translation->file_id = $final_lms_file_id[$lms_file_translation->file_id] ?? null;
              $new_lms_file_translation->save();
            }
          }

          //lms_sessions
          $lms_sessions = LMSSession::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_session_id = $lms_sessions->pluck('id')->toArray();
          $new_lms_session_id = [];
          if($lms_sessions){
            foreach($lms_sessions as $lms_session){
              $new_lms_session = $lms_session->replicate();
              $new_lms_session->creator_id = $final_lms_user_id[$lms_session->creator_id] ?? null;
              $new_lms_session->webinar_id = $final_lms_webinar_id[$lms_session->webinar_id] ?? null;
              $new_lms_session->chapter_id = $final_lms_webinar_chapter_id[$lms_session->chapter_id] ?? null;
              $new_lms_session->created_at = $lms_session->created_at;
              $new_lms_session->updated_at = $lms_session->updated_at;
              $new_lms_session->save();
              array_push($new_lms_session_id,$new_lms_session->id);
            }
            $final_lms_session_id = array_combine($old_lms_session_id,$new_lms_session_id);
          }

          //lms_session_translations
          $lms_session_translations = LMSSessionTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_session_translations){
            foreach($lms_session_translations as $lms_session_translation){
              $new_lms_session_translation = $lms_session_translation->replicate();
              $new_lms_session_translation->session_id = $final_lms_session_id[$lms_session_translation->session_id] ?? null;
              $new_lms_session_translation->save();
            }
          }

          //lms_session_reminds
          $lms_session_reminds = LMSSessionRemind::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_session_reminds){
            foreach($lms_session_reminds as $lms_session_remind){
              $new_lms_session_remind = $lms_session_remind->replicate();
              $new_lms_session_remind->session_id = $final_lms_session_id[$lms_session_remind->session_id] ?? null;
              $new_lms_session_remind->user_id = $final_lms_user_id[$lms_session_remind->user_id] ?? null;
              $new_lms_session_remind->created_at = $lms_session_remind->created_at;
              $new_lms_session_remind->save();
            }
          }

          //lms_text_lessons
          $lms_text_lessons = LMSTextLesson::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_text_lesson_id = $lms_text_lessons->pluck('id')->toArray();
          $new_lms_text_lesson_id = [];
          if($lms_text_lessons){
            foreach($lms_text_lessons as $lms_text_lesson){
              $new_lms_text_lesson = $lms_text_lesson->replicate();
              $new_lms_text_lesson->creator_id = $final_lms_user_id[$lms_text_lesson->creator_id] ?? null;
              $new_lms_text_lesson->webinar_id = $final_lms_webinar_id[$lms_text_lesson->webinar_id] ?? null;
              $new_lms_text_lesson->chapter_id = $final_lms_webinar_chapter_id[$lms_text_lesson->chapter_id] ?? null;
              $new_lms_text_lesson->created_at = $lms_text_lesson->created_at;
              $new_lms_text_lesson->updated_at = $lms_text_lesson->updated_at;
              $new_lms_text_lesson->save();
              array_push($new_lms_text_lesson_id,$new_lms_text_lesson->id);
            }
            $final_lms_text_lesson_id = array_combine($old_lms_text_lesson_id,$new_lms_text_lesson_id);
          }

          //lms_text_lesson_translations
          $lms_text_lesson_translations = LMSTextLessonTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_text_lesson_translations){
            foreach($lms_text_lesson_translations as $lms_text_lesson_translation){
              $new_lms_text_lesson_translation = $lms_text_lesson_translation->replicate();
              $new_lms_text_lesson_translation->text_lesson_id = $final_lms_text_lesson_id[$lms_text_lesson_translation->text_lesson_id] ?? null;
              $new_lms_text_lesson_translation->save();
            }
          }

          //lms_text_lessons_attachments
          $lms_text_lessons_attachments = LMSTextLessonAttachment::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_text_lessons_attachments){
            foreach($lms_text_lessons_attachments as $lms_text_lessons_attachment){
              $new_lms_text_lessons_attachment = $lms_text_lessons_attachment->replicate();
              $new_lms_text_lessons_attachment->text_lesson_id = $final_lms_text_lesson_id[$lms_text_lessons_attachment->text_lesson_id] ?? null;
              $new_lms_text_lessons_attachment->file_id = $final_lms_file_id[$lms_text_lessons_attachment->file_id] ?? null;
              $new_lms_text_lessons_attachment->created_at = $lms_text_lessons_attachment->created_at;
              $new_lms_text_lessons_attachment->save();
            }
          }

          //lms_quizzes
          $lms_quizzes = LMSQuiz::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_quiz_id = $lms_quizzes->pluck('id')->toArray();
          $new_lms_quiz_id = [];
          if($lms_quizzes){
            foreach($lms_quizzes as $lms_quiz){
              $new_lms_quiz = $lms_quiz->replicate();
              $new_lms_quiz->creator_id = $final_lms_user_id[$lms_quiz->creator_id] ?? null;
              $new_lms_quiz->webinar_id = $final_lms_webinar_id[$lms_quiz->webinar_id] ?? null;
              $new_lms_quiz->chapter_id = $final_lms_webinar_chapter_id[$lms_quiz->chapter_id] ?? null;
              $new_lms_quiz->created_at = $lms_quiz->created_at;
              $new_lms_quiz->updated_at = $lms_quiz->updated_at;
              $new_lms_quiz->save();
              array_push($new_lms_quiz_id,$new_lms_quiz->id);
            }
            $final_lms_quiz_id = array_combine($old_lms_quiz_id,$new_lms_quiz_id);
          }

          //lms_quiz_translations
          $lms_quiz_translations = LMSQuizTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_quiz_translations){
            foreach($lms_quiz_translations as $lms_quiz_translation){
              $new_lms_quiz_translation = $lms_quiz_translation->replicate();
              $new_lms_quiz_translation->quiz_id = $final_lms_quiz_id[$lms_quiz_translation->quiz_id] ?? null;
              $new_lms_quiz_translation->save();
            }
          }

          //lms_quizzes_questions
          $lms_quizzes_questions = LMSQuizzesQuestion::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_quizzes_question_id = $lms_quizzes_questions->pluck('id')->toArray();
          $new_lms_quizzes_question_id = [];
          if($lms_quizzes_questions){
            foreach($lms_quizzes_questions as $lms_quizzes_question){
              $new_lms_quizzes_question = $lms_quizzes_question->replicate();
              $new_lms_quizzes_question->quiz_id = $final_lms_quiz_id[$lms_quizzes_question->quiz_id] ?? null;
              $new_lms_quizzes_question->creator_id = $final_lms_user_id[$lms_quizzes_question->creator_id] ?? null;
              $new_lms_quizzes_question->created_at = $lms_quizzes_question->created_at;
              $new_lms_quizzes_question->updated_at = $lms_quizzes_question->updated_at;
              $new_lms_quizzes_question->save();
              array_push($new_lms_quizzes_question_id,$new_lms_quizzes_question->id);
            }
            $final_lms_quizzes_question_id = array_combine($old_lms_quizzes_question_id,$new_lms_quizzes_question_id);
          }

          //lms_quizzes_question_translations
          $lms_quizzes_question_translations = LMSQuizzesQuestionTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_quizzes_question_translations){
            foreach($lms_quizzes_question_translations as $lms_quizzes_question_translation){
              $new_lms_quizzes_question_translation = $lms_quizzes_question_translation->replicate();
              $new_lms_quizzes_question_translation->quizzes_question_id = $final_lms_quizzes_question_id[$lms_quizzes_question_translation->quizzes_question_id] ?? null;
              $new_lms_quizzes_question_translation->save();
            }
          }

          //lms_quizzes_questions_answers
          $lms_quizzes_questions_answers = LMSQuizzesQuestionsAnswer::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_quizzes_questions_answer_id = $lms_quizzes_questions_answers->pluck('id')->toArray();
          $new_lms_quizzes_questions_answer_id = [];
          if($lms_quizzes_questions_answers){
            foreach($lms_quizzes_questions_answers as $lms_quizzes_questions_answer){
              $new_lms_quizzes_questions_answer = $lms_quizzes_questions_answer->replicate();
              $new_lms_quizzes_questions_answer->question_id = $final_lms_quizzes_question_id[$lms_quizzes_questions_answer->question_id] ?? null;
              $new_lms_quizzes_questions_answer->creator_id = $final_lms_user_id[$lms_quizzes_questions_answer->creator_id] ?? null;
              $new_lms_quizzes_questions_answer->created_at = $lms_quizzes_questions_answer->created_at;
              $new_lms_quizzes_questions_answer->updated_at = $lms_quizzes_questions_answer->updated_at;
              $new_lms_quizzes_questions_answer->save();
              array_push($new_lms_quizzes_questions_answer_id,$new_lms_quizzes_questions_answer->id);
            }
            $final_lms_quizzes_questions_answer_id = array_combine($old_lms_quizzes_questions_answer_id,$new_lms_quizzes_questions_answer_id);
          }

          //lms_quizzes_questions_answer_translations
          $lms_quizzes_questions_answer_translations = LMSQuizzesQuestionsAnswerTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_quizzes_questions_answer_translations){
            foreach($lms_quizzes_questions_answer_translations as $lms_quizzes_questions_answer_translation){
              $new_lms_quizzes_questions_answer_translation = $lms_quizzes_questions_answer_translation->replicate();
              $new_lms_quizzes_questions_answer_translation->quizzes_questions_answer_id = $final_lms_quizzes_questions_answer_id[$lms_quizzes_questions_answer_translation->quizzes_questions_answer_id] ?? null;
              $new_lms_quizzes_questions_answer_translation->save();
            }
          }

          //lms_quizzes_results
          $lms_quizzes_results = LMSQuizzesResult::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_quizzes_result_id = $lms_quizzes_results->pluck('id')->toArray();
          $new_lms_quizzes_result_id = [];
          if($lms_quizzes_results){
            foreach($lms_quizzes_results as $lms_quizzes_result){
              $new_lms_quizzes_result = $lms_quizzes_result->replicate();
              $new_lms_quizzes_result->quiz_id = $final_lms_quiz_id[$lms_quizzes_result->quiz_id] ?? null;
              $new_lms_quizzes_result->user_id = $final_lms_user_id[$lms_quizzes_result->user_id] ?? null;
              $new_lms_quizzes_result->created_at = $lms_quizzes_result->created_at;
              $new_lms_quizzes_result->save();
              array_push($new_lms_quizzes_result_id,$new_lms_quizzes_result->id);
            }
            $final_lms_quizzes_result_id = array_combine($old_lms_quizzes_result_id,$new_lms_quizzes_result_id);
          }

          //lms_webinar_chapter_items
          $lms_webinar_chapter_items = LMSWebinarChapterItem::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_webinar_chapter_items){
            foreach($lms_webinar_chapter_items as $lms_webinar_chapter_item){
              $new_lms_webinar_chapter_item = $lms_webinar_chapter_item->replicate();
              $new_lms_webinar_chapter_item->chapter_id = $final_lms_webinar_chapter_id[$lms_webinar_chapter_item->chapter_id] ?? null;
              $new_lms_webinar_chapter_item->user_id = $final_lms_user_id[$lms_webinar_chapter_item->user_id] ?? null;
              if($lms_webinar_chapter_item->type=='session'){
                $new_lms_webinar_chapter_item->item_id = $final_lms_session_id[$lms_webinar_chapter_item->item_id] ?? null;
              }
              elseif($lms_webinar_chapter_item->type=='text_lesson'){
                $new_lms_webinar_chapter_item->item_id = $final_lms_text_lesson_id[$lms_webinar_chapter_item->item_id] ?? null;
              }
              elseif($lms_webinar_chapter_item->type=='quiz'){
                $new_lms_webinar_chapter_item->item_id = $final_lms_quiz_id[$lms_webinar_chapter_item->item_id] ?? null;
              }
              elseif($lms_webinar_chapter_item->type=='file'){
                $new_lms_webinar_chapter_item->item_id = $final_lms_file_id[$lms_webinar_chapter_item->item_id] ?? null;
              }
              elseif($lms_webinar_chapter_item->type=='assignment'){
                $new_lms_webinar_chapter_item->item_id = $final_lms_webinar_assignment_id[$lms_webinar_chapter_item->item_id] ?? null;
              }
              $new_lms_webinar_chapter_item->created_at = $lms_webinar_chapter_item->created_at;
              $new_lms_webinar_chapter_item->save();
            }
          }

          //lms_certificates
          $lms_certificates = LMSCertificate::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_certificates){
            foreach($lms_certificates as $lms_certificate){
              $new_lms_certificate = $lms_certificate->replicate();
              $new_lms_certificate->quiz_id = $final_lms_quiz_id[$lms_certificate->quiz_id] ?? null;
              $new_lms_certificate->quiz_result_id = $final_lms_quizzes_result_id[$lms_certificate->quiz_result_id] ?? null;
              $new_lms_certificate->student_id = $final_lms_user_id[$lms_certificate->student_id] ?? null;
              $new_lms_certificate->webinar_id = $final_lms_webinar_id[$lms_certificate->webinar_id] ?? null;
              $new_lms_certificate->created_at = $lms_certificate->created_at;
              $new_lms_certificate->save();
            }
          }

          //lms_certificate_templates
          $lms_certificate_templates = LMSCertificateTemplate::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_certificate_template_id = $lms_certificate_templates->pluck('id')->toArray();
          $new_lms_certificate_template_id = [];
          if($lms_certificate_templates){
            foreach($lms_certificate_templates as $lms_certificate_template){
              $new_lms_certificate_template = $lms_certificate_template->replicate();
              $new_lms_certificate_template->created_at = $lms_certificate_template->created_at;
              $new_lms_certificate_template->updated_at = $lms_certificate_template->updated_at;
              $new_lms_certificate_template->save();
              array_push($new_lms_certificate_template_id,$new_lms_certificate_template->id);
            }
            $final_lms_certificate_template_id = array_combine($old_lms_certificate_template_id,$new_lms_certificate_template_id);
          }

          //lms_certificate_template_translations
          $lms_certificate_template_translations = LMSCertificateTemplateTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_certificate_template_translations){
            foreach($lms_certificate_template_translations as $lms_certificate_template_translation){
              $new_lms_certificate_template_translation = $lms_certificate_template_translation->replicate();
              $new_lms_certificate_template_translation->certificate_template_id = $final_lms_certificate_template_id[$lms_certificate_template_translation->certificate_template_id] ?? null;
              $new_lms_certificate_template_translation->save();
            }
          }

          //lms_comments
          $lms_comments = LMSComment::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_comment_id = $lms_comments->pluck('id')->toArray();
          $new_lms_comment_id = [];
          if($lms_comments){
            foreach($lms_comments as $lms_comment){
              $new_lms_comment = $lms_comment->replicate();
              $new_lms_comment->user_id = $final_lms_user_id[$lms_comment->user_id] ?? null;
              $new_lms_comment->review_id = $final_lms_webinar_review_id[$lms_comment->review_id] ?? null;
              $new_lms_comment->webinar_id = $final_lms_webinar_id[$lms_comment->webinar_id] ?? null;
              $new_lms_comment->bundle_id = $final_lms_bundle_id[$lms_comment->bundle_id] ?? null;
              $new_lms_comment->blog_id = $final_lms_blog_id[$lms_comment->blog_id] ?? null;
              $new_lms_comment->upcoming_course_id = $final_lms_upcoming_course_id[$lms_comment->upcoming_course_id] ?? null;
              $new_lms_comment->created_at = $lms_comment->created_at;
              $new_lms_comment->save();
              array_push($new_lms_comment_id,$new_lms_comment->id);
            }
            $final_lms_comment_id = array_combine($old_lms_comment_id,$new_lms_comment_id);
            // update new reply_id
            $new_lms_comments = LMSComment::withoutGlobalScopes()->where('domain_id',$user->domain_id)->whereNotNull('reply_id')->get();
            if($new_lms_comments){
              foreach($new_lms_comments as $new_lms_comment){
                $new_lms_comment->reply_id = $final_lms_comment_id[$new_lms_comment->reply_id] ?? null;
                $new_lms_comment->save();
              }
            }
          }

          //lms_comments_reports
          $lms_comments_reports = LMSCommentReport::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_comments_reports){
            foreach($lms_comments_reports as $lms_comments_report){
              $new_lms_comments_report = $lms_comments_report->replicate();
              $new_lms_comments_report->user_id = $final_lms_user_id[$lms_comments_report->user_id] ?? null;
              $new_lms_comments_report->blog_id = $final_lms_blog_id[$lms_comments_report->blog_id] ?? null;
              $new_lms_comments_report->webinar_id = $final_lms_webinar_id[$lms_comments_report->webinar_id] ?? null;
              $new_lms_comments_report->bundle_id = $final_lms_bundle_id[$lms_comments_report->bundle_id] ?? null;
              $new_lms_comments_report->comment_id = $final_lms_comment_id[$lms_comments_report->comment_id] ?? null;
              $new_lms_comments_report->created_at = $lms_comments_report->created_at;
              $new_lms_comments_report->save();
            }
          }

          //lms_course_forums
          $lms_course_forums = LMSCourseForum::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_course_forum_id = $lms_course_forums->pluck('id')->toArray();
          $new_lms_course_forum_id = [];
          if($lms_course_forums){
            foreach($lms_course_forums as $lms_course_forum){
              $new_lms_course_forum = $lms_course_forum->replicate();
              $new_lms_course_forum->user_id = $final_lms_user_id[$lms_course_forum->user_id] ?? null;
              $new_lms_course_forum->webinar_id = $final_lms_webinar_id[$lms_course_forum->webinar_id] ?? null;
              $new_lms_course_forum->created_at = $lms_course_forum->created_at;
              $new_lms_course_forum->save();
              array_push($new_lms_course_forum_id,$new_lms_course_forum->id);
            }
            $final_lms_course_forum_id = array_combine($old_lms_course_forum_id,$new_lms_course_forum_id);
          }

          //lms_course_forum_answers
          $lms_course_forum_answers = LMSCourseForumAnswer::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_course_forum_answers){
            foreach($lms_course_forum_answers as $lms_course_forum_answer){
              $new_lms_course_forum_answer = $lms_course_forum_answer->replicate();
              $new_lms_course_forum_answer->forum_id = $final_lms_course_forum_id[$lms_course_forum_answer->forum_id] ?? null;
              $new_lms_course_forum_answer->user_id = $final_lms_user_id[$lms_course_forum_answer->user_id] ?? null;
              $new_lms_course_forum_answer->created_at = $lms_course_forum_answer->created_at;
              $new_lms_course_forum_answer->save();
            }
          }
          
          //lms_course_learnings
          $lms_course_learnings = LMSCourseLearning::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_course_learnings){
            foreach($lms_course_learnings as $lms_course_learning){
              $new_lms_course_learning = $lms_course_learning->replicate();
              $new_lms_course_learning->user_id = $final_lms_user_id[$lms_course_learning->user_id] ?? null;
              $new_lms_course_learning->text_lesson_id = $final_lms_text_lesson_id[$lms_course_learning->text_lesson_id] ?? null;
              $new_lms_course_learning->file_id = $final_lms_file_id[$lms_course_learning->file_id] ?? null;
              $new_lms_course_learning->session_id = $final_lms_session_id[$lms_course_learning->session_id] ?? null;
              $new_lms_course_learning->created_at = $lms_course_learning->created_at;
              $new_lms_course_learning->save();
            }
          }

          //lms_course_noticeboards
          $lms_course_noticeboards = LMSCourseNoticeboard::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_course_noticeboard_id = $lms_course_noticeboards->pluck('id')->toArray();
          $new_lms_course_noticeboard_id = [];
          if($lms_course_noticeboards){
            foreach($lms_course_noticeboards as $lms_course_noticeboard){
              $new_lms_course_noticeboard = $lms_course_noticeboard->replicate();
              $new_lms_course_noticeboard->creator_id = $final_lms_user_id[$lms_course_noticeboard->creator_id] ?? null;
              $new_lms_course_noticeboard->webinar_id = $final_lms_webinar_id[$lms_course_noticeboard->webinar_id] ?? null;
              $new_lms_course_noticeboard->created_at = $lms_course_noticeboard->created_at;
              $new_lms_course_noticeboard->save();
              array_push($new_lms_course_noticeboard_id,$new_lms_course_noticeboard->id);
            }
            $final_lms_course_noticeboard_id = array_combine($old_lms_course_noticeboard_id,$new_lms_course_noticeboard_id);
          }

          //lms_course_noticeboard_statuss
          $lms_course_noticeboard_statuss = LMSCourseNoticeboardStatus::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_course_noticeboard_statuss){
            foreach($lms_course_noticeboard_statuss as $lms_course_noticeboard_status){
              $new_lms_course_noticeboard_status = $lms_course_noticeboard_status->replicate();
              $new_lms_course_noticeboard_status->noticeboard_id = $final_lms_course_noticeboard_id[$lms_course_noticeboard_status->noticeboard_id] ?? null;
              $new_lms_course_noticeboard_status->user_id = $final_lms_user_id[$lms_course_noticeboard_status->user_id] ?? null;
              $new_lms_course_noticeboard_status->save();
            }
          }

          //lms_delete_account_requests
          $lms_delete_account_requests = LMSDeleteAccountRequest::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_delete_account_requests){
            foreach($lms_delete_account_requests as $lms_delete_account_request){
              $new_lms_delete_account_request = $lms_delete_account_request->replicate();
              $new_lms_delete_account_request->user_id = $final_lms_user_id[$lms_delete_account_request->user_id] ?? null;
              $new_lms_delete_account_request->created_at = $lms_delete_account_request->created_at;
              $new_lms_delete_account_request->save();
            }
          }

          //lms_discounts
          $lms_discounts = LMSDiscount::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_discount_id = $lms_discounts->pluck('id')->toArray();
          $new_lms_discount_id = [];
          if($lms_discounts){
            foreach($lms_discounts as $lms_discount){
              $new_lms_discount = $lms_discount->replicate();
              $new_lms_discount->creator_id = $final_lms_user_id[$lms_discount->creator_id] ?? null;
              $new_lms_discount->created_at = $lms_discount->created_at;
              $new_lms_discount->save();
              array_push($new_lms_discount_id,$new_lms_discount->id);
            }
            $final_lms_discount_id = array_combine($old_lms_discount_id,$new_lms_discount_id);
          }

          //lms_discount_categories
          $lms_discount_categories = LMSDiscountCategory::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_discount_categories){
            foreach($lms_discount_categories as $lms_discount_category){
              $new_lms_discount_category = $lms_discount_category->replicate();
              $new_lms_discount_category->discount_id = $final_lms_discount_id[$lms_discount_category->discount_id] ?? null;
              $new_lms_discount_category->category_id = $final_lms_category_id[$lms_discount_category->category_id] ?? null;
              $new_lms_discount_category->created_at = $lms_discount_category->created_at;
              $new_lms_discount_category->save();
            }
          }

          //lms_upcoming_courses
          $lms_upcoming_courses = LMSUpcomingCourse::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_upcoming_course_id = $lms_upcoming_courses->pluck('id')->toArray();
          $new_lms_upcoming_course_id = [];
          if($lms_upcoming_courses){
            foreach($lms_upcoming_courses as $lms_upcoming_course){
              $new_lms_upcoming_course = $lms_upcoming_course->replicate();
              $new_lms_upcoming_course->creator_id = $final_lms_user_id[$lms_upcoming_course->creator_id] ?? null;
              $new_lms_upcoming_course->teacher_id = $final_lms_user_id[$lms_upcoming_course->teacher_id] ?? null;
              $new_lms_upcoming_course->category_id = $final_lms_bundle_id[$lms_upcoming_course->bundle_id] ?? null;
              $new_lms_upcoming_course->webinar_id = $final_lms_webinar_id[$lms_upcoming_course->webinar_id] ?? null;
              $new_lms_upcoming_course->created_at = $lms_upcoming_course->created_at;
              $new_lms_upcoming_course->save();
              array_push($new_lms_upcoming_course_id,$new_lms_upcoming_course->id);
            }
            $final_lms_upcoming_course_id = array_combine($old_lms_upcoming_course_id,$new_lms_upcoming_course_id);
          }

          //lms_upcoming_course_translations
          $lms_upcoming_course_translations = LMSUpcomingCourseTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_upcoming_course_translations){
            foreach($lms_upcoming_course_translations as $lms_upcoming_course_translation){
              $new_lms_upcoming_course_translation = $lms_upcoming_course_translation->replicate();
              $new_lms_upcoming_course_translation->upcoming_course_id = $final_lms_upcoming_course_id[$lms_upcoming_course_translation->upcoming_course_id] ?? null;
              $new_lms_upcoming_course_translation->save();
            }
          }

          //lms_upcoming_course_filter_options
          $lms_upcoming_course_filter_options = LMSUpcomingCourseFilterOption::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_upcoming_course_filter_options){
            foreach($lms_upcoming_course_filter_options as $lms_upcoming_course_filter_option){
              $new_lms_upcoming_course_filter_option = $lms_upcoming_course_filter_option->replicate();
              $new_lms_upcoming_course_filter_option->upcoming_course_id = $final_lms_upcoming_course_id[$lms_upcoming_course_filter_option->upcoming_course_id] ?? null;
              $new_lms_upcoming_course_filter_option->filter_option_id = $final_lms_filter_option_id[$lms_upcoming_course_filter_option->filter_option_id] ?? null;
              $new_lms_upcoming_course_filter_option->save();
            }
          }

          //lms_upcoming_course_followers
          $lms_upcoming_course_followers = LMSUpcomingCourseFollower::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_upcoming_course_followers){
            foreach($lms_upcoming_course_followers as $lms_upcoming_course_follower){
              $new_lms_upcoming_course_follower = $lms_upcoming_course_follower->replicate();
              $new_lms_upcoming_course_follower->upcoming_course_id = $final_lms_upcoming_course_id[$lms_upcoming_course_follower->upcoming_course_id] ?? null;
              $new_lms_upcoming_course_follower->user_id = $final_lms_user_id[$lms_upcoming_course_follower->user_id] ?? null;
              $new_lms_upcoming_course_follower->created_at = $lms_upcoming_course_follower->created_at;
              $new_lms_upcoming_course_follower->save();
            }
          }

          //lms_upcoming_course_reports
          $lms_upcoming_course_reports = LMSUpcomingCourseReport::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_upcoming_course_reports){
            foreach($lms_upcoming_course_reports as $lms_upcoming_course_report){
              $new_lms_upcoming_course_report = $lms_upcoming_course_report->replicate();
              $new_lms_upcoming_course_report->upcoming_course_id = $final_lms_upcoming_course_id[$lms_upcoming_course_report->upcoming_course_id] ?? null;
              $new_lms_upcoming_course_report->user_id = $final_lms_user_id[$lms_upcoming_course_report->user_id] ?? null;
              $new_lms_upcoming_course_report->save();
            }
          }

          //lms_faqs
          $lms_faqs = LMSFaq::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_faq_id = $lms_faqs->pluck('id')->toArray();
          $new_lms_faq_id = [];
          if($lms_faqs){
            foreach($lms_faqs as $lms_faq){
              $new_lms_faq = $lms_faq->replicate();
              $new_lms_faq->creator_id = $final_lms_user_id[$lms_faq->creator_id] ?? null;
              $new_lms_faq->webinar_id = $final_lms_webinar_id[$lms_faq->webinar_id] ?? null;
              $new_lms_faq->bundle_id = $final_lms_bundle_id[$lms_faq->bundle_id] ?? null;
              $new_lms_faq->upcoming_course_id = $final_lms_upcoming_course_id[$lms_faq->upcoming_course_id] ?? null;
              $new_lms_faq->created_at = $lms_faq->created_at;
              $new_lms_faq->updated_at = $lms_faq->updated_at;
              $new_lms_faq->save();
              array_push($new_lms_faq_id,$new_lms_faq->id);
            }
            $final_lms_faq_id = array_combine($old_lms_faq_id,$new_lms_faq_id);
          }

          //lms_faq_translations
          $lms_faq_translations = LMSFaqTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_faq_translations){
            foreach($lms_faq_translations as $lms_faq_translation){
              $new_lms_faq_translation = $lms_faq_translation->replicate();
              $new_lms_faq_translation->faq_id = $final_lms_faq_id[$lms_faq_translation->faq_id] ?? null;
              $new_lms_faq_translation->save();
            }
          }
    
          //lms_favorites
          $lms_favorites = LMSFavorite::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_favorites){
            foreach($lms_favorites as $lms_favorite){
              $new_lms_favorite = $lms_favorite->replicate();
              $new_lms_favorite->user_id = $final_lms_user_id[$lms_favorite->user_id] ?? null;
              $new_lms_favorite->webinar_id = $final_lms_webinar_id[$lms_favorite->webinar_id] ?? null;
              $new_lms_favorite->bundle_id = $final_lms_bundle_id[$lms_favorite->bundle_id] ?? null;
              $new_lms_favorite->upcoming_course_id = $final_lms_upcoming_course_id[$lms_favorite->upcoming_course_id] ?? null;
              $new_lms_favorite->created_at = $lms_favorite->created_at;
              $new_lms_favorite->save();
            }
          }

          //lms_feature_webinars
          $lms_feature_webinars = LMSFeatureWebinar::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_feature_webinar_id = $lms_feature_webinars->pluck('id')->toArray();
          $new_lms_feature_webinar_id = [];
          if($lms_feature_webinars){
            foreach($lms_feature_webinars as $lms_feature_webinar){
              $new_lms_feature_webinar = $lms_feature_webinar->replicate();
              $new_lms_feature_webinar->webinar_id = $final_lms_webinar_id[$lms_feature_webinar->webinar_id] ?? null;
              $new_lms_feature_webinar->updated_at = $lms_feature_webinar->updated_at;
              $new_lms_feature_webinar->save();
              array_push($new_lms_feature_webinar_id,$new_lms_feature_webinar->id);
            }
            $final_lms_feature_webinar_id = array_combine($old_lms_feature_webinar_id,$new_lms_feature_webinar_id);
          }

          //lms_feature_webinar_translations
          $lms_feature_webinar_translations = LMSFeatureWebinarTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_feature_webinar_translations){
            foreach($lms_feature_webinar_translations as $lms_feature_webinar_translation){
              $new_lms_feature_webinar_translation = $lms_feature_webinar_translation->replicate();
              $new_lms_feature_webinar_translation->feature_webinar_id = $final_lms_feature_webinar_id[$lms_feature_webinar_translation->feature_webinar_id] ?? null;
              $new_lms_feature_webinar_translation->save();
            }
          }

          //lms_forums
          $lms_forums = LMSForum::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_forum_id = $lms_forums->pluck('id')->toArray();
          $new_lms_forum_id = [];
          if($lms_forums){
            foreach($lms_forums as $lms_forum){
              $new_lms_forum = $lms_forum->replicate()->makeHidden('locale','title','description','translations');
              $new_lms_forum->role_id = $final_lms_role_id[$lms_forum->role_id] ?? null;
              $new_lms_forum->group_id = $final_lms_group_id[$lms_forum->group_id] ?? null;
              $new_lms_forum = LMSForum::create($new_lms_forum->toArray());
              $new_lms_forum->save();
              array_push($new_lms_forum_id,$new_lms_forum->id);
            }
            $final_lms_forum_id = array_combine($old_lms_forum_id,$new_lms_forum_id);
            // update new parent_id
            $new_lms_forums = LMSForum::withoutGlobalScopes()->where('domain_id',$user->domain_id)->whereNotNull('parent_id')->get();
            if($new_lms_forums){
              foreach($new_lms_forums as $new_lms_forum){
                $new_lms_forum->parent_id = $final_lms_forum_id[$new_lms_forum->parent_id] ?? null;
                $new_lms_forum->save();
              }
            }
          }

          //lms_forum_translations
          $lms_forum_translations = LMSForumTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_forum_translations){
            foreach($lms_forum_translations as $lms_forum_translation){
              $new_lms_forum_translation = $lms_forum_translation->replicate();
              $new_lms_forum_translation->forum_id = $final_lms_forum_id[$lms_forum_translation->forum_id] ?? null;
              $new_lms_forum_translation->save();
            }
          }

          //lms_forum_topics
          $lms_forum_topics = LMSForumTopic::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_forum_topic_id = $lms_forum_topics->pluck('id')->toArray();
          $new_lms_forum_topic_id = [];
          if($lms_forum_topics){
            foreach($lms_forum_topics as $lms_forum_topic){
              $new_lms_forum_topic = $lms_forum_topic->replicate();
              $new_lms_forum_topic->creator_id = $final_lms_user_id[$lms_forum_topic->creator_id] ?? null;
              $new_lms_forum_topic->forum_id = $final_lms_forum_id[$lms_forum_topic->forum_id] ?? null;
              $new_lms_forum_topic->created_at = $lms_forum_topic->created_at;
              $new_lms_forum_topic->save();
              array_push($new_lms_forum_topic_id,$new_lms_forum_topic->id);
            }
            $final_lms_forum_topic_id = array_combine($old_lms_forum_topic_id,$new_lms_forum_topic_id);
          }

          //lms_forum_topic_attachments
          $lms_forum_topic_attachments = LMSForumTopicAttachment::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_forum_topic_attachments){
            foreach($lms_forum_topic_attachments as $lms_forum_topic_attachment){
              $new_lms_forum_topic_attachment = $lms_forum_topic_attachment->replicate();
              $new_lms_forum_topic_attachment->creator_id = $final_lms_user_id[$lms_forum_topic_attachment->creator_id] ?? null;
              $new_lms_forum_topic_attachment->topic_id = $final_lms_forum_topic_id[$lms_forum_topic_attachment->topic_id] ?? null;
              $new_lms_forum_topic_attachment->save();
            }
          }

          //lms_forum_topic_posts
          $lms_forum_topic_posts = LMSForumTopicPost::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_forum_topic_post_id = $lms_forum_topic_posts->pluck('id')->toArray();
          $new_lms_forum_topic_post_id = [];
          if($lms_forum_topic_posts){
            foreach($lms_forum_topic_posts as $lms_forum_topic_post){
              $new_lms_forum_topic_post = $lms_forum_topic_post->replicate();
              $new_lms_forum_topic_post->user_id = $final_lms_user_id[$lms_forum_topic_post->user_id] ?? null;
              $new_lms_forum_topic_post->topic_id = $final_lms_forum_topic_id[$lms_forum_topic_post->topic_id] ?? null;
              $new_lms_forum_topic_post->created_at = $lms_forum_topic_post->created_at;
              $new_lms_forum_topic_post->save();
              array_push($new_lms_forum_topic_post_id,$new_lms_forum_topic_post->id);
            }
            $final_lms_forum_topic_post_id = array_combine($old_lms_forum_topic_post_id,$new_lms_forum_topic_post_id);
            // update new parent_id
            $new_lms_forum_topic_posts = LMSForumTopicPost::withoutGlobalScopes()->where('domain_id',$user->domain_id)->whereNotNull('parent_id')->get();
            if($new_lms_forum_topic_posts){
              foreach($new_lms_forum_topic_posts as $new_lms_forum_topic_post){
                $new_lms_forum_topic_post->parent_id = $final_lms_forum_topic_post_id[$new_lms_forum_topic_post->parent_id] ?? null;
                $new_lms_forum_topic_post->save();
              }
            }
          }
          
          //lms_forum_topic_reports
          $lms_forum_topic_reports = LMSForumTopicReport::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_forum_topic_reports){
            foreach($lms_forum_topic_reports as $lms_forum_topic_report){
              $new_lms_forum_topic_report = $lms_forum_topic_report->replicate();
              $new_lms_forum_topic_report->user_id = $final_lms_user_id[$lms_forum_topic_report->user_id] ?? null;
              $new_lms_forum_topic_report->topic_id = $final_lms_forum_topic_id[$lms_forum_topic_report->topic_id] ?? null;
              $new_lms_forum_topic_report->topic_post_id = $final_lms_forum_topic_post_id[$lms_forum_topic_report->topic_post_id] ?? null;
              $new_lms_forum_topic_report->created_at = $lms_forum_topic_report->created_at;
              $new_lms_forum_topic_report->save();
            }
          }

          //lms_forum_recommended_topics
          $lms_forum_recommended_topics = LMSForumRecommendedTopic::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_forum_recommended_topic_id = $lms_forum_recommended_topics->pluck('id')->toArray();
          $new_lms_forum_recommended_topic_id = [];
          if($lms_forum_recommended_topics){
            foreach($lms_forum_recommended_topics as $lms_forum_recommended_topic){
              $new_lms_forum_recommended_topic = $lms_forum_recommended_topic->replicate();
              $new_lms_forum_recommended_topic->created_at = $lms_forum_recommended_topic->created_at;
              $new_lms_forum_recommended_topic->save();
              array_push($new_lms_forum_recommended_topic_id,$new_lms_forum_recommended_topic->id);
            }
            $final_lms_forum_recommended_topic_id = array_combine($old_lms_forum_recommended_topic_id,$new_lms_forum_recommended_topic_id);
          }
          
          //lms_forum_recommended_topic_items
          $lms_forum_recommended_topic_items = LMSForumRecommendedTopicItem::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_forum_recommended_topic_items){
            foreach($lms_forum_recommended_topic_items as $lms_forum_recommended_topic_item){
              $new_lms_forum_recommended_topic_item = $lms_forum_recommended_topic_item->replicate();
              $new_lms_forum_recommended_topic_item->recommended_topic_id = $final_lms_forum_recommended_topic_id[$lms_forum_recommended_topic_item->recommended_topic_id] ?? null;
              $new_lms_forum_recommended_topic_item->topic_id = $final_lms_forum_topic_id[$lms_forum_recommended_topic_item->topic_id] ?? null;
              $new_lms_forum_recommended_topic_item->created_at = $lms_forum_recommended_topic_item->created_at;
              $new_lms_forum_recommended_topic_item->save();
            }
          }

          //lms_notification_templates
          $lms_notification_templates = LMSNotificationTemplate::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_notification_templates){
            foreach($lms_notification_templates as $lms_notification_template){
              $new_lms_notification_template = $lms_notification_template->replicate();
              $new_lms_notification_template->save();
            }
          }

          //lms_pages
          $lms_pages = LMSPage::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_page_id = $lms_pages->pluck('id')->toArray();
          $new_lms_page_id = [];
          if($lms_pages){
            foreach($lms_pages as $lms_page){
              $new_lms_page = $lms_page->replicate();
              $new_lms_page->created_at = $lms_page->created_at;
              $new_lms_page->save();
              array_push($new_lms_page_id,$new_lms_page->id);
            }
            $final_lms_page_id = array_combine($old_lms_page_id,$new_lms_page_id);
          }

          //lms_page_translations
          $lms_page_translations = LMSPageTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_page_translations){
            foreach($lms_page_translations as $lms_page_translation){
              $new_lms_page_translation = $lms_page_translation->replicate();
              $new_lms_page_translation->page_id = $final_lms_page_id[$lms_page_translation->page_id] ?? null;
              $new_lms_page_translation->save();
            }
          }

          //lms_promotions
          $lms_promotions = LMSPromotion::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_promotion_id = $lms_promotions->pluck('id')->toArray();
          $new_lms_promotion_id = [];
          if($lms_promotions){
            foreach($lms_promotions as $lms_promotion){
              $new_lms_promotion = $lms_promotion->replicate();
              $new_lms_promotion->created_at = $lms_promotion->created_at;
              $new_lms_promotion->save();
              array_push($new_lms_promotion_id,$new_lms_promotion->id);
            }
            $final_lms_promotion_id = array_combine($old_lms_promotion_id,$new_lms_promotion_id);
          }

          //lms_promotion_translations
          $lms_promotion_translations = LMSPromotionTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_promotion_translations){
            foreach($lms_promotion_translations as $lms_promotion_translation){
              $new_lms_promotion_translation = $lms_promotion_translation->replicate();
              $new_lms_promotion_translation->promotion_id = $final_lms_promotion_id[$lms_promotion_translation->promotion_id] ?? null;
              $new_lms_promotion_translation->save();
            }
          }

          //lms_regions
          $lms_regions = LMSRegion::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_region_id = $lms_regions->pluck('id')->toArray();
          $new_lms_region_id = [];
          if($lms_regions){
            foreach($lms_regions as $lms_region){
              $new_lms_region = $lms_region->replicate();
              $new_lms_region->created_at = $lms_region->created_at;
              $new_lms_region->save();
              array_push($new_lms_region_id,$new_lms_region->id);
            }
            $final_lms_region_id = array_combine($old_lms_region_id,$new_lms_region_id);
            // update new parent_id
            $new_lms_regions = LMSRegion::withoutGlobalScopes()->where('domain_id',$user->domain_id)->whereNotNull('country_id')->get();
            if($new_lms_regions){
              foreach($new_lms_regions as $new_lms_region){
                $new_lms_region->country_id = $final_lms_region_id[$new_lms_region->country_id] ?? null;
                if($new_lms_region->province_id){
                  $new_lms_region->province_id = $final_lms_region_id[$new_lms_region->province_id] ?? null;
                }
                if($new_lms_region->city_id){
                  $new_lms_region->city_id = $final_lms_region_id[$new_lms_region->city_id] ?? null;
                }
                $new_lms_region->save();
              }
            }
          }

          //lms_subscribes
          $lms_subscribes = LMSSubscribe::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_subscribe_id = $lms_subscribes->pluck('id')->toArray();
          $new_lms_subscribe_id = [];
          if($lms_subscribes){
            foreach($lms_subscribes as $lms_subscribe){
              $new_lms_subscribe = $lms_subscribe->replicate();
              $new_lms_subscribe->created_at = $lms_subscribe->created_at;
              $new_lms_subscribe->save();
              array_push($new_lms_subscribe_id,$new_lms_subscribe->id);
            }
            $final_lms_subscribe_id = array_combine($old_lms_subscribe_id,$new_lms_subscribe_id);
          }

          //lms_subscribe_translations
          $lms_subscribe_translations = LMSSubscribeTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_subscribe_translations){
            foreach($lms_subscribe_translations as $lms_subscribe_translation){
              $new_lms_subscribe_translation = $lms_subscribe_translation->replicate();
              $new_lms_subscribe_translation->subscribe_id = $final_lms_subscribe_id[$lms_subscribe_translation->subscribe_id] ?? null;
              $new_lms_subscribe_translation->save();
            }
          }

          //lms_special_offers
          $lms_special_offers = LMSSpecialOffer::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_special_offers){
            foreach($lms_special_offers as $lms_special_offer){
              $new_lms_special_offer = $lms_special_offer->replicate();
              $new_lms_special_offer->creator_id = $final_lms_user_id[$lms_special_offer->creator_id] ?? null;
              $new_lms_special_offer->webinar_id = $final_lms_webinar_id[$lms_special_offer->webinar_id] ?? null;
              $new_lms_special_offer->bundle_id = $final_lms_bundle_id[$lms_special_offer->bundle_id] ?? null;
              $new_lms_special_offer->subscribe_id = $final_lms_subscribe_id[$lms_special_offer->subscribe_id] ?? null;
              $new_lms_special_offer->registration_package_id = $final_lms_registration_package_id[$lms_special_offer->registration_package_id] ?? null;
              $new_lms_special_offer->created_at = $lms_special_offer->created_at;
              $new_lms_special_offer->save();
            }
          }

          //lms_tags
          $lms_tags = LMSTag::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_tags){
            foreach($lms_tags as $lms_tag){
              $new_lms_tag = $lms_tag->replicate();
              $new_lms_tag->webinar_id = $final_lms_webinar_id[$lms_tag->webinar_id] ?? null;
              $new_lms_tag->bundle_id = $final_lms_bundle_id[$lms_tag->bundle_id] ?? null;
              $new_lms_tag->upcoming_course_id = $final_lms_upcoming_course_id[$lms_tag->upcoming_course_id] ?? null;
              $new_lms_tag->save();
            }
          }

          //lms_testimonials
          $lms_testimonials = LMSTestimonial::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_testimonial_id = $lms_testimonials->pluck('id')->toArray();
          $new_lms_testimonial_id = [];
          if($lms_testimonials){
            foreach($lms_testimonials as $lms_testimonial){
              $new_lms_testimonial = $lms_testimonial->replicate();
              $new_lms_testimonial->created_at = $lms_testimonial->created_at;
              $new_lms_testimonial->save();
              array_push($new_lms_testimonial_id,$new_lms_testimonial->id);
            }
            $final_lms_testimonial_id = array_combine($old_lms_testimonial_id,$new_lms_testimonial_id);
          }

          //lms_testimonial_translations
          $lms_testimonial_translations = LMSTestimonialTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_testimonial_translations){
            foreach($lms_testimonial_translations as $lms_testimonial_translation){
              $new_lms_testimonial_translation = $lms_testimonial_translation->replicate();
              $new_lms_testimonial_translation->testimonial_id = $final_lms_testimonial_id[$lms_testimonial_translation->testimonial_id] ?? null;
              $new_lms_testimonial_translation->save();
            }
          }

          //lms_tickets
          $lms_tickets = LMSTicket::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_ticket_id = $lms_tickets->pluck('id')->toArray();
          $new_lms_ticket_id = [];
          if($lms_tickets){
            foreach($lms_tickets as $lms_ticket){
              $new_lms_ticket = $lms_ticket->replicate();
              $new_lms_ticket->creator_id = $final_lms_user_id[$lms_ticket->creator_id] ?? null;
              $new_lms_ticket->webinar_id = $final_lms_webinar_id[$lms_ticket->webinar_id] ?? null;
              $new_lms_ticket->bundle_id = $final_lms_bundle_id[$lms_ticket->bundle_id] ?? null;
              $new_lms_ticket->created_at = $lms_ticket->created_at;
              $new_lms_ticket->updated_at = $lms_ticket->updated_at;
              $new_lms_ticket->save();
              array_push($new_lms_ticket_id,$new_lms_ticket->id);
            }
            $final_lms_ticket_id = array_combine($old_lms_ticket_id,$new_lms_ticket_id);
          }

          //lms_ticket_translations
          $lms_ticket_translations = LMSTicketTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_ticket_translations){
            foreach($lms_ticket_translations as $lms_ticket_translation){
              $new_lms_ticket_translation = $lms_ticket_translation->replicate();
              $new_lms_ticket_translation->ticket_id = $final_lms_ticket_id[$lms_ticket_translation->ticket_id] ?? null;
              $new_lms_ticket_translation->save();
            }
          }

          //lms_ticket_users
          $lms_ticket_users = LMSTicketUser::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_ticket_users){
            foreach($lms_ticket_users as $lms_ticket_user){
              $new_lms_ticket_user = $lms_ticket_user->replicate();
              $new_lms_ticket_user->ticket_id = $final_lms_ticket_id[$lms_ticket_user->ticket_id] ?? null;
              $new_lms_ticket_user->user_id = $final_lms_user_id[$lms_ticket_user->user_id] ?? null;
              $new_lms_ticket_user->created_at = $lms_ticket_user->created_at;
              $new_lms_ticket_user->save();
            }
          }

          //lms_users_metas
          $lms_users_metas = LMSUserMeta::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_users_metas){
            foreach($lms_users_metas as $lms_users_meta){
              $new_lms_users_meta = $lms_users_meta->replicate();
              $new_lms_users_meta->user_id = $final_lms_user_id[$lms_users_meta->user_id] ?? null;
              $new_lms_users_meta->save();
            }
          }

          //lms_users_occupations
          $lms_users_occupations = LMSUserOccupation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_users_occupations){
            foreach($lms_users_occupations as $lms_users_occupation){
              $new_lms_users_occupation = $lms_users_occupation->replicate();
              $new_lms_users_occupation->user_id = $final_lms_user_id[$lms_users_occupation->user_id] ?? null;
              $new_lms_users_occupation->category_id = $final_lms_category_id[$lms_users_occupation->category_id] ?? null;
              $new_lms_users_occupation->save();
            }
          }
          
          //lms_floating_bars
          $lms_floating_bars = LMSFloatingBar::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_floating_bar_id = $lms_floating_bars->pluck('id')->toArray();
          $new_lms_floating_bar_id = [];
          if($lms_floating_bars){
            foreach($lms_floating_bars as $lms_floating_bar){
              $new_lms_floating_bar = $lms_floating_bar->replicate();
              $new_lms_floating_bar->save();
              array_push($new_lms_floating_bar_id,$new_lms_floating_bar->id);
            }
            $final_lms_floating_bar_id = array_combine($old_lms_floating_bar_id,$new_lms_floating_bar_id);
          }

          //lms_floating_bar_translations
          $lms_floating_bar_translations = LMSFloatingBarTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_floating_bar_translations){
            foreach($lms_floating_bar_translations as $lms_floating_bar_translation){
              $new_lms_floating_bar_translation = $lms_floating_bar_translation->replicate();
              $new_lms_floating_bar_translation->floating_bar_id = $final_lms_floating_bar_id[$lms_floating_bar_translation->floating_bar_id] ?? null;
              $new_lms_floating_bar_translation->save();
            }
          }

          //lms_currencies
          $lms_currencies = LMSCurrency::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_currencies){
            foreach($lms_currencies as $lms_currency){
              $new_lms_currency = $lms_currency->replicate();
              $new_lms_currency->created_at = $lms_currency->created_at;
              $new_lms_currency->save();
            }
          }

          //lms_home_page_statistics
          $lms_home_page_statistics = LMSHomePageStatistic::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_home_page_statistic_id = $lms_home_page_statistics->pluck('id')->toArray();
          $new_lms_home_page_statistic_id = [];
          if($lms_home_page_statistics){
            foreach($lms_home_page_statistics as $lms_home_page_statistic){
              $new_lms_home_page_statistic = $lms_home_page_statistic->replicate();
              $new_lms_home_page_statistic->save();
              array_push($new_lms_home_page_statistic_id,$new_lms_home_page_statistic->id);
            }
            $final_lms_home_page_statistic_id = array_combine($old_lms_home_page_statistic_id,$new_lms_home_page_statistic_id);
          }

          //lms_home_page_statistic_translations
          $lms_home_page_statistic_translations = LMSHomePageStatisticTranslation::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_home_page_statistic_translations){
            foreach($lms_home_page_statistic_translations as $lms_home_page_statistic_translation){
              $new_lms_home_page_statistic_translation = $lms_home_page_statistic_translation->replicate();
              $new_lms_home_page_statistic_translation->home_page_statistic_id = $final_lms_home_page_statistic_id[$lms_home_page_statistic_translation->home_page_statistic_id] ?? null;
              $new_lms_home_page_statistic_translation->save();
            }
          }

          //lms_waitlists
          $lms_waitlists = LMSWaitlist::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_waitlists){
            foreach($lms_waitlists as $lms_waitlist){
              $new_lms_waitlist = $lms_waitlist->replicate();
              $new_lms_waitlist->webinar_id = $final_lms_webinar_id[$lms_waitlist->webinar_id] ?? null;
              $new_lms_waitlist->user_id = $final_lms_user_id[$lms_waitlist->user_id] ?? null;
              $new_lms_waitlist->created_at = $lms_waitlist->created_at;
              $new_lms_waitlist->save();
            }
          }

          //lms_meetings
          $lms_meetings = LMSMeeting::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          $old_lms_meeting_id = $lms_meetings->pluck('id')->toArray();
          $new_lms_meeting_id = [];
          if($lms_meetings){
            foreach($lms_meetings as $lms_meeting){
              $new_lms_meeting = $lms_meeting->replicate();
              $new_lms_meeting->creator_id = $final_lms_user_id[$lms_meeting->creator_id] ?? null;
              $new_lms_meeting->created_at = $lms_meeting->created_at;
              $new_lms_meeting->save();
              array_push($new_lms_meeting_id,$new_lms_meeting->id);
            }
            $final_lms_meeting_id = array_combine($old_lms_meeting_id,$new_lms_meeting_id);
          }

          //lms_meeting_times
          $lms_meeting_times = LMSMeetingTime::withoutGlobalScopes()->where('domain_id',$user_template->domain_id)->get();
          if($lms_meeting_times){
            foreach($lms_meeting_times as $lms_meeting_time){
              $new_lms_meeting_time = $lms_meeting_time->replicate();
              $new_lms_meeting_time->meeting_id = $final_lms_meeting_id[$lms_meeting_time->meeting_id] ?? null;
              $new_lms_meeting_time->created_at = $lms_meeting_time->created_at;
              $new_lms_meeting_time->save();
            }
          }
        }
    }

    public function deleteDataBeforeClone($domain_template)
    {
        $user_id = Auth::user()->id;

        //check shop not lms
        if($domain_template->shop_type!=2)
        {
          //Store or Business
          Menu::where('user_id',$user_id)->delete();

          $category = Category::where('user_id', $user_id)->pluck('id')->toArray();
          Categorymeta::whereIn('category_id', $category)->delete();

          $term = Term::where('user_id',$user_id)->pluck('id')->toArray();
          Meta::whereIn('term_id', $term)->delete();
          Postcategory::whereIn('term_id', $term)->delete();
          Postmedia::whereIn('term_id', $term)->delete();
          Price::whereIn('term_id', $term)->delete();
          Stock::whereIn('term_id', $term)->delete();

          $product_feature = ProductFeature::where('user_id', $user_id)->pluck('id')->toArray();
          $product_feature_detail = ProductFeatureDetail::whereIn('feature_page_id', $product_feature)->pluck('id')->toArray();
          ProductFeatureSectionElement::whereIn('feature_page_detail_id', $product_feature_detail)->delete();
          ProductFeatureDetail::whereIn('feature_page_id', $product_feature)->delete();

          ProductFeature::where('user_id',$user_id)->delete();
          Term::where('user_id',$user_id)->delete();
          Media::where('user_id',$user_id)->delete();
          Portfolio::where('user_id',$user_id)->delete();
          Category::where('user_id', $user_id)->delete();
          Career::where('user_id', $user_id)->delete();
          Faq::where('user_id', $user_id)->delete();
          Partner::where('user_id', $user_id)->delete();
          Package::where('user_id', $user_id)->delete();
          Service::where('user_id', $user_id)->delete();
          Team::where('user_id', $user_id)->delete();
          Testimonial::where('user_id', $user_id)->delete();
          Post::where('user_id', $user_id)->delete();
          ContactLists::where('user_id', $user_id)->delete();
          Location::where('user_id', $user_id)->delete();
        } 
        else {
          //LMS
          LMSSetting::query()->delete();
          LMSSettingTranslation::query()->delete();
          LMSHomeSection::query()->delete();
          LMSAdvertisingBanner::query()->delete();
          LMSAdvertisingBannerTranslation::query()->delete();
          LMSBadge::query()->delete();
          LMSBadgeTranslation::query()->delete();
          LMSRegistrationPackage::query()->delete();
          LMSRegistrationPackageTranslation::query()->delete();
          LMSUser::query()->delete();
          LMSRole::query()->delete();
          LMSPermission::query()->delete();
          LMSGroup::query()->delete();
          LMSGroupUser::query()->delete();
          LMSCategory::query()->delete();
          LMSCategoryTranslation::query()->delete();
          LMSTrendCategory::query()->delete();
          LMSBecomeInstructor::query()->delete();
          LMSBlogCategory::query()->delete();
          LMSBlog::query()->delete();
          LMSBlogTranslation::query()->delete();
          LMSFilter::query()->delete();
          LMSFilterTranslation::query()->delete();
          LMSFilterOption::query()->delete();
          LMSFilterOptionTranslation::query()->delete();
          LMSWebinar::query()->delete();
          LMSWebinarTranslation::query()->delete();
          LMSBundle::query()->delete();
          LMSBundleTranslation::query()->delete();
          LMSBundleFilterOption::query()->delete();
          LMSBundleWebinar::query()->delete();
          LMSWebinarExtraDescription::query()->delete();
          LMSWebinarExtraDescriptionTranslation::query()->delete();
          LMSWebinarFilterOption::query()->delete();
          LMSWebinarPartnerTeacher::query()->delete();
          LMSWebinarReport::query()->delete();
          LMSWebinarReview::query()->delete();
          LMSWebinarChapter::query()->delete();
          LMSWebinarChapterTranslation::query()->delete();
          LMSWebinarAssignment::query()->delete();
          LMSWebinarAssignmentTranslation::query()->delete();
          LMSWebinarAssignmentAttachment::query()->delete();
          LMSWebinarAssignmentHistory::query()->delete();
          LMSWebinarAssignmentHistoryMessage::query()->delete();
          LMSFile::query()->delete();
          LMSFileTranslation::query()->delete();
          LMSSession::query()->delete();
          LMSSessionTranslation::query()->delete();
          LMSSessionRemind::query()->delete();
          LMSTextLesson::query()->delete();
          LMSTextLessonTranslation::query()->delete();
          LMSTextLessonAttachment::query()->delete();
          LMSQuiz::query()->delete();
          LMSQuizTranslation::query()->delete();
          LMSQuizzesQuestion::query()->delete();
          LMSQuizzesQuestionTranslation::query()->delete();
          LMSQuizzesQuestionsAnswer::query()->delete();
          LMSQuizzesQuestionsAnswerTranslation::query()->delete();
          LMSQuizzesResult::query()->delete();
          LMSWebinarChapterItem::query()->delete();
          LMSCertificate::query()->delete();
          LMSCertificateTemplate::query()->delete();
          LMSCertificateTemplateTranslation::query()->delete();
          LMSComment::query()->delete();
          LMSCommentReport::query()->delete();
          LMSCourseForum::query()->delete();
          LMSCourseForumAnswer::query()->delete();
          LMSCourseLearning::query()->delete();
          LMSCourseNoticeboard::query()->delete();
          LMSCourseNoticeboardStatus::query()->delete();
          LMSDeleteAccountRequest::query()->delete();
          LMSDiscount::query()->delete();
          LMSDiscountCategory::query()->delete();
          LMSFaq::query()->delete();
          LMSFaqTranslation::query()->delete();
          LMSFavorite::query()->delete();
          LMSFeatureWebinar::query()->delete();
          LMSFeatureWebinarTranslation::query()->delete();
          LMSForum::query()->delete();
          LMSForumTranslation::query()->delete();
          LMSForumTopic::query()->delete();
          LMSForumTopicAttachment::query()->delete();
          LMSForumTopicPost::query()->delete();
          LMSForumTopicReport::query()->delete();
          LMSForumRecommendedTopic::query()->delete();
          LMSForumRecommendedTopicItem::query()->delete();
          LMSNotificationTemplate::query()->delete();
          LMSPage::query()->delete();
          LMSPageTranslation::query()->delete();
          LMSPromotion::query()->delete();
          LMSPromotionTranslation::query()->delete();
          LMSRegion::query()->delete();
          LMSSpecialOffer::query()->delete();
          LMSSubscribe::query()->delete();
          LMSSubscribeTranslation::query()->delete();
          LMSTag::query()->delete();
          LMSTestimonial::query()->delete();
          LMSTestimonialTranslation::query()->delete();
          LMSTicket::query()->delete();
          LMSTicketTranslation::query()->delete();
          LMSTicketUser::query()->delete();
          LMSUserMeta::query()->delete();
          LMSUserOccupation::query()->delete();
          LMSUpcomingCourse::query()->delete();
          LMSUpcomingCourseTranslation::query()->delete();
          LMSUpcomingCourseFilterOption::query()->delete();
          LMSUpcomingCourseFollower::query()->delete();
          LMSUpcomingCourseReport::query()->delete();
          LMSFloatingBar::query()->delete();
          LMSFloatingBarTranslation::query()->delete();
          LMSCurrency::query()->delete();
          LMSHomePageStatistic::query()->delete();
          LMSHomePageStatisticTranslation::query()->delete();
          LMSWaitlist::query()->delete();

        }
    }
}
