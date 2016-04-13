<?php namespace Sanatorium\Pricing\Handlers\Currencieshistory;

interface CurrencieshistoryDataHandlerInterface {

	/**
	 * Prepares the given data for being stored.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function prepare(array $data);

}
