<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Bundle;
use App\Models\LMS\Comment;
use App\Models\LMS\OfflinePayment;
use App\Models\LMS\Payout;
use App\Models\LMS\Webinar;
use App\Models\LMS\WebinarReview;
use Illuminate\Http\Request;

class SidebarController extends Controller
{
    public function getCoursesBeep()
    {
        $waitingCoursesCount = Webinar::where('type', Webinar::$course)
            ->where('status', Webinar::$pending)
            ->count();

        return ($waitingCoursesCount > 0);
    }

    public function getBundlesBeep()
    {
        $waitingBundlesCount = Bundle::where('status', Webinar::$pending)
            ->count();

        return ($waitingBundlesCount > 0);
    }

    public function getWebinarsBeep()
    {
        $waitingWebinarCount = Webinar::where('type', Webinar::$webinar)
            ->where('status', Webinar::$pending)
            ->count();

        return ($waitingWebinarCount > 0);
    }

    public function getTextLessonsBeep()
    {
        $waitingTextLessonCount = Webinar::where('type', Webinar::$textLesson)
            ->where('status', Webinar::$pending)
            ->count();

        return ($waitingTextLessonCount > 0);
    }

    public function getReviewsBeep()
    {
        $count = WebinarReview::where('status', 'pending')
            ->count();

        return ($count > 0);
    }

    public function getClassesCommentsBeep()
    {
        $count = Comment::whereNotNull('webinar_id')
            ->where('status', 'pending')
            ->count();

        return ($count > 0);
    }

    public function getBundleCommentsBeep()
    {
        $count = Comment::whereNotNull('bundle_id')
            ->where('status', 'pending')
            ->count();

        return ($count > 0);
    }

    public function getBlogCommentsBeep()
    {
        $count = Comment::whereNotNull('blog_id')
            ->where('status', 'pending')
            ->count();

        return ($count > 0);
    }

    public function getProductCommentsBeep()
    {
        $count = Comment::whereNotNull('product_id')
            ->where('status', 'pending')
            ->count();

        return ($count > 0);
    }

    public function getPayoutRequestBeep()
    {
        $count = Payout::where('status', Payout::$waiting)->count();

        return ($count > 0);
    }

    public function getOfflinePaymentsBeep()
    {
        $count = OfflinePayment::where('status', OfflinePayment::$waiting)->count();

        return ($count > 0);
    }
}
