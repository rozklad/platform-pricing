<?php namespace Sanatorium\Pricing\Handlers\Currency;

use Sanatorium\Pricing\Models\Currency;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface CurrencyEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a currency is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a currency is created.
	 *
	 * @param  \Sanatorium\Pricing\Models\Currency  $currency
	 * @return mixed
	 */
	public function created(Currency $currency);

	/**
	 * When a currency is being updated.
	 *
	 * @param  \Sanatorium\Pricing\Models\Currency  $currency
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Currency $currency, array $data);

	/**
	 * When a currency is updated.
	 *
	 * @param  \Sanatorium\Pricing\Models\Currency  $currency
	 * @return mixed
	 */
	public function updated(Currency $currency);

	/**
	 * When a currency is deleted.
	 *
	 * @param  \Sanatorium\Pricing\Models\Currency  $currency
	 * @return mixed
	 */
	public function deleted(Currency $currency);

}
