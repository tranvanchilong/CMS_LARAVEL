<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RegistrationBonusExport implements FromCollection, WithHeadings, WithMapping
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
            trans('lms/update.user_id'),
            trans('lms/admin/main.name'),
            trans('lms/admin/main.mobile'),
            trans('lms/admin/main.email'),
            trans('lms/admin/main.role'),
            trans('lms/update.bonus'),
            trans('lms/update.referred_users'),
            trans('lms/update.referred_purchases'),
            trans('lms/update.registration_date'),
            trans('lms/update.bonus_status'),
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
            $user->mobile,
            $user->email,
            $user->role->caption,
            handlePrice($user->registration_bonus_amount ?? 0),
            $user->referred_users ?? 0,
            $user->referred_purchases ?? 0,
            dateTimeFormat($user->created_at, 'j M Y'),
            $user->bonus_status
        ];
    }
}
