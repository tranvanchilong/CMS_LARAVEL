<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->users;
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            trans('lms/admin/main.id'),
            trans('lms/admin/pages/users.full_name'),
            trans('lms/admin/main.email'),
            trans('lms/public.mobile'),
            trans('lms/admin/pages/users.role_name'),
            trans('lms/admin/pages/financial.income'),
            trans('lms/admin/pages/users.status'),
            trans('lms/admin/main.created_at'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->full_name,
            $user->email,
            $user->mobile,
            $user->role->name,
            20,
            $user->status,
            dateTimeFormat($user->created_at,'j M Y | H:i')
        ];
    }
}
