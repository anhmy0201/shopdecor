<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Sanpham;
use App\Models\LoaiSanpham;

class ChatbotController extends Controller
{

    public function index()
    {
        return view('chatbot.widget');
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message'           => 'required|string|max:500',
            'history'           => 'nullable|array',
            'history.*.role'    => 'in:user,model',
            'history.*.text'    => 'string|max:1000',
        ]);

        $userMessage = trim($request->input('message'));
        $history     = $request->input('history', []);

        $productContext = Cache::remember('chatbot_product_context', 3600, function () {
            return $this->buildProductContext();
        });

        $systemPrompt = $this->buildSystemPrompt($productContext);

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        foreach ($history as $turn) {
            $messages[] = [
                'role'    => $turn['role'] === 'model' ? 'assistant' : 'user',
                'content' => $turn['text'],
            ];
        }

        $messages[] = ['role' => 'user', 'content' => $userMessage];

        $apiKey = config('services.groq.api_key');
        $model  = config('services.groq.model', 'llama-3.3-70b-versatile');
        $apiUrl = 'https://api.groq.com/openai/v1/chat/completions';

        $payload = [
            'model'       => $model,
            'messages'    => $messages,
            'temperature' => 0.7,
            'max_tokens'  => 512,
            'top_p'       => 0.9,
        ];

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $apiKey,
                ])
                ->post($apiUrl, $payload);

            if ($response->failed()) {
                \Log::error('Groq API error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                $msg = match($response->status()) {
                    429     => 'Hệ thống AI đang bận, vui lòng thử lại sau ít phút.',
                    401     => 'Lỗi xác thực API. Vui lòng liên hệ quản trị viên.',
                    default => 'Hệ thống AI tạm thời gặp sự cố. Vui lòng thử lại sau.',
                };

                return response()->json(['success' => false, 'message' => $msg]);
            }

            $data     = $response->json();
            $botReply = $data['choices'][0]['message']['content']
                        ?? 'Xin lỗi, tôi chưa hiểu câu hỏi của bạn. Bạn có thể diễn đạt lại không?';

            return response()->json([
                'success' => true,
                'message' => $botReply,
            ]);

        } catch (\Exception $e) {
            \Log::error('Chatbot exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi kết nối. Vui lòng thử lại sau.',
            ]);
        }
    }

    /**
     * Lấy danh sách sản phẩm từ DB và format thành chuỗi ngắn gọn cho AI
     */
    private function buildProductContext(): string
    {
        $products = Sanpham::with('loai')
            ->whereNull('deleted_at')
            ->select([
                'id', 'ten_san_pham', 'slug',
                'gia', 'gia_cu',
                'so_luong',
                'loai_id',
                'co_bien_the',
            ])
            ->orderBy('luot_mua', 'desc')
            ->limit(20)
            ->get();

        $lines = [];
        foreach ($products as $p) {
            $price = ($p->gia_cu && $p->gia_cu > $p->gia)
                ? number_format($p->gia, 0, ',', '.') . 'đ (giảm từ ' . number_format($p->gia_cu, 0, ',', '.') . 'đ)'
                : number_format($p->gia, 0, ',', '.') . 'đ';

            $stock = $p->ton_kho > 0 ? 'còn hàng' : 'hết hàng';
            $cat   = $p->loai->ten_loai ?? 'Khác';

            $lines[] = "{$p->ten_san_pham} ({$cat}) - {$price} - {$stock}";
        }

        return implode("\n", $lines) ?: 'Hiện chưa có sản phẩm nào.';
    }

    private function buildSystemPrompt(string $productContext): string
    {
        return <<<PROMPT
Bạn là trợ lý AI của ShopDecor — cửa hàng chuyên phụ kiện và đồ trang trí bàn làm việc cao cấp.

Nhiệm vụ của bạn:
1. Tư vấn sản phẩm dựa trên danh sách bên dưới (tên, giá, loại, tình trạng hàng).
2. Hỗ trợ khách đặt hàng: hướng dẫn cách đặt, chính sách giao hàng, thanh toán.
3. Tra cứu đơn hàng: nếu khách cung cấp mã đơn, hướng dẫn vào trang tra cứu tại /tra-cuu-don-hang.
4. Trả lời thân thiện, ngắn gọn (tối đa 150 từ mỗi câu), dùng tiếng Việt.
5. KHÔNG bịa đặt sản phẩm, giá, hoặc thông tin không có trong danh sách.
6. Nếu không biết, hãy nói: "Bạn vui lòng liên hệ hotline 0799 669 238 hoặc nhắn Zalo để được hỗ trợ nhé!"

=== DANH SÁCH SẢN PHẨM HIỆN CÓ ===
{$productContext}
=== KẾT THÚC DANH SÁCH ===

Thông tin cửa hàng:
- Website: ShopDecor
- Hotline: 0799 669 238
- Email: anhmy0201@gmail.com
- Địa chỉ: 123 Nguyễn Huệ, Q.1, TP.HCM
- Giờ mở cửa: 8:00 – 22:00 mỗi ngày
- Giao hàng toàn quốc, miễn phí đơn từ 500.000đ
- Thanh toán: COD, chuyển khoản, PayOS
- Đổi trả trong 7 ngày nếu lỗi do nhà sản xuất
PROMPT;
    }
}