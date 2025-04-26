<?php

namespace App\Services;

use App\Models\Diary;
use Carbon\Carbon;

class TarotDiaryService
{
    /**
     * 建立新日記
     */
    public function createDiary($userId, $data)
    {
        return Diary::create([
            'user_id' => $userId,
            'tarot_specification_id' => $data['tarot_id'] = 1,
            'user_entry_text' => $data['user_entry_text'],
            'created_at' => $data['created_at'] ?? now(),
        ]);
    }

    /**
     * 取得單一日記資料（含塔羅牌資訊）
     */
    public function getDiary(int $userId, int $diaryId): Diary
    {
        return Diary::with(['tarot_specification.tarot'])
            ->where('user_id', $userId)
            ->findOrFail($diaryId);
    }

    /**
     * 更新日記的日期
     */
    public function updateDiaryDate(int $userId, int $diaryId, string $content): Diary
    {
        $diary = Diary::where('user_id', $userId)->findOrFail($diaryId);

        $diary->user_entry_text = $content;
        $diary->updated_at = now();
        $diary->save();

        return $diary;
    }

    /**
     * 取得月曆範圍日記(前後各一個月份)
     */
    public function getMonthDiary($user_id, $baseDate = null)
    {
        // 1. 設定基準日期（如果未提供，則使用當前日期）如果提供 $baseDate，解析它，否則使用當前時間
        $baseDate = $baseDate ? Carbon::parse($baseDate) : Carbon::now();

        // 2. 調整到當月第一天（00:00:00）
        $baseDate->startOfMonth();

        // 3. 計算查詢範圍：
        //    - 開始日期 = 前一個月的第一天（00:00:00）
        //    - 結束日期 = 後一個月的最後一天（23:59:59）
        $startDate = $baseDate->copy()->subMonth(); // 前一個月
        $endDate = $baseDate->copy()->addMonth()->endOfMonth(); // 後一個月的最後時刻

        // 4. 查詢該用戶的日記（確保只查詢該用戶的資料）
        return Diary::with(['tarot_specification.tarot'])
            ->where('user_id', $user_id) // 確保只查詢該用戶的日記
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
