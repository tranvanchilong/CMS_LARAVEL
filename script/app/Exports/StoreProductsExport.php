<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StoreProductsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->products;
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            trans('lms/admin/main.id'),
            trans('lms/admin/main.title'),
            trans('lms/admin/main.creator'),
            trans('lms/admin/main.type'),
            trans('lms/update.inventory'),
            trans('lms/admin/main.price'),
            trans('lms/update.delivery_fee'),
            trans('lms/admin/main.sales'),
            trans('lms/admin/main.income'),
            trans('lms/admin/main.updated_at'),
            trans('lms/admin/main.created_at'),
            trans('lms/admin/main.status'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function map($product): array
    {
        $getAvailability = $product->getAvailability();

        $status = '';

        switch ($product->status) {
            case(\App\Models\LMS\Product::$active):
                $status = trans('lms/admin/main.published');
                break;
            case(\App\Models\LMS\Product::$draft):
                $status = trans('lms/admin/main.is_draft');
                break;
            case(\App\Models\LMS\Product::$pending):
                $status = trans('lms/admin/main.waiting');
                break;
            case(\App\Models\LMS\Product::$inactive):
                $status = trans('lms/public.rejected');
                break;
        }

        return [
            $product->id,
            !empty($product->category) ? $product->category->title : '',
            !empty($product->creator) ? $product->creator->full_name : '',
            trans('lms/update.' . $product->type),
            ($getAvailability == 99999) ? trans('lms/update.unlimited') : $getAvailability,
            !empty($product->price) ? addCurrencyToPrice($product->price) : '-',
            $product->delivery_fee ? addCurrencyToPrice($product->delivery_fee) : '-',
            $product->salesCount(),
            addCurrencyToPrice($product->sales()->sum('total_amount')),
            dateTimeFormat($product->updated_at, 'Y M j | H:i'),
            dateTimeFormat($product->created_at, 'Y M j | H:i'),
            $status,
        ];
    }
}
