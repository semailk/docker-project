<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Manufacturer;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), ProductCreateRequest::rules(), ProductCreateRequest::PRODUCT_ERROR_MESSAGES);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], Response::HTTP_BAD_REQUEST);
        }

        $newProduct = new Product();
        $newProduct->description = $request->description;
        $newProduct->image = '/storage/' . $request->file('image')->store('images', 'public');
        $newProduct->name = $request->name;
        $newProduct->save();

        Manufacturer::query()->whereIn('id', $request->manufacturers)->get()->each(function (Manufacturer $manufacturer) use ($newProduct) {
            $manufacturer->products()->attach($newProduct);
        });

        return \response()->json(['success'], Response::HTTP_OK);
    }

    public function show(Product $product)
    {
        $product->relations = $product->belongsToMany(Manufacturer::class)->get()->pluck('id')->flatten()->toArray();

        return $product;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), ProductUpdateRequest::rules(), ProductCreateRequest::PRODUCT_ERROR_MESSAGES);
        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], Response::HTTP_BAD_REQUEST);
        }
        $product = Product::query()->find($request->product_id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->save();
        $product->manufacturer()->sync($request->manufacturers);

        return \response()->json(['success'], Response::HTTP_OK);
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function delete(Product $product): JsonResponse
    {
        File::delete('storage/images/' . Arr::last(explode('/', $product->image)));
        $product->delete();

        return \response()->json(['success'], Response::HTTP_OK);
    }
}
