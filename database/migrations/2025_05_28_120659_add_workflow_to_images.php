<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkflowToImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->integer('source')->comment('来源 0-默认 1-好机绘')->defalut(0);
            $table->integer('workflow_id')->comment('工作流ID')->defalut(0);
            $table->string('workflow_name')->comment('工作流名称')->defalut("");
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
            $table->dropColumn('source');
            $table->dropColumn('workflow_id');
            $table->dropColumn('workflow_name');
        });
    }
}
