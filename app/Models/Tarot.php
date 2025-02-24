<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $image_path
 * @property string $element
 * 
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Tarot extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'element',
        'image_path',
    ];

    public function tarot_specifications()
    {
        return $this->hasMany(TarotSpecification::class);
    }
}
