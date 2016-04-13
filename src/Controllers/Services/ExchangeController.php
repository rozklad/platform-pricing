<?php namespace Sanatorium\Pricing\Controllers\Services;

use Sanatorium\Pricing\Models\Currency;
use Sanatorium\Pricing\Models\CurrenciesHistory;
use Sanatorium\Pricing\Repositories\Currency\CurrencyRepositoryInterface;
use Sanatorium\Pricing\Controllers\Services\ExchangeController;
use Carbon\Carbon;

class ExchangeController extends \Platform\Foundation\Controllers\Controller {

	/**
     * {@inheritDoc}
     */
    protected $activeThemeArea = 'admin';

    /**
     * {@inheritDoc}
     */
    protected $fallbackThemeArea = 'admin';

	public function __construct(CurrencyRepositoryInterface $currencies)
	{
		parent::__construct();

		$this->currencies = $currencies;
	}


	public function exchange(\Illuminate\Http\Request $request)
	{

		$service_provider = get_called_class();
		
		$data = $service_provider::getSourceData();
		
		foreach( $data as $code => $course ) {

			$currency = Currency::where('code', $code)->first();

			if ($currency) {
				// Store the currency
				list($messages) = $this->currencies->store($currency->id, ['unit' => $course['rate']]);
			}
		}

		if ($request->ajax()) {
			return response('Success');
		}

		$this->alerts->success(trans("sanatorium/pricing::message.success.exchange"));

		return redirect()->back();
	}

	public function history(\Illuminate\Http\Request $request, $days = 30)
	{
		for ( $i = 0; $i < $days; $i++ ) {
			$day = Carbon::now()->subDays($i);

			$service_provider = get_called_class();

			$data = $service_provider::getSourceDataByDate( $day->format('j.n.Y') );

			foreach( $data as $code => $course ) {

				$currency = Currency::where('code', $code)->first();

				if ($currency) {
					CurrenciesHistory::create([
						'currency_id' => $currency->id,
						'rate' => $course['rate'],
						'created_at' => $day->format('Y-m-d H:i:s')
					]);
				}
			}
			
		}

		if ($request->ajax()) {
			return response('Success');
		}

		$this->alerts->success(trans("sanatorium/pricing::message.success.history"));

		return redirect()->back();
	}

}
