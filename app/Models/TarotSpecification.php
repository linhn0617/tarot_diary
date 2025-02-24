<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $tarot_id
 * @property bool $is_upright
 * @property string $message1
 * @property string $message2
 * 
 * @mixin \Illuminate\Database\Eloquent\Builder
 */

class TarotSpecification extends Model
{
    use HasFactory;

    protected $fillable = [
        'tarot_id',
        'is_upright',
        'message1',
        'message2',
    ];

    public function tarot()
    {
        return $this->belongsTo(Tarot::class);
    }

    public function diaries()
    {
        return $this->hasMany(Diary::class);
    }
}
