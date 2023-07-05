@if(!empty($authUser))

    <div class="custom-dropdown navbar-auth-user-dropdown position-relative ml-50">
        <div class="custom-dropdown-toggle d-flex align-items-center navbar-user cursor-pointer">
            <img src="{{ $authUser->getAvatar() }}" class="rounded-circle" alt="{{ $authUser->full_name }}">
            <span class="font-16 user-name ml-10 text-dark-blue font-14">{{ $authUser->full_name }}</span>
        </div>

        <div class="custom-dropdown-body pb-10">

            <div class="dropdown-user-avatar d-flex align-items-center p-15 m-15 mb-10 rounded-sm border">
                <div class="size-40 rounded-circle position-relative">
                    <img src="{{ $authUser->getAvatar() }}" class="img-cover rounded-circle" alt="{{ $authUser->full_name }}">
                </div>

                <div class="ml-5">
                    <div class="font-14 font-weight-bold text-secondary">{{ $authUser->full_name }}</div>
                    <span class="mt-5 text-gray font-12">{{ $authUser->role->caption }}</span>
                </div>
            </div>

            <ul class="my-8">
                @if($authUser->isAdmin())
                    <li class="navbar-auth-user-dropdown-item">
                        <a href="/lms{{ getAdminPanelUrl() }}" class="d-flex align-items-center w-100 px-15 py-10 text-gray font-14 bg-transparent">
                            <img src="/assets/lms/assets/default/img/icons/user_menu/dashboard.svg" class="icons">
                            <span class="ml-5">{{ trans('lms/panel.dashboard') }}</span>
                        </a>
                    </li>

                    <li class="navbar-auth-user-dropdown-item">
                        <a href="/lms{{ getAdminPanelUrl("/settings") }}" class="d-flex align-items-center w-100 px-15 py-10 text-gray font-14 bg-transparent">
                            <img src="/assets/lms/assets/default/img/icons/user_menu/settings.svg" class="icons">
                            <span class="ml-5">{{ trans('lms/panel.settings') }}</span>
                        </a>
                    </li>
                @else
                    <li class="navbar-auth-user-dropdown-item">
                        <a href="/lms/panel" class="d-flex align-items-center w-100 px-15 py-10 text-gray font-14 bg-transparent">
                            <img src="/assets/lms/assets/default/img/icons/user_menu/dashboard.svg" class="icons">
                            <span class="ml-5">{{ trans('lms/panel.dashboard') }}</span>
                        </a>
                    </li>


                    <li class="navbar-auth-user-dropdown-item">
                        <a href="/lms{{ ($authUser->isUser()) ? '/panel/webinars/purchases' : '/panel/webinars' }}" class="d-flex align-items-center w-100 px-15 py-10 text-gray font-14 bg-transparent">
                            <img src="/assets/lms/assets/default/img/icons/user_menu/my_courses.svg" class="icons">
                            <span class="ml-5">{{ trans('lms/update.my_courses') }}</span>
                        </a>
                    </li>

                    @if(!$authUser->isUser())
                        <li class="navbar-auth-user-dropdown-item">
                            <a href="/lms/panel/financial/sales" class="d-flex align-items-center w-100 px-15 py-10 text-gray font-14 bg-transparent">
                                <img src="/assets/lms/assets/default/img/icons/user_menu/sales_history.svg" class="icons">
                                <span class="ml-5">{{ trans('lms/financial.sales_history') }}</span>
                            </a>
                        </li>
                    @endif

                    <li class="navbar-auth-user-dropdown-item">
                        <a href="/lms/panel/support" class="d-flex align-items-center w-100 px-15 py-10 text-gray font-14 bg-transparent">
                            <img src="/assets/lms/assets/default/img/icons/user_menu/support.svg" class="icons">
                            <span class="ml-5">{{ trans('lms/panel.support') }}</span>
                        </a>
                    </li>

                    @if(!$authUser->isUser())
                        <li class="navbar-auth-user-dropdown-item">
                            <a href="/lms{{ $authUser->getProfileUrl() }}" class="d-flex align-items-center w-100 px-15 py-10 text-gray font-14 bg-transparent">
                                <img src="/assets/lms/assets/default/img/icons/user_menu/profile.svg" class="icons">
                                <span class="ml-5">{{ trans('lms/public.profile') }}</span>
                            </a>
                        </li>
                    @endif

                    <li class="navbar-auth-user-dropdown-item">
                        <a href="/lms/panel/setting" class="d-flex align-items-center w-100 px-15 py-10 text-gray font-14 bg-transparent">
                            <img src="/assets/lms/assets/default/img/icons/user_menu/settings.svg" class="icons">
                            <span class="ml-5">{{ trans('lms/panel.settings') }}</span>
                        </a>
                    </li>
                @endif

                <li class="navbar-auth-user-dropdown-item">
                    <a href="/lms/logout" class="d-flex align-items-center w-100 px-15 py-10 text-danger font-14 bg-transparent">
                        <img src="/assets/lms/assets/default/img/icons/user_menu/logout.svg" class="icons">
                        <span class="ml-5">{{ trans('lms/auth.logout') }}</span>
                    </a>
                </li>

            </ul>

        </div>
    </div>
@else
    <div class="d-flex align-items-center ml-md-50">
        <a href="/lms/login" class="py-5 px-10 mr-10 text-dark-blue font-14">{{ trans('lms/auth.login') }}</a>
        <a href="/lms/register" class="py-5 px-10 text-dark-blue font-14">{{ trans('lms/auth.register') }}</a>
    </div>
@endif
