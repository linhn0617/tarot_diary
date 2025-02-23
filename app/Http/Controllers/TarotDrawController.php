<?php

namespace App\Http\Controllers;

use App\Services\TarotDrawService;
use Illuminate\Http\JsonResponse;

class TarotDrawController extends Controller
{
    protected $tarotDrawService;

    public function __construct(TarotDrawService $tarotDrawService)
    {
        $this->tarotDrawService = $tarotDrawService;
    }

    public function drawCard(): JsonResponse
    {
        $tarotCard = $this->tarotDrawService->drawCard();

        return response()->json([
            'data' => [
                'message' => '完成抽牌',
                'tarot_card' => $tarotCard,
            ],
        ]);
    }
}
