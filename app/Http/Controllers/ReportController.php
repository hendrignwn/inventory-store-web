<?php

namespace App\Http\Controllers;

use App\Helpers\FormatConverter;
use App\Models\Item;
use App\Models\ItemStock;
use App\Models\Order;
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
                'dom' => '<"html5buttons">Bfrtip',
                'buttons' => [
                    [
                        'extend' => 'csv',
                        'title' => 'Laporan Stok Barang',
                    ],
                    [
                        'extend' => 'pdf',
                        'title' => 'Laporan Stok Barang',
                    ],
                    [
                        'extend' => 'print',
                        'title' => 'Laporan Stok Barang',
                    ],
                ],
            ])
            ->addColumn(['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => '#', 'orderable' => false, 'searchable' => false, 'width' => 30])
            ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama Barang'])
            ->addColumn(['data' => 'minimum_stock', 'name' => 'created_at', 'title' => 'Stok Minimal'])
            ->addColumn(['data' => 'stock', 'name' => 'stock', 'title' => 'Sisa Stok', 'searchable' => false]);
        return view('report.item-stock', compact('dataTable'));
    }

    public function orderRecap(Request $request) {
        if ($request->ajax()) {
            $data = Order::select([
                    '*',
                    DB::raw('SUM(grand_total) as `total`'),
                    DB::raw("DATE_FORMAT(order_date, '%m-%Y') date")
                ])
                ->groupBy('date');
            $datatables = DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('date', function($row) {
                    return Carbon::createFromFormat('m-Y', $row->date)->format('M Y');
                })
                ->editColumn('total', function($row) {
                    return FormatConverter::rupiahFormat($row->total);
                });
            return $datatables->make(true);
        }

        $dataTable = $this->htmlBuilder
            ->parameters([
                'paging' => true,
                'searching' => true,
                'info' => false,
                'searchDelay' => 350,
                'dom' => '<"html5buttons">Bfrtip',
                'buttons' => [
                    [
                        'extend' => 'csv',
                        'title' => 'Laporan Rekapitulasi Pembelian Barang',
                    ],
                    [
                        'extend' => 'pdf',
                        'title' => 'Laporan Rekapitulasi Pembelian Barang',
                    ],
                    [
                        'extend' => 'print',
                        'title' => 'Laporan Rekapitulasi Pembelian Barang',
                    ],
                ],
            ])
            ->addColumn(['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => '#', 'orderable' => false, 'searchable' => false, 'width' => 30])
            ->addColumn(['data' => 'date', 'name' => 'name', 'title' => 'Tahun - Bulan'])
            ->addColumn(['data' => 'total', 'name' => 'stock', 'title' => 'Total Pembelian', 'searchable' => false]);
        return view('report.order-recap', compact('dataTable'));
    }

    public function transactionRecap(Request $request) {
        if ($request->ajax()) {
            $data = Transaction::select([
                '*',
                DB::raw('SUM(grand_total) as `total`'),
                DB::raw("DATE_FORMAT(created_at, '%m-%Y') date")
            ])
                ->groupBy('date');
            $datatables = DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('date', function($row) {
                    return Carbon::createFromFormat('m-Y', $row->date)->format('M Y');
                })
                ->editColumn('total', function($row) {
                    return FormatConverter::rupiahFormat($row->total);
                });
            return $datatables->make(true);
        }

        $dataTable = $this->htmlBuilder
            ->parameters([
                'paging' => true,
                'searching' => true,
                'info' => false,
                'searchDelay' => 350,
                'dom' => '<"html5buttons">Bfrtip',
                'buttons' => [
                    [
                        'extend' => 'csv',
                        'title' => 'Laporan Rekapitulasi Transaksi Barang',
                    ],
                    [
                        'extend' => 'pdf',
                        'title' => 'Laporan Rekapitulasi Transaksi Barang',
                    ],
                    [
                        'extend' => 'print',
                        'title' => 'Laporan Rekapitulasi Transaksi Barang',
                    ],
                ],
            ])
            ->addColumn(['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => '#', 'orderable' => false, 'searchable' => false, 'width' => 30])
            ->addColumn(['data' => 'date', 'name' => 'name', 'title' => 'Tahun - Bulan'])
            ->addColumn(['data' => 'total', 'name' => 'stock', 'title' => 'Total Penjualan', 'searchable' => false]);
        return view('report.transaction-recap', compact('dataTable'));
    }

    public function stockMinimum(Request $request) {
        if ($request->ajax()) {
            $data = Item::select(['*'])
                ->with(['itemType'])
                ->withCount([
                    'itemStocks as stock' => function ($query) {
                        return $query->select([DB::raw('SUM(stock)')]);
                    }
                ])
                ->where('status', Item::STATUS_ACTIVE)
                ->whereRaw('minimum_stock > (select SUM(stock) from item_stocks where item_id = items.id)');
            $datatables = DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('item_type_id', function($row) {
                    return $row->itemType ? $row->itemType->name : '-';
                });
            return $datatables->make(true);
        }

        $dataTable = $this->htmlBuilder
            ->parameters([
                'paging' => true,
                'searching' => true,
                'info' => false,
                'searchDelay' => 350,
                'dom' => '<"html5buttons">Bfrtip',
                'buttons' => [
                    [
                        'extend' => 'csv',
                        'title' => 'Laporan Stok Hampir Habis',
                    ],
                    [
                        'extend' => 'pdf',
                        'title' => 'Laporan Stok Hampir Habis',
                    ],
                    [
                        'extend' => 'print',
                        'title' => 'Laporan Stok Hampir Habis',
                    ],
                ],
            ])
            ->addColumn(['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => '#', 'orderable' => false, 'searchable' => false, 'width' => 30])
            ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama Barang'])
            ->addColumn(['data' => 'item_type_id', 'name' => 'item_type_id', 'title' => 'Jenis Barang'])
            ->addColumn(['data' => 'minimum_stock', 'name' => 'minimum_stock', 'title' => 'Stok Minimal', 'searchable' => false])
            ->addColumn(['data' => 'stock', 'name' => 'stock', 'title' => 'Sisa Stok', 'searchable' => false]);
        return view('report.stock-minimum', compact('dataTable'));
    }

    public function stockSupplier(Request $request) {
        if ($request->ajax()) {
            $data = ItemStock::select(['*'])
                ->with(['item', 'supplier']);
            $datatables = DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('item_id', function($row) {
                    return $row->item ? $row->item->name : '-';
                })
                ->editColumn('supplier_id', function($row) {
                    return $row->supplier ? $row->supplier->name : '-';
                });
            return $datatables->make(true);
        }

        $dataTable = $this->htmlBuilder
            ->parameters([
                'paging' => true,
                'searching' => true,
                'info' => false,
                'searchDelay' => 350,
                'dom' => '<"html5buttons">Bfrtip',
                'buttons' => [
                    [
                        'extend' => 'csv',
                        'title' => 'Laporan Stok Per-Supplier',
                    ],
                    [
                        'extend' => 'pdf',
                        'title' => 'Laporan Stok Per-Supplier',
                    ],
                    [
                        'extend' => 'print',
                        'title' => 'Laporan Stok Per-Supplier',
                    ],
                ],
            ])
            ->addColumn(['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => '#', 'orderable' => false, 'searchable' => false, 'width' => 30])
            ->addColumn(['data' => 'item_id', 'name' => 'item_id', 'title' => 'Nama Barang'])
            ->addColumn(['data' => 'supplier_id', 'name' => 'supplier_id', 'title' => 'Supplier'])
            ->addColumn(['data' => 'stock', 'name' => 'stock', 'title' => 'Stok', 'searchable' => false]);
        return view('report.stock-minimum', compact('dataTable'));
    }

    public function transactionCustomer(Request $request) {
        if ($request->ajax()) {
            $data = Transaction::select([
                '*',
                DB::raw('SUM(grand_total) as `total`'),
                DB::raw("DATE_FORMAT(created_at, '%m-%Y') date")

            ])
                ->with(['customer'])
                ->groupBy('customer_id', 'date');
            $datatables = DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('date', function($row) {
                    return Carbon::createFromFormat('m-Y', $row->date)->format('M Y');
                })
                ->editColumn('customer_id', function($row) {
                    return $row->customer ? $row->customer->name : '-';
                })
                ->editColumn('total', function($row) {
                    return FormatConverter::rupiahFormat($row->total);
                });
            return $datatables->make(true);
        }

        $dataTable = $this->htmlBuilder
            ->parameters([
                'paging' => true,
                'searching' => true,
                'info' => false,
                'searchDelay' => 350,
                'dom' => '<"html5buttons">Bfrtip',
                'buttons' => [
                    [
                        'extend' => 'csv',
                        'title' => 'Laporan Transaksi Barang Customer',
                    ],
                    [
                        'extend' => 'pdf',
                        'title' => 'Laporan Transaksi Barang Customer',
                    ],
                    [
                        'extend' => 'print',
                        'title' => 'Laporan Transaksi Barang Customer',
                    ],
                ],
            ])
            ->addColumn(['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => '#', 'orderable' => false, 'searchable' => false, 'width' => 30])
            ->addColumn(['data' => 'customer_id', 'name' => 'customer_id', 'title' => 'Customer'])
            ->addColumn(['data' => 'date', 'name' => 'date', 'title' => 'Bulan - Tahun', 'searchable' => false])
            ->addColumn(['data' => 'total', 'name' => 'stock', 'title' => 'Total Penjualan', 'searchable' => false]);
        return view('report.transaction-customer', compact('dataTable'));
    }

    public function transactionUser(Request $request) {
        if ($request->ajax()) {
            $data = Transaction::select([
                '*',
                DB::raw('SUM(grand_total) as `total`'),
                DB::raw("DATE_FORMAT(created_at, '%m-%Y') date")

            ])
                ->with(['user'])
                ->groupBy('user_id', 'date');
            $datatables = DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('date', function($row) {
                    return Carbon::createFromFormat('m-Y', $row->date)->format('M Y');
                })
                ->editColumn('user_id', function($row) {
                    return $row->user ? $row->user->name : '-';
                })
                ->editColumn('total', function($row) {
                    return FormatConverter::rupiahFormat($row->total);
                });
            return $datatables->make(true);
        }

        $dataTable = $this->htmlBuilder
            ->parameters([
                'paging' => true,
                'searching' => true,
                'info' => false,
                'searchDelay' => 350,
                'dom' => '<"html5buttons">Bfrtip',
                'buttons' => [
                    [
                        'extend' => 'csv',
                        'title' => 'Laporan Transaksi Barang Customer',
                    ],
                    [
                        'extend' => 'pdf',
                        'title' => 'Laporan Transaksi Barang Customer',
                    ],
                    [
                        'extend' => 'print',
                        'title' => 'Laporan Transaksi Barang Customer',
                    ],
                ],
            ])
            ->addColumn(['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => '#', 'orderable' => false, 'searchable' => false, 'width' => 30])
            ->addColumn(['data' => 'user_id', 'name' => 'user_id', 'title' => 'Karyawan'])
            ->addColumn(['data' => 'date', 'name' => 'date', 'title' => 'Bulan - Tahun', 'searchable' => false])
            ->addColumn(['data' => 'total', 'name' => 'stock', 'title' => 'Total Penjualan', 'searchable' => false]);
        return view('report.transaction-user', compact('dataTable'));
    }

    public function orderSupplier(Request $request) {
        if ($request->ajax()) {
            $data = Order::select([
                '*',
                DB::raw('SUM(grand_total) as `total`'),
                DB::raw("DATE_FORMAT(order_date, '%m-%Y') date")
            ])
                ->with(['supplier'])
                ->groupBy('supplier_id', 'date');
            $datatables = DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('date', function($row) {
                    return Carbon::createFromFormat('m-Y', $row->date)->format('M Y');
                })
                ->editColumn('supplier_id', function($row) {
                    return $row->supplier ? $row->supplier->name : '-';
                })
                ->editColumn('total', function($row) {
                    return FormatConverter::rupiahFormat($row->total);
                });
            return $datatables->make(true);
        }

        $dataTable = $this->htmlBuilder
            ->parameters([
                'paging' => true,
                'searching' => true,
                'info' => false,
                'searchDelay' => 350,
                'dom' => '<"html5buttons">Bfrtip',
                'buttons' => [
                    [
                        'extend' => 'csv',
                        'title' => 'Laporan Rekapitulasi Pembelian Barang Supplier',
                    ],
                    [
                        'extend' => 'pdf',
                        'title' => 'Laporan Rekapitulasi Pembelian Barang Supplier',
                    ],
                    [
                        'extend' => 'print',
                        'title' => 'Laporan Rekapitulasi Pembelian Barang Supplier',
                    ],
                ],
            ])
            ->addColumn(['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => '#', 'orderable' => false, 'searchable' => false, 'width' => 30])
            ->addColumn(['data' => 'supplier_id', 'name' => 'name', 'supplier_id' => 'Supplier', 'searchable' => false])
            ->addColumn(['data' => 'date', 'name' => 'date', 'title' => 'Bulan - Tahun', 'searchable' => false])
            ->addColumn(['data' => 'total', 'name' => 'total', 'title' => 'Total Pembelian', 'searchable' => false]);
        return view('report.order-supplier', compact('dataTable'));
    }

    public function top5Item(Request $request) {
        if ($request->ajax()) {
            $data = TransactionDetail::select([
                '*',
                DB::raw('SUM(qty) as `quantity`'),
                DB::raw('SUM(total_price) as `total`'),
                DB::raw("DATE_FORMAT(created_at, '%m-%Y') date")

            ])
                ->with(['item'])
                ->groupBy('item_id', 'date');
            $datatables = DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('date', function($row) {
                    return Carbon::createFromFormat('m-Y', $row->date)->format('M Y');
                })
                ->editColumn('item_id', function($row) {
                    return $row->item ? $row->item->name : '-';
                })
                ->editColumn('total', function($row) {
                    return FormatConverter::rupiahFormat($row->total);
                });
            return $datatables->make(true);
        }

        $dataTable = $this->htmlBuilder
            ->parameters([
                'paging' => true,
                'searching' => true,
                'info' => false,
                'searchDelay' => 350,
                'order' => [[3, 'desc']],
                'dom' => '<"html5buttons">Bfrtip',
                'buttons' => [
                    [
                        'extend' => 'csv',
                        'title' => 'Laporan Top Barang Terlaris',
                    ],
                    [
                        'extend' => 'pdf',
                        'title' => 'Laporan Top Barang Terlaris',
                    ],
                    [
                        'extend' => 'print',
                        'title' => 'Laporan Top Barang Terlaris',
                    ],
                ],
            ])
            ->addColumn(['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => '#', 'orderable' => false, 'searchable' => false, 'width' => 30])
            ->addColumn(['data' => 'item_id', 'name' => 'item_id', 'title' => 'Nama Barang'])
            ->addColumn(['data' => 'date', 'name' => 'date', 'title' => 'Bulan - Tahun', 'searchable' => false])
            ->addColumn(['data' => 'quantity', 'name' => 'quantity', 'title' => 'Jumlah', 'searchable' => false])
            ->addColumn(['data' => 'total', 'name' => 'total', 'title' => 'Total Penjualan', 'searchable' => false]);
        return view('report.top-5-item', compact('dataTable'));
    }

    public function topCustomer(Request $request) {
        if ($request->ajax()) {
            $data = Transaction::select([
                '*',
                DB::raw('SUM(grand_total) as `total`'),
            ])
                ->with(['customer'])
                ->groupBy('customer_id');
            $datatables = DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('customer_id', function($row) {
                    return $row->customer ? $row->customer->name : '-';
                })
                ->editColumn('total', function($row) {
                    return FormatConverter::rupiahFormat($row->total);
                });
            return $datatables->make(true);
        }

        $dataTable = $this->htmlBuilder
            ->parameters([
                'paging' => true,
                'searching' => true,
                'info' => false,
                'searchDelay' => 350,
                'dom' => '<"html5buttons">Bfrtip',
                'buttons' => [
                    [
                        'extend' => 'csv',
                        'title' => 'Laporan Top Customer',
                    ],
                    [
                        'extend' => 'pdf',
                        'title' => 'Laporan Top Customer',
                    ],
                    [
                        'extend' => 'print',
                        'title' => 'Laporan Top Customer',
                    ],
                ],
            ])
            ->addColumn(['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => '#', 'orderable' => false, 'searchable' => false, 'width' => 30])
            ->addColumn(['data' => 'customer_id', 'name' => 'customer_id', 'title' => 'Customer'])
            ->addColumn(['data' => 'total', 'name' => 'stock', 'title' => 'Total Pembelian', 'searchable' => false, 'orderable' => false, ]);
        return view('report.top-customer', compact('dataTable'));
    }


    public function revenue(Request $request) {
        if ($request->ajax()) {
            $data = TransactionDetail::select([
                    '*',
                    DB::raw('SUM(qty) as `quantity`'),
                    DB::raw('SUM(total_price) as `total`'),
                    DB::raw("DATE_FORMAT(created_at, '%m-%Y') date")
                ])->withCount(['item as purchase_price' => function ($query) {
                    return $query->select([DB::raw('SUM(purchase_price)')]);
                }])
                ->with(['item'])
                ->groupBy('item_id','date');
            $datatables = DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('date', function($row) {
                    return Carbon::createFromFormat('m-Y', $row->date)->format('M Y');
                })
                ->editColumn('item_id', function($row) {
                    return $row->item ? $row->item->name : '-';
                })
                ->editColumn('total', function($row) {
                    return FormatConverter::rupiahFormat($row->total);
                })
                ->addColumn('revenue', function($row) {
                    return FormatConverter::rupiahFormat((float)$row->total - ((float)$row->purchase_price * (float)$row->quantity));
                });
            return $datatables->make(true);
        }

        $dataTable = $this->htmlBuilder
            ->parameters([
                'paging' => true,
                'searching' => true,
                'info' => false,
                'searchDelay' => 350,
                'order' => [[3, 'desc']],
                'dom' => '<"html5buttons">Bfrtip',
                'buttons' => [
                    [
                        'extend' => 'csv',
                        'title' => 'Laporan Laba',
                    ],
                    [
                        'extend' => 'pdf',
                        'title' => 'Laporan Laba',
                    ],
                    [
                        'extend' => 'print',
                        'title' => 'Laporan Laba',
                    ],
                ],
            ])
            ->addColumn(['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => '#', 'orderable' => false, 'searchable' => false, 'width' => 30])
            ->addColumn(['data' => 'item_id', 'name' => 'item_id', 'title' => 'Nama Barang'])
            ->addColumn(['data' => 'date', 'name' => 'date', 'title' => 'Bulan - Tahun', 'searchable' => false])
            ->addColumn(['data' => 'quantity', 'name' => 'quantity', 'title' => 'Jumlah', 'searchable' => false])
            ->addColumn(['data' => 'total', 'name' => 'total', 'title' => 'Total Penjualan', 'searchable' => false])
            ->addColumn(['data' => 'revenue', 'name' => 'revenue', 'title' => 'Laba Bersih', 'searchable' => false]);
        return view('report.revenue', compact('dataTable'));
    }

}
