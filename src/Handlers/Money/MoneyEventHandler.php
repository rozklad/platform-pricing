<?php namespace Sanatorium\Pricing\Handlers\Money;

use Illuminate\Events\Dispatcher;
use Sanatorium\Pricing\Models\Money;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class MoneyEventHandler extends BaseEventHandler implements MoneyEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.pricing.money.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.pricing.money.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.pricing.money.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.pricing.money.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.pricing.money.deleted', __CLASS__.'@deleted');
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
	public function created(Money $money)
	{
		$this->flushCache($money);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Money $money, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Money $money)
	{
		$this->flushCache($money);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Money $money)
	{
		$this->flushCache($money);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Pricing\Models\Money  $money
	 * @return void
	 */
	protected function flushCache(Money $money)
	{
		$this->app['cache']->forget('sanatorium.pricing.money.all');

		$this->app['cache']->forget('sanatorium.pricing.money.'.$money->id);
	}

}
