<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Booking;
use App\Location;
use App\Service;
use App\Useroption;
use Validator;

class BookingController extends Controller
{
    /**
     * Booking Status
     * 1 - new, 2 - confirm, 3-complete, 4-cancel
     */
    public function index(Request $request, $type)
    {
        $user_id = Auth::id();
        $data = Booking::where('user_id', $user_id);

        // Select data by type
        $query =  $type == 'all' ? $data : $data->where('status', $type);

        //  Select data by booking_date
        if (!empty($request->start) && !empty($request->end)) {
            $rules = [
                'start' => 'required',
                'end' => 'required|after_or_equal:start',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }
            $start = date("Y-m-d", strtotime($request->start));
            $end = date("Y-m-d", strtotime($request->end));

            $bookings = $query->whereBetween('booking_date', [$start, $end])->latest()->paginate(20);
        } else if (!empty($request->start)) {
            $start = date("Y-m-d", strtotime($request->start));
            $bookings = $query->whereDate('booking_date', '>=', $start)->latest()->paginate(20);
        } else if (!empty($request->end)) {
            $end = date("Y-m-d", strtotime($request->end));
            $bookings = $query->whereDate('booking_date', '<=', $end)->latest()->paginate(20);
        } else {
            $bookings = $query->latest()->paginate(20);
        }

        $type = $type;
        $locations = Location::where('user_id', $user_id)->get();
        $services = Service::where('user_id', $user_id)->where('type', 'service_booking')->get();
        $new = Booking::where('user_id', $user_id)->where('status', 1)->count();
        $confirm = Booking::where('user_id', $user_id)->where('status', 2)->count();
        $complete = Booking::where('user_id', $user_id)->where('status', 3)->count();
        $cancel = Booking::where('user_id', $user_id)->where('status', 4)->count();
        return view('seller.booking.index', compact('bookings', 'type', 'locations', 'services', 'new', 'confirm', 'complete', 'cancel'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'phone' => 'required',
            'booking_date' => 'required',
            'category_service_id' => 'required',
            'service_id' => 'required',
            'location_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $user_id = Auth::id();
        $prefix = Useroption::where('user_id', $user_id)->where('key', 'order_prefix')->first();
        $max_id = Booking::max('id');
        $prefix = empty($prefix) ? $max_id + 1 : $prefix->value . $max_id;

        $booking = new Booking;
        $booking->booking_no = $prefix;
        $booking->name = $request->name;
        $booking->phone = $request->phone;
        $booking->booking_date = $request->booking_date;
        $booking->status = 1;
        $booking->user_id =  $user_id;
        $booking->category_service_id =  $request->category_service_id;
        $booking->service_id =  $request->service_id;
        $booking->location_id =  $request->location_id;

        $booking->save();
        return response()->json(['success', 'Booking Created']);
    }

    public function destroy(Request $request)
    {
        $user_id = Auth::id();
        if ($request->method == 'delete') {
            if ($request->ids) {
                foreach ($request->ids as $id) {
                    $booking = Booking::where('user_id',  $user_id)->findorFail($id);
                    $booking->delete();
                }
            }
        } else {
            if ($request->ids) {
                foreach ($request->ids as $id) {
                    $booking = Booking::where('user_id',  $user_id)->findorFail($id);
                    $booking->status = $request->method;
                    $booking->save();
                }
            }
        }

        return response()->json(['Success']);
    }
}
