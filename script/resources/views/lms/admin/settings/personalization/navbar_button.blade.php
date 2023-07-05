<div class=" mt-3">
    <div class="row">
        <div class="col-12 col-md-6">
            <form action="/lms{{ getAdminPanelUrl() }}/settings/personalization/navbar_button" method="post">
                {{ csrf_field() }}
                @if(!empty($navbarButton))
                    <input type="hidden" name="item_id" value="{{ $navbarButton->id }}">
                @endif


                @if(!empty(getGeneralSettings('content_translate')))
                    <div class="form-group">
                        <label class="input-label">{{ trans('lms/auth.language') }}</label>
                        <select name="locale" class="form-control {{ !empty($navbarButton) ? 'js-edit-content-locale' : '' }}">
                            @foreach($userLanguages as $lang => $language)
                                <option value="{{ $lang }}" @if(mb_strtolower(!empty($selectedLocale) ? $selectedLocale : app()->getLocale()) == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
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
                    <label>{{ trans('lms//admin/main.role_name') }}</label>

                    @if(!empty($navbarButton) and $navbarButton->role_id)
                        <input type="hidden" name="role_id" value="{{ $navbarButton->role_id }}">
                    @endif

                    <select class="form-control @error('role_id') is-invalid @enderror" name="role_id" {{ (!empty($navbarButton) and $navbarButton->role_id) ? 'disabled' : '' }}>
                        <option disabled selected>{{ trans('lms/admin/main.select_role') }}</option>
                        <option value="for_guest" {{ (!empty($navbarButton) and $navbarButton->for_guest) ? 'selected' :''}}>{{ trans('lms/update.guest') }}</option>

                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ (!empty($navbarButton) and !empty($navbarButton->role_id) and $navbarButton->role_id == $role->id) ? 'selected' :''}}>{{ $role->caption }}</option>
                        @endforeach
                    </select>
                    @error('role_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>


                <div class="form-group">
                    <label>{{ trans('lms/admin/main.title') }}</label>
                    <input type="text" name="title" value="{{ (!empty($navbarButton) and !empty($navbarButton->title)) ? $navbarButton->title : old('title') }}" class="form-control @error('title') is-invalid @enderror"/>
                    @error('title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>{{ trans('lms/admin/main.Url') }}</label>
                    <input type="text" name="url" value="{{ (!empty($navbarButton) and !empty($navbarButton->url)) ? $navbarButton->url : old('url') }}" class="form-control @error('url') is-invalid @enderror"/>
                    @error('url')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">{{ trans('lms/admin/main.save_change') }}</button>
            </form>
        </div>
    </div>

    @if(!empty($navbarButtons))
        <div class="table-responsive mt-5">
            <table class="table table-striped font-14">
                <tr>
                    <th>{{ trans('lms/admin/main.title') }}</th>
                    <th>{{ trans('lms/admin/main.Url') }}</th>
                    <th>{{ trans('lms/admin/main.role') }}</th>
                    <th>{{ trans('lms/admin/main.actions') }}</th>
                </tr>

                @foreach($navbarButtons as $key => $row)
                    <tr>
                        <td>{{ $row->title }}</td>
                        <td>{{ $row->url }}</td>
                        <td>
                            @if($row->for_guest)
                                {{ trans('lms/update.guest') }}
                            @else
                                {{ !empty($row->role) ? $row->role->caption : '' }}
                            @endif
                        </td>
                        <td>
                            @if($authUser->can('admin_settings_personalization'))
                                <a href="/lms{{ getAdminPanelUrl() }}/settings/personalization/navbar_button/{{ $row->id }}/edit" class="btn-sm" data-toggle="tooltip" data-placement="top" title="{{ trans('lms/admin/main.edit') }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            @endif

                            @if($authUser->can('admin_settings_personalization'))
                                @include('lms.admin.includes.delete_button',['url' => '/lms'.getAdminPanelUrl().'/settings/personalization/navbar_button/'. $row->id .'/delete' , 'btnClass' => 'btn-sm'])
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    @endif
</div>
