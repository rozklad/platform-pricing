<?php namespace Sanatorium\Pricing\Database\Seeds;

use DB;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Sanatorium\Pricing\Models\Currency;

class CurrenciesTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// $faker = Faker::create();

		DB::table('shop_currencies')->truncate();

		Currency::create([
			'name' => 'Czech koruna',
			'code' => 'czk',
			'unit' => 1,
			'symbol' => ',-',
			'format' => '0,0.00 Kč',
			'short_format' => '0!0 Kč',
			'locale' => 'cs'
			]);

		Currency::create([
			'name' => 'Dollar',
			'code' => 'usd',
			'unit' => 24.11,
			'symbol' => '$',
			'format' => '0,0.00 $',
			'short_format' => '0!0 $',
			'locale' => 'en_US'
			]);

		Currency::create([
			'name' => 'Euro',
			'code' => 'eur',
			'unit' => 27.06,
			'symbol' => '€',
			'format' => '0,0.00 €',
			'short_format' => '0!0 €',
			'locale' => 'eu'
			]);

        Currency::create([
            'name' => 'Swiss franc',
            'code' => 'chf',
            'unit' => 24.786,
            'symbol' => 'CHF',
            'format' => '0,0.00 CHF',
            'short_format' => '0!0 CHF',
            'locale' => 'de_CH'
        ]);
	}

}
