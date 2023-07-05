@php
    $getPanelSidebarSettings = getPanelSidebarSettings();
@endphp

<div class="xs-panel-nav d-flex d-lg-none justify-content-between py-5 px-15">
    <div class="user-info d-flex align-items-center justify-content-between">
        <div class="user-avatar bg-gray200">
            <img src="{{ $authUser->getAvatar(100) }}" class="img-cover" alt="{{ $authUser->full_name }}">
        </div>

        <div class="user-name ml-15">
            <h3 class="font-16 font-weight-bold">{{ $authUser->full_name }}</h3>
        </div>
    </div>

    <button class="sidebar-toggler btn-transparent d-flex flex-column-reverse justify-content-center align-items-center p-5 rounded-sm sidebarNavToggle" type="button">
        <span>{{ trans('lms/navbar.menu') }}</span>
        <i data-feather="menu" width="16" height="16"></i>
    </button>
</div>

<div class="panel-sidebar pt-50 pb-25 px-25" id="panelSidebar">
    <button class="btn-transparent panel-sidebar-close sidebarNavToggle">
        <i data-feather="x" width="24" height="24"></i>
    </button>

    <div class="user-info d-flex align-items-center flex-row flex-lg-column justify-content-lg-center">
        <a href="/lms/panel" class="user-avatar bg-gray200">
            <img src="{{ $authUser->getAvatar(100) }}" class="img-cover" alt="{{ $authUser->full_name }}">
        </a>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <a href="/lms/panel" class="user-name mt-15">
                <h3 class="font-16 font-weight-bold text-center">{{ $authUser->full_name }}</h3>
            </a>

            @if(!empty($authUser->getUserGroup()))
                <span class="create-new-user mt-10">{{ $authUser->getUserGroup()->name }}</span>
            @endif
        </div>
    </div>

    <div class="d-flex sidebar-user-stats pb-10 ml-20 pb-lg-20 mt-15 mt-lg-30">
        <div class="sidebar-user-stat-item d-flex flex-column">
            <strong class="text-center">{{ $authUser->webinars()->count() }}</strong>
            <span class="font-12">{{ trans('lms/panel.classes') }}</span>
        </div>

        <div class="border-left mx-30"></div>

        @if($authUser->isUser())
            <div class="sidebar-user-stat-item d-flex flex-column">
                <strong class="text-center">{{ $authUser->following()->count() }}</strong>
                <span class="font-12">{{ trans('lms/panel.following') }}</span>
            </div>
        @else
            <div class="sidebar-user-stat-item d-flex flex-column">
                <strong class="text-center">{{ $authUser->followers()->count() }}</strong>
                <span class="font-12">{{ trans('lms/panel.followers') }}</span>
            </div>
        @endif
    </div>

    <ul id="panel-sidebar-scroll" class="sidebar-menu pt-10 @if(!empty($authUser->userGroup)) has-user-group @endif @if(empty($getPanelSidebarSettings) or empty($getPanelSidebarSettings['background'])) without-bottom-image @endif" @if((!empty($isRtl) and $isRtl)) data-simplebar-direction="rtl" @endif>

        <li class="sidenav-item {{ (request()->is('panel')) ? 'sidenav-item-active' : '' }}">
            <a href="/lms/panel" class="d-flex align-items-center">
                <span class="sidenav-item-icon mr-10">
                    @include('lms.web.default.panel.includes.sidebar_icons.dashboard')
                </span>
                <span class="font-14 text-dark-blue font-weight-500">{{ trans('lms/panel.dashboard') }}</span>
            </a>
        </li>

        @if($authUser->isOrganization())
            <li class="sidenav-item {{ (request()->is('panel/instructors') or request()->is('panel/manage/instructors*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#instructorsCollapse" role="button" aria-expanded="false" aria-controls="instructorsCollapse">
                <span class="sidenav-item-icon mr-10">
                    @include('lms.web.default.panel.includes.sidebar_icons.teachers')
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('lms/public.instructors') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/instructors') or request()->is('panel/manage/instructors*')) ? 'show' : '' }}" id="instructorsCollapse">
                    <ul class="sidenav-item-collapse">
                        <li class="mt-5 {{ (request()->is('panel/instructors/new')) ? 'active' : '' }}">
                            <a href="/lms/panel/manage/instructors/new">{{ trans('lms/public.new') }}</a>
                        </li>
                        <li class="mt-5 {{ (request()->is('panel/manage/instructors')) ? 'active' : '' }}">
                            <a href="/lms/panel/manage/instructors">{{ trans('lms/public.list') }}</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="sidenav-item {{ (request()->is('panel/students') or request()->is('panel/manage/students*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#studentsCollapse" role="button" aria-expanded="false" aria-controls="studentsCollapse">
                <span class="sidenav-item-icon mr-10">
                    @include('lms.web.default.panel.includes.sidebar_icons.students')
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('lms/quiz.students') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/students') or request()->is('panel/manage/students*')) ? 'show' : '' }}" id="studentsCollapse">
                    <ul class="sidenav-item-collapse">
                        <li class="mt-5 {{ (request()->is('panel/manage/students/new')) ? 'active' : '' }}">
                            <a href="/lms/panel/manage/students/new">{{ trans('lms/public.new') }}</a>
                        </li>
                        <li class="mt-5 {{ (request()->is('panel/manage/students')) ? 'active' : '' }}">
                            <a href="/lms/panel/manage/students">{{ trans('lms/public.list') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif

        <li class="sidenav-item {{ (request()->is('panel/webinars') or request()->is('panel/webinars/*')) ? 'sidenav-item-active' : '' }}">
            <a class="d-flex align-items-center" data-toggle="collapse" href="#webinarCollapse" role="button" aria-expanded="false" aria-controls="webinarCollapse">
                <span class="sidenav-item-icon mr-10">
                    @include('lms.web.default.panel.includes.sidebar_icons.webinars')
                </span>
                <span class="font-14 text-dark-blue font-weight-500">{{ trans('lms/panel.webinars') }}</span>
            </a>

            <div class="collapse {{ (request()->is('panel/webinars') or request()->is('panel/webinars/*')) ? 'show' : '' }}" id="webinarCollapse">
                <ul class="sidenav-item-collapse">
                    @if($authUser->isOrganization() || $authUser->isTeacher())
                        <li class="mt-5 {{ (request()->is('panel/webinars/new')) ? 'active' : '' }}">
                            <a href="/lms/panel/webinars/new">{{ trans('lms/public.new') }}</a>
                        </li>

                        <li class="mt-5 {{ (request()->is('panel/webinars')) ? 'active' : '' }}">
                            <a href="/lms/panel/webinars">{{ trans('lms/panel.my_classes') }}</a>
                        </li>

                        <li class="mt-5 {{ (request()->is('panel/webinars/invitations')) ? 'active' : '' }}">
                            <a href="/lms/panel/webinars/invitations">{{ trans('lms/panel.invited_classes') }}</a>
                        </li>
                    @endif

                    @if(!empty($authUser->organ_id))
                        <li class="mt-5 {{ (request()->is('panel/webinars/organization_classes')) ? 'active' : '' }}">
                            <a href="/lms/panel/webinars/organization_classes">{{ trans('lms/panel.organization_classes') }}</a>
                        </li>
                    @endif

                    <li class="mt-5 {{ (request()->is('panel/webinars/purchases')) ? 'active' : '' }}">
                        <a href="/lms/panel/webinars/purchases">{{ trans('lms/panel.my_purchases') }}</a>
                    </li>

                    @if($authUser->isOrganization() || $authUser->isTeacher())
                        <li class="mt-5 {{ (request()->is('panel/webinars/comments')) ? 'active' : '' }}">
                            <a href="/lms/panel/webinars/comments">{{ trans('lms/panel.my_class_comments') }}</a>
                        </li>
                    @endif

                    <li class="mt-5 {{ (request()->is('panel/webinars/my-comments')) ? 'active' : '' }}">
                        <a href="/lms/panel/webinars/my-comments">{{ trans('lms/panel.my_comments') }}</a>
                    </li>

                    <li class="mt-5 {{ (request()->is('panel/webinars/favorites')) ? 'active' : '' }}">
                        <a href="/lms/panel/webinars/favorites">{{ trans('lms/panel.favorites') }}</a>
                    </li>
                </ul>
            </div>
        </li>

        @if(!empty(getFeaturesSettings('upcoming_courses_status')))
            <li class="sidenav-item {{ (request()->is('panel/upcoming_courses') or request()->is('panel/upcoming_courses/*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#upcomingCoursesCollapse" role="button" aria-expanded="false" aria-controls="upcomingCoursesCollapse">
                <span class="sidenav-item-icon mr-10">
                    <i data-feather="film" class="img-cover"></i>
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('lms/update.upcoming_courses') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/upcoming_courses') or request()->is('panel/upcoming_courses/*')) ? 'show' : '' }}" id="upcomingCoursesCollapse">
                    <ul class="sidenav-item-collapse">
                        @if($authUser->isOrganization() || $authUser->isTeacher())
                            <li class="mt-5 {{ (request()->is('panel/upcoming_courses/new')) ? 'active' : '' }}">
                                <a href="/lms/panel/upcoming_courses/new">{{ trans('lms/public.new') }}</a>
                            </li>

                            <li class="mt-5 {{ (request()->is('panel/upcoming_courses')) ? 'active' : '' }}">
                                <a href="/lms/panel/upcoming_courses">{{ trans('lms/update.my_upcoming_courses') }}</a>
                            </li>
                        @endif

                        <li class="mt-5 {{ (request()->is('panel/upcoming_courses/followings')) ? 'active' : '' }}">
                            <a href="/lms/panel/upcoming_courses/followings">{{ trans('lms/update.following_courses') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif

        @if($authUser->isOrganization() or $authUser->isTeacher())
            <li class="sidenav-item {{ (request()->is('panel/bundles') or request()->is('panel/bundles/*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#bundlesCollapse" role="button" aria-expanded="false" aria-controls="bundlesCollapse">
                <span class="sidenav-item-icon assign-fill mr-10">
                    @include('lms.web.default.panel.includes.sidebar_icons.bundles')
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('lms/update.bundles') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/bundles') or request()->is('panel/bundles/*')) ? 'show' : '' }}" id="bundlesCollapse">
                    <ul class="sidenav-item-collapse">
                        <li class="mt-5 {{ (request()->is('panel/bundles/new')) ? 'active' : '' }}">
                            <a href="/lms/panel/bundles/new">{{ trans('lms/public.new') }}</a>
                        </li>

                        <li class="mt-5 {{ (request()->is('panel/bundles')) ? 'active' : '' }}">
                            <a href="/lms/panel/bundles">{{ trans('lms/update.my_bundles') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif

        @if(getFeaturesSettings('webinar_assignment_status'))
            <li class="sidenav-item {{ (request()->is('panel/assignments') or request()->is('panel/assignments/*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#assignmentCollapse" role="button" aria-expanded="false" aria-controls="assignmentCollapse">
                <span class="sidenav-item-icon mr-10">
                    @include('lms.web.default.panel.includes.sidebar_icons.assignments')
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('lms/update.assignments') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/assignments') or request()->is('panel/assignments/*')) ? 'show' : '' }}" id="assignmentCollapse">
                    <ul class="sidenav-item-collapse">

                        <li class="mt-5 {{ (request()->is('panel/assignments/my-assignments')) ? 'active' : '' }}">
                            <a href="/lms/panel/assignments/my-assignments">{{ trans('lms/update.my_assignments') }}</a>
                        </li>

                        @if($authUser->isOrganization() || $authUser->isTeacher())
                            <li class="mt-5 {{ (request()->is('panel/assignments/my-courses-assignments')) ? 'active' : '' }}">
                                <a href="/lms/panel/assignments/my-courses-assignments">{{ trans('lms/update.students_assignments') }}</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>
        @endif


        <li class="sidenav-item {{ (request()->is('panel/meetings') or request()->is('panel/meetings/*')) ? 'sidenav-item-active' : '' }}">
            <a class="d-flex align-items-center" data-toggle="collapse" href="#meetingCollapse" role="button" aria-expanded="false" aria-controls="meetingCollapse">
                <span class="sidenav-item-icon mr-10">
                    @include('lms.web.default.panel.includes.sidebar_icons.requests')
                </span>
                <span class="font-14 text-dark-blue font-weight-500">{{ trans('lms/panel.meetings') }}</span>
            </a>

            <div class="collapse {{ (request()->is('panel/meetings') or request()->is('panel/meetings/*')) ? 'show' : '' }}" id="meetingCollapse">
                <ul class="sidenav-item-collapse">

                    <li class="mt-5 {{ (request()->is('panel/meetings/reservation')) ? 'active' : '' }}">
                        <a href="/lms/panel/meetings/reservation">{{ trans('lms/public.my_reservation') }}</a>
                    </li>

                    @if($authUser->isOrganization() || $authUser->isTeacher())
                        <li class="mt-5 {{ (request()->is('panel/meetings/requests')) ? 'active' : '' }}">
                            <a href="/lms/panel/meetings/requests">{{ trans('lms/panel.requests') }}</a>
                        </li>

                        <li class="mt-5 {{ (request()->is('panel/meetings/settings')) ? 'active' : '' }}">
                            <a href="/lms/panel/meetings/settings">{{ trans('lms/panel.settings') }}</a>
                        </li>
                    @endif
                </ul>
            </div>
        </li>

        <li class="sidenav-item {{ (request()->is('panel/quizzes') or request()->is('panel/quizzes/*')) ? 'sidenav-item-active' : '' }}">
            <a class="d-flex align-items-center" data-toggle="collapse" href="#quizzesCollapse" role="button" aria-expanded="false" aria-controls="quizzesCollapse">
                <span class="sidenav-item-icon mr-10">
                    @include('lms.web.default.panel.includes.sidebar_icons.quizzes')
                </span>
                <span class="font-14 text-dark-blue font-weight-500">{{ trans('lms/panel.quizzes') }}</span>
            </a>

            <div class="collapse {{ (request()->is('panel/quizzes') or request()->is('panel/quizzes/*')) ? 'show' : '' }}" id="quizzesCollapse">
                <ul class="sidenav-item-collapse">
                    @if($authUser->isOrganization() || $authUser->isTeacher())
                        <li class="mt-5 {{ (request()->is('panel/quizzes/new')) ? 'active' : '' }}">
                            <a href="/lms/panel/quizzes/new">{{ trans('lms/quiz.new_quiz') }}</a>
                        </li>
                        <li class="mt-5 {{ (request()->is('panel/quizzes')) ? 'active' : '' }}">
                            <a href="/lms/panel/quizzes">{{ trans('lms/public.list') }}</a>
                        </li>
                        <li class="mt-5 {{ (request()->is('panel/quizzes/results')) ? 'active' : '' }}">
                            <a href="/lms/panel/quizzes/results">{{ trans('lms/public.results') }}</a>
                        </li>
                    @endif

                    <li class="mt-5 {{ (request()->is('panel/quizzes/my-results')) ? 'active' : '' }}">
                        <a href="/lms/panel/quizzes/my-results">{{ trans('lms/public.my_results') }}</a>
                    </li>

                    <li class="mt-5 {{ (request()->is('panel/quizzes/opens')) ? 'active' : '' }}">
                        <a href="/lms/panel/quizzes/opens">{{ trans('lms/public.not_participated') }}</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="sidenav-item {{ (request()->is('panel/certificates') or request()->is('panel/certificates/*')) ? 'sidenav-item-active' : '' }}">
            <a class="d-flex align-items-center" data-toggle="collapse" href="#certificatesCollapse" role="button" aria-expanded="false" aria-controls="certificatesCollapse">
                <span class="sidenav-item-icon mr-10">
                    @include('lms.web.default.panel.includes.sidebar_icons.certificate')
                </span>
                <span class="font-14 text-dark-blue font-weight-500">{{ trans('lms/panel.certificates') }}</span>
            </a>

            <div class="collapse {{ (request()->is('panel/certificates') or request()->is('panel/certificates/*')) ? 'show' : '' }}" id="certificatesCollapse">
                <ul class="sidenav-item-collapse">
                    @if($authUser->isOrganization() || $authUser->isTeacher())
                        <li class="mt-5 {{ (request()->is('panel/certificates')) ? 'active' : '' }}">
                            <a href="/lms/panel/certificates">{{ trans('lms/public.list') }}</a>
                        </li>
                    @endif

                    <li class="mt-5 {{ (request()->is('panel/certificates/achievements')) ? 'active' : '' }}">
                        <a href="/lms/panel/certificates/achievements">{{ trans('lms/quiz.achievements') }}</a>
                    </li>

                    <li class="mt-5">
                        <a href="/lms/certificate_validation">{{ trans('lms/site.certificate_validation') }}</a>
                    </li>

                    <li class="mt-5 {{ (request()->is('panel/certificates/webinars')) ? 'active' : '' }}">
                        <a href="/lms/panel/certificates/webinars">{{ trans('lms/update.course_certificates') }}</a>
                    </li>

                </ul>
            </div>
        </li>

        @if($authUser->checkCanAccessToStore())
            <li class="sidenav-item {{ (request()->is('panel/store') or request()->is('panel/store/*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#storeCollapse" role="button" aria-expanded="false" aria-controls="storeCollapse">
                <span class="sidenav-item-icon assign-fill mr-10">
                    @include('lms.web.default.panel.includes.sidebar_icons.store')
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('lms/update.store') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/store') or request()->is('panel/store/*')) ? 'show' : '' }}" id="storeCollapse">
                    <ul class="sidenav-item-collapse">
                        @if($authUser->isOrganization() || $authUser->isTeacher())
                            <li class="mt-5 {{ (request()->is('panel/store/products/new')) ? 'active' : '' }}">
                                <a href="/lms/panel/store/products/new">{{ trans('lms/update.new_product') }}</a>
                            </li>

                            <li class="mt-5 {{ (request()->is('panel/store/products')) ? 'active' : '' }}">
                                <a href="/lms/panel/store/products">{{ trans('lms/update.products') }}</a>
                            </li>

                            @php
                                $sellerProductOrderWaitingDeliveryCount = $authUser->getWaitingDeliveryProductOrdersCount();
                            @endphp

                            <li class="mt-5 {{ (request()->is('panel/store/sales')) ? 'active' : '' }}">
                                <a href="/lms/panel/store/sales">{{ trans('lms/panel.sales') }}</a>

                                @if($sellerProductOrderWaitingDeliveryCount > 0)
                                    <span class="d-inline-flex align-items-center justify-content-center font-weight-500 ml-15 panel-sidebar-store-sales-circle-badge">{{ $sellerProductOrderWaitingDeliveryCount }}</span>
                                @endif
                            </li>

                        @endif

                        <li class="mt-5 {{ (request()->is('panel/store/purchases')) ? 'active' : '' }}">
                            <a href="/lms/panel/store/purchases">{{ trans('lms/panel.my_purchases') }}</a>
                        </li>

                        @if($authUser->isOrganization() || $authUser->isTeacher())
                            <li class="mt-5 {{ (request()->is('panel/store/products/comments')) ? 'active' : '' }}">
                                <a href="/lms/panel/store/products/comments">{{ trans('lms/update.product_comments') }}</a>
                            </li>
                        @endif

                        <li class="mt-5 {{ (request()->is('panel/store/products/my-comments')) ? 'active' : '' }}">
                            <a href="/lms/panel/store/products/my-comments">{{ trans('lms/panel.my_comments') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif

        <li class="sidenav-item {{ (request()->is('panel/financial') or request()->is('panel/financial/*')) ? 'sidenav-item-active' : '' }}">
            <a class="d-flex align-items-center" data-toggle="collapse" href="#financialCollapse" role="button" aria-expanded="false" aria-controls="financialCollapse">
                <span class="sidenav-item-icon mr-10">
                    @include('lms.web.default.panel.includes.sidebar_icons.financial')
                </span>
                <span class="font-14 text-dark-blue font-weight-500">{{ trans('lms/panel.financial') }}</span>
            </a>

            <div class="collapse {{ (request()->is('panel/financial') or request()->is('panel/financial/*')) ? 'show' : '' }}" id="financialCollapse">
                <ul class="sidenav-item-collapse">

                    @if($authUser->isOrganization() || $authUser->isTeacher())
                        <li class="mt-5 {{ (request()->is('panel/financial/sales')) ? 'active' : '' }}">
                            <a href="/lms/panel/financial/sales">{{ trans('lms/financial.sales_report') }}</a>
                        </li>
                    @endif

                    <li class="mt-5 {{ (request()->is('panel/financial/summary')) ? 'active' : '' }}">
                        <a href="/lms/panel/financial/summary">{{ trans('lms/financial.financial_summary') }}</a>
                    </li>

                    <li class="mt-5 {{ (request()->is('panel/financial/payout')) ? 'active' : '' }}">
                        <a href="/lms/panel/financial/payout">{{ trans('lms/financial.payout') }}</a>
                    </li>

                    <li class="mt-5 {{ (request()->is('panel/financial/account')) ? 'active' : '' }}">
                        <a href="/lms/panel/financial/account">{{ trans('lms/financial.charge_account') }}</a>
                    </li>

                    <li class="mt-5 {{ (request()->is('panel/financial/subscribes')) ? 'active' : '' }}">
                        <a href="/lms/panel/financial/subscribes">{{ trans('lms/financial.subscribes') }}</a>
                    </li>

                    @if(($authUser->isOrganization() || $authUser->isTeacher()) and getRegistrationPackagesGeneralSettings('status'))
                        <li class="mt-5 {{ (request()->is('panel/financial/registration-packages')) ? 'active' : '' }}">
                            <a href="/lms{{ route('panelRegistrationPackagesLists') }}">{{ trans('lms/update.registration_packages') }}</a>
                        </li>
                    @endif

                    @if(getInstallmentsSettings('status'))
                        <li class="mt-5 {{ (request()->is('panel/financial/installments*')) ? 'active' : '' }}">
                            <a href="/lms/panel/financial/installments">{{ trans('lms/update.installments') }}</a>
                        </li>
                    @endif
                </ul>
            </div>
        </li>

        <li class="sidenav-item {{ (request()->is('panel/support') or request()->is('panel/support/*')) ? 'sidenav-item-active' : '' }}">
            <a class="d-flex align-items-center" data-toggle="collapse" href="#supportCollapse" role="button" aria-expanded="false" aria-controls="supportCollapse">
                <span class="sidenav-item-icon assign-fill mr-10">
                    @include('lms.web.default.panel.includes.sidebar_icons.support')
                </span>
                <span class="font-14 text-dark-blue font-weight-500">{{ trans('lms/panel.support') }}</span>
            </a>

            <div class="collapse {{ (request()->is('panel/support') or request()->is('panel/support/*')) ? 'show' : '' }}" id="supportCollapse">
                <ul class="sidenav-item-collapse">
                    <li class="mt-5 {{ (request()->is('panel/support/new')) ? 'active' : '' }}">
                        <a href="/lms/panel/support/new">{{ trans('lms/public.new') }}</a>
                    </li>
                    <li class="mt-5 {{ (request()->is('panel/support')) ? 'active' : '' }}">
                        <a href="/lms/panel/support">{{ trans('lms/panel.classes_support') }}</a>
                    </li>
                    <li class="mt-5 {{ (request()->is('panel/support/tickets')) ? 'active' : '' }}">
                        <a href="/lms/panel/support/tickets">{{ trans('lms/panel.support_tickets') }}</a>
                    </li>
                </ul>
            </div>
        </li>

        @if(!$authUser->isUser() or (!empty($referralSettings) and $referralSettings['status'] and $authUser->affiliate) or (!empty(getRegistrationBonusSettings('status')) and $authUser->enable_registration_bonus))
            <li class="sidenav-item {{ (request()->is('panel/marketing') or request()->is('panel/marketing/*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#marketingCollapse" role="button" aria-expanded="false" aria-controls="marketingCollapse">
                <span class="sidenav-item-icon mr-10">
                    @include('lms.web.default.panel.includes.sidebar_icons.marketing')
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('lms/panel.marketing') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/marketing') or request()->is('panel/marketing/*')) ? 'show' : '' }}" id="marketingCollapse">
                    <ul class="sidenav-item-collapse">
                        @if(!$authUser->isUser())
                            <li class="mt-5 {{ (request()->is('panel/marketing/special_offers')) ? 'active' : '' }}">
                                <a href="/lms/panel/marketing/special_offers">{{ trans('lms/panel.discounts') }}</a>
                            </li>
                            <li class="mt-5 {{ (request()->is('panel/marketing/promotions')) ? 'active' : '' }}">
                                <a href="/lms/panel/marketing/promotions">{{ trans('lms/panel.promotions') }}</a>
                            </li>
                        @endif

                        @if(!empty($referralSettings) and $referralSettings['status'] and $authUser->affiliate)
                            <li class="mt-5 {{ (request()->is('panel/marketing/affiliates')) ? 'active' : '' }}">
                                <a href="/lms/panel/marketing/affiliates">{{ trans('lms/panel.affiliates') }}</a>
                            </li>
                        @endif

                        @if(!empty(getRegistrationBonusSettings('status')) and $authUser->enable_registration_bonus)
                            <li class="mt-5 {{ (request()->is('panel/marketing/registration_bonus')) ? 'active' : '' }}">
                                <a href="/lms/panel/marketing/registration_bonus">{{ trans('lms/update.registration_bonus') }}</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>
        @endif

        @if(getFeaturesSettings('forums_status'))
            <li class="sidenav-item {{ (request()->is('panel/forums') or request()->is('panel/forums/*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#forumsCollapse" role="button" aria-expanded="false" aria-controls="forumsCollapse">
                <span class="sidenav-item-icon assign-fill mr-10">
                    @include('lms.web.default.panel.includes.sidebar_icons.forums')
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('lms/update.forums') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/forums') or request()->is('panel/forums/*')) ? 'show' : '' }}" id="forumsCollapse">
                    <ul class="sidenav-item-collapse">
                        <li class="mt-5 {{ (request()->is('/forums/create-topic')) ? 'active' : '' }}">
                            <a href="/lms/forums/create-topic">{{ trans('lms/update.new_topic') }}</a>
                        </li>
                        <li class="mt-5 {{ (request()->is('panel/forums/topics')) ? 'active' : '' }}">
                            <a href="/lms/panel/forums/topics">{{ trans('lms/update.my_topics') }}</a>
                        </li>

                        <li class="mt-5 {{ (request()->is('panel/forums/posts')) ? 'active' : '' }}">
                            <a href="/lms/panel/forums/posts">{{ trans('lms/update.my_posts') }}</a>
                        </li>

                        <li class="mt-5 {{ (request()->is('panel/forums/bookmarks')) ? 'active' : '' }}">
                            <a href="/lms/panel/forums/bookmarks">{{ trans('lms/update.bookmarks') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif


        @if($authUser->isTeacher())
            <li class="sidenav-item {{ (request()->is('panel/blog') or request()->is('panel/blog/*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#blogCollapse" role="button" aria-expanded="false" aria-controls="blogCollapse">
                <span class="sidenav-item-icon assign-fill mr-10">
                    @include('lms.web.default.panel.includes.sidebar_icons.blog')
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('lms/update.articles') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/blog') or request()->is('panel/blog/*')) ? 'show' : '' }}" id="blogCollapse">
                    <ul class="sidenav-item-collapse">
                        <li class="mt-5 {{ (request()->is('panel/blog/posts/new')) ? 'active' : '' }}">
                            <a href="/lms/panel/blog/posts/new">{{ trans('lms/update.new_article') }}</a>
                        </li>

                        <li class="mt-5 {{ (request()->is('panel/blog/posts')) ? 'active' : '' }}">
                            <a href="/lms/panel/blog/posts">{{ trans('lms/update.my_articles') }}</a>
                        </li>

                        <li class="mt-5 {{ (request()->is('panel/blog/comments')) ? 'active' : '' }}">
                            <a href="/lms/panel/blog/comments">{{ trans('lms/panel.comments') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif

        @if($authUser->isOrganization() || $authUser->isTeacher())
            <li class="sidenav-item {{ (request()->is('panel/noticeboard*') or request()->is('panel/course-noticeboard*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#noticeboardCollapse" role="button" aria-expanded="false" aria-controls="noticeboardCollapse">
                <span class="sidenav-item-icon mr-10">
                    @include('lms.web.default.panel.includes.sidebar_icons.noticeboard')
                </span>

                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('lms/panel.noticeboard') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/noticeboard*') or request()->is('panel/course-noticeboard*')) ? 'show' : '' }}" id="noticeboardCollapse">
                    <ul class="sidenav-item-collapse">
                        <li class="mt-5 {{ (request()->is('panel/noticeboard')) ? 'active' : '' }}">
                            <a href="/lms/panel/noticeboard">{{ trans('lms/public.history') }}</a>
                        </li>

                        <li class="mt-5 {{ (request()->is('panel/noticeboard/new')) ? 'active' : '' }}">
                            <a href="/lms/panel/noticeboard/new">{{ trans('lms/public.new') }}</a>
                        </li>

                        <li class="mt-5 {{ (request()->is('panel/course-noticeboard')) ? 'active' : '' }}">
                            <a href="/lms/panel/course-noticeboard">{{ trans('lms/update.course_notices') }}</a>
                        </li>

                        <li class="mt-5 {{ (request()->is('panel/course-noticeboard/new')) ? 'active' : '' }}">
                            <a href="/lms/panel/course-noticeboard/new">{{ trans('lms/update.new_course_notices') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif

        @php
            $rewardSetting = getRewardsSettings();
        @endphp

        @if(!empty($rewardSetting) and $rewardSetting['status'] == '1')
            <li class="sidenav-item {{ (request()->is('panel/rewards')) ? 'sidenav-item-active' : '' }}">
                <a href="/lms/panel/rewards" class="d-flex align-items-center">
                <span class="sidenav-item-icon assign-strock mr-10">
                    @include('lms.web.default.panel.includes.sidebar_icons.rewards')
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('lms/update.rewards') }}</span>
                </a>
            </li>
        @endif

        <li class="sidenav-item {{ (request()->is('panel/notifications')) ? 'sidenav-item-active' : '' }}">
            <a href="/lms/panel/notifications" class="d-flex align-items-center">
            <span class="sidenav-notification-icon sidenav-item-icon mr-10">
                    @include('lms.web.default.panel.includes.sidebar_icons.notifications')
                </span>
                <span class="font-14 text-dark-blue font-weight-500">{{ trans('lms/panel.notifications') }}</span>
            </a>
        </li>

        <li class="sidenav-item {{ (request()->is('panel/setting')) ? 'sidenav-item-active' : '' }}">
            <a href="/lms/panel/setting" class="d-flex align-items-center">
                <span class="sidenav-setting-icon sidenav-item-icon mr-10">
                    @include('lms.web.default.panel.includes.sidebar_icons.setting')
                </span>
                <span class="font-14 text-dark-blue font-weight-500">{{ trans('lms/panel.settings') }}</span>
            </a>
        </li>

        @if($authUser->isTeacher() or $authUser->isOrganization())
            <li class="sidenav-item ">
                <a href="/lms{{ $authUser->getProfileUrl() }}" class="d-flex align-items-center">
                <span class="sidenav-item-icon assign-strock mr-10">
                    <i data-feather="user" stroke="#1f3b64" stroke-width="1.5" width="24" height="24" class="mr-10 webinar-icon"></i>
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('lms/public.my_profile') }}</span>
                </a>
            </li>
        @endif

        <li class="sidenav-item">
            <a href="/lms/logout" class="d-flex align-items-center">
                <span class="sidenav-logout-icon sidenav-item-icon mr-10">
                    @include('lms.web.default.panel.includes.sidebar_icons.logout')
                </span>
                <span class="font-14 text-dark-blue font-weight-500">{{ trans('lms/panel.log_out') }}</span>
            </a>
        </li>
    </ul>

    @if(!empty($getPanelSidebarSettings) and !empty($getPanelSidebarSettings['background']))
        <div class="sidebar-create-class d-none d-md-block">
            <a href="/lms{{ !empty($getPanelSidebarSettings['link']) ? $getPanelSidebarSettings['link'] : '' }}" class="sidebar-create-class-btn d-block text-right p-5">
                <img src="{{get_path_lms()}}{{ !empty($getPanelSidebarSettings['background']) ? $getPanelSidebarSettings['background'] : '' }}" alt="">
            </a>
        </div>
    @endif
</div>
