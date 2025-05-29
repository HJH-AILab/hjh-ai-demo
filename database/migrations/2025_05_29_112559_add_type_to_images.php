<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->integer('type')->comment('来源 1-用户 2-AIGC')->defalut(0);
            $table->index(array('type'), 'idx_type');
            $table->index(array('created_at'), 'idx_created_at');
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
            $table->dropColumn('type');
            $table->dropIndex('idx_type');
            $table->dropIndex('idx_created_at');
        });
    }
}
