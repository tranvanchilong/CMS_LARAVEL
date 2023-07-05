@extends('lms.admin.layouts.app')

@push('libraries_top')


@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{!empty($tag) ?trans('lms//admin/main.edit'): trans('lms/admin/main.new') }} {{ trans('lms/admin/main.tag') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{ trans('lms/admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}/tags">{{ trans('lms/admin/main.tags') }}</a>
                </div>
                <div
                    class="breadcrumb-item">{{!empty($tag) ?trans('lms//admin/main.edit'): trans('lms/admin/main.new') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="/lms{{ getAdminPanelUrl() }}/tags/{{ !empty($tag) ? $tag->id.'/update' : 'store' }}"
                                  method="Post">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label>{{ trans('lms//admin/main.title') }}</label>
                                    <input type="text" name="title"
                                           class="form-control  @error('title') is-invalid @enderror"
                                           value="{{ !empty($tag) ? $tag->title : old('title') }}"
                                           placeholder="{{ trans('lms/admin/main.create_field_title_placeholder') }}"/>
                                    @error('title')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class=" mt-4">
                                    <button class="btn btn-primary">{{ trans('lms/admin/main.submit') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
