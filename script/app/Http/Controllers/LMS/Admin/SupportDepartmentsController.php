<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\SupportDepartment;
use App\Models\LMS\SupportDepartmentTranslation;
use Illuminate\Http\Request;

class SupportDepartmentsController extends Controller
{
    public function index()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_support_departments');

        removeContentLocale();

        $departments = SupportDepartment::withCount('supports')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/admin/main.support_departments_title'),
            'departments' => $departments
        ];

        return view('lms.admin.supports.departments', $data);
    }

    public function create()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_support_department_create');

        removeContentLocale();

        $data = [
            'pageTitle' => trans('lms/admin/pages/users.new_department'),
        ];

        return view('lms.admin.supports.department_create', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_support_department_create');

        $this->validate($request, [
            'title' => 'required|string|min:2'
        ]);

        $data = $request->all();

        $department = SupportDepartment::create([
            'created_at' => time(),
        ]);

        SupportDepartmentTranslation::updateOrCreate([
            'support_department_id' => $department->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
        ]);


        return redirect('/lms/admin/supports/departments');
    }

    public function edit(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_support_departments_edit');

        $department = SupportDepartment::findOrFail($id);

        $locale = $request->get('locale', app()->getLocale());
        storeContentLocale($locale, $department->getTable(), $department->id);

        $data = [
            'pageTitle' => trans('lms/admin/pages/users.edit_department'),
            'department' => $department
        ];

        return view('lms.admin.supports.department_create', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_support_departments_edit');

        $this->validate($request, [
            'title' => 'required|string|min:2'
        ]);

        $data = $request->all();

        $department = SupportDepartment::findOrFail($id);

        $department->update([
            'created_at' => time(),
        ]);

        SupportDepartmentTranslation::updateOrCreate([
            'support_department_id' => $department->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
        ]);

        removeContentLocale();

        return back();
    }

    public function delete($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_support_departments_delete');

        $department = SupportDepartment::findOrFail($id);

        $department->delete();

        return redirect('/lms/admin/supports/departments');
    }
}
