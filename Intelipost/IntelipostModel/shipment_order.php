<?php

namespace Intelipost\IntelipostModel;

final class shipment_order {

    public $quote_id;
    public $delivery_method_id;
    /**
     * @var end_customer
     */
    public $end_customer;
    /**
     *
     * @var shipment_order_volume_array
     */
    public $shipment_order_volume_array;
    public $order_number;
    public $estimated_delivery_date;

}
