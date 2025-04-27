<?php

namespace App\Http\Controllers;

use App\Http\Requests\TarotDiaryRequest;
use App\Http\Requests\UpdateTarotDiaryRequest;
use App\Services\TarotDiaryService;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TarotDiaryController extends Controller
{
    use ApiResponse;

    protected $service;

    public function __construct(TarotDiaryService $service)
    {
        $this->service = $service;
    }

    /**
     * 新增日記
     */
    public function store(TarotDiaryRequest $request): \Illuminate\Http\JsonResponse
    {
        $userId = Auth::id();

        $this->service->createDiary($userId, $request->validated());

        return $this->success('日記創建成功');
    }

    /**
     * 取得指定日記
     */
    public function show($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $userId = Auth::id();

        // 1. 取得指定日記
        $diary = $this->service->getDiary($userId, $id);

        // 2. 取得該日記所在範圍的月曆資料（前後三個月）
        $selectedDate = $request->query('date', $diary->created_at->toDateString());

        $diaries = $this->service->getMonthDiary($userId, $selectedDate);

        // 3. 格式化回傳的資料
        $data = [
            'created_at' => Carbon::parse($diary->created_at)->toDateString(),
            'user_entry_text' => $diary->user_entry_text,
            'tarot_card' => [
                'image' => $diary->tarot_specification->tarot->image_path,
                'name' => $diary->tarot_specification->tarot->name,
                'is_upright' => $diary->tarot_specification->is_upright,
                'blessing_message' => $diary->tarot_specification->is_upright
                    ? $diary->tarot_specification->message1
                    : $diary->tarot_specification->message2,
            ],
            // 4. 這是月曆範圍內的其他日記
            'month_diaries' => $diaries->map(function ($diary) {
                return [
                    'id' => $diary->id,
                    'created_at' => $diary->created_at->toDateString(),
                    'tarot_name' => $diary->tarot_specification->tarot->name,
                    'is_upright' => $diary->tarot_specification->is_upright,
                    'image' => $diary->tarot_specification->tarot->image_path,
                ];
            })->toArray(),
        ];

        return $this->responseWithData('日記取得成功', $data);
    }

    /**
     * 更新日記日期
     */
    public function update(UpdateTarotDiaryRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        $userId = Auth::id();

        $diary = $this->service->updateDiaryDate($userId, $id, $request->validated());

        $data = [
            'updated_at' => $diary->updated_at->toDateString(),
            'user_entry_text' => $diary->user_entry_text,
        ];

        return $this->responseWithData('日記更新成功', $data);
    }

    /**
     * 月曆模式取得日記（前後一個月）
     */
    public function getMonthDiaries(Request $request): \Illuminate\Http\JsonResponse
    {
        $userId = Auth::id();

        $selectedDate = $request->query('date');

        $diaries = $this->service->getMonthDiary($userId, $selectedDate);

        $data = $diaries->map(function ($diary) {
            return [
                'id' => $diary->id,
                'created_at' => $diary->created_at->toDateString(),
                'tarot_name' => $diary->tarotSpecification->tarot->name,
                'is_upright' => $diary->tarotSpecification->is_upright,
                'image' => $diary->tarotSpecification->tarot->image_path,
            ];
        })->toArray();

        return $this->responseWithData('日記清單取得成功', $data);
    }
}
