<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StoreOrdersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->orders;
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            trans('lms/admin/main.id'),
            trans('lms/update.customer'),
            trans('lms/update.customer_id'),
            trans('lms/admin/main.seller'),
            trans('lms/update.seller_id'),
            trans('lms/admin/main.type'),
            trans('lms/update.quantity'),
            trans('lms/admin/main.paid_amount'),
            trans('lms/admin/main.discount'),
            trans('lms/admin/main.tax'),
            trans('lms/admin/main.date'),
            trans('lms/admin/main.status'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function map($order): array
    {
        if ($order->status == \App\Models\LMS\ProductOrder::$waitingDelivery) {
            $status = trans('lms/update.product_order_status_waiting_delivery');
        } elseif ($order->status == \App\Models\LMS\ProductOrder::$success) {
            $status = trans('lms/update.product_order_status_success');
        } elseif ($order->status == \App\Models\LMS\ProductOrder::$shipped) {
            $status = trans('lms/update.product_order_status_shipped');
        } elseif ($order->status == \App\Models\LMS\ProductOrder::$canceled) {
            $status = trans('lms/update.product_order_status_canceled');
        }

        return [
            $order->id,
            !empty($order->buyer) ? $order->buyer->full_name : '',
            !empty($order->buyer) ? $order->buyer->id : '',
            !empty($order->seller) ? $order->seller->full_name : '',
            !empty($order->seller) ? $order->seller->id : '',
            !empty($order->product) ? trans('lms/update.product_type_' . $order->product->type) : '',
            $order->quantity,
            !empty($order->sale) ? addCurrencyToPrice(handlePriceFormat($order->sale->total_amount)) : '',
            !empty($order->sale) ? addCurrencyToPrice(handlePriceFormat($order->sale->discount)) : '',
            !empty($order->sale) ? addCurrencyToPrice(handlePriceFormat($order->sale->tax)) : '',
            dateTimeFormat($order->created_at, 'j F Y H:i'),
            $status ?? '',
        ];
    }
}
