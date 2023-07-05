<!DOCTYPE html>
<html class="no-js" lang="{{ App::getlocale() }}" >
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title></title>
        <!-- Fonts -->
        <link rel="stylesheet" href="{{ asset('assets/frontend/plugins/bootstrap/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/style.css') }}">
        <!-- Styles -->
</head>
<body>  
        <style>
            .for-margin {
                margin: auto;

                margin-bottom: 10%;
            }
            .page-not-found {
                margin-top: 30px;
                font-weight: 600;
                text-align: center;
            }
        </style>
        @php
            $parsedUrl = parse_url(url()->current());
            $host =  $parsedUrl['host'];
            $custom_domain = \App\Models\Requestdomain::where('domain', $host)->first();
            $domain = \App\Domain::where('user_id', $custom_domain->user_id)->first();
        @endphp
    <div class="container ">
        <div class="col-md-3"></div>
        <div class="col-md-6 for-margin">
            <h2 class="page-not-found">{{ __('Redirect Domain')}}</h2>

            <p style="text-align: center;">{{ __('We are sorry the custom domain not support in your plan')}}<br>{{ __('The domain name will change to a free domain name')}}</p>
            <div style="text-align: center;">
                <a class="btn btn-primary" href="{{$domain->full_domain}}">{{ __('OK')}}</a>
                <a class="btn btn-primary" href="https://di4l.vn/contact-us">{{ __('Register Package')}}</a>
            </div>

        </div>
    </div>
        
</body>
</html>