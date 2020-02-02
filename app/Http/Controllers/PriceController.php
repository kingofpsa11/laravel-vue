<?php

namespace App\Http\Controllers;

use App\Price;
use App\ProfitRate;
use App\Http\Resources\PriceCollection;
use App\Http\Resources\PriceResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new PriceCollection(Price::orderBy('id', 'desc')->take(20)->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $price = Price::create($request->all());
        return new PriceResource($price);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function show(Price $price)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function edit(Price $price)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Price $price)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function destroy(Price $price)
    {
        //
    }

    public function getPrice(Request $request)
    {
        $term = $request->q;
        $customer = $request->customer_id;
        $profitRate = ProfitRate::where('customer_id', $customer)->first();

        if ($profitRate) {
            $result = DB::table('prices')
                ->join('products', 'prices.product_id', '=', 'products.id')
                ->leftJoin('profit_rates', 'profit_rates.category_id', '=', 'products.category_id')
                ->select('prices.id', 'products.name', 'products.code', DB::raw('ROUND(prices.selling_price * profit_rates.rate, -3) as sell_price'))
                ->where('profit_rates.customer_id', '=', $customer)
                ->where('products.name', 'LIKE', '%' . $term . '%')
                ->take(20)
                ->get();
        } else {
            $result = DB::table('prices')
                ->join('products', 'prices.product_id', '=', 'products.id')
                ->select('prices.id', 'products.name', 'products.code', DB::raw('prices.selling_price as sell_price'))
                ->where('products.name', 'LIKE', '%' . $term . '%')
                ->take(20)
                ->get();
        }

        return response()->json($result, 200);
    }
}
