<?php

namespace App\Exports\Sheets;

use App\Models\Sanpham;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TopSanphamSheet implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    public function title(): string { return 'Top Sản Phẩm'; }

    public function headings(): array
    {
        return ['#', 'Tên Sản Phẩm', 'Danh Mục', 'Lượt Mua', 'Lượt Xem', 'Giá (đ)'];
    }

    public function collection()
    {
        return Sanpham::with('loai')
            ->orderByDesc('luot_mua')
            ->take(20)
            ->get()
            ->map(fn($sp, $i) => [
                $i + 1,
                $sp->ten_san_pham,
                $sp->loai->ten_loai ?? '—',
                $sp->luot_mua,
                $sp->luot_xem,
                (float) $sp->gia_ban,
            ]);
    }

    public function columnWidths(): array
    {
        return ['A' => 5, 'B' => 45, 'C' => 20, 'D' => 12, 'E' => 12, 'F' => 18];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                  'fill' => ['fillType' => 'solid', 'color' => ['rgb' => 'E74C3C']]],
        ];
    }
}
