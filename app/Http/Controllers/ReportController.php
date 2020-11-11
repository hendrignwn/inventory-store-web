<?php

namespace App\Http\Controllers;

use App\Helpers\FormatConverter;
use App\Models\Item;
use App\Models\ItemStock;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Yajra\DataTables\Html\Builder;

class ReportController extends Controller
{
    /**
     * Datatables Html Builder
     * @var Builder
     */
    protected $htmlBuilder;
    public function __construct(Builder $htmlBuilder) {
        $this->htmlBuilder = $htmlBuilder;
    }

    public function itemStock(Request $request) {
        if ($request->ajax()) {
            $data = Item::select(['*'])->withCount(['itemStocks as stock' => function ($query) {
                return $query->select([DB::raw('SUM(stock)')]);
            }]);
            $datatables = DataTables::of($data)
                ->addIndexColumn();
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
            ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama Barang'])
            ->addColumn(['data' => 'minimum_stock', 'name' => 'created_at', 'title' => 'Stok Minimal'])
            ->addColumn(['data' => 'stock', 'name' => 'stock', 'title' => 'Sisa Stok', 'searchable' => false]);
        return view('report.item-stock', compact('dataTable'));
    }
}
