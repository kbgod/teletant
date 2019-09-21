<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class SuccessfulPayment extends Entity
{
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

    public function orderInfo(): ?OrderInfo
    {
        return new OrderInfo(parent::_data('order_info'));
    }

    public function telegramPaymentChargeId(): ?string
    {
        return parent::_data('telegram_payment_charge_id');
    }

    public function providerPaymentChargeId(): ?string
    {
        return parent::_data('provider_payment_charge_id');
    }
}