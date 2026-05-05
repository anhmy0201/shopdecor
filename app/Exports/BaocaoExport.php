<?php

namespace App\Exports;

use App\Models\Donhang;
use App\Models\Sanpham;
use App\Models\User;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BaocaoExport implements WithMultipleSheets
{
    public function __construct(
        protected int $nam,
        protected string $thang = ''
    ) {}

    public function sheets(): array
    {
        return [
            'Doanh Thu Tháng'  => new Sheets\DoanhThuThangSheet($this->nam, $this->thang),
            'Top Sản Phẩm'     => new Sheets\TopSanphamSheet(),
            'Top Khách Hàng'   => new Sheets\TopKhachSheet(),
            'Tóm Tắt'          => new Sheets\TomTatSheet($this->nam),
        ];
    }
}