<?php namespace Sanatorium\Pricing\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;

class CurrencieshistoriesController extends Controller {

	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/pricing::index');
	}

}
