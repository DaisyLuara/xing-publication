<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_policies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('wx_user_id')->default(0);
            $table->integer('qiniu_id')->default(0)->comment('七牛ID');
            $table->integer('policy_id')->default(0)->comment('策略礼包ID');
            $table->string('belong')->default('')->comment('节目版本名称');
            $table->unique(['wx_user_id', 'qiniu_id', 'belong']);
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
        Schema::dropIfExists('user_policies');
    }
}
