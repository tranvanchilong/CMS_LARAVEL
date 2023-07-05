<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Permission;
use App\Models\LMS\Role;
use App\Models\LMS\Section;
use App\Models\LMS\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_roles_list');

        $roles = Role::with('users')
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/admin/pages/roles.page_lists_title'),
            'roles' => $roles,
        ];

        return view('lms.admin.roles.lists', $data);
    }

    public function create()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_roles_create');

        $sections = Section::whereNull('section_group_id')
            ->with('children')
            ->get();

        $data = [
            'pageTitle' => trans('lms/admin/main.role_new_page_title'),
            'sections' => $sections
        ];

        return view('lms.admin.roles.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_roles_create');

        $this->validate($request, [
            'name' => 'required|min:3|max:64|unique:lms_roles,name',
            'caption' => 'required|min:3|max:64|unique:lms_roles,caption',
        ]);

        $data = $request->all();

        $role = Role::create([
            'name' => $data['name'],
            'caption' => $data['caption'],
            'is_admin' => (!empty($data['is_admin']) and $data['is_admin'] == 'on'),
            'created_at' => time(),
        ]);

        if ($role->is_admin and $request->has('permissions')) {
            $this->storePermission($role, $data['permissions']);
        }
        Cache::forget('sections');

        return redirect('/lms/admin/roles');
    }

    public function edit($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_roles_edit');

        $role = Role::findOrFail($id);
        $permissions = Permission::where('role_id', '=', $role->id)->get();
        $sections = Section::whereNull('section_group_id')
            ->with('children')
            ->get();

        $data = [
            'pageTitle' => trans('lms//admin/main.edit'),
            'role' => $role,
            'sections' => $sections,
            'permissions' => $permissions->keyBy('section_id')
        ];

        return view('lms.admin.roles.create', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_roles_edit');

        $role = Role::find($id);

        $this->validate($request, [
            'name' => 'required',
            'caption' => 'required',
        ]);

        $data = $request->all();

        $role->update([
            'caption' => $data['caption'],
            'is_admin' => ((!empty($data['is_admin']) and $data['is_admin'] == 'on') or $role->name == Role::$admin),
        ]);

        Permission::where('role_id', '=', $role->id)->delete();

        if ($role->is_admin and !empty($data['permissions'])) {
            $this->storePermission($role, $data['permissions']);
        }

        Cache::forget('sections');

        return redirect('/lms/admin/roles');
    }

    public function destroy(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_roles_delete');

        $role = Role::find($id);
        if ($role->id !== 2) {
            $role->delete();
        }

        return redirect('/lms/admin/roles');
    }

    public function storePermission($role, $sections)
    {
        $sectionsId = Section::whereIn('id', $sections)->pluck('id');
        $permissions = [];
        foreach ($sectionsId as $section_id) {
            $permissions[] = [
                'role_id' => $role->id,
                'section_id' => $section_id,
                'allow' => true,
            ];
        }
        Permission::insert($permissions);
    }
}
