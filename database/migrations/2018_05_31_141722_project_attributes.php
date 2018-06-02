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
        Schema::create('project_attributes', function (Blueprint $table) {
            $table->integer('attribute_id');
            $table->integer('project_id');
            $table->string('belong')->comment('节目别称')->default('');
            $table->index(['attribute_id', 'project_id']);
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
        Schema::dropIfExists('project_attributes');
    }
}
