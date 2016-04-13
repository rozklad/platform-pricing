<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('priced', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('priceable_type')->nullable();
			$table->integer('priceable_id')->nullable();
			$table->integer('money_id')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('priced');
	}

}
