<?php

namespace App\Http\Controllers;

use App\Contract;
use App\ContractDetail;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class ContractController extends Controller
{
    protected $contract;

    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }
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
        $data = ContractDetail::with(['contract', 'price.product']);

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
                'customer_id' => $value->contract->customer_id,
                'number' => $value->contract->number,
                'price_id' => $value->price_id,
                'quantity' => $value->quantity,
                'selling_price' => $value->selling_price,
                'date' => $value->contract->date,
                'deadline' => $value->deadline,
                'order' => $value->order,
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
        $value = $request->all();
        $this->contract->fill($value)->save();

        foreach ($request->details as $detail) {
            $this->contract->contractDetails()->create($detail);
        }

        return response()->json('success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function show(Contract $contract)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function edit(Contract $contract)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contract $contract)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contract $contract)
    {
        //
    }
}
