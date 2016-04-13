<?php namespace Sanatorium\Pricing\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Pricing\Repositories\Money\MoneyRepositoryInterface;

class MoneyController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Pricing repository.
	 *
	 * @var \Sanatorium\Pricing\Repositories\Money\MoneyRepositoryInterface
	 */
	protected $money;

	/**
	 * Holds all the mass actions we can execute.
	 *
	 * @var array
	 */
	protected $actions = [
		'delete',
		'enable',
		'disable',
	];

	/**
	 * Constructor.
	 *
	 * @param  \Sanatorium\Pricing\Repositories\Money\MoneyRepositoryInterface  $money
	 * @return void
	 */
	public function __construct(MoneyRepositoryInterface $money)
	{
		parent::__construct();

		$this->money = $money;
	}

	/**
	 * Display a listing of money.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/pricing::money.index');
	}

	/**
	 * Datasource for the money Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->money->grid();

		$columns = [
			'id',
			'amount',
			'type',
			'currency_id',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.pricing.money.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new money.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new money.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating money.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating money.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified money.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->money->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/pricing::money/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.pricing.money.all');
	}

	/**
	 * Executes the mass action.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function executeAction()
	{
		$action = request()->input('action');

		if (in_array($action, $this->actions))
		{
			foreach (request()->input('rows', []) as $row)
			{
				$this->money->{$action}($row);
			}

			return response('Success');
		}

		return response('Failed', 500);
	}

	/**
	 * Shows the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return mixed
	 */
	protected function showForm($mode, $id = null)
	{
		// Do we have a money identifier?
		if (isset($id))
		{
			if ( ! $money = $this->money->find($id))
			{
				$this->alerts->error(trans('sanatorium/pricing::money/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.pricing.money.all');
			}
		}
		else
		{
			$money = $this->money->createModel();
		}

		// Show the page
		return view('sanatorium/pricing::money.form', compact('mode', 'money'));
	}

	/**
	 * Processes the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function processForm($mode, $id = null)
	{
		// Store the money
		list($messages) = $this->money->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/pricing::money/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.pricing.money.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
