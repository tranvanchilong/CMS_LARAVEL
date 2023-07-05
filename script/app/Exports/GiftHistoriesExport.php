<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GiftHistoriesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $gifts;

    public function __construct($gifts)
    {
        $this->gifts = $gifts;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->gifts;
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            trans('lms/admin/main.title'),
            trans('lms/admin/main.sender'),
            trans('lms/update.sender_mobile'),
            trans('lms/update.sender_email'),
            trans('lms/update.receipt'),
            trans('lms/update.receipt_email'),
            trans('lms/update.receipt_status'),
            trans('lms/update.gift_message'),
            trans('lms/admin/main.amount'),
            trans('lms/update.submit_date'),
            trans('lms/update.receive_date'),
            trans('lms/update.gift_status'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function map($gift): array
    {
        $status = trans('lms/update.sent');
        if (!empty($gift->date) and $gift->date > time()) {
            $status = trans('lms/admin/main.pending');
        } elseif ($gift->status == 'cancel') {
            $status = trans('lms/admin/main.pending');
        }

        return [
            $gift->getItemTitle(),
            $gift->user->full_name,
            $gift->user->mobile,
            $gift->user->email,
            !empty($gift->receipt) ? $gift->receipt->full_name : $gift->name,
            $gift->email,
            !empty($gift->receipt) ? trans('lms/update.registered') : trans('lms/update.unregistered'),
            $gift->description,
            (!empty($gift->sale) and $gift->sale->total_amount > 0) ? handlePrice($gift->sale->total_amount) : trans('lms/admin/main.free'),
            dateTimeFormat($gift->created_at, 'j M Y H:i'),
            !empty($gift->date) ? dateTimeFormat($gift->date, 'j M Y H:i') : trans('lms/update.instantly'),
            $status
        ];
    }
}
