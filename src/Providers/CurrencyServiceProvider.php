<?php namespace Sanatorium\Pricing\Providers;

use Cartalyst\Support\ServiceProvider;
use Converter;
use Sanatorium\Pricing\Models\Currency;
use Illuminate\Foundation\AliasLoader;

class CurrencyServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Pricing\Models\Currency']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.pricing.currency.handler.event');

		$this->registerCartalystConverterPackage();

		// Set the measurements
		$measurements = [
			'currency' => []
		];

        // Check if currencies are cached
        if ( ! $currencies = $this->app['cache']->get('sanatorium.pricing.currency.all') )
        {

            $currencies = $this->app['sanatorium.pricing.currency']->findAll();

        }

		foreach( $currencies as $currency ) {
			$measurements['currency'][$currency->code] = [
				'format' => $currency->format,
				'unit' => $currency->unit
			];
		}

		Converter::setMeasurements($measurements);

		// Register the Blade @pricing widget
        $this->registerBladePricingWidget();

        $this->prepareResources();

        // Register product as product
        AliasLoader::getInstance()->alias('Currency', 'Sanatorium\Pricing\Models\Currency');  
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.pricing.currency', 'Sanatorium\Pricing\Repositories\Currency\CurrencyRepository');

		// Register the data handler
		$this->bindIf('sanatorium.pricing.currency.handler.data', 'Sanatorium\Pricing\Handlers\Currency\CurrencyDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.pricing.currency.handler.event', 'Sanatorium\Pricing\Handlers\Currency\CurrencyEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.pricing.currency.validator', 'Sanatorium\Pricing\Validator\Currency\CurrencyValidator');
	
		// Register the manager
        $this->bindIf('sanatorium.pricing.exchange', 'Sanatorium\Pricing\Repositories\Exchange\ExchangeRepository');
	}

	/**
	 * Register cartalyst/converter package
	 * @return
	 */
	protected function registerCartalystConverterPackage() {
		$serviceProvider = 'Cartalyst\Converter\Laravel\ConverterServiceProvider';

		if (!$this->app->getProvider($serviceProvider)) {
			$this->app->register($serviceProvider);
			AliasLoader::getInstance()->alias('Converter', 'Cartalyst\Converter\Laravel\Facades\Converter');
		}
	}

	 /**
     * Register the Blade @pricing widget.
     *
     * @return void
     */
    protected function registerBladePricingWidget()
    {
        $this->app['blade.compiler']->directive('pricing', function ($value) {
            return "<?php echo Widget::make('sanatorium/pricing::entity.show', array$value); ?>";
        });
    }

    /**
     * Prepare the package resources.
     *
     * @return void
     */
    protected function prepareResources()
    {
        $config = realpath(__DIR__.'/../../config/config.php');

        $this->mergeConfigFrom($config, 'sanatorium-pricing');

        $this->publishes([
            $config => config_path('sanatorium-pricing.php'),
        ], 'config');
    }

}
