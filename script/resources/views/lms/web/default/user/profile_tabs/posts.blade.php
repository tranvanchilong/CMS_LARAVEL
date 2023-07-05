@if(!empty($user->blog) and !$user->blog->isEmpty())
    <div class="row">

        @foreach($user->blog as $post)
            <div class="col-12 col-md-4">
                <div class="mt-30">
                    @include('lms.web.default.blog.grid-list',['post' => $post])
                </div>
            </div>
        @endforeach
    </div>
@else
    @include('lms.' . getTemplate() . '.includes.no-result',[
        'file_name' => 'webinar.png',
        'title' => trans('lms/update.instructor_not_have_posts'),
        'hint' => '',
    ])
@endif

