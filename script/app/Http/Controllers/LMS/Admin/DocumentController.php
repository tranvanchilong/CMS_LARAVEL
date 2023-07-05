<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Mixins\Financial\MultiCurrency;
use App\Models\LMS\Accounting;
use App\Models\LMS\Group;
use App\Models\LMS\GroupUser;
use App\Models\LMS\Sale;
use App\Models\LMS\Webinar;
use App\Models\LMS\User;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_documents_list');

        $documents = Accounting::query();
        $users = User::whereNull('deleted_at')->get()->keyBy('id');

        $from = $request->input('from');
        $to = $request->input('to');
        $user = $request->input('user');
        $webinar = $request->input('webinar');
        $type = $request->input('type');
        $typeAccount = $request->input('type_account');

        $documents = fromAndToDateFilter($from, $to, $documents, 'created_at');

        if (isset($user)) {
            $userIds = $user;
            $documents->whereIn('user_id', $userIds);
        }

        $webinarModel = '';
        if (isset($webinar)) {
            $documents->whereIn('webinar_id', [$webinar]);
            $webinarModel = Webinar::where('id', $webinar)->first();
        }

        if (isset($type) && $type !== 'all') {
            $documents->where('type', $type);
        }

        if (isset($typeAccount) && $typeAccount !== 'all') {
            $documents->where('type_account', $typeAccount);
        }

        $documents = $documents->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/admin/pages/financial.document_page_title'),
            'documents' => $documents,
            'users' => $users,
            'webinar' => $webinarModel,
        ];

        return view('lms.admin.financial.documents.lists', $data);
    }


    public function create()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_documents_create');

        $data = [
            'pageTitle' => trans('lms/admin/pages/financial.new_document_page_title'),
        ];

        return view('lms.admin.financial.documents.new', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_documents_create');

        $this->validate($request, [
            'currency' => 'required',
            'amount' => 'required',
            'user_id' => 'required|exists:lms_users,id',
            'type' => 'required',
        ]);

        $data = $request->all();
        $user = User::query()->findOrFail($data['user_id']);

        $amount = $data['amount'];

        $multiCurrency = new MultiCurrency();
        $specificCurrency = $multiCurrency->getSpecificCurrency($data['currency']);

        if (!empty($specificCurrency)) {
            $amount = convertPriceToDefaultCurrency($amount, $specificCurrency);
        }

        Accounting::create([
            'creator_id' => auth()->guard('lms_user')->user()->id,
            'amount' => $amount,
            'user_id' => $user->id,
            'type' => $data['type'],
            'description' => $data['description'],
            'type_account' => Accounting::$asset,
            'store_type' => Accounting::$storeManual,
            'created_at' => time(),
        ]);

        $notifyOptions = [
            '[c.title]' => '',
            '[f.d.type]' => $data['type'],
            '[amount]' => handlePrice($amount, true, true, false, $user),
        ];

        sendNotification('new_financial_document', $notifyOptions, $user->id);

        return redirect('/lms/admin/financial/documents');
    }

    public function printer($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_documents_print');

        $document = Accounting::findOrFail($id);

        $data = [
            'document' => $document
        ];

        return view('lms.admin.financial.documents.print', $data);
    }
}