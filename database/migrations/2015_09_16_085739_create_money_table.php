<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoneyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_money', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('amount')->nullable();
			$table->string('type')->nullable();
			$table->string('currency_id')->nullable();
			$table->integer('manual')->default('0');
			$table->integer('primary')->default('0');
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
		Schema::drop('shop_money');
	}

}
