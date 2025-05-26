<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMusicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('musics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('歌曲名');
            $table->string('artist')->comment('艺术家');
            $table->string('cover')->comment('歌曲封面');
            $table->string('source')->comment('歌曲地址');
            $table->integer('user_id')->comment('创建者');
            $table->integer('image_id')->comment('所属图片');
            $table->text('desc')->comment('文字说明');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
            Schema::dropIfExists('musics');
    }
}
