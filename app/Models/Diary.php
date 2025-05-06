<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tarot_specification_id',
        'user_entry_text',
    ];

    // 定義與 User 的關聯（多對一）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 關聯到 TarotSpecification 模型
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tarot_specification()
    {
        return $this->belongsTo(TarotSpecification::class);
    }
}
