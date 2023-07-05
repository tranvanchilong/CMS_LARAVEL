<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Exports\AgoraHistoryExport;
use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\AgoraHistory;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AgoraHistoryController extends Controller
{
    public function index()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_agora_history_list');

        $agoraHistories = AgoraHistory::whereNotNull('end_at')
            ->orderBy('start_at')
            ->with([
                'session' => function ($query) {
                    $query->with('webinar');
                }
            ])
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/update.agora_history'),
            'agoraHistories' => $agoraHistories
        ];

        return view('lms.admin.agora_history.index', $data);
    }

    public function exportExcel()
    {
        $agoraHistories = AgoraHistory::whereNotNull('end_at')
            ->orderBy('start_at')
            ->with([
                'session' => function ($query) {
                    $query->with('webinar');
                }
            ])
            ->get();

        $export = new AgoraHistoryExport($agoraHistories);

        return Excel::download($export, 'agoraHistory.xlsx');
    }
}
