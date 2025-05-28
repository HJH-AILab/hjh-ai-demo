<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->index(array('source'), 'idx_source');
            $table->index(array('workflow_id'), 'idx_workflow_id');
            $table->index(array('user_id'), 'idx_user_id');
            $table->index(array('desc'), 'idx_desc');
            $table->index(array('keywords'), 'idx_keywords');
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
            $table->dropIndex('idx_source');
            $table->dropIndex('idx_workflow_id');
            $table->dropIndex('idx_user_id');
            $table->dropIndex('idx_desc');
            $table->dropIndex('idx_keywords');
        });
    }
}
