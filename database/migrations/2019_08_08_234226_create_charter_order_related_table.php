<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCharterOrderRelatedTable extends Migration {

	public function up()
	{
		Schema::create('charter_order_related', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('order_id')->unsigned();
			$table->integer('charter_id')->unsigned();
			$table->float('price');
		});
	}

	public function down()
	{
		Schema::drop('charter_order_related');
	}
}