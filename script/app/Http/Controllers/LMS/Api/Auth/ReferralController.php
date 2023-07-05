<?php

namespace App\Http\Controllers\LMS\Api\Auth;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\AffiliateCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class ReferralController extends Controller
{
    public function referral($code)
    {
        $check = AffiliateCode::where('code', $code)->first();

        if (!empty($check)) {
            Cookie::queue('referral_code', $code, 24 * 60);
        }

        return redirect('/lms/register');
    }
}
