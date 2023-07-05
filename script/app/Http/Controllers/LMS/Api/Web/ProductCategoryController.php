<?php

namespace App\Http\Controllers\LMS\Api\Web;

use App\Http\Controllers\LMS\Controller;
use App\Http\Resources\ProductCategoryResource;
use App\Models\LMS\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{

    public function index()
    {
        $categories = ProductCategory::whereNull('parent_id')
            ->with([
                'subCategories' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
            ])
            ->get();

        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'), [
            'categories' => ProductCategoryResource::collection($categories)
        ]);
    }
}
