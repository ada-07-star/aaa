<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('evaluation_objects', function(Blueprint $table) {
            $table->increments('id');
			$table->foreignId('evaluation_id')->constrained()->nullable();
			$table->foreignId('object_id')->constrained()->nullable();
			$table->integer('order_of');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('evaluation_objects');
	}
};
