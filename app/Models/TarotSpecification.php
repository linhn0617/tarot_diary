<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
