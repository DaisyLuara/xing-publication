<?php

use App\Http\Controllers\Admin\Privilege\V1\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_staffs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('role');
            $table->string('line')->comment('contract,invoice,payment');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_staffs');
    }
}
