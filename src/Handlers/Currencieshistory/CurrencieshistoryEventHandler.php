<?php namespace Sanatorium\Pricing\Handlers\Currencieshistory;

use Illuminate\Events\Dispatcher;
use Sanatorium\Pricing\Models\Currencieshistory;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class CurrencieshistoryEventHandler extends BaseEventHandler implements CurrencieshistoryEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.pricing.currencieshistory.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.pricing.currencieshistory.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.pricing.currencieshistory.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.pricing.currencieshistory.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.pricing.currencieshistory.deleted', __CLASS__.'@deleted');
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
	public function created(Currencieshistory $currencieshistory)
	{
		$this->flushCache($currencieshistory);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Currencieshistory $currencieshistory, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Currencieshistory $currencieshistory)
	{
		$this->flushCache($currencieshistory);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Currencieshistory $currencieshistory)
	{
		$this->flushCache($currencieshistory);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Pricing\Models\Currencieshistory  $currencieshistory
	 * @return void
	 */
	protected function flushCache(Currencieshistory $currencieshistory)
	{
		$this->app['cache']->forget('sanatorium.pricing.currencieshistory.all');

		$this->app['cache']->forget('sanatorium.pricing.currencieshistory.'.$currencieshistory->id);
	}

}
