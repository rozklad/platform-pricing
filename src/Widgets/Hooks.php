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
		$select = ['id', 'code'];

		$currencies = app('sanatorium.pricing.currency')->get($select);

		$active_currency = app('sanatorium.pricing.currency')->whereId( Product::getActiveCurrencyId() )->first($select);

		return view('sanatorium/pricing::hooks/currencies', compact('currencies', 'active_currency', 'class'));
	}

}
