<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Location;
use App\Models\CustomerFavoriteLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function getLocations(Request $request)
    {
        $locations = Location::where('user_id', domain_info('user_id'))
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%')
                    ->orWhere('city', 'like', '%' . $request->name . '%')
                    ->orWhere('state', 'like', '%' . $request->name . '%')
                    ->orWhere('country', 'like', '%' . $request->name . '%')
                    ->orWhere('address', 'like', '%' . $request->name . '%');
            })
            ->get();
        return response()->json($locations, 200);
    }

    public function getCustomersFavoriteLocation()
    {
        $favorites = DB::table('customer_favorite_locations')
            ->join('customers', 'customer_favorite_locations.customer_id', '=', 'customers.id')
            ->join('locations', 'customer_favorite_locations.location_id', '=', 'locations.id')
            ->where('customer_favorite_locations.customer_id', Auth::id())
            ->select(
                'customers.id as customer_id',
                'customers.name as cutomer_name',
                'locations.id',
                'locations.name',
                'locations.city',
                'locations.state',
                'locations.country',
                'locations.address',
                'locations.phone',
                'locations.image',
                'locations.latitude',
                'locations.longitude',
                'locations.work_time',
                'locations.open_hour',
                'locations.close_hour',
                'locations.is_default',
            )
            ->get();
        return response()->json($favorites);
    }

    public function addFavoriteLocation(Request $request)
    {
        $favorite = CustomerFavoriteLocation::where('location_id', $request->location_id)->first();
        if (isset($favorite)) {
            return response()->json(['message' => 'Location is already in favorites']);
        }
        $favorite = new CustomerFavoriteLocation();
        $favorite->location_id = $request->location_id;
        $favorite->customer_id = Auth::id();
        $favorite->save();
        return response()->json(['message' => 'Add Favorite Successfully']);
    }

    public function removeFavoriteLocation(Request $request)
    {
        $favorite = CustomerFavoriteLocation::where('location_id', $request->location_id)->first();
        if (!isset($favorite)) {
            return response()->json(['message' => 'Failed to remove favorite. Please try again later.']);
        }
        $favorite->delete();
        return response()->json(['message' => 'Remove Favorite Successfully']);
    }
}
