<?php

namespace App\Services;

use App\Models\Tarot;
use App\Models\TarotSpecification;

/**
 * Service class for drawing tarot cards
 */
class TarotDrawService
{
    /**
     * @return array{tarot_id: int, image: string, name: string, is_upright: bool, message: string}
     */
    public function drawCard(): array
    {
        // 隨機抽取一張塔羅牌
        $tarotSpecification = TarotSpecification::inRandomOrder()->firstOrFail();
        /** @var Tarot $tarot */
        $tarot = Tarot::findOrFail($tarotSpecification->tarot_id);
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
