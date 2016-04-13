<?php namespace Sanatorium\Pricing\Handlers\Currency;

use Illuminate\Events\Dispatcher;
use Sanatorium\Pricing\Models\Currency;
use Sanatorium\Pricing\Models\CurrenciesHistory;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class CurrencyEventHandler extends BaseEventHandler implements CurrencyEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.pricing.currency.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.pricing.currency.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.pricing.currency.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.pricing.currency.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.pricing.currency.deleted', __CLASS__.'@deleted');
	}

	/**
	 * {@inheritDoc}
	 */
	public function creating(array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function created(Currency $currency)
	{
		// Currency rate changed
		CurrenciesHistory::create([
				'rate' => $currency->unit,
				'currency_id' => $currency->id
			]);

		$this->flushCache($currency);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Currency $currency, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Currency $currency)
	{
		// Currency rate changed
		CurrenciesHistory::create([
				'rate' => $currency->unit,
				'currency_id' => $currency->id
			]);

		$this->flushCache($currency);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Currency $currency)
	{
		$this->flushCache($currency);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Pricing\Models\Currency  $currency
	 * @return void
	 */
	protected function flushCache(Currency $currency)
	{
		$this->app['cache']->forget('sanatorium.pricing.currency.all');

		$this->app['cache']->forget('sanatorium.pricing.currency.'.$currency->id);
	}

}
