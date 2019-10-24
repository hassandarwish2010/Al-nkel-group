<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCharterPassengersRelatedTable extends Migration {

	public function up()
	{
		Schema::create('charter_passengers_related', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('passenger_id');
			$table->string('ticket_number', 10);
			$table->float('price');
		});
	}

	public function down()
	{
		Schema::drop('charter_passengers_related');
	}
}