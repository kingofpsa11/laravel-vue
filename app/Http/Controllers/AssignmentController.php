<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\AssignmentDetail;
use App\ManufacturerOrder;
use App\Http\Resources\AssignmentResource;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AssignmentController extends Controller
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
        $data = AssignmentDetail::with(['assignment.factory', 'product', 'contractDetail.manufacturerOrderDetail.manufacturerOrder']);

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
                'id' => $value->assignment_id,
                'factory_name' => $value->assignment->factory->name ?? '',
                'manufacturer_order_number' => $value->contractDetail->manufacturerOrderDetail->manufacturerOrder->number,
                'number' => $value->assignment->number,
                'product_name' => $value->product->name,
                'product_code' => $value->product->code,
                'quantity' => $value->quantity,
                'date' => $value->assignment->date,
                'deadline' => $value->deadline,
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
        if (isset($request->manufacturer_order_id)) {
            $manufacturerOrder = ManufacturerOrder::with([
                'manufacturerOrderDetails.contractDetail.price.product.boms.bomDetails'
            ])
                ->find($request->manufacturer_order_id);

            $assignment = new Assignment();
            $assignment->number = $this->getNewNumber();
            $assignment->date = date('d/m/Y');
            if ($assignment->save()) {
                foreach ($manufacturerOrder->manufacturerOrderDetails as $manufacturerOrderDetail) {
                    $bom = $manufacturerOrderDetail->contractDetail->price->product->boms->first();
                    if (isset($bom)) {
                        foreach ($bom->bomDetails as $bomDetail) {
                            $assignment->assignmentDetails()->create(
                                [
                                    'product_id' => $bomDetail->product_id,
                                    'quantity' => $bomDetail->quantity * $manufacturerOrderDetail->contractDetail->quantity,
                                    'contract_detail_id' => $manufacturerOrderDetail->contract_detail_id,
                                    'deadline' => $manufacturerOrderDetail->contractDetail->deadline,
                                ]
                            );
                        }
                    }
                }
            }

            $manufacturerOrder->contract()->update(['status' => 9]);
        } else {
            $assignment = new Assignment();
            if ($assignment->fill($request->all())->save()) {
                foreach ($request->assignment_details as $detail) {
                    $assignment->assignmentDetails()->create($detail);
                }
            }
        }

        return response()->json([
            'id' => $assignment->id,
            'status' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Assignment  $assignment
     * @return \Illuminate\Http\Response
     */
    public function show(Assignment $assignment)
    {
        $assignment->load('assignmentDetails.contractDetail.manufacturerOrderDetail.manufacturerOrder', 'assignmentDetails.product', 'factory');

        return new AssignmentResource($assignment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Assignment  $assignment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Assignment $assignment)
    {
        $assignment->update($request->all());
        $assignment->assignmentDetails()->update(['status' => 9]);

        foreach ($request->assignment_details as $detail) {
            $id = $detail['id'];
            unset($detail['id']);
            $detail['status'] = 10;
            $assignment->assignmentDetails()->updateOrCreate(['id' => $id], $detail);
        }

        $assignment->assignmentDetails()->where('status', 9)->delete();

        return response()->json([
            'id' => $assignment->id,
            'status' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Assignment  $assignment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assignment $assignment)
    {
        //
    }

    public function getNewNumber()
    {
        return Assignment::whereYear('date', date('Y'))->orderBy('number', 'desc')->first()->number + 1 ?? 1;
    }
}
