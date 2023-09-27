<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class RekapExport implements FromView
{
    protected $data;
    protected $exportView;
    protected $template;

    public function __construct($data, $exportView) {
        $this->data = $data;
        $this->exportView = $exportView;
    }

    public function view():View{
        $data['data'] = $this->data;
        $exportView = $this->exportView;
        return view('excel.'.$exportView, $data);
    }
}
