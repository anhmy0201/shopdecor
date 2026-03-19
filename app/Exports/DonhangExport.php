<?php

namespace App\Exports;

use App\Models\Donhang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DonhangExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Donhang::with(['user', 'magiamgia'])->latest('ngay_dat')->get();
    }

    public function headings(): array
    {
        return [
            'Mã đơn',
            'Khách hàng',
            'Người nhận',
            'Số điện thoại',
            'Địa chỉ',
            'Phương thức thanh toán',
            'Trạng thái thanh toán',
            'Tổng tiền hàng',
            'Giảm giá',
            'Tổng thanh toán',
            'Trạng thái đơn',
            'Ngày đặt',
            'Ghi chú khách',
        ];
    }

    public function map($row): array
    {
        $trangThai = match($row->trang_thai) {
            0 => 'Mới',
            1 => 'Đang xử lý',
            2 => 'Hoàn tất',
            3 => 'Đã hủy',
            default => 'Không rõ',
        };

        $trangThaiTT = match($row->trang_thai_thanhtoan) {
            'chua_thanh_toan' => 'Chưa thanh toán',
            'da_thanh_toan'   => 'Đã thanh toán',
            'hoan_tien'       => 'Hoàn tiền',
            default           => $row->trang_thai_thanhtoan,
        };

        $phuongThuc = match($row->phuong_thuc_thanhtoan) {
            'cod'          => 'Tiền mặt (COD)',
            'chuyen_khoan' => 'Chuyển khoản',
            'momo'         => 'Momo',
            default        => $row->phuong_thuc_thanhtoan,
        };

        return [
            '#DH' . str_pad($row->id, 6, '0', STR_PAD_LEFT),
            $row->user?->ho_ten ?? 'Khách',
            $row->ten_nguoi_nhan,
            $row->so_dien_thoai,
            $row->dia_chi_chi_tiet . ', ' . $row->phuong_xa . ', ' . $row->quan_huyen . ', ' . $row->tinh_thanh,
            $phuongThuc,
            $trangThaiTT,
            $row->tong_tien_hang,
            $row->so_tien_giam,
            $row->tong_thanh_toan,
            $trangThai,
            $row->ngay_dat->format('d/m/Y H:i'),
            $row->ghi_chu_khach,
        ];
    }
}
