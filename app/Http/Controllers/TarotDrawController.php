<?php

namespace App\Http\Controllers;

use App\Services\TarotDrawService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class TarotDrawController extends Controller
{
    use ApiResponse;

    protected $tarotDrawService;

    public function __construct(TarotDrawService $tarotDrawService)
    {
        $this->tarotDrawService = $tarotDrawService;
    }

    public function drawCard(): JsonResponse
    {
        $tarotCard = $this->tarotDrawService->drawCard();

        return $this->responseWithData('完成抽牌', $tarotCard);
    }
}
