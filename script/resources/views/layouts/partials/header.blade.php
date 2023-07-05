 <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
        
          </ul>
        </form>
        @if(Auth::user()->role_id==3)
       <div class="dropdown">
            <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="mr-2" alt="{{language_active()}}" width="24px" height="24px" src="{{ asset('uploads/'.language_active().'.png') }}"><span>{{language_name(language_active())}}</span>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              @foreach(languages_auth() as $lang_key=> $language)
                <a class="dropdown-item" href="{{ route('seller.set_lang',['lang'=>$language]) }}"><img class="mr-2" alt="EN" width="24px" height="24px" src="{{ asset('uploads/'.$language.'.png') }}">{{$lang_key}}</a>
              @endforeach
            </div>
        </div>
        @endif
        <ul class="navbar-nav navbar-right">
         
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <img alt="image" src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" class="rounded-circle mr-1">
            <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name }}</div></a>
            <div class="dropdown-menu dropdown-menu-right">
              @if(Auth::user()->role_id == 3)
               @if(Auth::user()->status == 1)
               <a href="{{ route('seller.seller.settings') }}" class="dropdown-item has-icon">
                <i class="far fa-user"></i> {{ __('Profile Settings') }}
               </a>
               @else
               <a href="{{ route('merchant.profile.settings') }}" class="dropdown-item has-icon">
                <i class="far fa-user"></i> {{ __('Profile Settings') }}
               </a>
               @endif
              @else
              <a href="{{ route('admin.profile.settings') }}" class="dropdown-item has-icon">
                <i class="far fa-user"></i> {{ __('Profile Settings') }}
              </a>

              @endif
             
             
              <div class="dropdown-divider"></div>
              <a href="{{ route('logout') }}"
              onclick="event.preventDefault();
              document.getElementById('logout-form').submit();" class="dropdown-item has-icon text-danger">
              <i class="fas fa-sign-out-alt"></i>  {{ __('Logout') }}
            </a>



            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="none">
              @csrf
            </form>
          </div>
        </li>
      </ul>
    </nav>