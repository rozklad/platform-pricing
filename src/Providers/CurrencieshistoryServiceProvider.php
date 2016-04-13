<?php namespace Sanatorium\Pricing\Providers;

use Cartalyst\Support\ServiceProvider;

class CurrencieshistoryServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Pricing\Models\CurrenciesHistory']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.pricing.currencieshistory.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.pricing.currencieshistory', 'Sanatorium\Pricing\Repositories\Currencieshistory\CurrencieshistoryRepository');

		// Register the data handler
		$this->bindIf('sanatorium.pricing.currencieshistory.handler.data', 'Sanatorium\Pricing\Handlers\Currencieshistory\CurrencieshistoryDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.pricing.currencieshistory.handler.event', 'Sanatorium\Pricing\Handlers\Currencieshistory\CurrencieshistoryEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.pricing.currencieshistory.validator', 'Sanatorium\Pricing\Validator\Currencieshistory\CurrencieshistoryValidator');
	}

}
