<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrenciesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_currencies', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name')->nullable()->default('Dollar');
			$table->string('code')->nullable()->default('USD');
			$table->string('unit')->nullable()->default('1');
			$table->string('symbol')->nullable()->default(',-');
			$table->string('format')->default('$1,0.00');
			$table->string('short_format')->default('0!0.00 $');
			$table->string('locale')->nullable()->default('en_US');
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
		Schema::drop('shop_currencies');
	}

}
