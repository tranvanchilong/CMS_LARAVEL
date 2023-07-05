<?php

namespace App\Http\Controllers\Api;

use App\Booking;
use App\Category;
use App\Http\Controllers\Controller;
use App\Service;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Useroption;
use Auth;
use DB;
use Exception;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'phone' => 'required|max:20',
            'booking_date' => 'required|after_or_equal:now',
            'category_service_id' => 'nullable',
            'service_id' => 'nullable',
            'location_id' => 'nullable',

        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 401);
        }
        $prefix = Useroption::where('user_id', $user->created_by)->where('key', 'order_prefix')->first();
        $max_id = Booking::max('id');
        $prefix = empty($prefix) ? $max_id + 1 : $prefix->value . $max_id;

        try {
            DB::beginTransaction();
            $booking = new Booking;
            $booking->booking_no = $prefix;
            $booking->name = $request->name;
            $booking->phone = $request->phone;
            $booking->booking_date = $request->booking_date;
            $booking->status = 1;
            $booking->user_id = $user->created_by;
            $booking->customer_id = $user->id;
            $booking->location_id = $request->location_id;
            $booking->service_id = $request->service_id;
            $booking->category_service_id = $request->category_service_id;
            $booking->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }
        return response()->json(['status' => true, 'message' => 'Booking Successfully!'], 200);
    }

    public function cancelBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 401);
        }
        if ($booking = Booking::where(['id' => $request->booking_id])->where('user_id', domain_info('user_id'))->first()) {
            Booking::where(['id' => $request->booking_id])->update([
                'status' => 4
            ]);
            $booking->save();
            return response()->json(['status' => true, 'message' => 'Cancel Booking Successfully!'], 200);
        }
        return response()->json(['status' => false, 'message' => 'Status_not_changable_now'], 302);
    }
    public function getAllBookedForUser(Request $request)
    {
        $bookings = Booking::where('user_id', domain_info('user_id'))->where('customer_id', Auth::id())->with(['category_services', 'services', 'locations'])->get();
        return response()->json(['status' => true, 'data' => $bookings], 200);
    }
    public function getCategoryServices()
    {
        $category_service = Category::where('user_id', domain_info('user_id'))->where('type', 'booking')->with('services')->get();
        return response()->json(['status' => true, 'data' => $category_service], 200);
    }
    public function getServices()
    {
        $services = Service::where('user_id', domain_info('user_id'))->where('type', 'service_booking')->get();
        return response()->json(['status' => true, 'data' => $services], 200);
    }
    public function getBookedIn7Days()
    {
        $bookings = Booking::where('user_id', domain_info('user_id'))->whereBetween('booking_date', [date('Y-m-d'), date('Y-m-d', strtotime("+7 days"))])->get();
        return response()->json(['status' => true, 'data' => $bookings], 200);
    }
    public function getBookedOfUserIn7Days()
    {
        $bookings = Booking::where('user_id', domain_info('user_id'))->whereBetween('booking_date', [date('Y-m-d'), date('Y-m-d', strtotime("+7 days"))])->where('customer_id', Auth::id())->get();
        return response()->json(['status' => true, 'data' => $bookings], 200);
    }
    public function getBookingSetting()
    {
        $booking_setting = Useroption::where('user_id', Auth::id())->where('key', 'booking_setting')->first();
        return response()->json(['status' => true, 'data' => $booking_setting->value ?? null], 200);
    }
}
