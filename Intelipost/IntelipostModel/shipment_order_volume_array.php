<?php

namespace integracao\Logistica\IntegracaoIntelipost\IntelipostModel;

final class shipment_order_volume_array {
    /*
      "shipment_order_volume_number": 1,
      "weight": 100,
      "volume_type_code": "box",
      "width": 10,
      "height": 20,
      "length": 30,
      "products_nature": "beverages",
      "products_quantity": 3,
      "is_icms_exempt": false,
      "tracking_code": "SW123456789BR",
      "shipment_order_volume_invoice": {}
     */

    public $shipment_order_volume_number;
    public $weight;
    public $volume_type_code;
    public $width;
    public $height;
    public $length;
    public $products_nature;
    public $products_quantity;
    public $is_icms_exempt;
    public $tracking_code;
    /**
     *
     * @var shipment_order_volume_invoice
     */
    public $shipment_order_volume_invoice;

}
