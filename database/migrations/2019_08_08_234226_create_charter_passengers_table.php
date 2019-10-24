<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCharterPassengersTable extends Migration {

	public function up()
	{
		Schema::create('charter_passengers', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('order_id')->unsigned();
			$table->string('ticket_number', 10);
			$table->enum('flight_class', array('Economy', 'Business'));
			$table->enum('title', array('MRS', 'MR', 'INF'));
			$table->string('first_name', 100);
			$table->string('last_name', 100);
			$table->date('birth_date');
			$table->integer('nationality');
			$table->string('passport_number', 100);
			$table->date('passport_expire_date');
			$table->float('price');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('charter_passengers');
	}
}