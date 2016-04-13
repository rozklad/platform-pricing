<?php namespace Sanatorium\Pricing\Repositories\Exchange;


class ExchangeRepository implements ExchangeRepositoryInterface {

	/**
     * Array of registered namespaces.
     *
     * @var array
     */
    protected $services;

    /**
     * {@inheritDoc}
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * {@inheritDoc}
     */
    public function registerService($service)
    {
        $this->services[] = $service;
    }

}
