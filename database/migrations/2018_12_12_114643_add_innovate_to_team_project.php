<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInnovateToTeamProject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('team_projects', function (Blueprint $table) {
            $table->string('remark', 1000)->change();
            $table->string('art_innovate', 1000)->nullable()->after('launch_date')->comment("艺术风格创新点");
            $table->string('dynamic_innovate', 1000)->nullable()->after('art_innovate')->comment("动效体验创新点");
            $table->string('interact_innovate', 1000)->nullable()->after('dynamic_innovate')->comment("交互技术创新点");
            $table->string('media_id')->nullable()->comment('压缩包地址')->after('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('team_projects', function (Blueprint $table) {
            $table->string('remark')->change();
            $table->dropColumn('art_innovate', 1000);
            $table->dropColumn('dynamic_innovate', 1000);
            $table->dropColumn('interact_innovate', 1000);
            $table->dropColumn('media_id');
        });
    }
}
