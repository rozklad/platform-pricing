<?php namespace Sanatorium\Pricing\Widgets;

use Sanatorium\Pricing\Models\CurrenciesHistory;
use Sanatorium\Pricing\Models\Currency;

/**
 * Widget::make('sanatorium/pricing::currencytoprimary.show', 'eur')
 */

class Currencytoprimary {

	public function show($currency_code = 'czk', $primary_code = 'czk')
	{
		$currency = Currency::where('code', $currency_code)->first();
		$primary = Currency::where('code', $primary_code)->first();

		// The target currency for widget is not available in the given instance
		if ( !is_object($currency) )
			return null;

		$yesterday_currency = CurrenciesHistory::where('currency_id', $currency->id)->first();

		$change = (($currency->unit - $yesterday_currency->rate) / $yesterday_currency->rate) * 100;

		switch( true ) {
			case ($change > 0):
				$way = 'up';
			break;
			case ($change < 0):
				$way = 'down';
			break;
			case ($change == 0):
				$way = 'equal';
			break;
		}

		return view('sanatorium/pricing::widgets/currencytoprimary', compact('currency', 'primary', 'change', 'way'));
	}

}
