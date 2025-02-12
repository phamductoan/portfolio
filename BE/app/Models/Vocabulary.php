<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{
    use HasFactory;

    protected $table = 'vocabulary';
    
    protected $fillable = [
        'japanese_text',
        'kanji',
        'romaji',
        'significance',
        'unit',
        'user_id'  // Thêm user_id vào fillable
    ];

    // Thêm relationship với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}