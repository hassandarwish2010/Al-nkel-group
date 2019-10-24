<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCharterOrdersTable extends Migration {

	public function up()
	{
		Schema::create('charter_orders', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('charter_id');
			$table->float('price');
			$table->string('pnr', 8);
			$table->enum('status', array('awaiting', 'delivered', 'cancelled', 'rejected'));
			$table->integer('delivered_by');
			$table->string('phone', 30);
			$table->text('note');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('charter_orders');
	}
}