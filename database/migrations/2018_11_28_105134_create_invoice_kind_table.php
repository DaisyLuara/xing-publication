<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceKind;

class CreateInvoiceKindTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_kinds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $data = [
            [
                'name' => '硬件',
            ],
            [
                'name' => '软件'
            ],
            [
                'name' => '服务费'
            ]
        ];

        foreach ($data as $item) {
            InvoiceKind::create($item);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_kinds');
    }
}
