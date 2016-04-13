<?php namespace Sanatorium\Pricing\Repositories\Currency;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class CurrencyRepository implements CurrencyRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Pricing\Handlers\Currency\CurrencyDataHandlerInterface
	 */
	protected $data;

	/**
	 * The Eloquent pricing model.
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * Constructor.
	 *
	 * @param  \Illuminate\Container\Container  $app
	 * @return void
	 */
	public function __construct(Container $app)
	{
		$this->setContainer($app);

		$this->setDispatcher($app['events']);

		$this->data = $app['sanatorium.pricing.currency.handler.data'];

		$this->setValidator($app['sanatorium.pricing.currency.validator']);

		$this->setModel(get_class($app['Sanatorium\Pricing\Models\Currency']));
	}

	/**
	 * {@inheritDoc}
	 */
	public function grid()
	{
		return $this
			->createModel();
	}

	/**
	 * {@inheritDoc}
	 */
	public function findAll()
	{
		return $this->container['cache']->rememberForever('sanatorium.pricing.currency.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.pricing.currency.'.$id, function() use ($id)
		{
			return $this->createModel()->find($id);
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForCreation(array $input)
	{
		return $this->validator->on('create')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForUpdate($id, array $input)
	{
		return $this->validator->on('update')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function store($id, array $input)
	{
		return ! $id ? $this->create($input) : $this->update($id, $input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(array $input)
	{
		// Create a new currency
		$currency = $this->createModel();

		// Fire the 'sanatorium.pricing.currency.creating' event
		if ($this->fireEvent('sanatorium.pricing.currency.creating', [ $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForCreation($data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Save the currency
			$currency->fill($data)->save();

			// Fire the 'sanatorium.pricing.currency.created' event
			$this->fireEvent('sanatorium.pricing.currency.created', [ $currency ]);
		}

		return [ $messages, $currency ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the currency object
		$currency = $this->find($id);

		// Fire the 'sanatorium.pricing.currency.updating' event
		if ($this->fireEvent('sanatorium.pricing.currency.updating', [ $currency, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($currency, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the currency
			$currency->fill($data)->save();

			// Fire the 'sanatorium.pricing.currency.updated' event
			$this->fireEvent('sanatorium.pricing.currency.updated', [ $currency ]);
		}

		return [ $messages, $currency ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the currency exists
		if ($currency = $this->find($id))
		{
			// Fire the 'sanatorium.pricing.currency.deleted' event
			$this->fireEvent('sanatorium.pricing.currency.deleted', [ $currency ]);

			// Delete the currency entry
			$currency->delete();

			return true;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function enable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => true ]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function disable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => false ]);
	}

}
