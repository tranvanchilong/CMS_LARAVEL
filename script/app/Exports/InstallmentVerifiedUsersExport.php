<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InstallmentVerifiedUsersExport implements FromCollection, WithHeadings, WithMapping
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
            trans('lms/admin/main.user'),
            trans('lms/admin/main.mobile'),
            trans('lms/admin/main.email'),
            trans('lms/admin/main.register_date'),
            trans('lms/update.total_purchases'),
            trans('lms/update.total_installments'),
            trans('lms/update.installments_count'),
            trans('lms/update.overdue_installments'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function map($user): array
    {

        return [
            $user->id . ' - ' . $user->full_name,
            $user->mobile,
            $user->email,
            dateTimeFormat($user->created_at, 'j M Y'),
            handlePrice($user->getPurchaseAmounts()),
            handlePrice($user->totalAmount),
            $user->unpaidStepsCount . ($user->unpaidStepsAmount ? " (" . handlePrice($user->unpaidStepsAmount) . ")" : ''),
            $user->overdueCount . ($user->overdueAmount ? " (" . handlePrice($user->overdueAmount) . ")" : '')
        ];
    }
}
