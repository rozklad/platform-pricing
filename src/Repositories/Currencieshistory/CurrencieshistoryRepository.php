<?php namespace Sanatorium\Pricing\Repositories\Currencieshistory;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class CurrencieshistoryRepository implements CurrencieshistoryRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Pricing\Handlers\Currencieshistory\CurrencieshistoryDataHandlerInterface
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

		$this->data = $app['sanatorium.pricing.currencieshistory.handler.data'];

		$this->setValidator($app['sanatorium.pricing.currencieshistory.validator']);

		$this->setModel(get_class($app['Sanatorium\Pricing\Models\Currencieshistory']));
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
		return $this->container['cache']->rememberForever('sanatorium.pricing.currencieshistory.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.pricing.currencieshistory.'.$id, function() use ($id)
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
		// Create a new currencieshistory
		$currencieshistory = $this->createModel();

		// Fire the 'sanatorium.pricing.currencieshistory.creating' event
		if ($this->fireEvent('sanatorium.pricing.currencieshistory.creating', [ $input ]) === false)
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
			// Save the currencieshistory
			$currencieshistory->fill($data)->save();

			// Fire the 'sanatorium.pricing.currencieshistory.created' event
			$this->fireEvent('sanatorium.pricing.currencieshistory.created', [ $currencieshistory ]);
		}

		return [ $messages, $currencieshistory ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the currencieshistory object
		$currencieshistory = $this->find($id);

		// Fire the 'sanatorium.pricing.currencieshistory.updating' event
		if ($this->fireEvent('sanatorium.pricing.currencieshistory.updating', [ $currencieshistory, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($currencieshistory, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the currencieshistory
			$currencieshistory->fill($data)->save();

			// Fire the 'sanatorium.pricing.currencieshistory.updated' event
			$this->fireEvent('sanatorium.pricing.currencieshistory.updated', [ $currencieshistory ]);
		}

		return [ $messages, $currencieshistory ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the currencieshistory exists
		if ($currencieshistory = $this->find($id))
		{
			// Fire the 'sanatorium.pricing.currencieshistory.deleted' event
			$this->fireEvent('sanatorium.pricing.currencieshistory.deleted', [ $currencieshistory ]);

			// Delete the currencieshistory entry
			$currencieshistory->delete();

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
