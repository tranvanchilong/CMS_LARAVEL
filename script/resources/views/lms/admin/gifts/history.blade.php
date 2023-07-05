@extends('lms.admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/lms{{ getAdminPanelUrl() }}">{{ trans('lms/admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    {{ $pageTitle }}
                </div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-gift"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('lms/update.total_gifts')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalGifts }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-money-bill"></i>
                        </div>

                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('lms/update.total_gift_amount')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ handlePrice($totalGiftAmount) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-square-arrow-up-right"></i></div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('lms/update.total_senders')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalSenders }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-square-arrow-down-left"></i></div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('lms/update.total_receipts')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalReceipts }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <section class="card">
                <div class="card-body">
                    <form method="get" class="mb-0">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('lms/admin/main.search')}}</label>
                                    <input name="search" type="text" class="form-control" value="{{ request()->get('search') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('lms/admin/main.start_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="from" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('lms/admin/main.end_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="to" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                    </div>
                                </div>
                            </div>


                            @php
                                $filters = ['amount_asc', 'amount_desc', 'submit_date_asc', 'submit_date_desc', 'receive_date_asc', 'receive_date_desc'];
                            @endphp
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('lms/admin/main.filters')}}</label>
                                    <select name="sort" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('lms/admin/main.all')}}</option>

                                        @foreach($filters as $filter)
                                            <option value="{{ $filter }}" @if(request()->get('sort') == $filter) selected @endif>{{trans('lms/update.'.$filter)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('lms/admin/main.user')}}</label>
                                    <select name="user_ids[]" multiple="multiple" class="form-control search-user-select2"
                                            data-placeholder="Search users">

                                        @if(!empty($selectedUsers) and $selectedUsers->count() > 0)
                                            @foreach($selectedUsers as $user)
                                                <option value="{{ $user->id }}" selected>{{ $user->full_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('lms/update.receipt_status')}}</label>
                                    <select name="receipt_status" class="form-control">
                                        <option value="">{{ trans('lms/admin/main.all') }}</option>
                                        <option value="registered" {{ request()->get('receipt_status') == "registered" ? 'selected' : '' }}>{{ trans('lms/update.registered') }}</option>
                                        <option value="unregistered" {{ request()->get('receipt_status') == "unregistered" ? 'selected' : '' }}>{{ trans('lms/update.unregistered') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('lms/update.gift_status')}}</label>
                                    <select name="gift_status" class="form-control">
                                        <option value="">{{ trans('lms/admin/main.all') }}</option>
                                        <option value="pending" {{ request()->get('gift_status') == "pending" ? 'selected' : '' }}>{{ trans('lms/admin/main.pending') }}</option>
                                        <option value="sent" {{ request()->get('gift_status') == "sent" ? 'selected' : '' }}>{{ trans('lms/update.sent') }}</option>
                                        <option value="canceled" {{ request()->get('gift_status') == "canceled" ? 'selected' : '' }}>{{ trans('lms/public.canceled') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mt-1">
                                    <label class="input-label mb-4"> </label>
                                    <input type="submit" class="text-center btn btn-primary w-100" value="{{trans('lms/admin/main.show_results')}}">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

            {{-- Lists --}}
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            @if($authUser->can('admin_gift_export'))
                                <div class="text-right">
                                    <a href="/lms{{ getAdminPanelUrl('/gifts/excel?'. http_build_query(request()->all())) }}" class="btn btn-primary">{{ trans('lms/admin/main.export_xls') }}</a>
                                </div>
                            @endif
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14 ">
                                    <tr>
                                        <th class="text-left">{{ trans('lms/admin/main.title') }}</th>
                                        <th class="text-left">{{ trans('lms/admin/main.sender') }}</th>
                                        <th>{{ trans('lms/update.receipt') }}</th>
                                        <th>{{ trans('lms/update.receipt_status') }}</th>
                                        <th>{{ trans('lms/update.gift_message') }}</th>
                                        <th>{{ trans('lms/admin/main.amount') }}</th>
                                        <th>{{ trans('lms/update.submit_date') }}</th>
                                        <th>{{ trans('lms/update.receive_date') }}</th>
                                        <th>{{ trans('lms/update.gift_status') }}</th>
                                        <th width="120">{{ trans('lms/admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($gifts as $gift)
                                        <tr class="text-center">

                                            <td class="text-left">
                                                {{ $gift->getItemTitle() }}
                                            </td>

                                            <td class="text-left">
                                                <div class="mt-0 mb-1 font-weight-bold">{{ $gift->user->full_name }}</div>

                                                @if($gift->user->mobile)
                                                    <div class="text-primary text-small font-600-bold">{{ $gift->user->mobile }}</div>
                                                @endif

                                                @if($gift->user->email)
                                                    <div class="text-primary text-small font-600-bold">{{ $gift->user->email }}</div>
                                                @endif
                                            </td>

                                            <td>
                                                @if(!empty($gift->receipt))
                                                    <div class="mt-0 mb-1 font-weight-bold">{{ $gift->receipt->full_name }}</div>
                                                @else
                                                    <div class="mt-0 mb-1 font-weight-bold">{{ $gift->name }}</div>
                                                @endif
                                                <div class="text-primary text-small font-600-bold">{{ $gift->email }}</div>
                                            </td>

                                            <td class="">
                                                <span class="">{{ $gift->receipt_status ? trans('lms/update.registered') : trans('lms/update.unregistered') }}</span>
                                            </td>

                                            <td class="">
                                                <div class="d-flex">
                                                    <button type="button" class="js-show-gift-message btn btn-outline-primary">{{ trans('lms/update.message') }}</button>
                                                    <input type="hidden" value="{{ nl2br($gift->description) }}">
                                                </div>
                                            </td>

                                            <td class="">
                                                @if(!empty($gift->sale) and $gift->sale->total_amount > 0)
                                                    {{ handlePrice($gift->sale->total_amount) }}
                                                @else
                                                    {{ trans('lms/admin/main.free') }}
                                                @endif
                                            </td>

                                            <td class="">
                                                {{ dateTimeFormat($gift->created_at, 'j M Y H:i') }}
                                            </td>

                                            <td class="">
                                                @if(!empty($gift->date))
                                                    {{ dateTimeFormat($gift->date, 'j M Y H:i') }}
                                                @else
                                                    {{ trans('lms/update.instantly') }}
                                                @endif
                                            </td>

                                            <td class="">
                                                @if(!empty($gift->date) and $gift->date > time())
                                                    <span class="text-warning">{{ trans('lms/admin/main.pending') }}</span>
                                                @elseif($gift->status == 'cancel')
                                                    <span class="text-danger">{{ trans('lms/admin/main.pending') }}</span>
                                                @else
                                                    <span class="text-success">{{ trans('lms/update.sent') }}</span>
                                                @endif
                                            </td>

                                            <td class="text-center mb-2" width="120">

                                                @if($gift->status != 'cancel')
                                                    <div class="d-flex align-items-center justify-content-end">
                                                        @if(empty($gift->date) or $gift->date < time())
                                                            @if($authUser->can('admin_gift_send_reminder'))
                                                                @include('lms.admin.includes.delete_button',[
                                                                        'url' => '/lms'.getAdminPanelUrl("/gifts/{$gift->id}/send_reminder"),
                                                                        'btnClass' => 'text-primary btn-transparent',
                                                                        'tooltip' => trans('lms/admin/main.send_reminder'),
                                                                        'btnIcon' => 'fa-paper-plane'
                                                                        ])
                                                            @endif
                                                        @endif


                                                        @if($authUser->can('admin_gift_cancel'))
                                                            @include('lms.admin.includes.delete_button',[
                                                                        'url' => '/lms'.getAdminPanelUrl("/gifts/{$gift->id}/cancel"),
                                                                        'btnClass' => 'text-danger btn-transparent ml-2',
                                                                        'tooltip' => trans('lms/admin/main.cancel'),
                                                                        'btnIcon' => 'fa-times'
                                                                        ])
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $gifts->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="giftMessage" tabindex="-1" aria-labelledby="giftMessageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="giftMessageLabel">{{ trans('lms/admin/main.message') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('lms/admin/main.close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/lms/assets/default/js/admin/gifts.min.js"></script>
@endpush
