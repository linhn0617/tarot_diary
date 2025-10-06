<?php

namespace App\Services;

use App\Models\Tarot;
use App\Models\TarotSpecification;

/**
 * Service class for drawing tarot cards.服務類別：塔羅牌抽取
 * 此類別負責從 `tarot_specifications` 表中隨機抽取一張塔羅牌，並返回其相關資訊。
 */
class TarotDrawService
{
    /**
     * 隨機抽取一張塔羅牌，並返回牌的詳細資訊
     *
     * @return array{tarot_specification_id: int, tarot_id: int, image: string, name: string, is_upright: bool, message: string}
     */
    public function drawCard(): array
    {
        // 隨機抽取一張塔羅牌
        $tarotSpecification = TarotSpecification::with('tarot')->inRandomOrder()->firstOrFail(); // @phpstan-ignore-line

        // 隨機選擇一個關心小語
        $message = random_int(0, 1) ? $tarotSpecification->message1 : $tarotSpecification->message2;

        return [
            'tarot_specification_id' => $tarotSpecification->id,
            'tarot_id' => $tarotSpecification->tarot->id, // @phpstan-ignore-line
            'image' => $tarotSpecification->tarot->image_path, // @phpstan-ignore-line
            'name' => $tarotSpecification->tarot->name, // @phpstan-ignore-line
            'is_upright' => $tarotSpecification->is_upright,
            'message' => $message,
        ];
    }
}
