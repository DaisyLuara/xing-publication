<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAttributeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ar')->table('xs_attributes', function (Blueprint $table) {
            if (Schema::connection('ar')->hasColumn('xs_attributes', 'pid')) {
                $table->renameColumn('pid', 'parent_id');
            }
            if (!Schema::connection('ar')->hasColumn('xs_attributes', 'lft')) {
                $table->integer('lft')->nullable();
            }
            if (!Schema::connection('ar')->hasColumn('xs_attributes', 'rgt')) {
                $table->integer('rgt')->nullable();
            }
            if (!Schema::connection('ar')->hasColumn('xs_attributes', 'depth')) {
                $table->integer('depth')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('ar')->table('xs_attributes', function (Blueprint $table) {
            $table->renameColumn('parent_id', 'pid');
            $table->dropColumn('lft');
            $table->dropColumn('rgt');
            $table->dropColumn('depth');
        });
    }
}
