<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePairsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pairs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('instance_id');
            $table->foreign('instance_id')->references('id')->on('instances');
            $table->string('name');
            $table->string('first_currency');
            $table->string('second_currency');
            $table->string('symbol');
            $table->boolean('is_enabled');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP(0)'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pairs');
    }
}
