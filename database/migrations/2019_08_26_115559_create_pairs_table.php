<?php

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
            $table->string('symbol');
            $table->boolean('is_enabled');
            $table->timestamps();
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
