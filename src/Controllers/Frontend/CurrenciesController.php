<?php namespace Sanatorium\Pricing\Controllers\Frontend;

use Cart;
use Event;
use Platform\Foundation\Controllers\Controller;
use Product;

class CurrenciesController extends Controller {

	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/pricing::index');
	}

	public function set($id = null)
	{
		request()->session()->put('active_currency_id', $id);

		// Remove all other discount conditions from cart
		Cart::removeConditionByType('discount');

		if ( Cart::count() > 0 ) {

			$items = Cart::items();

			foreach( $items as $item ) {

				$product_id = $item->get('id');
				$rowId = $item->get('rowId');
				$quantity = $item->get('quantity');

				try {
					Cart::remove($rowId);
				} catch(\Cartalyst\Cart\Exceptions\CartItemNotFoundException $e) {
					//
					$object = [
						'level' => '601',
						'level_name' => 'cart',
						'datetime' => \Carbon\Carbon::now(),
						'message' => 'Cart::remove('.$rowId.') failed for ' . $_SERVER['REMOTE_ADDR']
					]; 
					Event::fire('logger.error', [ $record ]);
				}

				$product = Product::find( $product_id );

				$item = [
					'id' 		=> $product->id,
					'quantity' 	=> $quantity,
					'name' 		=> $product->product_title,
					'price' 	=> $product->getPrice('vat', 1, null, false),
					'weight' 	=> $product->weight
				];

				Cart::add($item);

			}

		}

		return redirect()->back();
	}

	public function test()
	{
		try {
			Cart::remove('nesmysl');
		} catch(\Cartalyst\Cart\Exceptions\CartItemNotFoundException $e) {
			// 
			dd($e);
		}

		return redirect()->back();
	}

}
