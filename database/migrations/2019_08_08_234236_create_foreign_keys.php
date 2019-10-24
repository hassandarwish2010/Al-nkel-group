<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('charter', function(Blueprint $table) {
			$table->foreign('aircraft')->references('id')->on('aircraft')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('charter_locked', function(Blueprint $table) {
			$table->foreign('charter_id')->references('id')->on('charter')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('charter_order_related', function(Blueprint $table) {
			$table->foreign('order_id')->references('id')->on('charter_orders')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('charter_order_related', function(Blueprint $table) {
			$table->foreign('charter_id')->references('id')->on('charter')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('charter_passengers', function(Blueprint $table) {
			$table->foreign('order_id')->references('id')->on('charter_orders')
						->onDelete('no action')
						->onUpdate('no action');
		});
	}

	public function down()
	{
		Schema::table('charter', function(Blueprint $table) {
			$table->dropForeign('charter_aircraft_foreign');
		});
		Schema::table('charter_locked', function(Blueprint $table) {
			$table->dropForeign('charter_locked_charter_id_foreign');
		});
		Schema::table('charter_order_related', function(Blueprint $table) {
			$table->dropForeign('charter_order_related_order_id_foreign');
		});
		Schema::table('charter_order_related', function(Blueprint $table) {
			$table->dropForeign('charter_order_related_charter_id_foreign');
		});
		Schema::table('charter_passengers', function(Blueprint $table) {
			$table->dropForeign('charter_passengers_order_id_foreign');
		});
	}
}