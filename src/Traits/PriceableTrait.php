<?php namespace Sanatorium\Pricing\Traits;

use Sanatorium\Pricing\Models\Currency;
use Sanatorium\Pricing\Models\Money;
use Converter;
use Cache;

trait PriceableTrait {

	/**
	 * Price
	 *
	 * Defines manyToMany polymorphic relationship on table `priced`
	 * that goes by name `priceable`, therefore will look for columns
	 * `priceable_type` and `priceable_id`.
	 *
	 * @return object Relation object
	 */
	public function price()
    {
        return $this->morphToMany('Sanatorium\Pricing\Models\Money', 'priceable', 'priced');
    }

    public function getPriceAttribute()				{ return $this->getPrice('plain'); }
    public function getPriceVatAttribute() 			{ return $this->getPrice('vat'); }

    public function setPriceAttribute($value) 		{ return $this->setPrice('plain', $value); }
    public function setPriceVatAttribute($value)	{ return $this->setPrice('vat', $value); }

    /**
     * Check if product is free
     * @return [type] [description]
     */
    public function getFreeAttribute()
    {
        if ($this->getPrice('vat', 1, null, false) == 0 )
            return true;

        return false;
    }

    public function getPriceObject($currency, $type = 'plain', $force = true)
    {
        if ( is_object($currency) )
            $currency_id = $currency->id;
        else if ( is_numeric($currency) )
            $currency_id = $currency;
        else if ( is_string($currency) )
            $currency_id = Currency::where('code', $currency)->first()->id;

        // Find price data
        $price = $this->price()
                       ->where('shop_money.type', $type)
                       ->where('shop_money.currency_id', $currency_id);

        if ( $price->count() == 0 && $force ) {
            return $this->getCalculatedPriceObject($currency_id, $type);
        } else if ( $price->count() == 0 && !$force ) {
            return false;
        }

        return $price->orderBy('created_at', 'DESC')->first();
    }

    public function getCalculatedPriceObject($currency_id, $type = 'plain')
    {
        // Find primary currency price
        $primary_currency_id = $this->getPrimaryCurrencyId();

        $primary_price = $this->getPriceObject($primary_currency_id, $type, false);

        // Primary price could not be found, return blank price object
        if ( !$primary_price ) {
            return new Money([
                'type' => $type,
                'currency_id' => $currency_id,
                ]);
        }

        $currency_needed = Currency::find($currency_id);
 
        return new Money([
                'type' => $type,
                'currency_id' => $currency_id,
                'amount' => $primary_price->amount / $currency_needed->unit,
                'manual' => 0,
                'primary' => 0
                ]);
    }

    /**
     * [getPrice description]
     * @example $product->getPrice('plain', 1, true, true)
     * @example $product->getPrice('vat', 1, true, true)
     * @param  string  $type            [description]
     * @param  integer $quantity        [description]
     * @param  integer $currency_id     [description]
     * @param  boolean $formatted       [description]
     * @param  boolean $short_formatted [description]
     * @param  boolean $force           [description]
     * @return [type]                   [description]
     */
    public function getPrice($type = 'plain', $quantity = 1, $currency_id = null, $formatted = true, $short_formatted = true, $force = true)
    {
        $cache_price_key = implode('.', [
            get_class($this),
            $this->id,
            $type,
            $quantity,
            $currency_id,
            (int)$formatted,
            (int)$short_formatted,
            (int)$force
        ]);

        if ( Cache::has($cache_price_key) ) {
            return Cache::get($cache_price_key);
        }

        // Get active currency id
        if ( !$currency_id )
            $currency_id = self::getActiveCurrencyId();
        
    	if ( $formatted && !$short_formatted )		// Return formatted value
    		return $this->format($this->getPrice($type, $quantity, $currency_id, !$formatted), $currency_id);
    	else if ( $formatted && $short_formatted )	// Return short formatted value
    		return $this->short_format($this->getPrice($type, $quantity, $currency_id, !$formatted), $currency_id);

    	// Find price data
    	$price = $this->price()
    	               ->where('shop_money.type', $type)
    	               ->where('shop_money.currency_id', $currency_id);

    	// If no price data available, return 0
    	if ( !$price->count() && $force ) {
    		return $this->getPriceObject($currency_id, $type)->amount * $quantity;
        } else if ( !$price->count() ) {
            return 0;
        }

    	// Total amount
        $total = $price->orderBy('created_at', 'DESC')->first()->amount * $quantity;

        Cache::put($cache_price_key, $total, 60);

    	return $total;
    }

    public function setPrice($type = 'plain', $value, $currency_id = null, $primary = 0, $manual = 1)
    {
        // Get active currency id
        if ( !$currency_id )
            $currency_id = self::getActiveCurrencyId();
 
    	$money = new Money([
    		'amount'      => $value,
    		'type'        => $type,
    		'currency_id' => $currency_id,
            'manual'      => $manual,
            'primary'     => $primary
    		]);

    	$this->price()->save($money);
    }

    public function setPricesAttribute($value)
    {
        if ( isset($value['primary']) ) {
            $primary = $value['primary'];
            unset($value['primary']);
        }

        foreach($value as $type => $prices) 
        {
            foreach( $prices as $currency => $price ) {

                $currency_id = Currency::where('code', $currency)->first()->id;
                
                $is_primary = $primary == $currency ? true : false;

                $this->setPrice($type, $price['value'], $currency_id, $is_primary, 1);

            }
        }
    }

    public function format($value, $currency_id)
    {
    	$currency = Currency::find($currency_id);

    	if ( !$currency )
    		return $value;

    	return $this->makeFormat('currency.' . $currency->code, $value, $currency->format);
    }

    public function short_format($value, $currency_id)
    {
    	$currency = Currency::find($currency_id);

    	if ( !$currency )
    		return $value;

    	return $this->makeFormat('currency.' . $currency->code, $value, $currency->short_format);
    }

    public function makeFormat($to, $value, $format) 
    {
    	return Converter::to($to)->value($value)->format($format);
    }

    public function calculate($value, $from, $to, $ratio = 1, $currency_id = 1) 
    {
    	return $this->setPrice($to, $value * $ratio, $currency_id);
    }

    /**
     * Generic format function, that can be called statically
     * @param  integer $value       [description]
     * @param  integer $currency_id [description]
     * @return [type]               [description]
     */
    public static function formatGeneric($value = 0, $currency_id = 1, $short_format = true)
    {
        $class = get_called_class();
        $product = new $class;

        if ( !$short_format )
            return $product->format($value, $currency_id);

        return $product->short_format($value, $currency_id);
    }

    public static function getActiveCurrencyId()
    {
        if ( $active_currency_id = request()->session()->get('active_currency_id') ) {
            return $active_currency_id;
        }

        return config('sanatorium-pricing.active_currency_id');
    }

    public function getPrimaryCurrencyId()
    {
        $primary = $this->price()
                       ->where('shop_money.primary', 1);

        if ( $primary->count() ) {
            return $primary->orderBy('created_at', 'DESC')->first()->currency_id;
        }

        return 1;
    }
}

