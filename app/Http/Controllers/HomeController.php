<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * @return View
     */
    public function welcome(): View
    {
        $products = DB::select("SELECT p.name,p.description,p.id,p.image FROM products p ORDER BY p.created_at DESC");
        $manufacturer = Manufacturer::all();

        return view('welcome', compact('products', 'manufacturer'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function productsSort(Request $request): JsonResponse
    {
        if ($request->has('order_by')) {
            $products = DB::select("SELECT p.name,p.description,p.id,p.image FROM  products p ORDER BY p.created_at $request->order_by");
        }

        if (isset($request->manufacturer) && $request->manufacturer !== 0) {
            $products = DB::select("SELECT p.name,p.description,p.id,p.image FROM laravel.manufacturer_product lm
                                        INNER JOIN products p ON p.id = lm.product_id WHERE lm.manufacturer_id = $request->manufacturer ORDER BY p.created_at DESC");
        }
        if ($request->manufacturer == '0') {
            $products = DB::select("SELECT p.name,p.description,p.id,p.image FROM products p ORDER BY p.created_at DESC");
        }
        if ($request->has('search')) {
            $products = DB::select("SELECT p.name,p.description,p.id,p.image FROM products p WHERE p.name LIKE '%$request->search%' ORDER BY p.created_at DESC");
        }
        return response()->json($products, Response::HTTP_OK);
    }
}
