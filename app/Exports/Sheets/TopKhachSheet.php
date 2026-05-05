<?php

namespace App\Exports\Sheets;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TopKhachSheet implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    public function title(): string { return 'Top Khách Hàng'; }

    public function headings(): array
    {
        return ['#', 'Họ Tên', 'Email', 'Số Đơn Hàng', 'Tổng Chi Tiêu (đ)'];
    }

    public function collection()
    {
        return User::where('quyen_han', User::USER)
            ->withCount('donhangs')
            ->withSum('donhangs', 'tong_thanh_toan')
            ->orderByDesc('donhangs_sum_tong_thanh_toan')
            ->take(20)
            ->get()
            ->map(fn($kh, $i) => [
                $i + 1,
                $kh->ho_ten,
                $kh->email,
                $kh->donhangs_count,
                (float) ($kh->donhangs_sum_tong_thanh_toan ?? 0),
            ]);
    }

    public function columnWidths(): array
    {
        return ['A' => 5, 'B' => 28, 'C' => 32, 'D' => 14, 'E' => 22];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                  'fill' => ['fillType' => 'solid', 'color' => ['rgb' => '8E44AD']]],
        ];
    }
}
