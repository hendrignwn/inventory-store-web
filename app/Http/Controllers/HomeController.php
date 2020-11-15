<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemStock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $itemStocks = Item::select(['*'])
            ->withCount([
                'itemStocks as stock' => function ($query) {
                    return $query->select([DB::raw('SUM(stock)')]);
                }
            ])
            ->where('status', Item::STATUS_ACTIVE)
            ->whereRaw('minimum_stock > (select SUM(stock) from item_stocks where item_id = items.id)')->get();
        $itemsNew = Item::select(['*'])
            ->whereNotIn('id', ItemStock::pluck('item_id'))
            ->get();

        if (Auth::user()->role == User::ROLE_ADMIN) {
            if (count($itemStocks) > 0) {
                $url = route('report.stock-minimum');
                alert()->message("<a href='{$url}'>Lihat Barang</a>", count($itemStocks) . ' stok barang hampir habis')->html()->persistent('Tutup')->autoclose();
            }
        }
        return view('home', compact('itemStocks', 'itemsNew'));
    }
}
