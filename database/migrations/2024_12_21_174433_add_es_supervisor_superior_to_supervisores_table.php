<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEsSupervisorSuperiorToSupervisoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supervisores', function (Blueprint $table) {
		 $table->boolean('es_supervisor_superior')->default(false);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supervisores', function (Blueprint $table) {
            $table->dropColumn('es_supervisor_superior');
        });
    }
}
