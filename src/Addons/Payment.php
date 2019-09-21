<?php


namespace Askoldex\Teletant\Addons;

class Payment
{
    public static function Prices()
    {
        return new LabeledPriceType();
    }

    public static function ShippingOption()
    {
        return new ShippingOptionType();
    }
}


class LabeledPriceType
{
    public $data = [];

    public function add($label, $amount)
    {
        $this->data[] = ['label' => $label, 'amount' => $amount];
        return $this;
    }

    public function build()
    {
        return json_encode($this->data);
    }
}

class ShippingOptionType
{
    public $data = [];

    public function add($id, $title, $prices)
    {
        $query = ['id' => $id, 'title' => $title];
        if (is_object($prices)) {
            $query['prices'] = $prices->data;
        } else $query['prices'] = $prices;

        $this->data[] = $query;
        return $this;
    }

    public function build()
    {
        return json_encode($this->data);
    }
}
