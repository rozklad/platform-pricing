<?php namespace Sanatorium\Pricing\Handlers\Money;

interface MoneyDataHandlerInterface {

	/**
	 * Prepares the given data for being stored.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function prepare(array $data);

}
