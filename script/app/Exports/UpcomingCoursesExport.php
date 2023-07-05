<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UpcomingCoursesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $upcomingCourses;

    public function __construct($upcomingCourses)
    {
        $this->upcomingCourses = $upcomingCourses;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->upcomingCourses;
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            trans('lms/admin/main.id'),
            trans('lms/admin/main.title'),
            trans('lms/admin/main.instructor'),
            trans('lms/admin/main.type'),
            trans('lms/admin/main.price'),
            trans('lms/update.followers'),
            trans('lms/admin/main.start_date'),
            trans('lms/admin/main.created_at'),
            trans('lms/admin/main.status'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function map($upcomingCourse): array
    {
        return [
            $upcomingCourse->id,
            $upcomingCourse->title,
            $upcomingCourse->teacher->full_name,
            $upcomingCourse->type,
            $upcomingCourse->price,
            $upcomingCourse->followers_count,
            dateTimeFormat($upcomingCourse->publish_date, 'Y M j | H:i'),
            dateTimeFormat($upcomingCourse->created_at, 'j M Y | H:i'),
            $upcomingCourse->status,
        ];
    }
}
