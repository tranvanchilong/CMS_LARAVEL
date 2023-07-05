<div class="tab-pane mt-3 fade" id="topics" role="tabpanel" aria-labelledby="topics-tab">
    <div class="row">

        <div class="col-12">
            <h5 class="section-title after-line">{{ trans('lms/update.forum_topics') }}</h5>

            <div class="table-responsive mt-5">
                <table class="table table-striped table-md">
                    <tr>
                        <th>{{ trans('lms/public.topic') }}</th>
                        <th>{{ trans('lms/admin/main.category') }}</th>
                        <th>{{ trans('lms/site.posts') }}</th>
                        <th>{{ trans('lms/admin/main.created_at') }}</th>
                        <th>{{ trans('lms/admin/main.updated_at') }}</th>
                        <th class="text-right">{{ trans('lms/admin/main.actions') }}</th>
                    </tr>

                    @if(!empty($topics))
                        @foreach($topics as $topic)

                            <tr>
                                <td width="25%">
                                    <a href="/lms{{ $topic->getPostsUrl() }}" target="_blank" class="">{{ $topic->title }}</a>
                                </td>

                                <td>
                                    {{ $topic->forum->title }}
                                </td>
                                <td>{{ $topic->posts_count }}</td>
                                <td class="text-center">{{ dateTimeFormat($topic->created_at,'j M Y | H:i') }}</td>
                                <td class="text-center">{{ (!empty($topic->posts) and count($topic->posts)) ? dateTimeFormat($topic->posts->first()->created_at,'j M Y | H:i') : '-' }}</td>
                                <td class="text-right">

                                    @if($authUser->can('admin_forum_topics_lists'))
                                        @if(!$topic->close)
                                            @include('lms.admin.includes.delete_button',[
                                                'url' => "/admin/forums/{$topic->forum_id}/topics/{$topic->id}/close",
                                                'tooltip' => trans('lms/public.close'),
                                                'btnClass' => 'mr-1',
                                                'btnIcon' => 'fa-lock'
                                            ])
                                        @else
                                            @include('lms.admin.includes.delete_button',[
                                                'url' => "/admin/forums/{$topic->forum_id}/topics/{$topic->id}/open",
                                                'tooltip' => trans('lms/public.open'),
                                                'btnClass' => 'mr-1',
                                                'btnIcon' => 'fa-unlock'
                                            ])
                                        @endif
                                    @endif

                                    @if($authUser->can('admin_forum_topics_posts'))
                                        <a href="/lms{{ getAdminPanelUrl() }}/forums/{{ $topic->forum_id }}/topics/{{ $topic->id }}/posts"
                                           class="btn-transparent btn-sm text-primary mr-1"
                                           data-toggle="tooltip" data-placement="top" title="{{ trans('lms/site.posts') }}"
                                        >
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @endif

                                    @if($authUser->can('admin_enrollment_block_access'))
                                        @include('lms.admin.includes.delete_button',[
                                                'url' => "/admin/forums/{$topic->forum_id}/topics/{$topic->id}/delete?no_redirect=true",
                                            ])
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
