<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAircraftTable extends Migration {

	public function up()
	{
		Schema::create('aircraft', function(Blueprint $table) {
			$table->increments('id');
			$table->text('name');
			$table->text('logo');
			$table->string('type', 30);
		});
	}

	public function down()
	{
		Schema::drop('aircraft');
	}
}