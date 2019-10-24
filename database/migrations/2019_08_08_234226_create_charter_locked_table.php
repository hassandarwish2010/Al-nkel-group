<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCharterLockedTable extends Migration {

	public function up()
	{
		Schema::create('charter_locked', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('charter_id')->unsigned();
			$table->integer('user_id');
			$table->integer('seats');
			$table->float('price');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('charter_locked');
	}
}