<?php

namespace App\Http\Controllers\LMS\Panel\LMS\Store;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\ProductFilter;
use Illuminate\Http\Request;

class ProductFilterController extends Controller
{
    public function getByCategoryId($categoryId)
    {
        $defaultLocale = getDefaultLocale();

        $filters = ProductFilter::select('*')
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
