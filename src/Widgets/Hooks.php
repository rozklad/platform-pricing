<?php namespace Sanatorium\Pricing\Widgets;

use Sanatorium\Pricing\Models\Currency;
use Product;

class Hooks {

	public function price($object = null)
	{
		// hotfix, @todo make a template
		return null;

		if ( is_object($object) ) 
			return $object->price;

		return null;
	}

	public function currencies($class = null)
	{
		$currencies = Currency::all();

		$active_currency = Currency::find( Product::getActiveCurrencyId() );

		return view('sanatorium/pricing::hooks/currencies', compact('currencies', 'active_currency', 'class'));
	}

}
