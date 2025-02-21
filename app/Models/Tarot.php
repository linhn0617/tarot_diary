<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
