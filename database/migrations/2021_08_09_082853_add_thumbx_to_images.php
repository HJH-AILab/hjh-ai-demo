<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThumbxToImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->string('thumb1920')->comment('1920缩略图');
            $table->string('thumb1280')->comment('1280缩略图');
            $table->string('thumb640')->comment('640缩略图');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropColumn('thumb1920');
            $table->dropColumn('thumb1280');
            $table->dropColumn('thumb640');
        });
    }
}
