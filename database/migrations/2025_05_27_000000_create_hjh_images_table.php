<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHjhImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hjh_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lens')->nullable()->comment('镜头');
            $table->string('size')->nullable()->comment('文件大小');
            $table->string('resolution')->nullable()->comment('分辨率');
            $table->string('aspect_ratio')->nullable()->comment('宽高比');
            $table->string('colour')->nullable()->comment('颜色');
            $table->integer('user_id')->comment('创建者');
            $table->string('thumb')->nullable()->comment('缩略图');
            $table->string('desc')->comment('图片描述');
            $table->integer('released')->comment('发布状态')->defalut(0);
            $table->string('thumb1920')->comment('1920缩略图')->defalut("");
            $table->string('thumb1280')->comment('1280缩略图')->defalut("");
            $table->string('thumb640')->comment('640缩略图')->defalut("");
            $table->string('keywords')->nullable()->comment('标签');
            $table->string('views')->default(0)->comment('图片views');
            $table->string('downnums')->default(0)->comment('图片下载数量');
            $table->integer('workflow_id')->comment('工作流ID')->defalut(0);
            $table->string('workflow_name')->comment('工作流名称')->defalut("");
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
        Schema::dropIfExists('hjh_images');
    }
}
