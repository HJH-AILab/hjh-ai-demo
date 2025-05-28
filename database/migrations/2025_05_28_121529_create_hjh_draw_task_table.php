<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHjhDrawTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hjh_draw_task', function (Blueprint $table) {
            $table->increments('id');
            $table->string('task_no')->comment('任务编码')->unique('idx_task_no')->default('');
            $table->integer('user_id')->comment('创建者')->default(0);
            $table->integer('workflow_id')->comment('工作流ID')->default(0);
            $table->string('workflow_name')->comment('工作流名称')->default("");
            $table->string('name', 30)->comment('任务名称')->default('');
            $table->string('type', 20)->comment('类型')->default('');
            $table->integer('order_id')->comment('订单ID')->default(0);
            $table->json('user_parameter')->nullable()->comment('用户参数');
            $table->json('result_detail')->nullable()->comment('结果详情');
            $table->string('images', 1024)->comment('结果图片')->default('');
            $table->integer('task_status')->comment('任务状态 1:创建,2:执行中,3:执行完成,10:执行失败')->default(0);
            $table->string('task_desc')->comment('任务描述')->default('');
            $table->integer('add_time')->comment('创建任务时间')->default(0);
            $table->integer('start_time')->comment('开始时间')->default(0);
            $table->integer('end_time')->comment('结束时间')->default(0);
            $table->integer('status')->comment('状态 (0:禁用,1:启用)')->default(0);
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
        Schema::dropIfExists('hjh_draw_task');
    }
}
