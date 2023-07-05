<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PayoutExport implements FromCollection, WithHeadings, WithMapping
{
    protected $payouts;

    public function __construct($payouts)
    {
        $this->payouts = $payouts;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->payouts;
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            trans('lms/admin/main.user'),
            trans('lms/admin/main.role'),
            trans('lms/admin/main.payout_amount'),
            trans('lms/admin/main.bank'),
            trans('lms/admin/main.account_id'),
            trans('lms/admin/main.iban'),
            trans('lms/admin/main.phone'),
            trans('lms/admin/main.last_payout_date'),
            trans('lms/admin/main.status'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function map($payout): array
    {
        return [
            $payout->user->full_name,
            $payout->user->role->caption,
            addCurrencyToPrice($payout->amount),
            $payout->account_bank_name,
            $payout->user->account_id,
            $payout->account_number,
            $payout->user->mobile,
            dateTimeFormat($payout->created_at, 'Y/m/j-H:i'),
            trans('lms/public.'.$payout->status)
        ];
    }
}
