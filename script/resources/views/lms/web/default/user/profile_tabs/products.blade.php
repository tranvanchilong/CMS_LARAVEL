@if(!empty($user->products) and !$user->products->isEmpty())
    <div class="row">

        @foreach($user->products as $product)
            <div class="col-12 col-md-6 col-lg-4 mt-20">
                @include('lms.web.default.products.includes.card')
            </div>
        @endforeach
    </div>
@else
    @include('lms.' . getTemplate() . '.includes.no-result',[
        'file_name' => 'webinar.png',
        'title' => trans('lms/update.instructor_not_have_products'),
        'hint' => '',
    ])
@endif

