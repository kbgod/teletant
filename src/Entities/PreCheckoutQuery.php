<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class PreCheckoutQuery extends Entity
{
    public function id(): ?string
    {
        return parent::_data('id');
    }

    public function from(): User
    {
        return new User(parent::_data('from'));
    }

    public function currency(): ?string
    {
        return parent::_data('currency');
    }

    public function totalAmount(): ?int
    {
        return parent::_data('total_amount');
    }

    public function invoicePayload(): ?string
    {
        return parent::_data('invoice_payload');
    }

    public function shippingOptionId(): ?string
    {
        return parent::_data('shipping_option_id');
    }

    public function orderInfo(): OrderInfo
    {
        return new OrderInfo(parent::_data('order_info'));
    }
}