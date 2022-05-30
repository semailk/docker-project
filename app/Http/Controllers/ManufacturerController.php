<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ManufacturerController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|min:3|max:100',
            'country' => 'required|min:3|max:100'
        ]);

        if (Manufacturer::query()->where('title', '=', $request->title)->get()->isNotEmpty()) {
            return response()->json(['errors' => ['Name unique error']], Response::HTTP_BAD_REQUEST);
        }

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], Response::HTTP_BAD_REQUEST);
        }
        $manufacturer = new Manufacturer();
        $manufacturer->title = $request->title;
        $manufacturer->country = $request->country;
        $manufacturer->save();

        return response()->json(['success'], Response::HTTP_OK);
    }
}
