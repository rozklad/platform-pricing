<?php namespace Sanatorium\Pricing\Widgets;

use Sanatorium\Pricing\Models\Currency; 

class Entity
{

    /**
     * Show the entity form.
     *
     * @return \Illuminate\View\View|null
     */
    public function show($entity)
    {
        return $this->renderForm(
            $entity
        );
    }

    /**
     * Renders the entity form for the given attributes.
     *
     * @return \Illuminate\View\View|null
     */
    protected function renderForm($entity, $view = null)
    {
        $view = $view ?: 'sanatorium/pricing::widgets/form';

        // @todo make dynamic
        $types = [
            'vat' => 'S daní',
            'plain' => 'Bez daně',
        ];

        $currencies = Currency::all();

        $primary_currency = Currency::where('unit', 1)->first();

        return view($view, compact('entity', 'types', 'currencies', 'primary_currency'));
    }
}
