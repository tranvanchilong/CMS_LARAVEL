<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AgoraHistoryExport implements FromCollection, WithHeadings, WithMapping
{
    protected $agoraHistories;

    public function __construct($agoraHistories)
    {
        $this->agoraHistories = $agoraHistories;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->agoraHistories;
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            trans('lms/admin/main.course'),
            trans('lms/admin/main.session'),
            trans('lms/update.session_duration'),
            trans('lms/admin/main.start_date'),
            trans('lms/admin/main.end_date'),
            trans('lms/update.meeting_duration'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function map($agoraHistory): array
    {
        $meetingDuration = ($agoraHistory->end_at - $agoraHistory->start_at) / 60;

        return [
            $agoraHistory->session->webinar->title,
            $agoraHistory->session->title,
            convertMinutesToHourAndMinute($agoraHistory->session->duration),
            dateTimeFormat($agoraHistory->start_at, 'j M Y | H:i'),
            dateTimeFormat($agoraHistory->end_at, 'j M Y | H:i'),
            convertMinutesToHourAndMinute($meetingDuration)
        ];
    }
}
