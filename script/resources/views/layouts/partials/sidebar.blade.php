  <div class="main-sidebar">
      <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
              <a href="#">{{ \App\Useroption::where('user_id', Auth::user()->id)->where('key','shop_name')->first()->value ?? env('APP_NAME')}}</a>

          </div>
          <div class="sidebar-brand sidebar-brand-sm">
              <a href="#">{{ Str::limit(env('APP_NAME'), $limit = 1) }}</a>
          </div>
          <div class="p-3 hide-sidebar-mini">
              <a href="{{ url('/') }}" class="btn btn-primary btn-lg btn-block btn-icon-split">
                  <i class="fas fa-external-link-alt"></i>{{ __('Website Homepage') }}
              </a>
          </div>
          @if(Auth::user()->role_id==3)
          <div class="pt-0 p-3 hide-sidebar-mini">
              <a target="_blank" href="{{ route('seller.analytics') }}" class="btn btn-info btn-lg btn-block btn-icon-split">
                  <i class="fas fa-external-link-alt"></i>{{ __('Analytics') }}
              </a>
          </div>
          @if(domain_info('shop_type')==2)
            <div class="pt-0 p-3 hide-sidebar-mini">
                <a target="_blank" href="/lms/admin" class="btn btn-info btn-lg btn-block btn-icon-split">
                    <i class="fas fa-external-link-alt"></i>{{ __('LMS Admin') }}
                </a>
            </div>
          @endif
          @endif
          <ul class="sidebar-menu">
              @if(Auth::user()->role_id==1)
              @can('dashboard')
              <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('admin.dashboard') }}">
                      <i class="flaticon-dashboard"></i> <span>{{ __('Dashboard') }}</span>
                  </a>
              </li>
              @endcan

              @can('order.list')
              @if(Route::has('admin.order.index'))
              <li class="{{ Request::is('admin/order*') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('admin.order.index') }}">
                      <i class="flaticon-note"></i> <span>{{ __('Orders') }}</span>
                  </a>
              </li>
              @endif
              @endcan

              @php
              $plan=false;
              @endphp
              @can('plan.create')
              @php
              $plan=true;
              @endphp
              @endcan
              @can('plan.list')
              @php
              $plan=true;
              @endphp
              @endcan
              @if($plan == true)
              @if(Route::has('admin.plan.index'))
              <li class="dropdown {{ Request::is('admin/plan*') ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-pricing"></i> <span>{{ __('Plans') }}</span></a>
                  <ul class="dropdown-menu">
                      @can('plan.create')
                      <li><a class="nav-link {{ Request::is('admin/plan/create') ? 'active' : '' }}" href="{{ route('admin.plan.create') }}">{{ __('Create') }}</a></li>
                      @endcan
                      @can('plan.list')
                      <li><a class="nav-link {{ Request::is('admin/plan') ? 'active' : '' }}" href="{{ route('admin.plan.index') }}">{{ __('All Plans') }}</a></li>
                      @endcan
                  </ul>
              </li>
              @endif
              @endif
              @if(Route::has('admin.plan.index'))
              @can('report.view')
              <li class="{{ Request::is('admin/report*') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('admin.report') }}">
                      <i class="flaticon-dashboard-1"></i> <span>{{ __('Reports') }}</span>
                  </a>
              </li>
              @endcan
              @endif

              @if(Route::has('admin.customer.index'))
              @can('customer.create','customer.list','customer.request','customer.list')
              <li class="dropdown {{ Request::is('admin/customer*') ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-customer"></i> <span>{{ __('Customers') }}</span></a>
                  <ul class="dropdown-menu">
                      @can('customer.create')
                      <li><a class="nav-link" href="{{ route('admin.customer.create') }}">{{ __('Create Customer') }}</a></li>
                      @endcan
                      @can('customer.list')
                      <li><a class="nav-link" href="{{ route('admin.customer.index','type=1') }}">{{ __('All Customers') }}</a></li>
                      @endcan
                      @can('customer.request')
                      <li><a class="nav-link" href="{{ route('admin.customer.index','type=3') }}">{{ __('Customer Request') }}</a></li>
                      @endcan
                      @can('customer.list')
                      <li><a class="nav-link" href="{{ route('admin.customer.index','type=2') }}">{{ __('Suspended Customers') }}</a></li>
                      @endcan
                  </ul>
              </li>
              @endcan
              @endif

              @can('domain.create','domain.list')
              <li class="dropdown {{ Request::is('admin/domain*') ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-www"></i> <span>{{ __('Domains') }}</span></a>
                  <ul class="dropdown-menu">
                      @can('domain.create')
                      <li><a class="nav-link {{ Request::is('admin/domain/create') ? 'active' : '' }}" href="{{ route('admin.domain.create') }}">{{ __('Create Domain') }}</a></li>
                      @endcan
                      @can('domain.list')
                      <li><a class="nav-link {{ Request::is('admin/domain') ? 'active' : '' }}" href="{{ route('admin.domain.index') }}">{{ __('All Domains') }}</a></li>
                      @if(getenv("AUTO_APPROVED_DOMAIN") !== false)

                      <li><a class="nav-link {{ Request::is('admin/domain') ? 'active' : '' }}" href="{{ route('admin.customdomain.index') }}">{{ __('Custom Domains Requests') }}</a></li>
                      @endif
                      @endcan
                  </ul>
              </li>
              @endcan

              @can('cron_job')
              <li class="{{ Request::is('admin/cron') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('admin.cron.index') }}">
                      <i class="flaticon-task"></i> <span>{{ __('Cron Jobs') }}</span>
                  </a>
              </li>
              @endcan
              @if(Route::has('admin.payment-geteway.index'))
              @can('payment_gateway.config')
              <li class="{{ Request::is('admin/payment-geteway*') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('admin.payment-geteway.index') }}">
                      <i class="flaticon-credit-card"></i> <span>{{ __('Payment Gateways') }}</span>
                  </a>
              </li>
              @endcan
              @endif
              @can('template.list')
              <li class="{{ Request::is('admin/template') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('admin.template.index') }}">
                      <i class="flaticon-template"></i> <span>{{ __('Templates') }}</span>
                  </a>
              </li>
              @endcan
              @can('page.create','page.list')
              <li class="dropdown {{ Request::is('admin/page*') ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-document"></i> <span>{{ __('Pages') }}</span></a>
                  <ul class="dropdown-menu">
                      @can('page.create')
                      <li><a class="nav-link" href="{{ route('admin.page.create') }}">{{ __('Create Pages') }}</a></li>
                      @endcan
                      @can('page.list')
                      <li><a class="nav-link" href="{{ route('admin.page.index') }}">{{ __('All Pages') }}</a></li>
                      @endcan
                  </ul>
              </li>
              @endcan
              @can('blog.create','blog.list')
              <li class="dropdown {{ Request::is('admin/blog*') || Request::is('admin/bcategory*') ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-document"></i> <span>{{ __('Blog') }}</span></a>
                  <ul class="dropdown-menu">
                      @can('bcategory.list')
                      <li><a class="nav-link" href="{{ route('admin.bcategory.index') }}">{{ __('Blog Category') }}</a></li>
                      @endcan
                      @can('blog.list')
                      <li><a class="nav-link" href="{{ route('admin.blog.index') }}">{{ __('Blog') }}</a></li>
                      @endcan
                  </ul>
              </li>
              @endcan

              @can('language_edit')
              <li class="dropdown {{ Request::is('admin/language*') ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-translation"></i> <span>{{ __('Language') }}</span></a>
                  <ul class="dropdown-menu">
                      <li><a class="nav-link" href="{{ route('admin.language.create') }}">{{ __('Create language') }}</a></li>
                      <li><a class="nav-link" href="{{ route('admin.language.index') }}">{{ __('Manage language') }}</a></li>
                  </ul>
              </li>
              @endcan
              @can('site.settings')
              <li class="dropdown {{ Request::is('admin/appearance*') ? 'active' : '' }}  {{ Request::is('admin/gallery*') ? 'active' : '' }} {{ Request::is('admin/menu*') ? 'active' : '' }} {{ Request::is('admin/seo*') ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-settings"></i> <span>{{ __('Appearance') }}</span></a>
                  <ul class="dropdown-menu">
                      <li><a class="nav-link" href="{{ route('admin.appearance.show','header') }}">{{ __('Frontend Settings') }}</a></li>
                      <li><a class="nav-link" href="{{ route('admin.gallery.index') }}">{{ __('Gallery') }}</a></li>
                      <li><a class="nav-link" href="{{ route('admin.menu.index') }}">{{ __('Menu') }}</a></li>
                      <li><a class="nav-link" href="{{ route('admin.seo.index') }}">{{ __('SEO') }}</a></li>
                  </ul>
              </li>
              @endcan
              @can('marketing.tools')
              <li class="{{ Request::is('admin/marketing') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('admin.marketing.index') }}">
                      <i class="flaticon-megaphone"></i> <span>{{ __('Marketing Tools') }}</span>
                  </a>
              </li>
              @endcan

              @can('site.settings','environment.settings')
              <li class="dropdown {{ Request::is('admin/site-settings*') ? 'active' : '' }} {{ Request::is('admin/system-environment*') ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-settings"></i> <span>{{ __('Settings') }}</span></a>
                  <ul class="dropdown-menu">
                      @can('site.settings')
                      <li><a class="nav-link" href="{{ route('admin.site.settings') }}">{{ __('Site Settings') }}</a></li>
                      <li><a class="nav-link" href="{{ route('admin.contact.index') }}">{{ __('Contact List') }}</a>
                          @endcan
                          @can('environment.settings')
                      <li><a class="nav-link" href="{{ route('admin.site.environment') }}">{{ __('System Environment') }}</a></li>
                      @endcan
                  </ul>
              </li>
              @endcan


              @can('admin.list','role.list')
              <li class="dropdown {{ Request::is('admin/role*') ? 'active' : '' }} {{ Request::is('admin/users*') ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-member"></i> <span>{{ __('Admins & Roles') }}</span></a>
                  <ul class="dropdown-menu">
                      @can('role.list')
                      <li><a class="nav-link" href="{{ route('admin.role.index') }}">{{ __('Roles') }}</a></li>
                      @endcan
                      @can('admin.list')
                      <li><a class="nav-link" href="{{ route('admin.users.index') }}">{{ __('Admins') }}</a></li>
                      @endcan
                  </ul>
              </li>
              @endcan

              @endif

              @if(Auth::user()->role_id==3)

              @php
              $plan_limit=user_limit();


              @endphp
              <li class="{{ Request::is('seller/dashboard*') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('seller.dashboard') }}">
                      <i class="flaticon-dashboard"></i> <span>{{ __('Dashboard') }}</span>
                  </a>
              </li>

              <li class="dropdown {{ Request::is('seller/order*') ? 'active' : '' }} || {{ Request::is('seller/product*') ? 'active' : '' }} {{ Request::is('seller/inventory*') ? 'active' : '' }} {{ Request::is('seller/category*') ? 'active' : '' }} {{ Request::is('seller/attribute*') ? 'active' : '' }} {{ Request::is('seller/brand*') ? 'active' : '' }} {{ Request::is('seller/coupon*') ? 'active' : '' }} {{ Request::is('seller/transection*') ? 'active' : '' }} {{ Request::is('seller/report*') ? 'active' : '' }} {{ Request::is('seller/review*') ? 'active' : '' }} {{ Request::is('seller/location*') ? 'active' : '' }} {{ Request::is('seller/shipping*') ? 'active' : '' }} || {{ Request::is('seller/notifications*') ? 'active' : '' }} || {{ Request::is('seller/discount*') ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-note"></i> <span>{{ __('Ecommerce') }}</span></a>
                  <ul class="dropdown-menu">

                      <li class="dropdown {{ Request::is('seller/product*') ? 'active' : '' }} {{ Request::is('seller/inventory*') ? 'active' : '' }} {{ Request::is('seller/category*') ? 'active' : '' }} {{ Request::is('seller/attribute*') ? 'active' : '' }} {{ Request::is('seller/brand*') ? 'active' : '' }} {{ Request::is('seller/coupon*') ? 'active' : '' }}">
                          <a href="#" class="nav-link has-dropdown pd-40" data-toggle="dropdown"><i class="flaticon-box"></i> <span>{{ __('Products') }}</span></a>
                          <ul class="dropdown-menu">
                              <li><a class="nav-link" href="{{ route('seller.product.index') }}">{{ __('All Products') }}</a></li>
                              <li><a class="nav-link" @if(filter_var($plan_limit['inventory'])==true) href="{{ route('seller.inventory.index') }}" @endif>{{ __('Inventory') }} @if(filter_var($plan_limit['inventory']) != true) <i class="fa fa-lock text-danger"></i> @endif</a></li>
                              <li><a class="nav-link" href="{{ route('seller.category.index') }}">{{ __('Categories') }}</a></li>
                              <li><a class="nav-link" href="{{ route('seller.attribute.index') }}">{{ __('Attributes') }}</a></li>
                              <li><a class="nav-link" href="{{ route('seller.brand.index') }}">{{ __('Brands') }}</a></li>
                              <li><a class="nav-link" href="{{ route('seller.coupon.index') }}">{{ __('Coupons') }}</a></li>
                          </ul>
                      </li>

                      <li class="dropdown {{ Request::is('seller/order*') ? 'active' : '' }}">
                          <a href="#" class="nav-link has-dropdown pd-40" data-toggle="dropdown"><i class="flaticon-note"></i> <span>{{ __('Orders') }}</span></a>
                          <ul class="dropdown-menu">
                              <li><a class="nav-link" href="{{ url('/seller/orders/all') }}">{{ __('All Orders') }}</a></li>
                              <li><a class="nav-link" href="{{ url('/seller/orders/canceled') }}">{{ __('Canceled') }}</a></li>

                          </ul>
                      </li>

                      <li class="dropdown {{ Request::is('seller/location*') ? 'active' : '' }} {{ Request::is('seller/shipping*') ? 'active' : '' }}">
                          <a href="#" class="nav-link has-dropdown pd-40" data-toggle="dropdown"><i class="flaticon-delivery"></i> <span>{{ __('Shipping') }}</span></a>
                          <ul class="dropdown-menu">
                              <li><a class="nav-link" href="{{ route('seller.location.index') }}">{{ __('Locations') }}</a></li>
                              <li><a class="nav-link" href="{{ route('seller.shipping.index') }}">{{ __('Shipping Price') }}</a></li>
                          </ul>
                      </li>
                      <li><a class="nav-link pd-50" href="{{ route('seller.discount.index') }}">{{ __('Discount') }}</a></li>
                      <li><a class="nav-link pd-50" href="{{ route('seller.transection.index') }}">{{ __('Transactions') }}</a></li>
                      <li><a class="nav-link pd-50" href="{{ route('seller.report.index') }}">{{ __('Reports') }}</a></li>
                      <li><a class="nav-link pd-50" href="{{ route('seller.review.index') }}">{{ __('Review & Ratings') }}</a></li>

                  </ul>
              </li>

              <li class="dropdown {{ Request::is('seller/service*') || Request::is('seller/course*') || Request::is('seller/course_category*')||Request::is('seller/review_course') ||Request::is('seller/partner*') || Request::is('seller/team*') ||
            Request::is('seller/faq*') || Request::is('seller/testimonial*') || Request::is('seller/blog*') || Request::is('seller/bcategory*') || Request::is('seller/instructor*') ||
            Request::is('seller/feature-page*') || Request::is('seller/portfolio*') || Request::is('seller/portfolio_category*') || Request::is('seller/career*') ||
            Request::is('seller/career_category*') || Request::is('seller/setting/menu*') || Request::is('seller/setting/page*') || Request::is('seller/setting/seo*') ||
            Request::is('seller/package*') || Request::is('seller/package_category*') || Request::is('seller/gallery*') || Request::is('seller/gallery_category*') ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-box"></i> <span>{{ __('Website Content') }}</span></a>
                  <ul class="dropdown-menu">
                      <li class="dropdown {{ Request::is('seller/blog*') || Request::is('seller/bcategory*') ? 'active' : '' }}">
                          <a href="#" class="nav-link has-dropdown pd-40" data-toggle="dropdown"><i class="flaticon-document"></i> <span>{{ __('Blog') }}</span></a>
                          <ul class="dropdown-menu">
                              <li><a class="nav-link" href="{{ route('seller.bcategory.index') }}">{{ __('Blog Category') }}</a></li>
                              <li><a class="nav-link" href="{{ route('seller.blog.index') }}">{{ __('Blog') }}</a></li>
                          </ul>
                      </li>
                      <li class="dropdown {{ Request::is('seller/course*') || Request::is('seller/course_category*') || Request::is('seller/review_course*') ? 'active' : '' }}">
                          <a href="#" class="nav-link has-dropdown pd-40" data-toggle="dropdown"><i class="flaticon-dashboard-1"></i> <span>{{ __('Course') }}</span></a>
                          <ul class="dropdown-menu">
                              <li><a class="nav-link" href="{{ route('seller.course_category.index') }}">{{ __('Course Category') }}</a></li>
                              <li><a class="nav-link" href="{{ route('seller.course.index') }}">{{ __('Courses') }}</a></li>
                              <li><a class="nav-link" href="{{ route('seller.review_course.index') }}">{{ __('Review & Rating Courses') }}</a></li>
                          </ul>
                      </li>

                      <li class="dropdown {{ Request::is('seller/portfolio*') || Request::is('seller/portfolio_category*') ? 'active' : '' }}">
                          <a href="#" class="nav-link has-dropdown pd-40" data-toggle="dropdown"><i class="flaticon-dashboard-1"></i> <span>{{ __('Portfolios') }}</span></a>
                          <ul class="dropdown-menu">
                              <li><a class="nav-link" href="{{ route('seller.portfolio_category.index') }}">{{ __('Portfolio Category') }}</a></li>
                              <li><a class="nav-link" href="{{ route('seller.portfolio.index') }}">{{ __('Portfolios') }}</a></li>
                          </ul>
                      </li>

                      <li class="dropdown {{ Request::is('seller/career*') || Request::is('seller/career_category*') ? 'active' : '' }}">
                          <a href="#" class="nav-link has-dropdown pd-40" data-toggle="dropdown"><i class="flaticon-seller"></i> <span>{{ __('Careers') }}</span></a>
                          <ul class="dropdown-menu">
                              <li><a class="nav-link" href="{{ route('seller.career_category.index') }}">{{ __('Career Category') }}</a></li>
                              <li><a class="nav-link" href="{{ route('seller.career.index') }}">{{ __('Careers') }}</a></li>
                          </ul>
                      </li>

                      <li class="dropdown {{ Request::is('seller/package*') || Request::is('seller/package_category*') ? 'active' : '' }}">
                          <a href="#" class="nav-link has-dropdown pd-40" data-toggle="dropdown"><i class="flaticon-note"></i> <span>{{ __('Packages') }}</span></a>
                          <ul class="dropdown-menu">
                              <li><a class="nav-link" href="{{ route('seller.package_category.index') }}">{{ __('Packages Category') }}</a></li>
                              <li><a class="nav-link" href="{{ route('seller.package.index') }}">{{ __('Packages') }}</a></li>
                          </ul>
                      </li>
                      <li class="dropdown {{ Request::is('seller/gallery*') || Request::is('seller/gallery_category*') ? 'active' : '' }}">
                          <a href="#" class="nav-link has-dropdown pd-40" data-toggle="dropdown"><i class="flaticon-note"></i> <span>{{ __('Gallery') }}</span></a>
                          <ul class="dropdown-menu">
                              <li><a class="nav-link" href="{{ route('seller.gallery_category.index') }}">{{ __('Gallery Category') }}</a></li>
                              <li><a class="nav-link" href="{{ route('seller.gallery.index') }}">{{ __('Gallery') }}</a></li>
                          </ul>
                      </li>
                      <li class="dropdown {{ Request::is('seller/guide*') || Request::is('seller/guide_category*') ? 'active' : '' }}">
                          <a href="#" class="nav-link has-dropdown pd-40" data-toggle="dropdown"><i class="flaticon-dashboard-1"></i> <span>{{ __('Knowledges') }}</span></a>
                          <ul class="dropdown-menu">
                              <li><a class="nav-link" href="{{ route('seller.guide_category.index') }}">{{ __('Knowledge Category') }}</a></li>
                              <li><a class="nav-link" href="{{ route('seller.guide.index') }}">{{ __('Knowledge') }}</a></li>
                          </ul>
                      </li>
                      <li><a class="nav-link pd-50" href="{{ route('seller.feature_page.index') }}">{{ __('Home - Landing Page') }}</a></li>
                      <li><a class="nav-link pd-50" href="{{ route('seller.service.index') }}">{{ __('Services') }}</a></li>
                      <li><a class="nav-link pd-50" href="{{ route('seller.partner.index') }}">{{ __('Partners') }}</a></li>
                      <li><a class="nav-link pd-50" href="{{ route('seller.team.index') }}">{{ __('Teams') }}</a></li>
                      <li><a class="nav-link pd-50" href="{{ route('seller.instructor.index') }}">{{ __('Instructors') }}</a></li>
                      <li><a class="nav-link pd-50" href="{{ route('seller.faq.index') }}">{{ __('Faqs') }}</a></li>
                      <li><a class="nav-link pd-50" href="{{ route('seller.testimonial.index') }}">{{ __('Testimonials') }}</a></li>
                      <li><a class="nav-link pd-50" href="{{ route('seller.page.index') }}">{{ __('Pages') }}</a></li>
                      <li><a class="nav-link pd-50" href="{{ route('seller.menu.index') }}">{{ __('Menus') }}</a></li>
                      <li><a class="nav-link pd-50" href="{{ route('seller.seo.index') }}">{{ __('Seo') }}</a></li>
                  </ul>
              </li>

              @if(env('MULTILEVEL_CUSTOMER_REGISTER') == true)
              <li class="{{ Request::is('seller/customer*') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('seller.customer.index') }}">
                      <i class="flaticon-customer"></i> <span>{{ __('Customers') }}</span>
                  </a>
              </li>
              @endif

              <li class="dropdown {{ Request::is('seller/ads*') || Request::is('seller/setting/slider*') || Request::is('seller/ad/brand*') || Request::is('seller/top-banner*') ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-megaphone"></i> <span>{{ __('Banner & Ads') }}</span></a>
                  <ul class="dropdown-menu">
                      <li><a class="nav-link" href="{{ route('seller.ads.index') }}">{{ __('Service Banner') }}</a></li>
                      <li><a class="nav-link" href="{{ route('seller.ads.show','banner') }}">{{ __('Banner Ads') }}</a></li>
                      <li><a class="nav-link" href="{{ route('seller.ads.brand') }}">{{ __('Brand Banner') }}</a></li>
                      <li><a class="nav-link" href="{{ route('seller.slider.index') }}">{{ __('Sliders') }}</a></li>
                      <li><a class="nav-link" href="{{ route('seller.banner.show') }}">{{ __('Banner Topbar') }}</a></li>
                  </ul>
              </li>
              <li class="dropdown {{ Request::is('seller/bookings*') || Request::is('seller/booking-category*') || Request::is('seller/booking-service*') || Request::is('seller/setting/booking*') ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-note"></i> <span>{{ __('Bookings') }}</span></a>
                  <ul class="dropdown-menu">
                      <li><a class="nav-link" href="{{ route('seller.booking-category.index') }}">{{ __('Bookings Category') }}</a></li>
                      <li><a class="nav-link" href="{{ route('seller.booking-service.index') }}">{{ __('Bookings Service') }}</a></li>
                      <li><a class="nav-link" href="{{ url('/seller/bookings/all') }}">{{ __('All Bookings') }}</a></li>
                  </ul>
              </li>

              <li class="dropdown {{ Request::is('seller/loyalty-rank*') || Request::is('seller/loyalty*') || Request::is('seller/setting/loyalty*') || Request::is('seller/loyalty-promotion*') || Request::is('seller/loyalty-promotion-category*') || Request::is('seller/loyalty-benefit*') ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-note"></i> <span>{{ __('Loyalty') }}</span></a>
                  <ul class="dropdown-menu">
                      <li><a class="nav-link" href="{{ route('seller.loyalty.index') }}">{{ __('Loyalty') }}</a></li>
                      <li><a class="nav-link" href="{{ route('seller.loyalty-benefit.index') }}">{{ __('Loyalty Benefit') }}</a></li>
                      <li><a class="nav-link" href="{{ route('seller.loyalty-rank.index') }}">{{ __('Loyalty Rank') }}</a></li>
                      <li><a class="nav-link" href="{{ route('seller.loyalty_setting.show') }}">{{ __('Loyalty Setting') }}</a></li>
                      <li><a class="nav-link" href="{{ route('seller.loyalty-promotion.index') }}">{{ __('Promotion') }}</a></li>
                      <li><a class="nav-link" href="{{ route('seller.loyalty-promotion-category.index') }}">{{ __('Promotion Category') }}</a></li>
                  </ul>
              </li>

              <li class="dropdown {{ Request::is('seller/affiliate*') ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-note"></i> <span>{{ __('Affiliate System') }}</span></a>
                  <ul class="dropdown-menu">
                      <li><a class="nav-link" href="{{ route('seller.affiliate.index') }}">{{ __('Affiliate Configurations') }}</a></li>
                      <li><a class="nav-link" href="{{ route('seller.affiliate.users') }}">{{ __('Affiliate Users') }}</a></li>
                      <li><a class="nav-link" href="{{ route('seller.refferals.users') }}">{{ __('Refferal Users') }}</a></li>
                      <li><a class="nav-link" href="{{ route('seller.affiliate.withdraw_requests') }}">{{ __('Withdraw Requests') }}</a></li>
                      <li><a class="nav-link" href="{{ route('seller.affiliate.logs.admin') }}">{{ __('Affiliate Logs') }}</a></li>
                  </ul>
              </li>

              <li class="{{ Request::is('seller/notifications*') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('seller.notifications.index') }}">
                      <i class="flaticon-dashboard-1"></i> <span>{{ __('Notifications') }}</span>
                  </a>
              </li>

              <li class="dropdown {{ Request::is('seller/setting/domain*') || Request::is('seller/settings/plan*') ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-www"></i> <span>{{ __('Register & Domain') }}</span></a>
                  <ul class="dropdown-menu">
                      @if(Route::has('admin.payment-geteway.index'))
                      <li><a class="nav-link" href="{{ route('seller.settings.show','plan') }}">{{ __('Subscriptions') }}</a></li>
                      @endif
                      @if(getenv("AUTO_APPROVED_DOMAIN") !== false)
                      <li><a class="nav-link" href="{{ route('seller.domain.index') }}">{{ __('Domain Settings') }}</a></li>
                      @endif
                  </ul>
              </li>

              <li class="dropdown {{ Request::is('seller/setting*') && !Request::is('seller/settings/plan*') && !Request::is('seller/setting/domain*') && !Request::is('seller/setting/menu*') && !Request::is('seller/setting/page*') && !Request::is('seller/setting/seo*') && !Request::is('seller/setting/slider*') && !Request::is('seller/setting/redirect*') && !Request::is('seller/setting/booking*') ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-settings"></i> <span>{{ __('Settings') }}</span></a>
                  <ul class="dropdown-menu">
                      <li class="dropdown {{ Request::is('seller/settings/contact-list*') || Request::is('seller/settings/contact-page*') ? 'active' : '' }}">
                          <a href="#" class="nav-link has-dropdown pd-40" data-toggle="dropdown"><i class="flaticon-document"></i> <span>{{ __('Contact') }}</span></a>
                          <ul class="dropdown-menu">
                              <li><a class="nav-link" href="{{ route('seller.settings.show','contact-list') }}">{{ __('Contact List') }}</a></li>
                              <li><a class="nav-link" href="{{ route('seller.settings.show','contact-page') }}">{{ __('Contact Page') }}</a></li>
                          </ul>
                      </li>
                      <li><a class="nav-link" href="{{ route('seller.shop-location.index') }}">{{ __('Shop Location') }}</a></li>
                      <li><a class="nav-link" href="{{ route('seller.settings.show','shop-settings') }}">{{ __('Information Settings') }}</a></li>
                      <li><a class="nav-link" href="{{ route('seller.maintainance.index') }}">{{ __('Maintenance Mode') }}</a></li>
                      <li><a class="nav-link" href="{{ route('seller.settings.show','system-environment') }}">{{ __('System Environment') }}</a></li>
                      @if(Route::has('admin.payment-geteway.index'))
                      <li><a class="nav-link" href="{{ route('seller.settings.show','payment') }}">{{ __('Payment Options') }}</a></li>
                      @endif
                      <li><a class="nav-link" href="{{ route('seller.settings.show','social-login') }}">{{ __('Social Login') }}</a></li>
                      <li><a class="nav-link" href="{{ route('seller.logo_favicon.show') }}">{{ __('Logo & Favicon') }}</a></li>
                      {{-- <li><a class="nav-link" href="{{ route('seller.pasteTokenMyDi4Sell.show')}}">{{ __('Shop Sync Token') }}</a></li> --}}
                  </ul>
              </li>
              <li class="dropdown {{ Request::is('seller/setting/redirect*') || Request::is('seller/permalinks*') ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-task"></i> <span>{{ __('Tool') }}</span></a>
                  <ul class="dropdown-menu">
                      <li><a class="nav-link" href="{{ route('seller.redirect.index') }}">{{ __('Redirect Link') }}</a></li>
                      <li><a class="nav-link" href="{{ route('seller.permalink.show') }}">{{ __('Permalinks') }}</a></li>
                  </ul>
              </li>

              <li class="dropdown {{ Request::is('seller/marketing*') ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-megaphone"></i> <span>{{ __('Marketing Tools') }}</span></a>
                  <ul class="dropdown-menu">
                      <li><a class="nav-link" href="{{ route('seller.marketing.show','google-analytics') }}">{{ __('Google Analytics') }}</a></li>
                      <li><a class="nav-link" href="{{ route('seller.marketing.show','tag-manager') }}">{{ __('Google Tag Manager') }}</a></li>
                      <li><a class="nav-link" href="{{ route('seller.marketing.show','facebook-pixel') }}">{{ __('Facebook Pixel') }}</a></li>
                      <li><a class="nav-link" href="{{ route('seller.marketing.show','whatsapp') }}">{{ __('Whatsapp Api') }}</a></li>
                      <li><a class="nav-link" href="{{ route('seller.marketing.show','google-recaptcha') }}">{{ __('Google Recaptcha') }}</a></li>

                  </ul>
              </li>
              @if(!empty(auth()->user()->user_domain->template_enable) && auth()->user()->user_domain->template_enable == 1)
              <li class="{{ Request::is('seller/setting/template*') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('seller.template.index') }}">
                      <i class="flaticon-shop"></i> <span>{{ __('Templates') }} </span>
                  </a>
              </li>
              @endif
              <li class="{{ Request::is('seller/support*') ? 'active' : '' }}">
                  <a class="nav-link" @if(filter_var($plan_limit['live_support'])==true) href="{{ route('seller.support') }}" @endif>
                      @if(filter_var($plan_limit['live_support']) != true) <i class="fa fa-lock text-danger"></i> @else <i class="fa fa-user"></i> @endif <span>{{ __('Technical Support') }} </span>
                  </a>
              </li>

              <li class="{{ Request::is('seller/email-newsletter') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('seller.email.newsletter') }}">
                      <i class="fa fa-user"></i> <span>{{ __('Email Newsletter') }} </span>
                  </a>
              </li>

              <div class="p-3">
                  <a href="{{ route('seller.cache_clear') }}" class="btn btn-info btn-lg btn-block btn-icon-split">
                      <i class="fas fa-external-link-alt"></i>{{ __('Cache Clear') }}
                  </a>
              </div>

              @endif
      </aside>
  </div>
