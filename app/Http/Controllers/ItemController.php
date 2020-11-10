<?php

namespace App\Http\Controllers;

use App\Helpers\FormatConverter;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Yajra\DataTables\Html\Builder;

class ItemController extends Controller
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
            $data = Item::select(['*']);
            $datatables = DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $editRoute = route('item.edit', ['item' => $row->id ]);
                    $deleteRoute = route('item.destroy', ['item' => $row->id ]);
                    $csrf = csrf_field(); $method = method_field('DELETE');
                    $btn = "<div class='d-flex'>
                                <a href='{$editRoute}' class='btn btn-sm btn-primary'>
                                    <i class='far fa-edit icon-nm'></i>
                                </a>
                                <form method='POST' action='{$deleteRoute}'>{$csrf} {$method}
                                    <button class='btn btn-sm btn-danger' onclick=\"return confirm('Apakah Anda ingin menghapus data ini?')\"><i class='fa fa-trash icon-nm'></i>
                                    </button>
                                </form>
                                </div>";

                    return $btn;
                })
                ->editColumn('created_at', function($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->editColumn('updated_at', function($row) {
                    return Carbon::parse($row->updated_at)->toDateTimeString();
                })
                ->editColumn('sell_price', function($row) {
                    return FormatConverter::rupiahFormat($row->sell_price);
                })
                ->editColumn('purchase_price', function($row) {
                    return FormatConverter::rupiahFormat($row->purchase_price);
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
            ])
            ->addColumn(['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => '#', 'orderable' => false, 'searchable' => false, 'width' => 30])
            ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama'])
            ->addColumn(['data' => 'description', 'name' => 'description', 'title' => 'Deskripsi'])
            ->addColumn(['data' => 'sell_price', 'name' => 'sell_price', 'title' => 'Harga Jual'])
            ->addColumn(['data' => 'purchase_price', 'name' => 'purchase_price', 'title' => 'Harga Beli'])
            ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'])
            ->addColumn(['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Updated At'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'width' => 100]);
        return view('item.index', compact('dataTable'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('item.create');
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
            'name' => ['required'],
            'sell_price' => ['required'],
            'purchase_price' => ['required'],
            'description' => ['nullable'],
            'status' => ['required'],
            'item_type_id' => ['required'],
        ]);
        $models = new Item($request->all());
        $models->save();
        return redirect()->route('item.index')->with('success', 'Sukses!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Item::find($id);
        return view('item.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required'],
            'sell_price' => ['required'],
            'purchase_price' => ['required'],
            'description' => ['nullable'],
            'status' => ['required'],
            'item_type_id' => ['required'],
        ]);
        $model = Item::find($id);
        $model->fill($request->all());
        $model->save();
        return redirect()->route('item.index')->with('success', 'Sukses!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $models = Item::find($id);
        $models->delete();

        return redirect()->route('item.index')->with('success', 'Sukses dihapus!');
    }
}
