<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class salesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $sales;

    public function __construct($sales)
    {
        $this->sales = $sales;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->sales;
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            trans('lms/admin/main.id'),
            trans('lms/admin/main.student'),
            trans('lms/admin/main.student') . ' ' . trans('lms/admin/main.id'),
            trans('lms/admin/main.instructor'),
            trans('lms/admin/main.instructor') . ' ' . trans('lms/admin/main.id'),
            trans('lms/admin/main.paid_amount'),
            trans('lms/admin/main.item'),
            trans('lms/admin/main.item') . ' ' . trans('lms/admin/main.id'),
            trans('lms/admin/main.sale_type'),
            trans('lms/admin/main.date'),
            trans('lms/admin/main.status'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function map($sale): array
    {

        if ($sale->payment_method == \App\Models\LMS\Sale::$subscribe) {
            $paidAmount = trans('lms/admin/main.subscribe');
        } else {
            if (!empty($sale->total_amount)) {
                $paidAmount = addCurrencyToPrice(handlePriceFormat($sale->total_amount));
            } else {
                $paidAmount = trans('lms/public.free');
            }
        }

        $status = (!empty($sale->refund_at)) ? trans('lms/admin/main.refund') : trans('lms/admin/main.success');

        return [
            $sale->id,
            !empty($sale->buyer) ? $sale->buyer->full_name : 'Deleted User',
            !empty($sale->buyer) ? $sale->buyer->id : 'Deleted User',
            $sale->item_seller,
            $sale->seller_id,
            $paidAmount,
            $sale->item_title,
            $sale->item_id,
            trans('lms/admin/main.' . $sale->type),
            dateTimeFormat($sale->created_at, 'j M Y H:i'),
            $status
        ];
    }
}
