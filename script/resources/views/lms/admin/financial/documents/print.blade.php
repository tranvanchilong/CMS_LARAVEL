<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ trans('lms/admin/main.print') }} {{ $document->title }}</title>
    <link rel="stylesheet" href="/assets/lms/assets/admin/vendor/bootstrap/bootstrap.min.css"/>
    <link rel="stylesheet" href="/assets/lms/assets/admin/css/print.css">
</head>
<body>
<div class="main-box">
    <div class="print-1"></div>
    <div class="factor-logo-container">
        <img src="{{get_path_lms()}}{{ getGeneralSettings('logo') }}" class=""/>
        <br>
        <h3 class="mt-1">{{ trans('lms/financial.financial_documents') }}</h3>
    </div>
    <div class="print-2">
        <div>
            <span>{{ trans('lms/admin/main.document_number') }}:</span>&nbsp;<label>{{ $document->id }}</label>
        </div>
        <div class="mt-2">
            <span>{{ trans('lms/admin/main.created_at') }}:</span>&nbsp;<label>{{ dateTimeFormat($document->created_at,'d F Y - H:i') }}</label>
        </div>
    </div>
    <div class="w-100 clear-both"></div>
    <table>

        <th>{{ trans('lms/admin/main.email') }}</th>
        <th>{{ trans('lms/admin/main.mobile') }}</th>
        <th>{{ trans('lms/admin/main.title') }}</th>
        <th>{{ trans('lms/admin/main.type') }}</th>
        <th>{{ trans('lms/admin/main.amount') }}({{ $currency }})</th>

        <tr>
            <th>{{ $document->user->email ?? '-' }}</th>
            <th>{{ $document->user->mobile ?? '-' }}</th>
            <th>
                @if(!empty($document->webinar_id))
                    <span class="d-block font-weight-bold">{{ trans('lms/admin/main.item_purchased') }}</span>
                    <span class="d-block font-weight-bold">#{{ $document->webinar_id }}-{{ $document->webinar->title }}</span>
                @elseif(!empty($document->meeting_time_id))
                    <span class="d-block font-weight-bold">{{ trans('lms/admin/main.item_purchased') }}</span>
                    <span class="d-block font-weight-bold">{{ trans('lms/admin/main.meeting') }}</span>
                @elseif(!empty($document->subscribe_id))
                    <span class="d-block font-weight-bold">{{ trans('lms/admin/main.purchased_subscribe') }}</span>
                @elseif(!empty($document->promotion_id))
                    <span class="d-block font-weight-bold">{{ trans('lms/admin/main.purchased_promotion') }}</span>
                @endif
            </th>
            <th>
                @switch($document->type)
                    @case(\App\Models\LMS\Accounting::$addiction)
                    <span class="text-success">{{ trans('lms/admin/main.addiction') }}</span>
                    @break
                    @case(\App\Models\LMS\Accounting::$deduction)
                    <span class="text-danger">{{ trans('lms/admin/main.deduction') }}</span>
                    @break
                @endswitch
            </th>
            <th>{{ $document->amount }}</th>
        </tr>

    </table>

    <table>

        <tr>
            <th class="th-1">
                {{ !empty($document->description) ? $document->description : 'Description' }}
            </th>
        </tr>

    </table>

    <div class="print-3"></div>

    <div class="print-4">

        <div class="print-5">
            <span>{{ trans('lms/public.created_by') }}:</span>&nbsp;
            <label>{{ !empty($document->creator_id) ? $document->creator->full_name : 'Automatic' }}</label>
        </div>
    </div>

    <div class="print-6"></div>
</div>
</body>
</html>
