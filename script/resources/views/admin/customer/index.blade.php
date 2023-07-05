@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Customers'])
@endsection
@section('content')
@if(Session::has('success'))
<div class="row">
	<div class="col-sm-12">
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<strong>{{ Session::get('success') }}</strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	</div>
</div>
@endif
<div class="row">
  <div class="col-12 mt-2">
    <div class="card">
      <div class="card-body">
        <div class="row mb-2">
          <div class="col-sm-8">
          @if(isset($_GET['type']) && isset($_GET['test_account'])) 
            <a href="{{ route('admin.customer.index',['type' => 'all', 'test_account' => $_GET['test_account'] ]) }}" class="mr-2 btn btn-outline-primary @if($type==="all" && $test_account==$_GET['test_account']) active @endif">{{ __('All') }} ({{ $all }})</a>

            <a href="{{ route('admin.customer.index',['type' => 1, 'test_account' => $_GET['test_account'] ]) }}" class="mr-2 btn btn-outline-success @if($type==1 && $test_account==$_GET['test_account']) active @endif">{{ __('Active') }} ({{ $actives }})</a>

            <a href="{{ route('admin.customer.index',['type' => 2, 'test_account' => $_GET['test_account'] ]) }}" class="mr-2 btn btn-outline-warning @if($type==2 && $test_account==$_GET['test_account']) active @endif">{{ __('Suspened') }} ({{ $suspened }})</a>

            <a href="{{ route('admin.customer.index',['type' => 3, 'test_account' => $_GET['test_account'] ]) }}" class="mr-2 btn btn-outline-warning @if($type==3 && $test_account==$_GET['test_account']) active @endif">{{ __('Pending') }} ({{ $pendings }})</a>

            <a href="{{ route('admin.customer.index',['type' => 0, 'test_account' => $_GET['test_account'] ]) }}" class="mr-2 btn btn-outline-danger @if($type==0 && $test_account==$_GET['test_account']) active @endif">{{ __('Trash') }} ({{ $trash }})</a>
          @else
            <a href="{{ route('admin.customer.index','type=all') }}" class="mr-2 btn btn-outline-primary @if($type==="all") active @endif">{{ __('All') }} ({{ $all }})</a>

            <a href="{{ route('admin.customer.index','type=1') }}" class="mr-2 btn btn-outline-success @if($type==1) active @endif">{{ __('Active') }} ({{ $actives }})</a>

            <a href="{{ route('admin.customer.index','type=2') }}" class="mr-2 btn btn-outline-warning @if($type==2) active @endif">{{ __('Suspened') }} ({{ $suspened }})</a>

            <a href="{{ route('admin.customer.index','type=3') }}" class="mr-2 btn btn-outline-warning @if($type==3) active @endif">{{ __('Pending') }} ({{ $pendings }})</a>

            <a href="{{ route('admin.customer.index','type=0') }}" class="mr-2 btn btn-outline-danger @if($type==0) active @endif">{{ __('Trash') }} ({{ $trash }})</a>
          @endif  
            @if(isset($_GET['type']))
              @if(isset($_GET['type']) && $test_account==1)    
                <input id="test_account" type="checkbox" onchange="window.location.href='{{ route('admin.customer.index',['type' => $_GET['type']]) }}'" checked="checked">
                <label for="test_account">{{ __('Test Account') }}</label>
              @else
                <input id="test_account" type="checkbox" onchange="window.location.href='{{ route('admin.customer.index',['type' => $_GET['type'], 'test_account' => '1' ]) }}'">
                <label for="test_account">{{ __('Test Account') }}</label>
              @endif
            @endif
          </div>

          <div class="col-sm-4 text-right">
            @can('customer.create')
            <a href="{{ route('admin.customer.create') }}" class="btn btn-primary">{{ __('Create Customer') }}</a>
            @endcan
          </div>
        </div>

        <div class="float-right">
          <form>
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="hidden" name="test_account" value="{{ $test_account }}">
            <div class="input-group mb-2">

              <input type="text" id="src" class="form-control" placeholder="Search..." required="" name="src" autocomplete="off" value="{{ $request->src ?? '' }}">
              <select class="form-control selectric" name="term" id="term">
                <option value="domain">{{ __('Search By Domain') }}</option>
                <option value="name">{{ __('Search By Customer') }}</option>
                <option value="email">{{ __('Search By User Mail') }}</option>

              </select>
              <div class="input-group-append">
                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
              </div>
            </div>
          </form>
        </div>

        <form method="post" action="{{ route('admin.customers.destroys') }}" class="basicform_with_reload">
          @csrf
          <div class="float-left mb-1">
            @can('customer.delete')
            <div class="input-group">
              <select class="form-control selectric" name="method">
                <option value="" >{{ __('Select Action') }}</option>
                <option value="1" >{{ __('Publish') }}</option>
                <option value="2" >{{ __('Suspend') }}</option>
                <option value="3" >{{ __('Move To Pending') }}</option>
                 @if($type !== "0")
                <option value="trash" >{{ __('Move To Trash') }}</option>
                @endif
                @if($type=="0")
                <option value="delete" >{{ __('Delete Permanently') }}</option>
                @endif
              </select>
              <div class="input-group-append">
                <button class="btn btn-primary basicbtn" type="submit">{{ __('Submit') }}</button>
              </div>
            </div>
            @endcan
          </div>


          <div class="table-responsive">
            <table class="table table-striped table-hover text-center table-borderless">
              <thead>
                <tr>
                  <th><input type="checkbox" class="checkAll"></th>

                  <th>{{ __('Name') }}</th>
                  <th>{{ __('Email') }}</th>
                  <th>{{ __('Domain') }}</th>
                  <th>{{ __('Custom Domain') }}</th>
{{--                  <th>{{ __('Storage Used') }}</th>--}}
                  <th>{{ __('Plan') }}</th>
                  <th>{{ __('Status') }}</th>
                  <th>{{__('Email Status')}}</th>
                  <th>{{ __('Featured') }}</th>
                  <th>{{ __('Default') }}</th>
                  <th>{{ __('Template') }}</th>
                  <th>{{ __('Join at') }}</th>
                  <th>{{ __('Last login') }}</th>
                  <th>{{ __('Action') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach($posts as $row)
                @php
                  $feature_template = $row->user_domain->featured ?? 0;
                  $template_enable = $row->user_domain->template_enable ?? 1;
                  $default = $row->user_domain->is_default ?? 0;
                @endphp
                <tr id="row{{ $row->id }}">
                  <td><input type="checkbox" name="ids[]" value="{{ $row->id }}"></td>
                  <td>{{ $row->name }}</td>
                  <td><a href="mailto:{{ $row->email }}">{{ $row->email }}</a></td>
                  <td><a href="{{ $row->user_domain->full_domain ?? '' }}" target="_blank">{{ $row->user_domain->domain ?? '' }}</a></td>
                  <td><a href="{{ $row->custom_domain->full_domain ?? '' }}" target="_blank">{{ $row->custom_domain->domain ?? '' }}</a></td>
{{--                  <td>{{ folderSize('uploads/'.$row->id) }}MB / {{ $row->user_plan->plan_info->storage ?? 0 }} MB</td>--}}
                  <td>{{ $row->user_plan->plan_info->name ?? '' }}</td>
                  <td>
                    @if($row->status==1) <span class="badge badge-success">{{ __('Active') }}</span>
                    @elseif($row->status==0) <span class="badge badge-danger">{{ __('Trash') }}</span>
                    @elseif($row->status==2) <span class="badge badge-warning">{{ __('Suspended') }}</span>
                    @elseif($row->status==3) <span class="badge badge-warning">{{ __('Pending') }}</span>
                    @endif
                  </td>
                  <td>
                    @if ($row->email_verified == 1)
                      <a class="btn btn-success btn-sm" href="{{route('admin.customer.emailStatus',[$row->id,'email_verified'=>1])}}">Verified</a>
                    @else
                      <a class="btn btn-danger btn-sm" href="{{route('admin.customer.emailStatus',[$row->id,'email_verified'=>0])}}">Unverified</a>
                    @endif
                  </td>
                  <td>
                    <div class="d-flex">
                    <select style="width: auto" data-user_id="{{$row->id}}" class="template-select form-control form-control-sm {{$feature_template==1 ? 'bg-success' : 'bg-danger'}}" name="preview_template">
                      <option value="1" {{$feature_template==1 ? 'selected' : ''}}>Yes</option>
                      <option value="0" {{$feature_template==0 ? 'selected' : ''}}>No</option>
                    </select>
                    @if($feature_template==1)
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#templateModal{{$row->id}}">Edit</button>
                    @endif
                    </div>
                  </td>
                  <td>
                    @if($default==1) <span class="badge badge-success">{{ __('Default') }}</span>
                    @else <a style="color:#fff;background:#999" href="{{ route('admin.customer.default',$row->id) }}" class="badge badge-default">No</a>
                    @endif
                  </td>
                  <td>
                    <form class="basicform_with_reload" action="{{route('admin.customer.template_enable')}}" method="POST" id="template_enable{{$row->id}}">
                      @csrf
                      <input type="hidden" name="user_id" value="{{$row->id}}">
                      <input type="hidden" name="template_enable" value="{{$template_enable==1 ? 0 : 1}}">
                      <button form="template_enable{{$row->id}}" type="submit" class="btn btn-{{ $template_enable==1 ? 'primary' : 'danger' }}">{{ $template_enable==1 ? __('Enable') : __('Disable') }}</button>
                    </form>
                  </td>
                  <td>{{ $row->created_at->format('d-F-Y')  }}</td>
                  <td>{{ $row->last_login ?? ''  }}</td>
                  <td>
                    <div class="dropdown d-inline">
                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('Action') }}
                      </button>
                      <div class="dropdown-menu">

                         @can('customer.edit')
                        <a class="padding dropdown-item has-icon" href="{{ route('admin.customer.edit',$row->id) }}"><i class="fas fa-user-edit"></i> {{ __('Edit') }}</a>

                        <a class="padding dropdown-item has-icon" href="{{ route('admin.customer.planedit',$row->id) }}"><i class="far fa-edit"></i> {{ __('Edit Plan Info') }}</a>
                         @endcan
                          @can('customer.view')
                        <a class="padding dropdown-item has-icon" href="{{ route('admin.customer.show',$row->id) }}"><i class="far fa-eye"></i>{{ __('View') }}</a>
                         @endcan

                         <a class="padding dropdown-item has-icon" href="{{ route('admin.order.create','email='.$row->email) }}"><i class="fas fa-cart-arrow-down"></i>{{ __('Make Order') }}</a>

                         <a class="padding dropdown-item has-icon" href="{{ route('admin.customer.show',$row->id) }}"><i class="far fa-envelope"></i>{{ __('Send Email') }}</a>

                         <a class="padding dropdown-item has-icon" href="{{ route('admin.customer.login_seller',$row->id) }}" target="_blank"><i class="fas fa-key"></i>{{ __('Login as Shop Admin') }}</a>
                      </div>
                    </div>


                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                 <th><input type="checkbox" class="checkAll"></th>

                 <th>{{ __('Name') }}</th>
                 <th>{{ __('Email') }}</th>
                 <th>{{ __('Domain') }}</th>
                 <th>{{ __('Custom Domain') }}</th>
{{--                 <th>{{ __('Storage Used') }}</th>--}}
                 <th>{{ __('Plan') }}</th>
                 <th>{{ __('Status') }}</th>
                 <th>{{__('Email Status')}}</th>
                 <th>{{ __('Featured') }}</th>
                 <th>{{ __('Default') }}</th>
                 <th>{{ __('Template') }}</th>
                 <th>{{ __('Join at') }}</th>
                 <th>{{ __('Last login') }}</th>
                 <th>{{ __('Action') }}</th>
               </tr>
             </tfoot>
           </table>

         </div>
       </form>
        {{ $posts->appends($request->all())->links('vendor.pagination.bootstrap-4') }}
     </div>
   </div>
 </div>
</div>

@foreach($posts as $row)
@include('admin.customer.feature_template')
@endforeach

@endsection

@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $(".template-select").on('change', function() {
      console.log('a');
      let userId = $(this).data('user_id');
      let val = $(this).val();

      if(val == 1) {
        $("#templateModal" + userId).modal('show');
      }

      $(`#templateModal${userId} input[name='template']`).val(val);
      if(val == 0) {
        $(`#templateForm${userId}`).trigger('submit');
      }
    });
  });
</script>
@endpush
