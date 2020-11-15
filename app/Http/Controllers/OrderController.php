<?php

namespace App\Http\Controllers;

use App\Helpers\FormatConverter;
use App\Models\Item;
use App\Models\ItemStock;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Yajra\DataTables\Html\Builder;

class OrderController extends Controller
{
    /**
     * Datatables Html Builder
     * @var Builder
     */
    protected $htmlBuilder;
    public function __construct(Builder $htmlBuilder) {
        $this->htmlBuilder = $htmlBuilder;
    }

    public function index(Request $request) {
        if ($request->ajax()) {
            $data = Order::select(['*']);
            $datatables = DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $detail = route('order.show', ['order' => $row->id ]);
                    $print = route('order.print', ['order' => $row->id ]);
                    $btn = "<div class='d-flex'>
                                <a href='{$detail}' class='btn btn-sm btn-primary'>
                                    <i class='far fa-eye icon-nm'></i>
                                </a>&nbsp;
                                <a href='{$print}' class='btn btn-sm btn-success' target='_blank'>
                                    <i class='fa fa-print'></i>
                                </a>
                                </div>";

                    return $btn;
                })
                ->editColumn('created_at', function($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->editColumn('updated_at', function($row) {
                    return Carbon::parse($row->updated_at)->toDateTimeString();
                })
                ->editColumn('order_date', function($row) {
                    return Carbon::parse($row->order_date)->toDateString();
                })
                ->editColumn('grand_total', function($row) {
                    return FormatConverter::rupiahFormat($row->grand_total);
                })
                ->rawColumns(['action']);
            return $datatables->make(true);
        }

        $dataTable = $this->htmlBuilder
            ->parameters([
                'paging' => true,
                'searching' => true,
                'info' => false,
                'searchDelay' => 350,
                'buttons' => ['pdf'],
                'order' => [[4, 'desc']],
            ])
            ->addColumn(['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => '#', 'orderable' => false, 'searchable' => false, 'width' => 30])
            ->addColumn(['data' => 'order_number', 'name' => 'order_number', 'title' => 'Nomor Order'])
            ->addColumn(['data' => 'order_date', 'name' => 'order_date', 'title' => 'Tanggal'])
            ->addColumn(['data' => 'grand_total', 'name' => 'grand_total', 'title' => 'Grand Total'])
            ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'])
            ->addColumn(['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Updated At'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'width' => 100]);
        return view('order.index', compact('dataTable'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $items = Item::where('status', Item::STATUS_ACTIVE)->select('id', 'name')->get();
        return view('order.create', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => ['required'],
            'order_number' => ['required'],
            'total' => ['required'],
        ]);
        $model = new Order($request->all());
        $model->save();
        foreach ($request->item as $key => $item) {
            $modelDetail = new OrderDetail();
            $modelDetail->order_id = $model->id;
            $modelDetail->item_id = $item;
            $modelDetail->qty = $request->qty[$key];
            $modelDetail->price = $request->purchase_total[$key];
            $modelDetail->purchase_total = $request->total[$key];
            $modelDetail->save();

            $stock = ItemStock::where('supplier_id', $model->supplier_id)->where('item_id', $modelDetail->item_id)->first();
            if (!$stock) {
                $stock = new ItemStock();
            }
            $stock->item_id = $modelDetail->item_id;
            $stock->supplier_id = $model->supplier_id;
            $stock->stock += $modelDetail->qty;
            $stock->save();
        }
        return redirect()->route('order.index')->with('success', 'Sukses!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Order::find($id);
        return view('order.show', compact('model'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        $model = Order::find($id);
        return view('order.print', compact('model'));
    }
}
