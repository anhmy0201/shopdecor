<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

class CaidatController extends Controller
{
    private array $subjectMap = [
        'Sanpham'     => 'App\Models\Sanpham',
        'Donhang'     => 'App\Models\Donhang',
        'Magiamgia'   => 'App\Models\Magiamgia',
        'User'        => 'App\Models\User',
        'LoaiSanpham' => 'App\Models\LoaiSanpham',
        'TinTuc'      => 'App\Models\TinTuc',
    ];

    public function index(Request $request)
    {
        $settings = [];
        if (Storage::disk('local')->exists('settings.json')) {
            $settings = json_decode(Storage::disk('local')->get('settings.json'), true) ?? [];
        }

        // ── Query activity log với filter ──
        $logQuery = Activity::with('causer')->latest();

        if ($request->filled('log_causer')) {
            $logQuery->where('causer_id', $request->log_causer);
        }
        if ($request->filled('log_subject')) {
            $logQuery->where('subject_type', 'like', '%' . $request->log_subject . '%');
        }
        if ($request->filled('log_event')) {
            $logQuery->where('event', $request->log_event);
        }
        if ($request->filled('log_date_from')) {
            $logQuery->whereDate('created_at', '>=', $request->log_date_from);
        }
        if ($request->filled('log_date_to')) {
            $logQuery->whereDate('created_at', '<=', $request->log_date_to);
        }

       $activityLogs = $logQuery->paginate(15)->appends(
            array_merge(['tab' => 'log'], $request->query())
        );
        $tongLog      = Activity::count();
        $subjectMap   = $this->subjectMap;
        $causers = \App\Models\User::whereNotNull('quyen_han')
                    ->where('quyen_han', '>', 0)
                    ->orderBy('ho_ten') 
                    ->get(['id', 'ho_ten', 'email']);

        return view('admin.caidat.index', compact(
            'settings',
            'activityLogs',
            'tongLog',
            'subjectMap',
            'causers'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'ten_cua_hang'  => 'nullable|string|max:100',
            'email_lien_he' => 'nullable|email|max:100',
            'so_dien_thoai' => 'nullable|string|max:20',
            'dia_chi'       => 'nullable|string|max:255',
            'mo_ta_ngan'    => 'nullable|string|max:500',
        ]);

        $data = $request->only([
            'ten_cua_hang', 'email_lien_he', 'so_dien_thoai', 'dia_chi', 'mo_ta_ngan',
        ]);

        Storage::disk('local')->put('settings.json', json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        activity()
        ->causedBy(auth()->user())
        ->log('Cập nhật cài đặt hệ thống');
            return back()->with('success', 'Đã lưu cài đặt thành công!');
        }

    public function destroyLog(Activity $activity)
    {
        $activity->delete();
        return back()->with('success', 'Đã xóa mục log.');
    }


    public function destroyAllLog(Request $request)
    {
        $request->validate([
            'confirm_xoa' => 'required|in:XOA',
        ], [
            'confirm_xoa.required' => 'Vui lòng nhập mã xác nhận.',
            'confirm_xoa.in'       => 'Mã xác nhận không đúng. Hãy nhập "XOA".',
        ]);

        Activity::truncate();
        return back()->with('success', 'Đã xóa toàn bộ nhật ký hoạt động.');
    }
}