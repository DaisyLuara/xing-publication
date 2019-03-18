<?php

use Illuminate\Database\Migrations\Migration;

class UpdateCompanyBdUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Unknown database type enum, MySQL57Platform may not support i
         * @see https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/cookbook/mysql-enums.html
         *
         * Run Raw SQL
         * @see https://stackoverflow.com/questions/28787293/run-raw-sql-in-migration/28787323
         */
        DB::connection('mysql')->statement('alter table companies modify bd_user_id int unsigned default 0 null ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
