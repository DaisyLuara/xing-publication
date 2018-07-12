<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyProject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ar')->create('xs_company_project', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('project_id');
            $table->integer('user_id');
            $table->unique(['company_id', 'project_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('ar')->dropIfExists('xs_company_project');
    }
}
