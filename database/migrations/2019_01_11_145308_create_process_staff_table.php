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
        $legalRole = Role::findByName('legal-affairs');
        $legal = $legalRole->users()->first();
        DB::table('process_staffs')->insert(['user_id' => $legal->id, 'role' => 'legal-affairs', 'line' => 'contract']);
        DB::table('process_staffs')->insert(['user_id' => $legal->id, 'role' => 'legal-affairs', 'line' => 'invoice']);
        DB::table('process_staffs')->insert(['user_id' => $legal->id, 'role' => 'legal-affairs', 'line' => 'payment']);

        $financeRole = Role::findByName('finance');
        $finance = $financeRole->users()->first();
        DB::table('process_staffs')->insert(['user_id' => $finance->id, 'role' => 'finance', 'line' => 'invoice']);
        DB::table('process_staffs')->insert(['user_id' => $finance->id, 'role' => 'finance', 'line' => 'payment']);

        $auditorRole = Role::findByName('auditor');
        $auditor = $auditorRole->users()->first();
        DB::table('process_staffs')->insert(['user_id' => $auditor->id, 'role' => 'auditor', 'line' => 'payment']);

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
