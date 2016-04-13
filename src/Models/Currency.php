<?php namespace Sanatorium\Pricing\Models;

use Cartalyst\Attributes\EntityInterface;
use Illuminate\Database\Eloquent\Model;
use Platform\Attributes\Traits\EntityTrait;
use Cartalyst\Support\Traits\NamespacedEntityTrait;
use Converter;

class Currency extends Model implements EntityInterface {

	use EntityTrait, NamespacedEntityTrait;

	/**
	 * {@inheritDoc}
	 */
	protected $table = 'shop_currencies';

	/**
	 * {@inheritDoc}
	 */
	protected $guarded = [
		'id',
	];

	/**
	 * {@inheritDoc}
	 */
	protected $with = [
		'values.attribute',
	];

	/**
	 * {@inheritDoc}
	 */
	protected static $entityNamespace = 'sanatorium/pricing.currency';

	public static function getPrimaryCurrency()
	{
		if ( ! $active_currency_id = request()->session()->get('active_currency_id') ) {
			$active_currency_id = config('sanatorium-pricing.active_currency_id');
		}

		return Currency::find($active_currency_id); 
	}

	/**
	 * Alias function
	 */
	public static function getActiveCurrency()
    {
        return self::getPrimaryCurrency();
    }

    public static function format($value = 0)
    {
    	$currency = self::getActiveCurrency();

    	return Converter::to('currency.' . $currency->code)->value($value)->format($currency->short_format);
    }

    /**
     * [convert description]
     * @param  int 	  $amount Amount of money to be converted
     * @param  string $source Source currency
     * @param  target $target Target currency
     * @return [type]         [description]
     */
    public static function convert($amount, $source, $target)
    {
    	$source = Currency::where('code', $source)->first();
    	$target = Currency::where('code', $target)->first();

    	$neutral_amount = $amount / $target->unit;

    	return $neutral_amount * $source->unit;
    }

}
