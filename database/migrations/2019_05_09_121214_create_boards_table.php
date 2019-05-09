<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('boards', static function (Blueprint $table) {
            $table->increments('id');
            $table->string('campaign', 32)->default('');
            $table->string('belong', 32)->default('');
            $table->unsignedInteger('oid')->default(0);
            $table->unsignedInteger('count')->default(0);
            $table->unsignedInteger('candidate')->default(0)->comment('候选人ID');
            $table->string('candidate_z', 64)->default('')->comment('候选人Z值');
            $table->string('candidate_mobile', 32)->default('')->comment('候选人手机号码');
            $table->string('image_url')->default('');
            $table->bigInteger('created_date')->default(0);
            $table->timestamps();
        });

        Schema::create('board_votes', static function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('board_id')->default(0);
            $table->string('campaign', 32)->default('');
            $table->unsignedInteger('voter')->default(0);
            $table->unsignedInteger('candidate')->default(0);
            $table->string('voter_z', 32)->default('');
            $table->string('candidate_z', 32)->default('');
            $table->unsignedInteger('oid')->default(0);
            $table->string('belong', 32)->default('');
            $table->bigInteger('created_date')->default(0);
            $table->timestamps();
        });

        Schema::create('board_vote_configs', static function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('oid')->default(0);
            $table->string('belong', 32)->default('');
            $table->string('campaign', 32)->default('');
            $table->unsignedInteger('people_day_max_votes')->default(0)->comment('每天投票次数上限');
            $table->unsignedInteger('people_max_votes')->default(0)->comment('总投票次数上限');
            $table->unsignedInteger('vote_scope')->default(0)->comment('投票范围：0 当前候选人 1 所有候选人');
            $table->unsignedInteger('board_duration')->default(1)->comment('榜单有效期');
            $table->timestamps();
        });

        Schema::create('user_board_vote_configs', static function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('oid')->default(0);
            $table->string('belong', 32)->default('');
            $table->string('campaign', 32)->default('');
            $table->unsignedInteger('people_max_votes')->default(0)->comment('总投票次数');
            $table->unsignedInteger('people_votes_stock')->default(0)->comment('总剩余投票次数');
            $table->unsignedInteger('vote_scope')->default(0)->comment('投票范围：0 当前候选人 1 所有候选人');
            $table->bigInteger('created_date')->default(0);
            $table->timestamps();
        });

        Schema::create('user_board_vote_daily_configs', static function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_board_vote_config_id');
            $table->unsignedInteger('people_day_max_votes')->default(0)->comment('每天投票次数上限');
            $table->unsignedInteger('people_day_votes_stock')->default(0)->comment('每天剩余投票次数');
            $table->bigInteger('created_date')->default(0);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('boards');
        Schema::dropIfExists('board_votes');
        Schema::dropIfExists('board_vote_configs');
        Schema::dropIfExists('user_board_vote_configs');
        Schema::dropIfExists('user_board_vote_daily_configs');
    }
}
