<?php namespace Sanatorium\Pricing\Controllers\Frontend;

use Cart;
use Platform\Foundation\Controllers\Controller;
use Product;

class CartController extends Controller {

	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/pricing::cart/index');
	}

	public function add()
	{
		$product = Product::find( request()->get('id') );

		$item = [];

		$item['id'] = $product->id;

		$item['quantity'] = (int)request()->get('quantity');
		$item['name'] = $product->product_title;
		$item['price'] = $product->getPrice('vat', 1, false);
		$item['weight'] = $product->weight;

		Cart::add($item);

		if ( !request()->ajax() ) {
			return redirect()->back();
		}

		return redirect()->back();
	}
}
