<?php

namespace App\Http\Controllers\LMS\Panel;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Filter;

class FilterController extends Controller
{
    public function getByCategoryId($categoryId)
    {
        $defaultLocale = getDefaultLocale();

        $filters = Filter::select('*')
            ->where('category_id', $categoryId)
            ->with([
                'options'  => function ($query) {
                    $query->orderBy('order', 'asc');
                },
            ])
            ->get();

        return response()->json([
            'filters' => $filters,
            'defaultLocale' => mb_strtolower($defaultLocale)
        ], 200);
    }
}
