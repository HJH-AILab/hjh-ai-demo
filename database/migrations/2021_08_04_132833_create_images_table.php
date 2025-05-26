<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lens')->comment('镜头');
            $table->string('size')->comment('文件大小');
            $table->string('resolution')->comment('分辨率');
            $table->string('aspect_ratio')->comment('宽高比');
            $table->string('colour')->comment('颜色');
            $table->integer('user_id')->comment('创建者');
            $table->string('thumb')->comment('缩略图');
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
        Schema::dropIfExists('images');
    }
}
