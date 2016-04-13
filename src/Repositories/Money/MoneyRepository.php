<?php namespace Sanatorium\Pricing\Repositories\Money;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class MoneyRepository implements MoneyRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Pricing\Handlers\Money\MoneyDataHandlerInterface
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

		$this->data = $app['sanatorium.pricing.money.handler.data'];

		$this->setValidator($app['sanatorium.pricing.money.validator']);

		$this->setModel(get_class($app['Sanatorium\Pricing\Models\Money']));
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
		return $this->container['cache']->rememberForever('sanatorium.pricing.money.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.pricing.money.'.$id, function() use ($id)
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
		// Create a new money
		$money = $this->createModel();

		// Fire the 'sanatorium.pricing.money.creating' event
		if ($this->fireEvent('sanatorium.pricing.money.creating', [ $input ]) === false)
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
			// Save the money
			$money->fill($data)->save();

			// Fire the 'sanatorium.pricing.money.created' event
			$this->fireEvent('sanatorium.pricing.money.created', [ $money ]);
		}

		return [ $messages, $money ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the money object
		$money = $this->find($id);

		// Fire the 'sanatorium.pricing.money.updating' event
		if ($this->fireEvent('sanatorium.pricing.money.updating', [ $money, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($money, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the money
			$money->fill($data)->save();

			// Fire the 'sanatorium.pricing.money.updated' event
			$this->fireEvent('sanatorium.pricing.money.updated', [ $money ]);
		}

		return [ $messages, $money ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the money exists
		if ($money = $this->find($id))
		{
			// Fire the 'sanatorium.pricing.money.deleted' event
			$this->fireEvent('sanatorium.pricing.money.deleted', [ $money ]);

			// Delete the money entry
			$money->delete();

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
