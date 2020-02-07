<?php

namespace App\Http\Controllers;

use App\Contract;
use App\ContractDetail;
use App\Http\Resources\ManufacturerResource;
use App\ManufacturerOrder;
use App\ManufacturerOrderDetail;
use App\Supplier;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManufacturerOrderController extends Controller
{
    protected $manufacturerOrder;

    public function __construct(ManufacturerOrder $manufacturerOrder)
    {
        $this->manufacturerOrder = $manufacturerOrder;
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
        $data = ManufacturerOrderDetail::with(
            'manufacturerOrder',
            'contractDetail.price.product',
        );

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
                'id' => $value->manufacturer_order_id,
                'contract_number' => $value->contractDetail->contract->number,
                'product_name' => $value->contractDetail->price->product->name,
                'quantity' => $value->contractDetail->quantity,
                'date' => $value->manufacturerOrder->date,
                'deadline' => $value->contractDetail->deadline,
                'manufacturer_order_number' => $value->manufacturerOrder->number,
                'status' => $value->status
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::all();
        return view('manufacturer-order.create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ManufacturerOrder  $manufacturerOrder
     * @return \Illuminate\Http\Response
     */
    public function show(ManufacturerOrder $manufacturerOrder)
    {
        $manufacturerOrder->load('manufacturerOrderDetails.contractDetail.contract.customer');
        return new ManufacturerResource($manufacturerOrder);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ManufacturerOrder  $manufacturerOrder
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ManufacturerOrder  $manufacturerOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ManufacturerOrder $manufacturerOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ManufacturerOrder  $manufacturerOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(ManufacturerOrder $manufacturerOrder)
    {
        //
    }

    public function getNewNumber($supplier_id)
    {
        $newNumber =  ManufacturerOrder::where('supplier_id', $supplier_id)->whereYear('created_at', '>=', date('Y'))->orderBy('number', 'desc')->first();
        if (!isset($newNumber)) {
            return 1;
        }
        return ((int) $newNumber->number + 1);
    }

    public function showManufacturer()
    {
        $contract_details = ContractDetail::whereNotNull('manufacturer_order_id')
            ->where('status', '!=', 0)
            ->orderBy('id', 'desc')
            ->take(1000)
            ->get();
        return view('manufacturer-order.index')->with('contract_details', $contract_details);
    }

    public function getManufacturerByStatus(Request $request)
    {
        $search = $request->q;

        $query = DB::table('manufacturer_note_details AS mnd')
            ->groupBy('mnd.contract_detail_id')
            ->select('mnd.contract_detail_id', DB::raw('SUM(mnd.quantity) AS total_quantity'));

        $result = DB::table('manufacturer_orders AS m')
            ->where('m.number', 'LIKE', '%' . $search . '%')
            ->where('md.status', 10)
            ->orderBy('m.id', 'desc')
            ->join('manufacturer_order_details AS md', 'm.id', 'md.manufacturer_order_id')
            ->join('contract_details AS c', 'c.id', 'md.contract_detail_id')
            ->join('prices', 'prices.id', 'c.price_id')
            ->join('products AS p', 'p.id', 'prices.product_id')
            ->leftJoinSub($query, 'mnd', function ($join) {
                $join->on('mnd.contract_detail_id', '=', 'md.contract_detail_id');
            })
            ->select(
                'c.id',
                'm.number',
                'p.name',
                'p.code',
                'p.id AS product',
                DB::raw('(c.quantity - IFNULL(mnd.total_quantity, 0)) AS quantity')
            )
            ->having('quantity', '>', 0)
            ->get();

        return response()->json($result, 200);
    }

    public function getAllManufacturers(Request $request)
    {
        $columns = array(
            0 => 'date',
            1 => 'number',
            2 => 'code',
            3 => 'product',
            4 => 'quantity',
            5 => 'deadline',
            6 => 'first',
            7 => 'second',
            8 => 'third',
            9 => 'fourth',
            10 => 'status',
            11 => 'view',
        );

        $totalData = ManufacturerOrderDetail::count();

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $contractDetails = DB::table('contract_details AS cd')
            ->join('prices', 'prices.id', '=', 'cd.price_id')
            ->join('products', 'products.id', '=', 'prices.product_id')
            ->select('cd.id', 'products.code', 'products.name', 'cd.deadline', 'cd.status', 'cd.quantity');

        $step = DB::table('step_note_details AS sd')
            ->join('step_notes as s', 's.id', 'sd.step_note_id')
            ->groupBy('sd.contract_detail_id', 's.step_id')
            ->select(
                'sd.contract_detail_id',
                's.step_id',
                DB::raw('IFNULL(SUM(sd.quantity),0) AS total_quantity')
            );

        $query = DB::table('manufacturer_order_details as md')
            ->joinSub($contractDetails, 'cd', function ($join) {
                $join->on('cd.id', '=', 'md.contract_detail_id');
            })
            ->join('manufacturer_orders as m', 'm.id', 'md.manufacturer_order_id')
            ->leftJoinSub($step, 'first', function ($join) {
                $join->on('first.contract_detail_id', '=', 'md.contract_detail_id')
                    ->where('first.step_id', '=', 1);
            })
            ->leftJoinSub($step, 'second', function ($join) {
                $join->on('second.contract_detail_id', '=', 'md.contract_detail_id')
                    ->where('second.step_id', '=', 2);
            })
            ->leftJoinSub($step, 'third', function ($join) {
                $join->on('third.contract_detail_id', '=', 'md.contract_detail_id')
                    ->where('third.step_id', '=', 3);
            })
            ->leftJoinSub($step, 'fourth', function ($join) {
                $join->on('fourth.contract_detail_id', '=', 'md.contract_detail_id')
                    ->where('fourth.step_id', '=', 4);
            })
            ->select(
                'm.id',
                'm.date',
                'm.number',
                'cd.code',
                'cd.name as product',
                'cd.quantity',
                'cd.deadline',
                DB::raw('IFNULL(first.total_quantity,0) AS first'),
                DB::raw('IFNULL(second.total_quantity,0) AS second'),
                DB::raw('IFNULL(third.total_quantity,0) AS third'),
                DB::raw('IFNULL(fourth.total_quantity,0) AS fourth'),
                'cd.status'
            );

        if (empty($request->input('search.value')) && !array_filter(array_column(array_column($request->columns, 'search'), 'value'))) {
        } elseif (!empty($request->input('search.value'))) {

            $search = $request->input('search.value');

            $query = $query->where('m.date', 'LIKE', "%{$search}%")
                ->orWhere('m.number', 'LIKE', "%{$search}%")
                ->orWhere('cd.code', 'LIKE', "%{$search}%")
                ->orWhere('cd.name', 'LIKE', "%{$search}%")
                ->orWhere('cd.quantity', 'LIKE', "%{$search}%")
                ->orWhere('cd.deadline', 'LIKE', "%{$search}%")
                ->orWhere('first.total_quantity', 'LIKE', "%{$search}%")
                ->orWhere('second.total_quantity', 'LIKE', "%{$search}%")
                ->orWhere('third.total_quantity', 'LIKE', "%{$search}%")
                ->orWhere('fourth.total_quantity', 'LIKE', "%{$search}%")
                ->orWhere('cd.status', '=', "$search");
        } else {
            if (!empty($request->input('columns.0.search.value'))) {
                $search = $request->input('columns.0.search.value');
                $query =  $query
                    ->where('m.date', 'LIKE', "%{$search}%");
            }
            if (!empty($request->input('columns.1.search.value'))) {
                $search = $request->input('columns.1.search.value');
                $query =  $query
                    ->where('m.number', 'LIKE', "%{$search}%");
            }
            if (!empty($request->input('columns.2.search.value'))) {
                $search = $request->input('columns.2.search.value');
                $query =  $query
                    ->where('cd.code', 'LIKE', "%{$search}%");
            }
            if (!empty($request->input('columns.3.search.value'))) {
                $search = $request->input('columns.3.search.value');
                $query =  $query
                    ->where('cd.name', 'LIKE', "%{$search}%");
            }
            if (!empty($request->input('columns.4.search.value'))) {
                $search = $request->input('columns.4.search.value');
                $query =  $query
                    ->where('cd.quantity', 'LIKE', "%{$search}%");
            }
            if (!empty($request->input('columns.5.search.value'))) {
                $search = $request->input('columns.5.search.value');
                $query =  $query
                    ->where('cd.deadline', 'LIKE', "%{$search}%");
            }
            if (!empty($request->input('columns.6.search.value'))) {
                $search = $request->input('columns.6.search.value');
                $query =  $query
                    ->where('first.total_quantity', 'LIKE', "%{$search}%");
            }
            if (!empty($request->input('columns.7.search.value'))) {
                $search = $request->input('columns.7.search.value');
                $query =  $query
                    ->where('second.total_quantity', 'LIKE', "%{$search}%");
            }
            if (!empty($request->input('columns.8.search.value'))) {
                $search = $request->input('columns.8.search.value');
                $query =  $query
                    ->where('third.total_quantity', 'LIKE', "%{$search}%");
            }
            if (!empty($request->input('columns.9.search.value'))) {
                $search = $request->input('columns.9.search.value');
                $query =  $query
                    ->where('fourth.total_quantity', 'LIKE', "%{$search}%");
            }
            if (!empty($request->input('columns.10.search.value'))) {
                $search = $request->input('columns.10.search.value');
                $query =  $query
                    ->where('cd.status', 'LIKE', "%{$search}%");
            }
        }

        $totalFiltered = $query->count();

        $manufacturerOrderDetails = $query->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $data = [];

        if (!empty($manufacturerOrderDetails)) {
            foreach ($manufacturerOrderDetails as $manufacturerOrderDetail) {
                $show =  route('manufacturer-orders.show', $manufacturerOrderDetail->id);

                $nestedData['date'] = $manufacturerOrderDetail->date;
                $nestedData['number'] = $manufacturerOrderDetail->number;
                $nestedData['code'] = $manufacturerOrderDetail->code;
                $nestedData['product'] = $manufacturerOrderDetail->product;
                $nestedData['quantity'] = $manufacturerOrderDetail->quantity;
                $nestedData['deadline'] = $manufacturerOrderDetail->deadline;
                $nestedData['first'] = $manufacturerOrderDetail->first ?? 0;
                $nestedData['second'] = $manufacturerOrderDetail->second ?? 0;
                $nestedData['third'] = $manufacturerOrderDetail->third ?? 0;
                $nestedData['fourth'] = $manufacturerOrderDetail->fourth ?? 0;
                $nestedData['status'] = $manufacturerOrderDetail->status;
                $nestedData['view'] = "<a href='{$show}' title='Xem' class='btn btn-success'><i class=\"fa fa-tag\" aria-hidden=\"true\"></i> Xem</a>";
                $data[] = $nestedData;
            }
        }

        $json_data = [
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        ];

        return $json_data;
    }
}
