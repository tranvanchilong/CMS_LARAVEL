<?php

namespace App\Http\Controllers\LMS\Api\Web;

use App\Http\Controllers\LMS\Controller;
use App\Http\Resources\ProductResource;
use App\Models\LMS\AdvertisingBanner;
use App\Models\LMS\Api\Product;
use App\Models\LMS\Follow;
use App\Models\LMS\ProductCategory;
use App\Models\LMS\ProductSelectedSpecification;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();

        $products = Product::where('products.status', Product::$active)
            ->where('ordering', true)->handleFilters()->get();


        if (!empty($data['category_id'])) {
            $selectedCategory = ProductCategory::where('id', $data['category_id'])->first();
        }
        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'),
            [
                'products' => ProductResource::collection($products),
            ]);

    }

    public function show($id)
    {
        apiAuth();

        $product = Product::where('status', Product::$active)
            ->where('id', $id)
            ->with([
                'selectedSpecifications' => function ($query) {
                    $query->where('status', ProductSelectedSpecification::$Active);
                    $query->with(['specification']);
                },
                'comments' => function ($query) {
                    $query->where('status', 'active');
                    $query->whereNull('reply_id');
                    $query->with([
                        'replies' => function ($query) {
                            $query->where('status', 'active');
                        }
                    ]);
                    $query->orderBy('created_at', 'desc');
                },
                'files' => function ($query) {
                    $query->where('status', 'active');
                    $query->orderBy('order', 'asc');
                },
                'reviews' => function ($query) {
                    $query->where('status', 'active');
                    $query->with([
                        'comments' => function ($query) {
                            $query->where('status', 'active');
                        },
                    ]);
                },
            ])
            ->first();

        if (empty($product)) {
            abort(404);
        }

        $selectableSpecifications = $product->selectedSpecifications->where('allow_selection', true)
              ->where('type', 'multi_value');
          $selectedSpecifications = $product->selectedSpecifications->where('allow_selection', false);
         $seller = $product->creator;

        $resource = new ProductResource($product);
        $resource->show = true;
        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'),
            [
                'product' =>$resource ,

            ]);


        return view('lms.'. getTemplate() . '.products.show', $data);
    }


}
