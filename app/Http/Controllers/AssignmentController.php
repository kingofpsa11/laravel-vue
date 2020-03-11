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
        if ($request->manufacturer_order_id) {
            $manufacturerOrder = ManufacturerOrder::with([
                'manufacturerOrderDetails.contractDetail.price.product.boms.bomDetails'
            ])
                ->find($request->manufacturer_order_id);

            $assignment5 = null;
            $assignment4 = null;
            $assignment3 = null;
            $assignment2 = null;
            
            foreach ($manufacturerOrder->manufacturerOrderDetails as $manufacturerOrderDetail) {
                $contractDetail = $manufacturerOrderDetail->contractDetail;

                if ($contractDetail->price->product->category_id === 2) {
                    if ($assignment5 === null) {
                        $assignment = new Assignment();
                        $assignment->number = $this->getNewNumber();
                        $assignment->date = date('d/m/Y');
                        $assignment->factory_id = 5;
                        $assignment->save();
                        $assignment5 = $assignment->id;
                    }

                    $assignment->assignmentDetails()->create(
                        [
                            'product_id' => $contractDetail->price->product->id,
                            'quantity' => $contractDetail->quantity,
                            'contract_detail_id' => $contractDetail->id,
                            'deadline' => $contractDetail->deadline,
                        ]
                    );

                    $bom = $contractDetail->price->product->boms->first();

                    if ($bom && $contractDetail->status === 10) {
                        // $manufacturerOrderDetail->contractDetail()->update(['status' => 9]);

                        foreach ($bom->bomDetails as $bomDetail) {
                            $issetBomDetail = $bomDetail->product->boms->first();

                            if ($issetBomDetail) {
                                $assignmentCotToHan = new Assignment();
                                $assignmentCotToHan->number = $this->getNewNumber();
                                $assignmentCotToHan->date = date('d/m/Y');
                                $assignmentCotToHan->factory_id = 4;
                                $assignmentCotToHan->save();
                                
                                $assignmentCotToHan->assignmentDetails()->create(
                                    [
                                        'product_id' => $bomDetail->product_id,
                                        'quantity' => $bomDetail->quantity * $contractDetail->quantity,
                                        'contract_detail_id' => $contractDetail->id,
                                        'deadline' => $contractDetail->deadline,
                                    ]
                                );

                                
                                foreach($issetBomDetail->bomDetails as $bomDetail) {
                                    $issetBomDetailDetail = $issetBomDetail->product->boms->first();

                                    if ($issetBomDetailDetail) {
                                        $assignmentCotCatDap = new Assignment();
                                        $assignmentCotCatDap->number = $this->getNewNumber();
                                        $assignmentCotCatDap->date = date('d/m/Y');
                                        $assignmentCotCatDap->factory_id = 3;
                                        $assignmentCotCatDap->save();
                                        
                                        $assignmentCotCatDap->assignmentDetails()->create(
                                            [
                                                'product_id' => $bomDetail->product_id,
                                                'quantity' => $bomDetail->quantity * $contractDetail->quantity,
                                                'contract_detail_id' => $contractDetail->id,
                                                'deadline' => $contractDetail->deadline,
                                            ]
                                        );
                                    }
                                }
                            }
                        }
                    }
                } else {
                    if ($contractDetail->status === 10) {
                        if ($assignment2 === null) {
                            $assignment = new Assignment();
                            $assignment->number = $this->getNewNumber();
                            $assignment->date = date('d/m/Y');
                            $assignment->factory_id = 2;
                            $assignment->save();
                            $assignment2 = $assignment->id;
                        }

                        $assignment->assignmentDetails()->create(
                            [
                                'product_id' => $contractDetail->price->product->id,
                                'quantity' => $contractDetail->quantity,
                                'contract_detail_id' => $contractDetail->id,
                                'deadline' => $contractDetail->deadline,
                            ]
                        );
                    }
                }                }
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
        $number = Assignment::whereYear('date', date('Y'))->orderBy('number', 'desc')->first();
        return $number === NULL ? 1 : ($number->number + 1);
    }
}
