<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('instance_id');
            $table->bigInteger('pair_id');
            $table->foreign('instance_id')->references('id')->on('instances');
            $table->foreign('pair_id')->references('id')->on('pairs');
            $table->string('type');
            $table->unsignedDecimal('rate', 16, 9);
            $table->unsignedDecimal('quantity')->nullable();
            $table->unsignedDecimal('total', 16, 9);
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
        Schema::dropIfExists('orders');
    }
}
