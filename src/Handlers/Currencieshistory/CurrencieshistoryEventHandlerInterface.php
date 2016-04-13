<?php namespace Sanatorium\Pricing\Handlers\Currencieshistory;

use Sanatorium\Pricing\Models\Currencieshistory;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface CurrencieshistoryEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a currencieshistory is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a currencieshistory is created.
	 *
	 * @param  \Sanatorium\Pricing\Models\Currencieshistory  $currencieshistory
	 * @return mixed
	 */
	public function created(Currencieshistory $currencieshistory);

	/**
	 * When a currencieshistory is being updated.
	 *
	 * @param  \Sanatorium\Pricing\Models\Currencieshistory  $currencieshistory
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Currencieshistory $currencieshistory, array $data);

	/**
	 * When a currencieshistory is updated.
	 *
	 * @param  \Sanatorium\Pricing\Models\Currencieshistory  $currencieshistory
	 * @return mixed
	 */
	public function updated(Currencieshistory $currencieshistory);

	/**
	 * When a currencieshistory is deleted.
	 *
	 * @param  \Sanatorium\Pricing\Models\Currencieshistory  $currencieshistory
	 * @return mixed
	 */
	public function deleted(Currencieshistory $currencieshistory);

}
