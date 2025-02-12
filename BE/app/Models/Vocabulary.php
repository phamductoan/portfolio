<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{
    use HasFactory;

    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'vocabulary';
    protected $guarded = [];

    // Các trường có thể được gán giá trị một cách mass assignable
    protected $fillable = [
        'japanese_text',
        'kanji',
        'romaji',
        'significance',
        'unit',
    ];

    // Nếu bạn muốn bảo vệ tất cả các trường và chỉ cho phép các trường này được gán giá trị một cách mass assignable
    // protected $guarded = [];

    // Nếu bảng của bạn không có các trường timestamps (created_at và updated_at), bạn có thể đặt giá trị này thành false
    // public $timestamps = false;
}
