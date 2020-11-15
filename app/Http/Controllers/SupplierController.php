<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Yajra\DataTables\Html\Builder;

class SupplierController extends Controller
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
            $data = Supplier::select(['*']);
            $datatables = DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $editRoute = route('supplier.edit', ['supplier' => $row->id ]);
                    $deleteRoute = route('supplier.destroy', ['supplier' => $row->id ]);
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
            ->addColumn(['data' => 'phone', 'name' => 'phone', 'title' => 'Telp'])
            ->addColumn(['data' => 'address', 'name' => 'address', 'title' => 'Alamat'])
            ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'])
            ->addColumn(['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Updated At'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'width' => 100]);
        return view('supplier.index', compact('dataTable'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('supplier.create');
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
            'phone' => ['required', 'numeric'],
            'address' => ['required'],
        ]);
        $models = new Supplier($request->all());
        $models->save();
        return redirect()->route('supplier.index')->with('success', 'Sukses!');
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
        $model = Supplier::find($id);
        return view('supplier.edit', compact('model'));
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
            'phone' => ['required', 'numeric'],
            'address' => ['required'],
        ]);
        $model = Supplier::find($id);
        $model->fill($request->all());
        $model->save();
        return redirect()->route('supplier.index')->with('success', 'Sukses!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $models = Supplier::find($id);
        $models->delete();

        return redirect()->route('supplier.index')->with('success', 'Sukses dihapus!');
    }
}
