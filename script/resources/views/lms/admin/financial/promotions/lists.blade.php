@extends('lms.admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('lms/admin/main.promotions') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{trans('lms/admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('lms/admin/main.promotions') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th></th>
                                        <th>{{ trans('lms/admin/main.title') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.sale_count') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.price') }}</th>
                                        <th class="text-center">{{ trans('lms/public.days') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.is_popular') }}</th>
                                        <th class="text-center">{{ trans('lms/admin/main.created_at') }}</th>
                                        <th>{{ trans('lms/admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($promotions as $promotion)
                                        <tr>
                                            <td>
                                                <img src="{{get_path_lms()}}{{ $promotion->icon }}" width="50" height="50" alt="">
                                            </td>
                                            <td>{{ $promotion->title }}</td>
                                            <td class="text-center">{{ $promotion->sales->count() }}</td>
                                            <td class="text-center">{{ handlePrice($promotion->price) }}</td>
                                            <td class="text-center">{{ $promotion->days }} {{ trans('lms/public.day') }}</td>
                                            <td class="text-center">
                                                @if($promotion->is_popular)
                                                    <span class="fas fa-check text-success"></span>
                                                @else
                                                    <span class="fas fa-times text-danger"></span>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ dateTimeFormat($promotion->created_at, 'Y M j | H:i') }}</td>
                                            <td>
                                                @if($authUser->can('admin_promotion_edit'))
                                                    <a href="/lms{{ getAdminPanelUrl() }}/financial/promotions/{{ $promotion->id }}/edit" class="btn-sm btn-transparent text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.edit') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if($authUser->can('admin_promotion_delete'))
                                                    @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/financial/promotions/'. $promotion->id.'/delete','btnClass' => ''])
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $promotions->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="card">
        <div class="card-body">
            <div class="section-title ml-0 mt-0 mb-3"><h5>{{trans('lms/admin/main.hints')}}</h5></div>
            <div class="row">
                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('lms/admin/main.promotions_list_hint_title_1')}}</div>
                        <div class=" text-small font-600-bold mb-2">{{trans('lms/admin/main.promotions_list_hint_description_1')}}</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('lms/admin/main.promotions_list_hint_title_2')}}</div>
                        <div class=" text-small font-600-bold mb-2">{{trans('lms/admin/main.promotions_list_hint_description_2')}}</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('lms/admin/main.promotions_list_hint_title_3')}}</div>
                        <div class=" text-small font-600-bold mb-2">{{trans('lms/admin/main.promotions_list_hint_description_3')}}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

