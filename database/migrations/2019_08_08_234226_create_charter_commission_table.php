<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCharterCommissionTable extends Migration {

	public function up()
	{
		Schema::create('charter_commission', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('charter_id');
			$table->float('commission');
			$table->boolean('is_percent')->default(0);
		});
	}

	public function down()
	{
		Schema::drop('charter_commission');
	}
}