<?php

class OptionHandler
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function updateOptions()
    {
        $deliveryMethod = $this->data['deliveryMethod'];
        delete_option('sw_deliveryMethod');
        add_option('sw_deliveryMethod', $deliveryMethod);

        $categories = $this->data['categories'];
        delete_option('sw_categories');
        add_option('sw_categories', $categories);

        $maxPriceRadio = $this->data['maxPriceRadio'];
        delete_option('sw_maxPriceRadio');
        add_option('sw_maxPriceRadio', $maxPriceRadio);

        if ($maxPriceRadio == 'range') {
            $minPrice = $this->data['minPrice'];
            delete_option('sw_minPrice');
            add_option('sw_minPrice', $minPrice);

            $maxPrice = $this->data['maxPrice'];
            delete_option('sw_maxPrice');
            add_option('sw_maxPrice', $maxPrice);
        } else {
            delete_option('sw_minPrice');
            delete_option('sw_maxPrice');
        }

    }
}

function delete_sw_options()
{
    delete_option('sw_deliveryMethod');
    delete_option('sw_categories');
    delete_option('sw_maxPriceRadio');
    delete_option('sw_minPrice');
    delete_option('sw_maxPrice');
}
