<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProjectAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ar')->create('xs_project_attributes', function (Blueprint $table) {
            $table->integer('attribute_id')->comment('属性主键');
            $table->integer('project_id')->comment('节目主键');
            $table->string('belong')->comment('节目别称')->default('');
            $table->index(['attribute_id', 'project_id', 'belong']);
            $table->unique(['attribute_id', 'project_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('ar')->dropIfExists('xs_project_attributes');
    }
}
