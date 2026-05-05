<?php

namespace App\Exports\Sheets;

use App\Models\Donhang;
use App\Models\Sanpham;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TomTatSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(protected int $nam) {}

    public function title(): string { return 'Tóm Tắt'; }

    public function array(): array
    {
        $tongDT   = Donhang::where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)->sum('tong_thanh_toan');
        $dtNam    = Donhang::where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)->whereYear('created_at', $this->nam)->sum('tong_thanh_toan');
        $tongDon  = Donhang::count();
        $hoanTat  = Donhang::where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)->count();
        $daHuy    = Donhang::where('trang_thai', Donhang::TRANG_THAI_HUY)->count();
        $tbDon    = Donhang::where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)->avg('tong_thanh_toan') ?? 0;
        $tongSP   = Sanpham::count();
        $hetHang  = Sanpham::where('co_bien_the', false)->where('so_luong', 0)->count();
        $tongKhach = User::where('quyen_han', User::USER)->count();

        return [
            ['BÁO CÁO TỔNG QUAN - Xuất ngày ' . now()->format('d/m/Y H:i')],
            [],
            ['CHỈ SỐ', 'GIÁ TRỊ'],
            ['Tổng doanh thu (tất cả)', number_format($tongDT) . ' đ'],
            ['Doanh thu năm ' . $this->nam,  number_format($dtNam)  . ' đ'],
            ['Tổng đơn hàng',  $tongDon],
            ['Đơn hoàn tất',   $hoanTat],
            ['Đơn đã hủy',     $daHuy],
            ['TB / Đơn hoàn tất', number_format($btDon ?? $tbDon) . ' đ'],
            ['Tổng sản phẩm',  $tongSP],
            ['Sản phẩm hết hàng', $hetHang],
            ['Tổng khách hàng', $tongKhach],
        ];
    }

    public function columnWidths(): array
    {
        return ['A' => 35, 'B' => 30];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            3 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                  'fill' => ['fillType' => 'solid', 'color' => ['rgb' => '1A5276']]],
        ];
    }
}
