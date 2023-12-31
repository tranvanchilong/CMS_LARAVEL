@extends('lms.admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('lms/admin/main.support_departments') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{trans('lms/admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('lms/admin/main.departments') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body col-12">
                    <div class="tabs">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#list" data-toggle="tab"> {{ trans('lms/admin/main.departments') }} </a></li>
                            <li class="nav-item"><a class="nav-link" href="#newitem" data-toggle="tab">{{ trans('lms/admin/main.new_department') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="list" class="tab-pane active">
                                <div class="table-responsive">
                                    <table class="table table-striped font-14">

                                        <tr>
                                            <th>{{ trans('lms/admin/main.department') }}</th>
                                            <th class="text-center" width="200">{{ trans('lms/admin/main.conversations') }}</th>
                                            <th class="text-center" width="100">{{ trans('lms/admin/main.actions') }}</th>
                                        </tr>

                                        @foreach($departments as $department)
                                            <tr>
                                                <td>
                                                    <span>{{ $department->title }}</span>
                                                </td>

                                                <td>{{ $department->supports_count }}</td>

                                                <td class="text-center">
                                                    @if($authUser->can('admin_support_departments_edit'))
                                                        <a href="/lms{{ getAdminPanelUrl() }}/supports/departments/{{ $department->id }}/edit" class="btn-transparent text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.edit') }}">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    @if($authUser->can('admin_support_departments_delete'))
                                                        @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/supports/departments/'. $department->id.'/delete','btnClass' => ''])
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                    </table>
                                </div>

                                <div class="text-center mt-2">
                                    {{ $departments->appends(request()->input())->links() }}
                                </div>
                            </div>

                            <div id="newitem" class="tab-pane ">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <form action="/lms{{ getAdminPanelUrl() }}/supports/departments/store"
                                              method="Post">
                                            {{ csrf_field() }}

                                           @if(!empty(getGeneralSettings('content_translate')))
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('lms/auth.language') }}</label>
                                        <select name="locale" class="form-control {{ !empty($department) ? 'js-edit-content-locale' : '' }}">
                                            @foreach($userLanguages as $lang => $language)
                                                <option value="{{ $lang }}" @if(mb_strtolower(request()->get('locale', app()->getLocale())) == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
                                            @endforeach
                                        </select>
                                        @error('locale')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                @else
                                    <input type="hidden" name="locale" value="{{ getDefaultLocale() }}">
                                @endif


                                            <div class="form-group">
                                                <label>{{ trans('lms/admin/main.title') }}</label>
                                                <input type="text" name="title"
                                                       class="form-control  @error('title') is-invalid @enderror"
                                                       value="{{ old('title') }}"/>
                                                @error('title')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="text-right mt-4">
                                                <button class="btn btn-primary">{{ trans('lms/admin/main.submit') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
