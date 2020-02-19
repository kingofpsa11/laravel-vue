<?php

namespace App\Http\Controllers;

use App\Bom;
use App\BomDetail;
use App\Product;
use App\Http\Resources\BomResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $results = [];
        extract($request->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));
        $query = json_decode($query);
        $data = Bom::with('product');

        if (isset($query) && $query) {
            $data = $byColumn == 1 ?
                $this->filterByColumn($data, $query) :
                $this->filter($data, $query, $fields);
        }

        $count = $data->count();
        $data->limit($limit)
            ->skip($limit * ($page - 1));

        if (isset($orderBy)) {
            $direction = $ascending == 1 ? 'ASC' : 'DESC';
            $data->orderBy($orderBy, $direction);
        }

        $result = $data->get();

        foreach ($result as $value) {
            $results[] = [
                'id' => $value->id,
                'product_code' => $value->product->code,
                'product_name' => $value->product->name,
                'status' => $value->status,
            ];
        }

        return [
            'data' => $results,
            'count' => $count,
        ];
    }

    protected function filterByColumn($data, $queries)
    {
        return $data->where(function ($q) use ($queries) {
            foreach ($queries as $field => $query) {
                if (is_string($query)) {
                    $q->where($field, 'LIKE', "%{$query}%");
                } else {
                    $start = Carbon::createFromFormat('Y-m-d', $query['start'])->startOfDay();
                    $end = Carbon::createFromFormat('Y-m-d', $query['end'])->endOfDay();
                    $q->whereBetween($field, [$start, $end]);
                }
            }
        });
    }

    protected function filter($data, $query, $fields)
    {
        return $data->where(function ($q) use ($query, $fields) {
            foreach ($fields as $index => $field) {
                $method = $index ? 'orWhere' : 'where';
                $q->{$method}($field, 'LIKE', "%{$query}%");
            }
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bom = new Bom();
        $bom->fill($request->all())->save();
        foreach ($request->bom_details as $detail) {
            $bom->bomDetails()->create($detail);
        }

        return response()->json([
            'id' => $bom->id,
            'status' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bom  $bom
     * @return \Illuminate\Http\Response
     */
    public function show(Bom $bom)
    {
        $bom->load('bomDetails.product');
        return new BomResource($bom);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bom  $bom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bom $bom)
    {
        $bom->update($request->all());
        $bom->bomDetails()->update(['status' => 9]);

        for ($i = 0; $i < count($request->code); $i++) {
            BomDetail::updateOrCreate(
                [
                    'id' => $request->bom_detail_id[$i]
                ],
                [
                    'bom_id' => $bom->id,
                    'product_id' => $request->bom_product_id[$i],
                    'quantity' => $request->quantity[$i],
                    'status' => 10
                ]
            );

            $bom->bomDetails()->where('status', 9)->delete();
        }

        return redirect()->route('boms.show', $bom);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bom  $bom
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bom $bom)
    {
        $bom->delete();
        flash('Đã xóa định mức sản phẩm: ' . $bom->product->name)->success();
        return redirect()->route('boms.index');
    }

    public function getBom(Request $request)
    {
        $bom = DB::table('boms as b')
            ->join('bom_details AS bd', 'b.id', '=', 'bd.bom_id')
            //            ->join('boms as b', 'b.product_id', '=', 'bd.product_id')
            ->join('products AS p', 'p.id', '=', 'bd.product_id')
            ->where('b.product_id', $request->productId)
            ->select('bd.product_id', 'p.name AS product_name', 'p.code', 'bd.quantity', 'b.name', 'b.id as bom_id', 'bd.id as bom_detail_id')
            ->get();

        return response()->json($bom);
    }
}
