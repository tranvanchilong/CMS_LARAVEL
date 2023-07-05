<?php

namespace App\Http\Controllers\LMS\Api\Instructor;

use App\Exports\WebinarStudents;
use App\Http\Controllers\LMS\Controller;
use App\Http\Resources\BundleResource;
use App\Models\LMS\Bundle;
use App\Models\LMS\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BundleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = apiAuth();

        $query = Bundle::where(function ($query) use ($user) {
            $query->where('lms_bundles.teacher_id', $user->id);
            $query->orWhere('lms_bundles.creator_id', $user->id);
        });

        $bundlesHours = deepClone($query)->join('lms_bundle_webinars', 'lms_bundle_webinars.bundle_id', 'lms_bundles.id')
            ->join('lms_webinars', 'lms_webinars.id', 'lms_bundle_webinars.webinar_id')
            ->select('lms_bundles.*', DB::raw('sum(lms_webinars.duration) as duration'))
            ->sum('duration');

        $query->with([
            /*'reviews' => function ($query) {
                $query->where('status', 'active');
            },*/
            'bundleWebinars',
            'category',
            'teacher',
            'sales' => function ($query) {
                $query->where('type', 'bundle')
                    ->whereNull('refund_at');
            }
        ])->orderBy('updated_at', 'desc');

        $bundlesCount = $query->count();

        $bundles = $query->get();

        $bundleSales = Sale::where('seller_id', $user->id)
            ->where('type', 'bundle')
            ->whereNotNull('bundle_id')
            ->whereNull('refund_at')
            ->get();

        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'),
            [
                'bundles' => BundleResource::collection($bundles),
                'bundles_count' => $bundlesCount,
                'bundle_sales_amount' => $bundleSales->sum('amount'),
                'bundle_sales_count' => $bundleSales->count(),
                'bundles_hours' => $bundlesHours,

            ]);


    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bundle $bundle)
    {
        $bundle = $bundle->where('creator_id', apiauth()->guard('lms_user')->id)
            ->first();
        if (!$bundle) {
            abort(404);
        }
        $bundle->delete();
        return apiResponse2(1, 'deleted', trans('lms/api.public.deleted'));


    }


    public function export($id)
    {
        $user = apiAuth();
        $bundle = Bundle::where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })->first();

        if (!$bundle) {
            abort(404);
        }

        $sales = Sale::where('type', 'bundle')
            ->where('bundle_id', $bundle->id)
            ->whereNull('refund_at')
            ->with([
                'buyer' => function ($query) {
                    $query->select('id', 'full_name', 'email', 'mobile');
                }
            ])->get();

        if (!empty($sales) and !$sales->isEmpty()) {
            $export = new WebinarStudents($sales);
            return Excel::download($export, trans('lms/panel.users') . '.xlsx');
            //     $ee = Excel::store($export, trans('lms/panel.users') . '.xlsx');

            //  return response($ees);
        }

        return apiResponse2(0, 'failed', trans('lms/api.lms_bundles.exported'));

    }

}
