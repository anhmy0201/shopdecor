<?php

namespace App\Exports\Sheets;

use App\Models\Donhang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class DoanhThuThangSheet implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths, WithColumnFormatting
{
    public function __construct(
        protected int $nam,
        protected string $thang = ''
    ) {}

    public function title(): string
    {
        return 'Doanh Thu Tháng';
    }

    public function headings(): array
    {
        if ($this->thang !== '') {
            return ['Ngày', 'Tháng', 'Năm', 'Số Đơn', 'Doanh Thu (đ)'];
        }
        return ['Tháng', 'Năm', 'Số Đơn', 'Doanh Thu (đ)'];
    }

    public function collection()
    {
        $driver = config('database.connections.' . config('database.default') . '.driver');

        if ($this->thang !== '') {
            // Theo ngày
            if ($driver === 'sqlite') {
                $rows = Donhang::where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)
                    ->selectRaw("CAST(strftime('%d', created_at) AS INTEGER) as ngay,
                                  CAST(strftime('%m', created_at) AS INTEGER) as thang,
                                  CAST(strftime('%Y', created_at) AS INTEGER) as nam,
                                  COUNT(*) as so_don, SUM(tong_thanh_toan) as tong")
                    ->whereRaw("strftime('%Y', created_at) = ? AND strftime('%m', created_at) = ?",
                                [$this->nam, str_pad($this->thang, 2, '0', STR_PAD_LEFT)])
                    ->groupByRaw("strftime('%d', created_at)")
                    ->orderByRaw("strftime('%d', created_at)")
                    ->get();
            } else {
                $rows = Donhang::where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)
                    ->selectRaw('DAY(created_at) as ngay, MONTH(created_at) as thang,
                                  YEAR(created_at) as nam, COUNT(*) as so_don, SUM(tong_thanh_toan) as tong')
                    ->whereYear('created_at', $this->nam)
                    ->whereMonth('created_at', $this->thang)
                    ->groupByRaw('DAY(created_at), MONTH(created_at), YEAR(created_at)')
                    ->orderByRaw('DAY(created_at)')
                    ->get();
            }
            return $rows->map(fn($r) => [$r->ngay, $r->thang, $r->nam, $r->so_don, (float) $r->tong]);
        }

        // Theo tháng của năm
        if ($driver === 'sqlite') {
            $rows = Donhang::where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)
                ->selectRaw("CAST(strftime('%m', created_at) AS INTEGER) as thang,
                              CAST(strftime('%Y', created_at) AS INTEGER) as nam,
                              COUNT(*) as so_don, SUM(tong_thanh_toan) as tong")
                ->whereRaw("strftime('%Y', created_at) = ?", [$this->nam])
                ->groupByRaw("strftime('%Y', created_at), strftime('%m', created_at)")
                ->orderByRaw("strftime('%m', created_at)")
                ->get();
        } else {
            $rows = Donhang::where('trang_thai', Donhang::TRANG_THAI_HOAN_TAT)
                ->selectRaw('MONTH(created_at) as thang, YEAR(created_at) as nam,
                              COUNT(*) as so_don, SUM(tong_thanh_toan) as tong')
                ->whereYear('created_at', $this->nam)
                ->groupByRaw('YEAR(created_at), MONTH(created_at)')
                ->orderByRaw('MONTH(created_at)')
                ->get();
        }

        return $rows->map(fn($r) => [$r->thang, $r->nam, $r->so_don, (float) $r->tong]);
    }

    public function columnWidths(): array
    {
        return ['A' => 12, 'B' => 10, 'C' => 10, 'D' => 14, 'E' => 22];
    }

    public function columnFormats(): array
    {
        // Cột doanh thu format số có dấu phẩy
        return $this->thang !== ''
            ? ['E' => '#,##0']
            : ['D' => '#,##0'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['rgb' => '1A5276']],
                  'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']]],
        ];
    }
}
