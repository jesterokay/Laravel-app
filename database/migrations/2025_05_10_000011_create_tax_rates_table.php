<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxRatesTable extends Migration
{
    public function up()
    {
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->decimal('rate', 5, 2);
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('tax_rates');
    }
}