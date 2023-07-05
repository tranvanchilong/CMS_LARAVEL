@extends('frontend.norda.account.affiliate_layout')
@section('affiliate_content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="form-box-content p-3">
                    <div class="form-group">
                        <textarea id="referral_code_url" class="form-control" readonly="" type="text">{{ url('/user/register') . '?referral_code=' . $referral_code }}</textarea>
                    </div>
                    <button type="button" id="ref-cpurl-btn" class="btn btn-primary float-right" data-attrcpy="Copied"
                        onclick="copyToClipboard('url')">Copy
                        Url</button>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="card overflow-hidden">
        {{-- <form class="" id="sort_blogs" action="" method="GET"> --}}
        <div class="card-header row">
            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6">{{ __('Affiliate Stats') }}</h5>
            </div>
            {{-- <div class="col-md-5 col-xl-4">
                <div class="input-group mb-0">
                    <div class="dropdown bootstrap-select form-control aiz- dropup">
                        <select class="form-control aiz-selectpicker" name="type" data-live-search="true"
                            tabindex="-98">
                            <option value="">Choose</option>
                            <option value="Today">Today</option>
                            <option value="7">Last 7 Days</option>
                            <option value="30">Last 30 Days</option>
                        </select> --}}
            {{-- <button type="button" class="btn dropdown-toggle btn-light" data-toggle="dropdown"
                            role="combobox" aria-owns="bs-select-1" aria-haspopup="listbox" aria-expanded="false"
                            title="Choose">
                            <div class="filter-option">
                                <div class="filter-option-inner">
                                    <div class="filter-option-inner-inner">Choose</div>
                                </div>
                            </div>
                        </button>
                        <div class="dropdown-menu" style="overflow: hidden;">
                            <div class="bs-searchbox"><input type="search" class="form-control" autocomplete="off"
                                    role="combobox" aria-label="Search" aria-controls="bs-select-1"
                                    aria-autocomplete="list" aria-activedescendant="bs-select-1-0"></div>
                            <div class="inner show" role="listbox" id="bs-select-1" tabindex="-1"
                                style="overflow-y: auto;">
                                <ul class="dropdown-menu inner show" role="presentation"
                                    style="margin-top: 0px; margin-bottom: 0px;">
                                    <li class="selected active"><a role="option"
                                            class="dropdown-item active selected" id="bs-select-1-0" tabindex="0"
                                            aria-setsize="4" aria-posinset="1" aria-selected="true"><span
                                                class="text">Choose</span></a></li>
                                    <li><a role="option" class="dropdown-item" id="bs-select-1-1"
                                            tabindex="0"><span class="text">Today</span></a></li>
                                    <li><a role="option" class="dropdown-item" id="bs-select-1-2"
                                            tabindex="0"><span class="text">Last 7 Days</span></a></li>
                                    <li><a role="option" class="dropdown-item" id="bs-select-1-3"
                                            tabindex="0"><span class="text">Last 30 Days</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary input-group-append" type="submit">Filter</button>
                </div>
            </div> --}}
        </div>
        {{-- </form> --}}
        <div class="card-body">
            <div class="row gutters-10">
                <div class="col-md-3 mx-auto mb-3">
                    <a href="#">
                        <div class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg has-transition">
                            <span
                                class="size-60px rounded-circle mx-auto bg-secondary d-flex align-items-center justify-content-center mb-3">
                                <span class="la-3x text-white">
                                    @if($affliate_stats->count_click)
                                        {{ $affliate_stats->count_click }}
                                    @else 
                                        0
                                    @endif
                                </span>
                            </span>
                            <div class="fs-18 text-primary">{{ __('No of click') }}</div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 mx-auto mb-3">
                    <a href="#">
                        <div class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg has-transition">
                            <span
                                class="size-60px rounded-circle mx-auto bg-secondary d-flex align-items-center justify-content-center mb-3">
                                <span class="la-3x text-white">
                                    @if($affliate_stats->count_item)
                                        {{ $affliate_stats->count_item }}
                                    @else 
                                        0
                                    @endif
                                </span>
                            </span>
                            <div class="fs-18 text-primary">{{ __('No of item') }}</div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 mx-auto mb-3">
                    <a href="#">
                        <div class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg has-transition">
                            <span
                                class="size-60px rounded-circle mx-auto bg-secondary d-flex align-items-center justify-content-center mb-3">
                                <span class="la-3x text-white">
                                    @if($affliate_stats->count_delivered)
                                        {{ $affliate_stats->count_delivered }}
                                    @else 
                                        0
                                    @endif
                                </span>
                            </span>
                            <div class="fs-18 text-primary">{{ __('No of deliverd') }}</div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 mx-auto mb-3">
                    <a href="#">
                        <div class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg has-transition">
                            <span
                                class="size-60px rounded-circle mx-auto bg-secondary d-flex align-items-center justify-content-center mb-3">
                                <span class="la-3x text-white">
                                    @if($affliate_stats->count_cancel)
                                        {{ $affliate_stats->count_cancel }}
                                    @else 
                                        0
                                    @endif
                                </span>
                            </span>
                            <div class="fs-18 text-primary">{{ __('No of cancel') }}</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ __('Affiliate Earning History') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0 footable footable-1 breakpoint breakpoint-md" style="">
                <thead>
                    <tr class="footable-header">
                        <th style="display: table-cell;">#</th>
                        <th style="display: table-cell;">{{ __('Referral Customer') }}</th>
                        <th style="display: table-cell;">{{ __('Amount') }}</th>
                        <th data-breakpoints="lg">{{ __('Order Id') }}</th>
                        <th data-breakpoints="lg">{{ __('Referral Type') }}</th>
                        <th data-breakpoints="lg">{{ __('Product') }}</th>
                        <th data-breakpoints="lg">{{ __('Date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($affiliate_logs as $key=>$affiliate_log)
                        <tr class="footable-empty">
                            <td>{{ $key+1 }}</td>
                            <td>{{ $affiliate_log->customer->name ?? 'Guest' }}</td>
                            <td>{{ amount_format($affiliate_log->amount) }}</td>
                            <td>{{ $affiliate_log->order->order_no ?? '' }}</td>
                            <td>{{ ucwords(str_replace('_',' ', $affiliate_log->affiliate_type)) }}</td>
                            <td>{{ $affiliate_log->order_item->term->title ?? '' }}</td>
                            <td>{{ $affiliate_log->created_at->format('d, F Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $affiliate_logs->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
    <script>
        function copyToClipboard(btn) {
            // var el_code = document.getElementById('referral_code');
            var el_url = document.getElementById('referral_code_url');
            // var c_b = document.getElementById('ref-cp-btn');
            var c_u_b = document.getElementById('ref-cpurl-btn');

            // if(btn == 'code'){
            //     if(el_code != null && c_b != null){
            //         el_code.select();
            //         document.execCommand('copy');
            //         c_b .innerHTML  = c_b.dataset.attrcpy;
            //     }
            // }

            if (btn == 'url') {
                if (el_url != null && c_u_b != null) {
                    el_url.select();
                    document.execCommand('copy');
                    c_u_b.innerHTML = c_u_b.dataset.attrcpy;
                }
            }
        }

        // function show_affiliate_withdraw_modal(){
        //     $('#affiliate_withdraw_modal').modal('show');
        // }
    </script>
@endsection
