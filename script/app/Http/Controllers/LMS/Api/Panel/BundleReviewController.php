<?php

namespace App\Http\Controllers\LMS\Api\Panel;

use App\Http\Controllers\LMS\Api\Traits\ReviewTrait;
use App\Http\Controllers\LMS\Controller;
use Illuminate\Http\Request;

//use App\Http\Controllers\LMS\Api\Traits\ReviewTrait;

class BundleReviewController extends Controller
{
    use ReviewTrait;

    public function store()
    {
        return $this->store() ;
    }
}
