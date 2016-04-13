<?php namespace Sanatorium\Pricing\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Pricing\Repositories\Currencieshistory\CurrencieshistoryRepositoryInterface;
use Sanatorium\Pricing\Models\Currency;
use Sanatorium\Pricing\Models\CurrenciesHistory;
use Carbon\Carbon;

class CurrencieshistoriesController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Pricing repository.
	 *
	 * @var \Sanatorium\Pricing\Repositories\Currencieshistory\CurrencieshistoryRepositoryInterface
	 */
	protected $currencieshistories;

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
	 * @param  \Sanatorium\Pricing\Repositories\Currencieshistory\CurrencieshistoryRepositoryInterface  $currencieshistories
	 * @return void
	 */
	public function __construct(CurrencieshistoryRepositoryInterface $currencieshistories)
	{
		parent::__construct();

		$this->currencieshistories = $currencieshistories;
	}

	/**
	 * Display a listing of currencieshistory.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/pricing::currencieshistories.index');
	}

	/**
	 * Datasource for the currencieshistory Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->currencieshistories->grid();

		$columns = [
			'id',
			'rate',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.pricing.currencieshistories.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new currencieshistory.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new currencieshistory.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating currencieshistory.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating currencieshistory.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified currencieshistory.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->currencieshistories->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/pricing::currencieshistories/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.pricing.currencieshistories.all');
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
				$this->currencieshistories->{$action}($row);
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
		// Do we have a currencieshistory identifier?
		if (isset($id))
		{
			if ( ! $currencieshistory = $this->currencieshistories->find($id))
			{
				$this->alerts->error(trans('sanatorium/pricing::currencieshistories/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.pricing.currencieshistories.all');
			}
		}
		else
		{
			$currencieshistory = $this->currencieshistories->createModel();
		}

		// Show the page
		return view('sanatorium/pricing::currencieshistories.form', compact('mode', 'currencieshistory'));
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
		// Store the currencieshistory
		list($messages) = $this->currencieshistories->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/pricing::currencieshistories/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.pricing.currencieshistories.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

	public function history($days = 30)
	{
		$histories = [];

		$day = 24*60*60;
		$now = Carbon::now()->timestamp;
		$start = $now - $days*$day;

		$last_valid_rate = 1;

		foreach( Currency::where('unit', '!=', '1')->get() as $currency ) {
			
			$values = [];

			for ( $i = 0; $i < $days; $i++ ) {

				$day_before = $start + (($i) * $day);
				$day_after = $start + (($i+1) * ($day));
				$history = CurrenciesHistory::where('currency_id', $currency->id)
							->where('created_at', '>', \Carbon\Carbon::createFromTimeStamp($day_before)->format('Y-m-d H:i:s') )
							->where('created_at', '<',  \Carbon\Carbon::createFromTimeStamp($day_after)->format('Y-m-d H:i:s') )
				           ->first();

				if ( $history ) {
					$values[] = [$day_before, $history->rate];

					$last_valid_rate = $history->rate;
				} else {
					$values[] = [$day_before, $last_valid_rate];
				}
			}

			$histories[] = [
				'key' => $currency->name,
				'values' => $values
			];
		}

		return $histories;
	}
}
