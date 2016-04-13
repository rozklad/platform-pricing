<?php namespace Sanatorium\Pricing\Repositories\Money;

interface MoneyRepositoryInterface {

	/**
	 * Returns a dataset compatible with data grid.
	 *
	 * @return \Sanatorium\Pricing\Models\Money
	 */
	public function grid();

	/**
	 * Returns all the pricing entries.
	 *
	 * @return \Sanatorium\Pricing\Models\Money
	 */
	public function findAll();

	/**
	 * Returns a pricing entry by its primary key.
	 *
	 * @param  int  $id
	 * @return \Sanatorium\Pricing\Models\Money
	 */
	public function find($id);

	/**
	 * Determines if the given pricing is valid for creation.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForCreation(array $data);

	/**
	 * Determines if the given pricing is valid for update.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForUpdate($id, array $data);

	/**
	 * Creates or updates the given pricing.
	 *
	 * @param  int  $id
	 * @param  array  $input
	 * @return bool|array
	 */
	public function store($id, array $input);

	/**
	 * Creates a pricing entry with the given data.
	 *
	 * @param  array  $data
	 * @return \Sanatorium\Pricing\Models\Money
	 */
	public function create(array $data);

	/**
	 * Updates the pricing entry with the given data.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Sanatorium\Pricing\Models\Money
	 */
	public function update($id, array $data);

	/**
	 * Deletes the pricing entry.
	 *
	 * @param  int  $id
	 * @return bool
	 */
	public function delete($id);

}
