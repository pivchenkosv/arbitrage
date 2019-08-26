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
            $table->foreign('instance_id')->references('id')->on('instances');
            $table->string('first_currency');
            $table->string('second_currency');
            $table->string('type');
            $table->unsignedDecimal('rate', 16, 9);
            $table->unsignedInteger('quantity')->nullable();
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
