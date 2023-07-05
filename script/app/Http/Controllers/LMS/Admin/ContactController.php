<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Mail\sendContactReply;
use App\Mail\SendNotifications;
use App\Models\LMS\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_contacts_lists');

        $contacts = Contact::orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/admin/pages/users.contacts_lists'),
            'contacts' => $contacts
        ];

        return view('lms.admin.contacts.lists', $data);
    }

    public function reply($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_contacts_reply');

        $contact = Contact::findOrFail($id);

        $data = [
            'pageTitle' => trans('lms/admin/main.reply'),
            'contact' => $contact
        ];

        return view('lms.admin.contacts.reply', $data);
    }

    public function storeReply(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_contacts_reply');

        $this->validate($request, [
            'reply' => 'required'
        ]);

        $reply = $request->get('reply');

        $contact = Contact::findOrFail($id);
        $contact->reply = $reply;
        $contact->status = 'replied';
        $contact->save();

        if (!empty($contact->email)) {
            \Mail::to($contact->email)->send(new sendContactReply($contact));
        }

        return redirect('/lms/admin/contacts');
    }

    public function delete($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_contacts_delete');

        $contact = Contact::findOrFail($id);

        $contact->delete();

        return redirect('/lms/admin/contacts');
    }
}
