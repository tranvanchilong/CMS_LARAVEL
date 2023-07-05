<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CashbackTransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->transactions;
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            trans('lms/admin/main.user'),
            trans('lms/admin/main.product'),
            trans('lms/update.target_type'),
            trans('lms/admin/main.description'),
            trans('lms/admin/main.amount'),
            trans('lms/update.cashback_amount'),
            trans('lms/admin/main.date'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function map($transaction): array
    {
        $product = null;
        $targetType = null;

        if (!empty($transaction->webinar_id)) {
            $product = '#' . $transaction->webinar_id . ' - ' . (!empty($transaction->webinar) ? $transaction->webinar->title : '');
            $targetType = trans('lms/update.target_types_courses');
        } else if (!empty($transaction->bundle_id)) {
            $product = '#' . $transaction->bundle_id . ' - ' . (!empty($transaction->bundle) ? $transaction->bundle->title : '');
            $targetType = trans('lms/update.target_types_bundles');
        } else if (!empty($transaction->product_id)) {
            $product = '#'. $transaction->product_id . ' - ' . (!empty($transaction->product) ? $transaction->product->title : '');
            $targetType = trans('lms/update.target_types_store_products');
        } else if (!empty($transaction->meeting_time_id)) {
            $product = '#'. $transaction->meeting_time_id . ' - ' . trans('lms/admin/main.meeting');
            $targetType = trans('lms/update.target_types_meetings');
        } else if (!empty($transaction->subscribe_id)) {
            $product = trans('lms/admin/main.purchased_subscribe');
            $targetType = trans('lms/update.target_types_subscription_packages');
        } else if (!empty($transaction->promotion_id)) {

        } else if (!empty($transaction->registration_package_id)) {
            $product = trans('lms/update.purchased_registration_package');
            $targetType = trans('lms/update.target_types_registration_packages');
        }

        return [
            $transaction->user->full_name,
            $product,
            $targetType,
            $transaction->description,
            handlePrice($transaction->purchase_amount),
            handlePrice($transaction->amount),
            dateTimeFormat($transaction->created_at, 'j M Y'),
        ];
    }
}
