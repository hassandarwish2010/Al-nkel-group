<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCharterTable extends Migration {

	public function up()
	{
		Schema::create('charter', function(Blueprint $table) {
			$table->increments('id');
			$table->longText('name');
			$table->integer('from_where');
			$table->integer('to_where');
			$table->enum('flight_type', array('RoundTrip', 'OneWay'));
			$table->string('flight_number', 20);
			$table->integer('aircraft')->unsigned();
			$table->timestamp('flight_date');
			$table->string('departure_time', 20);
			$table->string('arrival_time', 20);
			$table->integer('economy_seats');
			$table->integer('business_seats');
			$table->boolean('can_cancel');
			$table->boolean('show_in_home');
			$table->float('price_business');
			$table->float('price_adult');
			$table->float('price_child');
			$table->float('price_baby');
			$table->float('price_business_2way');
			$table->float('price_adult_2way');
			$table->float('price_child_2way');
			$table->integer('price_baby_2way');
			$table->float('commission');
			$table->boolean('is_percent');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('charter');
	}
}