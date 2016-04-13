<?php namespace Sanatorium\Pricing\Handlers\Money;

use Sanatorium\Pricing\Models\Money;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface MoneyEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a money is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a money is created.
	 *
	 * @param  \Sanatorium\Pricing\Models\Money  $money
	 * @return mixed
	 */
	public function created(Money $money);

	/**
	 * When a money is being updated.
	 *
	 * @param  \Sanatorium\Pricing\Models\Money  $money
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Money $money, array $data);

	/**
	 * When a money is updated.
	 *
	 * @param  \Sanatorium\Pricing\Models\Money  $money
	 * @return mixed
	 */
	public function updated(Money $money);

	/**
	 * When a money is deleted.
	 *
	 * @param  \Sanatorium\Pricing\Models\Money  $money
	 * @return mixed
	 */
	public function deleted(Money $money);

}
