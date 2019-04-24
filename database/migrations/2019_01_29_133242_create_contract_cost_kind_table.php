<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Controllers\Admin\Contract\V1\Models\ContractCostKind;

class CreateContractCostKindTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_cost_kinds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $data = [
            ['name' => '物流费用'],
            ['name' => '运维费用'],
            ['name' => '4G网络费用'],
            ['name' => '人员差旅'],
            ['name' => '物料费用'],
            ['name' => '其他']
        ];
        foreach ($data as $item) {
            ContractCostKind::create($item);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_cost_kinds');
    }
}
