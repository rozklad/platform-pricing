<?php namespace Sanatorium\Pricing\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Pricing\Repositories\Currency\CurrencyRepositoryInterface;

class CurrenciesController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Pricing repository.
	 *
	 * @var \Sanatorium\Pricing\Repositories\Currency\CurrencyRepositoryInterface
	 */
	protected $currencies;

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
	 * @param  \Sanatorium\Pricing\Repositories\Currency\CurrencyRepositoryInterface  $currencies
	 * @return void
	 */
	public function __construct(CurrencyRepositoryInterface $currencies)
	{
		parent::__construct();

		$this->currencies = $currencies;
	}

	/**
	 * Display a listing of currency.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		$exchanges = app('sanatorium.pricing.exchange')->getServices();

		return view('sanatorium/pricing::currencies.index', compact('exchanges'));
	}

	/**
	 * Datasource for the currency Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->currencies->grid();

		$columns = [
			'id',
			'name',
			'code',
			'unit',
			'symbol',
			'format',
			'short_format',
			'locale',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.pricing.currencies.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new currency.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new currency.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating currency.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating currency.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified currency.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->currencies->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/pricing::currencies/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.pricing.currencies.all');
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
				$this->currencies->{$action}($row);
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
		// Do we have a currency identifier?
		if (isset($id))
		{
			if ( ! $currency = $this->currencies->find($id))
			{
				$this->alerts->error(trans('sanatorium/pricing::currencies/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.pricing.currencies.all');
			}
		}
		else
		{
			$currency = $this->currencies->createModel();
		}

		// Show the page
		return view('sanatorium/pricing::currencies.form', compact('mode', 'currency'));
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
		// Store the currency
		list($messages) = $this->currencies->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/pricing::currencies/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.pricing.currencies.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
