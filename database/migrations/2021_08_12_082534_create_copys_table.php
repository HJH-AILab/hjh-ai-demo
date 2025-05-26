<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCopysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('copys', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lens')->comment('镜头')->nullable();
            $table->string('size')->comment('大小')->nullable();
            $table->string('resolution')->comment('分辨率')->nullable();
            $table->string('aspect_ratio')->comment('宽高比')->nullable();
            $table->string('colour')->comment('颜色')->nullable();
            $table->string('desc')->comment('图片说明')->nullable();
            $table->integer('released')->comment('0未处理1处理');
            $table->string('url')->comment('采集地址');
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
        Schema::dropIfExists('copys');
    }
}
