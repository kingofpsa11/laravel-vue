<?php

namespace App\Http\Controllers;

use App\MaterialRequisition;
use App\MaterialRequisitionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MaterialRequisitionController extends Controller
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
        $data = MaterialRequisitionDetail::with(['materialRequisition', 'product']);

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
                'id' => $value->material_requisition_id,
                'number' => $value->materialRequisition->number,
                'product_name' => $value->product->name,
                'product_code' => $value->product->code,
                'quantity' => $value->quantity,
                'date' => $value->materialRequisition->date,
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
     * Display the specified resource.
     *
     * @param  \App\MaterialRequisition  $materialRequisition
     * @return \Illuminate\Http\Response
     */
    public function show(MaterialRequisition $materialRequisition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MaterialRequisition  $materialRequisition
     * @return \Illuminate\Http\Response
     */
    public function edit(MaterialRequisition $materialRequisition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MaterialRequisition  $materialRequisition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MaterialRequisition $materialRequisition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MaterialRequisition  $materialRequisition
     * @return \Illuminate\Http\Response
     */
    public function destroy(MaterialRequisition $materialRequisition)
    {
        //
    }
}
