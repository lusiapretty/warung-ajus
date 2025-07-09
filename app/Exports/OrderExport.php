<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OrderExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $orders;
    protected $start;
    protected $end;

    public function __construct($orders, $start, $end)
    {
        $this->orders = $orders;
        $this->start = $start;
        $this->end = $end;
    }

    public function view(): View
    {
        return view('admin.pesanan-pelanggan.laporan-excel', [
            'orders' => $this->orders,
            'start' => $this->start,
            'end' => $this->end,
        ]);
    }
}
