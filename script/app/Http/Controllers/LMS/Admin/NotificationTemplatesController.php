<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\NotificationTemplate;
use Illuminate\Http\Request;

class NotificationTemplatesController extends Controller
{
    public function index()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_notifications_list');

        $templates = NotificationTemplate::orderBy('id','desc')->paginate(10);

        $data = [
            'pageTitle' => trans('lms/admin/pages/users.templates'),
            'templates' => $templates
        ];

        return view('lms.admin.notifications.templates', $data);
    }

    public function create()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_notifications_template_create');

        $data = [
            'pageTitle' => trans('lms/admin/pages/users.new_template'),
        ];

        return view('lms.admin.notifications.new_template', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_notifications_template_create');

        $this->validate($request, [
            'title' => 'required',
            'template' => 'required',
        ]);

        $data = $request->all();

        NotificationTemplate::create([
            'title' => $data['title'],
            'template' => $data['template'],
        ]);

        return redirect('/lms/admin/notifications/templates');
    }

    public function edit($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_notifications_template_edit');

        $template = NotificationTemplate::findOrFail($id);

        $data = [
            'pageTitle' => trans('lms/admin/pages/users.edit_template'),
            'template' => $template
        ];

        return view('lms.admin.notifications.new_template', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_notifications_template_edit');

        $this->validate($request, [
            'title' => 'required',
            'template' => 'required',
        ]);

        $data = $request->all();
        $template = NotificationTemplate::findOrFail($id);

        $template->update([
            'title' => $data['title'],
            'template' => $data['template'],
        ]);

        return redirect('/lms/admin/notifications/templates');
    }

    public function delete($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_notifications_template_delete');

        $template = NotificationTemplate::findOrFail($id);

        $template->delete();

        return redirect('/lms/admin/notifications/templates');
    }
}
