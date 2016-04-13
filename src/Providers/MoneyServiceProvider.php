<?php namespace Sanatorium\Pricing\Providers;

use Cartalyst\Support\ServiceProvider;
use Product;

class MoneyServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Pricing\Models\Money']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.pricing.money.handler.event');

		// Register all the default hooks
        $this->registerHooks();
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.pricing.money', 'Sanatorium\Pricing\Repositories\Money\MoneyRepository');

		// Register the data handler
		$this->bindIf('sanatorium.pricing.money.handler.data', 'Sanatorium\Pricing\Handlers\Money\MoneyDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.pricing.money.handler.event', 'Sanatorium\Pricing\Handlers\Money\MoneyEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.pricing.money.validator', 'Sanatorium\Pricing\Validator\Money\MoneyValidator');
	}

	/**
     * Register all hooks.
     *
     * @return void
     */
    protected function registerHooks()
    {
        $hooks = [
            'catalog.product.bottom' => 'sanatorium/pricing::hooks.price',
            'shop.header' => 'sanatorium/pricing::hooks.currencies',
        ];

        $manager = $this->app['sanatorium.hooks.manager'];

        foreach ($hooks as $position => $hook) {
            $manager->registerHook($position, $hook);
        }
    }


}
