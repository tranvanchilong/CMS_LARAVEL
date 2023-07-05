<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="/lms/">
                @if(!empty($generalSettings['site_name']))
                    {{ strtoupper($generalSettings['site_name']) }}
                @else
                    Platform Title
                @endif
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="/lms/">
                @if(!empty($generalSettings['site_name']))
                    {{ strtoupper(substr($generalSettings['site_name'],0,2)) }}
                @endif
            </a>
        </div>

        <ul class="sidebar-menu">
            @if($authUser->can('admin_general_dashboard_show'))
                <li class="{{ (request()->is(getAdminPanelUrl('/'))) ? 'active' : '' }}">
                    <a href="/lms{{ getAdminPanelUrl() }}" class="nav-link">
                        <i class="fas fa-fire"></i>
                        <span>{{ trans('lms/admin/main.dashboard') }}</span>
                    </a>
                </li>
            @endif

            @if($authUser->can('admin_marketing_dashboard'))
                <li class="{{ (request()->is(getAdminPanelUrl('/marketing', false))) ? 'active' : '' }}">
                    <a href="/lms{{ getAdminPanelUrl('/marketing') }}" class="nav-link">
                        <i class="fas fa-chart-pie"></i>
                        <span>{{ trans('lms/admin/main.marketing_dashboard') }}</span>
                    </a>
                </li>
            @endif

            @if($authUser->can('admin_webinars') or
                $authUser->can('admin_bundles') or
                $authUser->can('admin_categories') or
                $authUser->can('admin_filters') or
                $authUser->can('admin_quizzes') or
                $authUser->can('admin_certificate') or
                $authUser->can('admin_reviews_lists') or
                $authUser->can('admin_webinar_assignments') or
                $authUser->can('admin_enrollment') or
                $authUser->can('admin_waitlists')
            )
                <li class="menu-header">{{ trans('lms/site.education') }}</li>
            @endif

            @if($authUser->can('admin_webinars'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/webinars*', false)) and !request()->is(getAdminPanelUrl('/webinars/comments*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-graduation-cap"></i>
                        <span>{{ trans('lms/panel.classes') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_webinars_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/webinars', false)) and request()->get('type') == 'course') ? 'active' : '' }}">
                                <a class="nav-link @if(!empty($sidebarBeeps['courses']) and $sidebarBeeps['courses']) beep beep-sidebar @endif" href="/lms{{ getAdminPanelUrl() }}/webinars?type=course">{{ trans('lms/admin/main.courses') }}</a>
                            </li>

                            <li class="{{ (request()->is(getAdminPanelUrl('/webinars', false)) and request()->get('type') == 'webinar') ? 'active' : '' }}">
                                <a class="nav-link @if(!empty($sidebarBeeps['webinars']) and $sidebarBeeps['webinars']) beep beep-sidebar @endif" href="/lms{{ getAdminPanelUrl() }}/webinars?type=webinar">{{ trans('lms/admin/main.live_classes') }}</a>
                            </li>

                            <li class="{{ (request()->is(getAdminPanelUrl('/webinars', false)) and request()->get('type') == 'text_lesson') ? 'active' : '' }}">
                                <a class="nav-link @if(!empty($sidebarBeeps['textLessons']) and $sidebarBeeps['textLessons']) beep beep-sidebar @endif" href="/lms{{ getAdminPanelUrl() }}/webinars?type=text_lesson">{{ trans('lms/admin/main.text_courses') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_webinars_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/webinars/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/webinars/create">{{ trans('lms/admin/main.new') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_agora_history_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/agora_history', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/agora_history">{{ trans('lms/update.agora_history') }}</a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif()

            @if($authUser->can('admin_bundles'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/bundles*', false)) and !request()->is(getAdminPanelUrl('/bundles/comments*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-cube"></i>
                        <span>{{ trans('lms/update.bundles') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_bundles_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/bundles', false)) and request()->get('type') == 'course') ? 'active' : '' }}">
                                <a href="/lms{{ getAdminPanelUrl() }}/bundles" class="nav-link @if(!empty($sidebarBeeps['bundles']) and $sidebarBeeps['bundles']) beep beep-sidebar @endif">{{ trans('lms/admin/main.lists') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_bundles_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/bundles/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/bundles/create">{{ trans('lms/admin/main.new') }}</a>
                            </li>
                        @endif()
                    </ul>
                </li>
            @endif()

            @if($authUser->can('admin_upcoming_courses'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/upcoming_courses*', false)) and !request()->is(getAdminPanelUrl('/upcoming_courses/comments*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-calendar"></i>
                        <span>{{ trans('lms/update.upcoming_courses') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_upcoming_courses_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/upcoming_courses', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl('/upcoming_courses') }}">{{ trans('lms/admin/main.lists') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_upcoming_courses_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/upcoming_courses/new', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl('/upcoming_courses/new') }}">{{ trans('lms/admin/main.new') }}</a>
                            </li>
                        @endif()

                    </ul>
                </li>
            @endif()

            @if($authUser->can('admin_quizzes'))
                <li class="{{ (request()->is(getAdminPanelUrl('/quizzes*', false))) ? 'active' : '' }}">
                    <a class="nav-link " href="/lms{{ getAdminPanelUrl() }}/quizzes">
                        <i class="fas fa-file"></i>
                        <span>{{ trans('lms/admin/main.quizzes') }}</span>
                    </a>
                </li>
            @endif()

            @if($authUser->can('admin_certificate'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/certificates*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-certificate"></i>
                        <span>{{ trans('lms/admin/main.certificates') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_certificate_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/certificates', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/certificates">{{ trans('lms/update.quizzes_related') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_course_certificate_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/certificates/course-competition', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/certificates/course-competition">{{ trans('lms/update.course_certificates') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_certificate_template_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/certificates/templates', false))) ? 'active' : '' }}">
                                <a class="nav-link"
                                   href="/lms{{ getAdminPanelUrl() }}/certificates/templates">{{ trans('lms/admin/main.certificates_templates') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_certificate_template_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/certificates/templates/new', false))) ? 'active' : '' }}">
                                <a class="nav-link"
                                   href="/lms{{ getAdminPanelUrl() }}/certificates/templates/new">{{ trans('lms/admin/main.new_template') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_webinar_assignments'))
                <li class="{{ (request()->is(getAdminPanelUrl('/assignments', false))) ? 'active' : '' }}">
                    <a href="/lms{{ getAdminPanelUrl() }}/assignments" class="nav-link">
                        <i class="fas fa-pen"></i>
                        <span>{{ trans('lms/update.assignments') }}</span>
                    </a>
                </li>
            @endif

            @if($authUser->can('admin_course_question_forum_list'))
                <li class="{{ (request()->is(getAdminPanelUrl('/webinars/course_forums', false))) ? 'active' : '' }}">
                    <a class="nav-link " href="/lms{{ getAdminPanelUrl() }}/webinars/course_forums">
                        <i class="fas fa-comment-alt"></i>
                        <span>{{ trans('lms/update.course_forum') }}</span>
                    </a>
                </li>
            @endif()

            @if($authUser->can('admin_course_noticeboards_list'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/course-noticeboards*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-clipboard-check"></i>
                        <span>{{ trans('lms/update.course_notices') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_course_noticeboards_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/course-noticeboards', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/course-noticeboards">{{ trans('lms/admin/main.lists') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_course_noticeboards_send'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/course-noticeboards/send', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/course-noticeboards/send">{{ trans('lms/admin/main.new') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_enrollment'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/enrollments*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-user-plus"></i>
                        <span>{{ trans('lms/update.enrollment') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_enrollment_history'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/enrollments/history', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/enrollments/history">{{ trans('lms/public.history') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_enrollment_add_student_to_items'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/enrollments/add-student-to-class', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/enrollments/add-student-to-class">{{ trans('lms/update.add_student_to_a_class') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_waitlists_lists'))
                <li class="{{ (request()->is(getAdminPanelUrl('/waitlists', false))) ? 'active' : '' }}">
                    <a href="/lms{{ getAdminPanelUrl("/waitlists") }}" class="nav-link">
                        <i class="fas fa-user-graduate"></i>
                        <span>{{ trans('lms/update.waitlists') }}</span>
                    </a>
                </li>
            @endif

            @if($authUser->can('admin_categories'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/categories*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-th"></i>
                        <span>{{ trans('lms/admin/main.categories') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_categories_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/categories', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/categories">{{ trans('lms/admin/main.lists') }}</a>
                            </li>
                        @endif()
                        @if($authUser->can('admin_categories_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/categories/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/categories/create">{{ trans('lms/admin/main.new') }}</a>
                            </li>
                        @endif()
                        @if($authUser->can('admin_trending_categories'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/categories/trends', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/categories/trends">{{ trans('lms/admin/main.trends') }}</a>
                            </li>
                        @endif()
                    </ul>
                </li>
            @endif()

            @if($authUser->can('admin_filters'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/filters*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-filter"></i>
                        <span>{{ trans('lms/admin/main.filters') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_filters_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/filters', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/filters">{{ trans('lms/admin/main.lists') }}</a>
                            </li>
                        @endif()
                        @if($authUser->can('admin_filters_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/filters/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/filters/create">{{ trans('lms/admin/main.new') }}</a>
                            </li>
                        @endif()
                    </ul>
                </li>
            @endif()

            @if($authUser->can('admin_reviews_lists'))
                <li class="{{ (request()->is(getAdminPanelUrl('/reviews', false))) ? 'active' : '' }}">
                    <a href="/lms{{ getAdminPanelUrl() }}/reviews" class="nav-link @if(!empty($sidebarBeeps['reviews']) and $sidebarBeeps['reviews']) beep beep-sidebar @endif">
                        <i class="fas fa-star"></i>
                        <span>{{ trans('lms/admin/main.reviews') }}</span>
                    </a>
                </li>
            @endif






            @if($authUser->can('admin_consultants_lists') or
                $authUser->can('admin_appointments_lists')
            )
                <li class="menu-header">{{ trans('lms/site.appointments') }}</li>
            @endif

            @if($authUser->can('admin_consultants_lists'))
                <li class="{{ (request()->is(getAdminPanelUrl('/consultants', false))) ? 'active' : '' }}">
                    <a href="/lms{{ getAdminPanelUrl() }}/consultants" class="nav-link">
                        <i class="fas fa-id-card"></i>
                        <span>{{ trans('lms/admin/main.consultants') }}</span>
                    </a>
                </li>
            @endif

            @if($authUser->can('admin_appointments_lists'))
                <li class="{{ (request()->is(getAdminPanelUrl('/appointments', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/appointments">
                        <i class="fas fa-address-book"></i>
                        <span>{{ trans('lms/admin/main.appointments') }}</span>
                    </a>
                </li>
            @endif

            @if($authUser->can('admin_users') or
                $authUser->can('admin_roles') or
                $authUser->can('admin_users_not_access_content') or
                $authUser->can('admin_group') or
                $authUser->can('admin_users_badges') or
                $authUser->can('admin_become_instructors_list') or
                $authUser->can('admin_delete_account_requests')
            )
                <li class="menu-header">{{ trans('lms/panel.users') }}</li>
            @endif

            @if($authUser->can('admin_users'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/staffs', false)) or request()->is(getAdminPanelUrl('/students', false)) or request()->is(getAdminPanelUrl('/instructors', false)) or request()->is(getAdminPanelUrl('/organizations', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-users"></i>
                        <span>{{ trans('lms/admin/main.users_list') }}</span>
                    </a>

                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_staffs_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/staffs', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/staffs">{{ trans('lms/admin/main.staff') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_users_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/students', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/students">{{ trans('lms/public.students') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_instructors_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/instructors', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/instructors">{{ trans('lms/home.instructors') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_organizations_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/organizations', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/organizations">{{ trans('lms/admin/main.organizations') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_users_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/users/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/users/create">{{ trans('lms/admin/main.new') }}</a>
                            </li>
                        @endif()
                    </ul>
                </li>
            @endif


            @if($authUser->can('admin_users_not_access_content_lists'))
                <li class="{{ (request()->is(getAdminPanelUrl('/users/not-access-to-content', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/users/not-access-to-content">
                        <i class="fas fa-user-lock"></i> <span>{{ trans('lms/update.not_access_to_content') }}</span>
                    </a>
                </li>
            @endif

            @if($authUser->can('admin_delete_account_requests'))
                <li class="nav-item {{ (request()->is(getAdminPanelUrl('/users/delete-account-requests*', false))) ? 'active' : '' }}">
                    <a href="/lms{{ getAdminPanelUrl() }}/users/delete-account-requests" class="nav-link">
                        <i class="fa fa-user-times"></i>
                        <span>{{ trans('lms/update.delete-account-requests') }}</span>
                    </a>
                </li>
            @endif

            @if($authUser->can('admin_roles'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/roles*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> <span>{{ trans('lms/admin/main.roles') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_roles_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/roles', false))) ? 'active' : '' }}">
                                <a class="nav-link active" href="/lms{{ getAdminPanelUrl() }}/roles">{{ trans('lms/admin/main.lists') }}</a>
                            </li>
                        @endif()
                        @if($authUser->can('admin_roles_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/roles/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/roles/create">{{ trans('lms/admin/main.new') }}</a>
                            </li>
                        @endif()
                    </ul>
                </li>
            @endif()

            @if($authUser->can('admin_group'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/users/groups*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-sitemap"></i>
                        <span>{{ trans('lms/admin/main.groups') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_group_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/users/groups', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/users/groups">{{ trans('lms/admin/main.lists') }}</a>
                            </li>
                        @endif
                        @if($authUser->can('admin_group_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/users/groups/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/users/groups/create">{{ trans('lms/admin/main.new') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_users_badges'))
                <li class="{{ (request()->is(getAdminPanelUrl('/users/badges', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/users/badges">
                        <i class="fas fa-trophy"></i>
                        <span>{{ trans('lms/admin/main.badges') }}</span>
                    </a>
                </li>
            @endif()



            @if($authUser->can('admin_become_instructors_list'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/users/become-instructors*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-list-alt"></i>
                        <span>{{ trans('lms/admin/main.instructor_requests') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="{{ (request()->is(getAdminPanelUrl('/users/become-instructors/instructors', false))) ? 'active' : '' }}">
                            <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/users/become-instructors/instructors">
                                <span>{{ trans('lms/admin/main.instructors') }}</span>
                            </a>
                        </li>

                        <li class="{{ (request()->is(getAdminPanelUrl('/users/become-instructors/organizations', false))) ? 'active' : '' }}">
                            <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/users/become-instructors/organizations">
                                <span>{{ trans('lms/admin/main.organizations') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif()

            @if(
                $authUser->can('admin_forum') or
                $authUser->can('admin_featured_topics')
                )
                <li class="menu-header">{{ trans('lms/update.forum') }}</li>
            @endif

            @if($authUser->can('admin_forum'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/forums*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-comment-dots"></i>
                        <span>{{ trans('lms/update.forums') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_forum_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/forums', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/forums">{{ trans('lms/admin/main.lists') }}</a>
                            </li>
                        @endif()
                        @if($authUser->can('admin_forum_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/forums/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/forums/create">{{ trans('lms/admin/main.new') }}</a>
                            </li>
                        @endif()
                    </ul>
                </li>
            @endif()

            @if($authUser->can('admin_featured_topics'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/featured-topics*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-comment"></i>
                        <span>{{ trans('lms/update.featured_topics') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_featured_topics_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/featured-topics', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/featured-topics">{{ trans('lms/admin/main.lists') }}</a>
                            </li>
                        @endif()
                        @if($authUser->can('admin_featured_topics_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/featured-topics/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/featured-topics/create">{{ trans('lms/admin/main.new') }}</a>
                            </li>
                        @endif()
                    </ul>
                </li>
            @endif()

            @if($authUser->can('admin_recommended_topics'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/recommended-topics*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-thumbs-up"></i>
                        <span>{{ trans('lms/update.recommended_topics') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_recommended_topics_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/recommended-topics', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/recommended-topics">{{ trans('lms/admin/main.lists') }}</a>
                            </li>
                        @endif()
                        @if($authUser->can('admin_recommended_topics_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/recommended-topics/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/recommended-topics/create">{{ trans('lms/admin/main.new') }}</a>
                            </li>
                        @endif()
                    </ul>
                </li>
            @endif()

            @if($authUser->can('admin_supports') or
                $authUser->can('admin_comments') or
                $authUser->can('admin_reports') or
                $authUser->can('admin_contacts') or
                $authUser->can('admin_noticeboards') or
                $authUser->can('admin_notifications')
            )
                <li class="menu-header">{{ trans('lms/admin/main.crm') }}</li>
            @endif

            @if($authUser->can('admin_supports'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/supports*', false)) and request()->get('type') != 'course_conversations') ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-headphones"></i>
                        <span>{{ trans('lms/admin/main.supports') }}</span>
                    </a>

                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_supports_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/supports', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/supports">{{ trans('lms/public.tickets') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_support_send'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/supports/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/supports/create">{{ trans('lms/admin/main.new_ticket') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_support_departments'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/supports/departments', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/supports/departments">{{ trans('lms/admin/main.departments') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>

                @if($authUser->can('admin_support_course_conversations'))
                    <li class="{{ (request()->is(getAdminPanelUrl('/supports*', false)) and request()->get('type') == 'course_conversations') ? 'active' : '' }}">
                        <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/supports?type=course_conversations">
                            <i class="fas fa-envelope-square"></i>
                            <span>{{ trans('lms/admin/main.classes_conversations') }}</span>
                        </a>
                    </li>
                @endif
            @endif

            @if($authUser->can('admin_comments'))
                <li class="nav-item dropdown {{ (!request()->is(getAdminPanelUrl('admin/comments/products, false')) and (request()->is(getAdminPanelUrl('/comments*', false)) and !request()->is(getAdminPanelUrl('/comments/webinars/reports', false)))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown"><i class="fas fa-comments"></i> <span>{{ trans('lms/admin/main.comments') }}</span></a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_webinar_comments'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/comments/webinars', false))) ? 'active' : '' }}">
                                <a class="nav-link @if(!empty($sidebarBeeps['classesComments']) and $sidebarBeeps['classesComments']) beep beep-sidebar @endif" href="/lms{{ getAdminPanelUrl() }}/comments/webinars">{{ trans('lms/admin/main.classes_comments') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_bundle_comments'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/comments/bundles', false))) ? 'active' : '' }}">
                                <a class="nav-link @if(!empty($sidebarBeeps['bundleComments']) and $sidebarBeeps['bundleComments']) beep beep-sidebar @endif" href="/lms{{ getAdminPanelUrl() }}/comments/bundles">{{ trans('lms/update.bundle_comments') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_blog_comments'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/comments/blog', false))) ? 'active' : '' }}">
                                <a class="nav-link @if(!empty($sidebarBeeps['blogComments']) and $sidebarBeeps['blogComments']) beep beep-sidebar @endif" href="/lms{{ getAdminPanelUrl() }}/comments/blog">{{ trans('lms/admin/main.blog_comments') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_product_comments'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/comments/products', false))) ? 'active' : '' }}">
                                <a class="nav-link @if(!empty($sidebarBeeps['productComments']) and $sidebarBeeps['productComments']) beep beep-sidebar @endif" href="/lms{{ getAdminPanelUrl() }}/comments/products">{{ trans('lms/update.product_comments') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_reports'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/reports*', false)) or request()->is(getAdminPanelUrl('/comments/webinars/reports', false)) or request()->is(getAdminPanelUrl('/comments/blog/reports', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown"><i class="fas fa-info-circle"></i> <span>{{ trans('lms/admin/main.reports') }}</span></a>

                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_webinar_reports'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/reports/webinars', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/reports/webinars">{{ trans('lms/panel.classes') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_webinar_comments_reports'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/comments/webinars/reports', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/comments/webinars/reports">{{ trans('lms/admin/main.classes_comments_reports') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_blog_comments_reports'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/comments/blog/reports', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/comments/blog/reports">{{ trans('lms/admin/main.blog_comments_reports') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_report_reasons'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/reports/reasons', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/reports/reasons">{{ trans('lms/admin/main.report_reasons') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_forum_topic_post_reports'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/reports/forum-topics', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/reports/forum-topics">{{ trans('lms/update.forum_topics') }}</a>
                            </li>
                        @endif()
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_contacts'))
                <li class="{{ (request()->is(getAdminPanelUrl('/contacts*', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/contacts">
                        <i class="fas fa-phone-square"></i>
                        <span>{{ trans('lms/admin/main.contacts') }}</span>
                    </a>
                </li>
            @endif

            @if($authUser->can('admin_noticeboards'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/noticeboards*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown"><i class="fas fa-sticky-note"></i> <span>{{ trans('lms/admin/main.noticeboard') }}</span></a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_noticeboards_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/noticeboards', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/noticeboards">{{ trans('lms/admin/main.lists') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_noticeboards_send'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/noticeboards/send', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/noticeboards/send">{{ trans('lms/admin/main.new') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_notifications'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/notifications*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        <span>{{ trans('lms/admin/main.notifications') }}</span>
                    </a>

                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_notifications_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/notifications', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/notifications">{{ trans('lms/public.history') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_notifications_posted_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/notifications/posted', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/notifications/posted">{{ trans('lms/admin/main.posted') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_notifications_send'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/notifications/send', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/notifications/send">{{ trans('lms/admin/main.new') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_notifications_templates'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/notifications/templates', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/notifications/templates">{{ trans('lms/admin/main.templates') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_notifications_template_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/notifications/templates/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/notifications/templates/create">{{ trans('lms/admin/main.new_template') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_blog') or
                $authUser->can('admin_pages') or
                $authUser->can('admin_additional_pages') or
                $authUser->can('admin_testimonials') or
                $authUser->can('admin_tags') or
                $authUser->can('admin_regions') or
                $authUser->can('admin_store')
            )
                <li class="menu-header">{{ trans('lms/admin/main.content') }}</li>
            @endif

            @if($authUser->can('admin_store'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/store*', false)) or request()->is(getAdminPanelUrl('/comments/products*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-store-alt"></i>
                        <span>{{ trans('lms/update.store') }}</span>
                    </a>
                    <ul class="dropdown-menu">

                        @if($authUser->can('admin_store_new_product'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/store/products/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/store/products/create">{{ trans('lms/update.new_product') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_store_products'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/store/products', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/store/products">{{ trans('lms/update.products') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_store_in_house_products'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/store/in-house-products', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/store/in-house-products">{{ trans('lms/update.in-house-products') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_store_products_orders'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/store/orders', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/store/orders">{{ trans('lms/update.orders') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_store_in_house_orders'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/store/in-house-orders', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/store/in-house-orders">{{ trans('lms/update.in-house-orders') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_store_products_sellers'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/store/sellers', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/store/sellers">{{ trans('lms/update.sellers') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_store_categories_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/store/categories', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/store/categories">{{ trans('lms/admin/main.categories') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_store_filters_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/store/filters', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/store/filters">{{ trans('lms/update.filters') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_store_specifications'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/store/specifications', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/store/specifications">{{ trans('lms/update.specifications') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_store_discounts'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/store/discounts', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/store/discounts">{{ trans('lms/admin/main.discounts') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_store_products_comments'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/comments/products*', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/comments/products">{{ trans('lms/admin/main.comments') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_products_comments_reports'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/comments/products/reports', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/comments/products/reports">{{ trans('lms/admin/main.comments_reports') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_store_products_reviews'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/store/reviews', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/store/reviews">{{ trans('lms/admin/main.reviews') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_store_settings'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/store/settings', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/store/settings">{{ trans('lms/admin/main.settings') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_blog'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/blog*', false)) and !request()->is(getAdminPanelUrl('/blog/comments', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-rss-square"></i>
                        <span>{{ trans('lms/admin/main.blog') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_blog_lists'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/blog', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/blog">{{ trans('lms/site.posts') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_blog_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/blog/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/blog/create">{{ trans('lms/admin/main.new_post') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_blog_categories'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/blog/categories', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/blog/categories">{{ trans('lms/admin/main.categories') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif()

            @if($authUser->can('admin_pages'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/pages*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-pager"></i>
                        <span>{{ trans('lms/admin/main.pages') }}</span>
                    </a>

                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_pages_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/pages', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/pages">{{ trans('lms/admin/main.lists') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_pages_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/pages/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/pages/create">{{ trans('lms/admin/main.new_page') }}</a>
                            </li>
                        @endif()
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_additional_pages'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/additional_page*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown"><i class="fas fa-plus-circle"></i> <span>{{ trans('lms/admin/main.additional_pages_title') }}</span></a>
                    <ul class="dropdown-menu">

                        @if($authUser->can('admin_additional_pages_404'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/additional_page/404', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/additional_page/404">{{ trans('lms/admin/main.error_404') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_additional_pages_contact_us'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/additional_page/contact_us', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/additional_page/contact_us">{{ trans('lms/admin/main.contact_us') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_additional_pages_footer'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/additional_page/footer', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/additional_page/footer">{{ trans('lms/admin/main.footer') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_additional_pages_navbar_links'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/additional_page/navbar_links', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/additional_page/navbar_links">{{ trans('lms/admin/main.top_navbar') }}</a>
                            </li>
                        @endif()
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_testimonials'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/testimonials*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-address-card"></i>
                        <span>{{ trans('lms/admin/main.testimonials') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_testimonials_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/testimonials', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/testimonials">{{ trans('lms/admin/main.lists') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_testimonials_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/testimonials/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/testimonials/create">{{ trans('lms/admin/main.new') }}</a>
                            </li>
                        @endif()
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_tags'))
                <li class="{{ (request()->is(getAdminPanelUrl('/tags', false))) ? 'active' : '' }}">
                    <a href="/lms{{ getAdminPanelUrl() }}/tags" class="nav-link">
                        <i class="fas fa-tags"></i>
                        <span>{{ trans('lms/admin/main.tags') }}</span>
                    </a>
                </li>
            @endif()

            @if($authUser->can('admin_regions'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/regions*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-map-marked"></i>
                        <span>{{ trans('lms/update.regions') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_regions_countries'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/regions/countries', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/regions/countries">{{ trans('lms/update.countries') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_regions_provinces'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/regions/provinces', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/regions/provinces">{{ trans('lms/update.provinces') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_regions_cities'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/regions/cities', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/regions/cities">{{ trans('lms/update.cities') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_regions_districts'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/regions/districts', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/regions/districts">{{ trans('lms/update.districts') }}</a>
                            </li>
                        @endif()
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_documents') or
                $authUser->can('admin_sales_list') or
                $authUser->can('admin_payouts') or
                $authUser->can('admin_offline_payments_list') or
                $authUser->can('admin_subscribe') or
                $authUser->can('admin_registration_packages') or
                $authUser->can('admin_installments')
            )
                <li class="menu-header">{{ trans('lms/admin/main.financial') }}</li>
            @endif

            @if($authUser->can('admin_documents'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/financial/documents*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>{{ trans('lms/admin/main.balances') }}</span>
                    </a>
                    <ul class="dropdown-menu">

                        @if($authUser->can('admin_documents_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/documents', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/financial/documents">{{ trans('lms/admin/main.list') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_documents_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/documents/new', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/financial/documents/new">{{ trans('lms/admin/main.new') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_sales_list'))
                <li class="{{ (request()->is(getAdminPanelUrl('/financial/sales*', false))) ? 'active' : '' }}">
                    <a href="/lms{{ getAdminPanelUrl() }}/financial/sales" class="nav-link">
                        <i class="fas fa-list-ul"></i>
                        <span>{{ trans('lms/admin/main.sales_list') }}</span>
                    </a>
                </li>
            @endif

            @if($authUser->can('admin_payouts'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/financial/payouts*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown"><i class="fas fa-credit-card"></i> <span>{{ trans('lms/admin/main.payout') }}</span></a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_payouts_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/payouts', false)) and request()->get('payout') == 'requests') ? 'active' : '' }}">
                                <a href="/lms{{ getAdminPanelUrl() }}/financial/payouts?payout=requests" class="nav-link @if(!empty($sidebarBeeps['payoutRequest']) and $sidebarBeeps['payoutRequest']) beep beep-sidebar @endif">
                                    <span>{{ trans('lms/panel.requests') }}</span>
                                </a>
                            </li>
                        @endif

                        @if($authUser->can('admin_payouts_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/payouts', false)) and request()->get('payout') == 'history') ? 'active' : '' }}">
                                <a href="/lms{{ getAdminPanelUrl() }}/financial/payouts?payout=history" class="nav-link">
                                    <span>{{ trans('lms/public.history') }}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_offline_payments_list'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/financial/offline_payments*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown"><i class="fas fa-university"></i> <span>{{ trans('lms/admin/main.offline_payments') }}</span></a>
                    <ul class="dropdown-menu">
                        <li class="{{ (request()->is(getAdminPanelUrl('/financial/offline_payments', false)) and request()->get('page_type') == 'requests') ? 'active' : '' }}">
                            <a href="/lms{{ getAdminPanelUrl() }}/financial/offline_payments?page_type=requests" class="nav-link @if(!empty($sidebarBeeps['offlinePayments']) and $sidebarBeeps['offlinePayments']) beep beep-sidebar @endif">
                                <span>{{ trans('lms/panel.requests') }}</span>
                            </a>
                        </li>

                        <li class="{{ (request()->is(getAdminPanelUrl('/financial/offline_payments', false)) and request()->get('page_type') == 'history') ? 'active' : '' }}">
                            <a href="/lms{{ getAdminPanelUrl() }}/financial/offline_payments?page_type=history" class="nav-link">
                                <span>{{ trans('lms/public.history') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_subscribe'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/financial/subscribes*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-cart-plus"></i>
                        <span>{{ trans('lms/admin/main.subscribes') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_subscribe_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/subscribes', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/financial/subscribes">{{ trans('lms/admin/main.packages') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_subscribe_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/subscribes/new', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/financial/subscribes/new">{{ trans('lms/admin/main.new_package') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif


            @if($authUser->can('admin_rewards'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/rewards*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fa fa-gift"></i>
                        <span>{{ trans('lms/update.rewards') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_rewards_history'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/rewards', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/rewards">{{ trans('lms/public.history') }}</a>
                            </li>
                        @endif
                        @if($authUser->can('admin_rewards_items'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/rewards/items', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/rewards/items">{{ trans('lms/update.conditions') }}</a>
                            </li>
                        @endif
                        @if($authUser->can('admin_rewards_settings'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/rewards/settings', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/rewards/settings">{{ trans('lms/admin/main.settings') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_registration_packages'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/financial/registration-packages*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fa fa-gem"></i>
                        <span>{{ trans('lms/update.registration_packages') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_registration_packages_lists'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/registration-packages', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/financial/registration-packages">{{ trans('lms/admin/main.packages') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_registration_packages_new'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/registration-packages/new', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/financial/registration-packages/new">{{ trans('lms/admin/main.new_package') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_registration_packages_reports'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/registration-packages/reports', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/financial/registration-packages/reports">{{ trans('lms/admin/main.reports') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_registration_packages_settings'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/registration-packages/settings', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/financial/registration-packages/settings">{{ trans('lms/admin/main.settings') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_installments'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/financial/installments*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fa fa-money-bill-wave"></i>
                        <span>{{ trans('lms/update.installments') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_installments_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/installments/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl('/financial/installments/create') }}">{{ trans('lms/update.new_plan') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_installments_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/installments', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl('/financial/installments') }}">{{ trans('lms/update.plans') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_installments_purchases'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/installments/purchases', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl('/financial/installments/purchases') }}">{{ trans('lms/update.purchases') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_installments_overdue_lists'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/installments/overdue', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl('/financial/installments/overdue') }}">{{ trans('lms/update.overdue') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_installments_overdue_history'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/installments/overdue_history', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl('/financial/installments/overdue_history') }}">{{ trans('lms/update.overdue_history') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_installments_verification_requests'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/installments/verification_requests', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl('/financial/installments/verification_requests') }}">{{ trans('lms/update.verification_requests') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_installments_verified_users'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/installments/verified_users', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl('/financial/installments/verified_users') }}">{{ trans('lms/update.verified_users') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_installments_settings'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/installments/settings', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl('/financial/installments/settings') }}">{{ trans('lms/admin/main.settings') }}</a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_discount_codes') or
                $authUser->can('admin_product_discount') or
                $authUser->can('admin_feature_webinars') or
                $authUser->can('admin_gift') or
                $authUser->can('admin_promotion') or
                $authUser->can('admin_advertising') or
                $authUser->can('admin_newsletters') or
                $authUser->can('admin_advertising_modal') or
                $authUser->can('admin_registration_bonus') or
                $authUser->can('admin_floating_bar_create')
            )
                <li class="menu-header">{{ trans('lms/admin/main.marketing') }}</li>
            @endif

            @if($authUser->can('admin_discount_codes'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/financial/discounts*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-percent"></i>
                        <span>{{ trans('lms/admin/main.discount_codes') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_discount_codes_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/discounts', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/financial/discounts">{{ trans('lms/admin/main.lists') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_discount_codes_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/discounts/new', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/financial/discounts/new">{{ trans('lms/admin/main.new') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_product_discount'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/financial/special_offers*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fa fa-fire"></i>
                        <span>{{ trans('lms/admin/main.special_offers') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_product_discount_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/special_offers', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/financial/special_offers">{{ trans('lms/admin/main.lists') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_product_discount_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/special_offers/new', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/financial/special_offers/new">{{ trans('lms/admin/main.new') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_feature_webinars'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/webinars/features*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-star"></i>
                        <span>{{ trans('lms/admin/main.feature_webinars') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_feature_webinars'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/webinars/features', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/webinars/features">{{ trans('lms/admin/main.lists') }}</a>
                            </li>
                        @endif()

                        @if($authUser->can('admin_feature_webinars_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/webinars/features/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/webinars/features/create">{{ trans('lms/admin/main.new') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_cashback'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/cashback*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-wallet"></i>
                        <span>{{ trans('lms/update.cashback') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_cashback_rules'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/cashback/rules/new', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl('/cashback/rules/new') }}">{{ trans('lms/update.new_rule') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_cashback_rules'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/cashback/rules', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl('/cashback/rules') }}">{{ trans('lms/update.rules') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_cashback_transactions'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/cashback/transactions', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl('/cashback/transactions') }}">{{ trans('lms/update.transactions') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_cashback_history'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/cashback/history', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl('/cashback/history') }}">{{ trans('lms/update.history') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_gift'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/gifts*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-gift"></i>
                        <span>{{ trans('lms/update.gifts') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_gift_history'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/gifts', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl("/gifts") }}">{{ trans('lms/public.history') }}</a>
                            </li>
                        @endif
                        @if($authUser->can('admin_gift_settings'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/gifts/settings', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl("/gifts/settings") }}">{{ trans('lms/admin/main.settings') }}</a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_promotion'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/financial/promotions*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-rocket"></i>
                        <span>{{ trans('lms/admin/main.promotions') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_promotion_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/promotions', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/financial/promotions">{{ trans('lms/admin/main.plans') }}</a>
                            </li>
                        @endif
                        @if($authUser->can('admin_promotion_list'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/promotions/sales', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/financial/promotions/sales">{{ trans('lms/admin/main.promotion_sales') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_promotion_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/promotions/new', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/financial/promotions/new">{{ trans('lms/admin/main.new_plan') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_advertising'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/advertising*', false)) and !request()->is(getAdminPanelUrl('/advertising_modal*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-file-image"></i>
                        <span>{{ trans('lms/admin/main.ad_banners') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_advertising_banners'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/advertising/banners', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/advertising/banners">{{ trans('lms/admin/main.list') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_advertising_banners_create'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/advertising/banners/new', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/advertising/banners/new">{{ trans('lms/admin/main.new') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_newsletters'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/newsletters*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-newspaper"></i>
                        <span>{{ trans('lms/admin/main.newsletters') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_newsletters_lists'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/newsletters', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/newsletters">{{ trans('lms/admin/main.list') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_newsletters_send'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/newsletters/send', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/newsletters/send">{{ trans('lms/admin/main.send') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_newsletters_history'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/newsletters/history', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/newsletters/history">{{ trans('lms/public.history') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_referrals'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/referrals*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fa fa-bullhorn"></i>
                        <span>{{ trans('lms/panel.affiliate') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($authUser->can('admin_referrals_history'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/referrals/history', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/referrals/history">{{ trans('lms/public.history') }}</a>
                            </li>
                        @endif

                        @if($authUser->can('admin_referrals_users'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/referrals/users', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/referrals/users">{{ trans('lms/admin/main.affiliate_users') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_registration_bonus'))
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/registration_bonus*', false))) ? 'active' : '' }}">
                    <a href="/lms#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fa fa-gem"></i>
                        <span>{{ trans('lms/update.registration_bonus') }}</span>
                    </a>
                    <ul class="dropdown-menu">

                        @if($authUser->can('admin_registration_bonus_history'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/registration_bonus/history', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl('/registration_bonus/history') }}">{{ trans('lms/update.bonus_history') }}</a>
                            </li>
                        @endif


                        @if($authUser->can('admin_registration_bonus_settings'))
                            <li class="{{ (request()->is(getAdminPanelUrl('/registration_bonus/settings', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="/lms{{ getAdminPanelUrl('/registration_bonus/settings') }}">{{ trans('lms/admin/main.settings') }}</a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif

            @if($authUser->can('admin_advertising_modal_config'))
                <li class="nav-item {{ (request()->is(getAdminPanelUrl('/advertising_modal*', false))) ? 'active' : '' }}">
                    <a href="/lms{{ getAdminPanelUrl() }}/advertising_modal" class="nav-link">
                        <i class="fa fa-ad"></i>
                        <span>{{ trans('lms/update.advertising_modal') }}</span>
                    </a>
                </li>
            @endif

            @if($authUser->can('admin_floating_bar_create'))
                <li class="nav-item {{ (request()->is(getAdminPanelUrl('/floating_bars*', false))) ? 'active' : '' }}">
                    <a href="/lms{{ getAdminPanelUrl() }}/floating_bars" class="nav-link">
                        <i class="fa fa-pager"></i>
                        <span>{{ trans('lms/update.top_bottom_bar') }}</span>
                    </a>
                </li>
            @endif

            @if($authUser->can('admin_settings')))
                <li class="menu-header">{{ trans('lms/admin/main.settings') }}</li>
            @endif

            @if($authUser->can('admin_settings'))
                @php
                    $settingClass ='';

                    if (request()->is(getAdminPanelUrl('/settings*', false)) and
                            !(
                                request()->is(getAdminPanelUrl('/settings/404', false)) or
                                request()->is(getAdminPanelUrl('/settings/contact_us', false)) or
                                request()->is(getAdminPanelUrl('/settings/footer', false)) or
                                request()->is(getAdminPanelUrl('/settings/navbar_links', false))
                            )
                        ) {
                            $settingClass = 'active';
                        }
                @endphp

                <li class="nav-item {{ $settingClass ?? '' }}">
                    <a href="/lms{{ getAdminPanelUrl() }}/settings" class="nav-link">
                        <i class="fas fa-cogs"></i>
                        <span>{{ trans('lms/admin/main.settings') }}</span>
                    </a>
                </li>
            @endif()


            <li>
                <a class="nav-link" href="/lms{{ getAdminPanelUrl() }}/logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>{{ trans('lms/admin/main.logout') }}</span>
                </a>
            </li>

        </ul>
        <br><br><br>
    </aside>
</div>
