<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVocabularyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vocabulary', function (Blueprint $table) {
            $table->id();
            // Thêm user_id ngay sau id và tạo foreign key
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            // Phần code của bạn giữ nguyên
            if (config('database.default') === 'sqlite') {
                // Nếu là SQLite thì không sử dụng collation
                $table->string('japanese_text');
            } else {
                // Nếu không phải SQLite thì sử dụng collation utf8mb4
                $table->string('japanese_text')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            }
            $table->string('kanji')->nullable();
            $table->string('romaji');
            $table->string('significance');
            $table->integer('unit');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vocabulary');
    }
}
