<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\RegistrationPackage;
use App\Models\LMS\Role;
use App\Models\LMS\Sale;
use App\Models\LMS\Setting;
use App\Models\LMS\RegistrationPackageTranslation;
use Illuminate\Http\Request;

class RegistrationPackagesController extends Controller
{
    public function index()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_registration_packages_lists');

        $query = RegistrationPackage::with([
            'sales' => function ($query) {
                $query->whereNull('refund_at');
            }
        ]);

        $packages = deepClone($query)->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalPackages = deepClone($query)->count();
        $totalActiveByInstructors = $this->getTotalActiveByInstructors();
        $totalActiveByOrganization = $this->getTotalActiveByOrganization();

        $data = [
            'pageTitle' => trans('lms/update.registration_packages'),
            'packages' => $packages,
            'totalPackages' => $totalPackages,
            'totalActiveByInstructors' => $totalActiveByInstructors,
            'totalActiveByOrganization' => $totalActiveByOrganization,
        ];

        return view('lms.admin.financial.registration_packages.lists', $data);
    }

    private function getTotalActiveByInstructors()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_registration_packages_lists');

        return Sale::whereNotNull('registration_package_id')
            ->whereNull('refund_at')
            ->whereHas('buyer', function ($query) {
                $query->where('role_name', Role::$teacher);
            })
            ->count();
    }

    private function getTotalActiveByOrganization()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_registration_packages_lists');

        return Sale::whereNotNull('registration_package_id')
            ->whereNull('refund_at')
            ->whereHas('buyer', function ($query) {
                $query->where('role_name', Role::$organization);
            })
            ->count();
    }

    public function create()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_registration_packages_new');

        $data = [
            'pageTitle' => trans('lms/admin/main.new_package'),
        ];

        return view('lms.admin.financial.registration_packages.new', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_registration_packages_new');

        $this->validate($request, [
            'title' => 'required|string',
            'description' => 'required|string',
            'days' => 'required|numeric',
            'price' => 'required|numeric',
            'icon' => 'required|string',
            'role' => 'required|in:instructors,organizations',
            'instructors_count' => 'nullable|numeric',
            'students_count' => 'nullable|numeric',
            'courses_capacity' => 'nullable|numeric',
            'courses_count' => 'nullable|numeric',
            'meeting_count' => 'nullable|numeric',
            'product_count' => 'nullable|numeric',
        ]);

        $data = $request->all();

        if (empty($data['status']) or !in_array($data['status'], ['disabled', 'active'])) {
            $data['status'] = 'disabled';
        }

        $package = RegistrationPackage::create([
            'days' => $data['days'],
            'price' => $data['price'],
            'icon' => $data['icon'],
            'role' => $data['role'],
            'instructors_count' => $data['instructors_count'] ?? null,
            'students_count' => $data['students_count'] ?? null,
            'courses_capacity' => $data['courses_capacity'] ?? null,
            'courses_count' => $data['courses_count'] ?? null,
            'meeting_count' => $data['meeting_count'] ?? null,
            'product_count' => $data['product_count'] ?? null,
            'status' => $data['status'],
            'created_at' => time(),
        ]);


        RegistrationPackageTranslation::updateOrCreate([
            'registration_package_id' => $package->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ]);

        return redirect(route('adminRegistrationPackagesLists'));
    }

    public function edit(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_registration_packages_edit');

        $package = RegistrationPackage::findOrFail($id);

        $locale = $request->get('locale', app()->getLocale());
        storeContentLocale($locale, $package->getTable(), $package->id);

        $data = [
            'pageTitle' => trans('lms/admin/main.edit'),
            'package' => $package
        ];

        return view('lms.admin.financial.registration_packages.new', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_registration_packages_edit');

        $package = RegistrationPackage::findOrFail($id);

        $this->validate($request, [
            'title' => 'required|string',
            'description' => 'required|string',
            'days' => 'required|numeric',
            'price' => 'required|numeric',
            'icon' => 'required|string',
            'role' => 'required|in:instructors,organizations',
            'instructors_count' => 'nullable|numeric',
            'students_count' => 'nullable|numeric',
            'courses_capacity' => 'nullable|numeric',
            'courses_count' => 'nullable|numeric',
            'meeting_count' => 'nullable|numeric',
            'product_count' => 'nullable|numeric',
        ]);

        $data = $request->all();

        if (empty($data['status']) or !in_array($data['status'], ['disabled', 'active'])) {
            $data['status'] = 'disabled';
        }

        $package->update([
            'days' => $data['days'],
            'price' => $data['price'],
            'icon' => $data['icon'],
            'role' => $data['role'],
            'instructors_count' => $data['instructors_count'] ?? null,
            'students_count' => $data['students_count'] ?? null,
            'courses_capacity' => $data['courses_capacity'] ?? null,
            'courses_count' => $data['courses_count'] ?? null,
            'meeting_count' => $data['meeting_count'] ?? null,
            'product_count' => $data['product_count'] ?? null,
            'status' => $data['status'],
            'created_at' => time(),
        ]);


        RegistrationPackageTranslation::updateOrCreate([
            'registration_package_id' => $package->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ]);

        return redirect()->back();
    }

    public function delete($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_registration_packages_delete');

        $package = RegistrationPackage::findOrFail($id);

        $package->delete();

        return redirect(route('adminRegistrationPackagesLists'));
    }

    public function settings()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_registration_packages_settings');

        removeContentLocale();

        $names = [Setting::$registrationPackagesGeneralName, Setting::$registrationPackagesInstructorsName, Setting::$registrationPackagesOrganizationsName];

        $settings = Setting::whereIn('name', $names)->get()->keyBy('name');

        if (count($settings)) {
            foreach ($settings as $setting) {
                $setting->value = json_decode($setting->value, true);
            }
        }

        $pageGeneralSettings = (!empty($settings) and !empty($settings[Setting::$registrationPackagesGeneralName])) ? $settings[Setting::$registrationPackagesGeneralName]->value : null;
        $instructorsSettings = (!empty($settings) and !empty($settings[Setting::$registrationPackagesInstructorsName])) ? $settings[Setting::$registrationPackagesInstructorsName]->value : null;
        $organizationsSettings = (!empty($settings) and !empty($settings[Setting::$registrationPackagesOrganizationsName])) ? $settings[Setting::$registrationPackagesOrganizationsName]->value : null;

        $data = [
            'pageTitle' => trans('lms/admin/main.settings'),
            'pageGeneralSettings' => $pageGeneralSettings,
            'instructorsSettings' => $instructorsSettings,
            'organizationsSettings' => $organizationsSettings,
        ];

        return view('lms.admin.financial.registration_packages.settings.index', $data);
    }

    public function reports()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_registration_packages_reports');

        $query = Sale::where('type', Sale::$registrationPackage)
            ->whereNotNull('registration_package_id')
            ->whereNull('refund_at');

        $sales = deepClone($query)->with([
            'registrationPackage',
            'buyer'
        ])->orderBy('created_at')
            ->paginate(10);

        $totalBuyInstructorsPackages = deepClone($query)->whereHas('registrationPackage', function ($query) {
            $query->where('role', 'instructors');
        })->count();

        $totalBuyOrganizationPackages = deepClone($query)->whereHas('registrationPackage', function ($query) {
            $query->where('role', 'organizations');
        })->count();

        $data = [
            'pageTitle' => trans('lms/admin/main.reports'),
            'sales' => $sales,
            'totalBuyInstructorsPackages' => $totalBuyInstructorsPackages,
            'totalBuyOrganizationPackages' => $totalBuyOrganizationPackages,
        ];

        return view('lms.admin.financial.registration_packages.reports', $data);
    }
}
