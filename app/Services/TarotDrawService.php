<?php

namespace App\Services;

use App\Models\Tarot;
use App\Models\TarotSpecification;

class TarotDrawService
{
    public function drawCard()
    {
        // 隨機抽取一張塔羅牌
        $tarotSpecification = TarotSpecification::inRandomOrder()->first();
        $tarot = Tarot::find($tarotSpecification->tarot_id);
        $message = rand(0, 1) ? $tarotSpecification->message1 : $tarotSpecification->message2;

        return [
            'tarot_id' => $tarot->id,
            'image' => $tarot->image_path,
            'name' => $tarot->name,
            'is_upright' => $tarotSpecification->is_upright,
            'message' => $message,
        ];
    }
}
