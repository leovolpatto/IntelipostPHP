<?php

namespace integracao\Logistica\IntegracaoIntelipost\IntelipostModel;

final class shipment_order_volume_invoice {
    /*
      "invoice_series": "2",
      "invoice_number": "10001",
      "invoice_key": "41140502834982004563550010000084111000132317",
      "invoice_date": "2014-03-15",
      "invoice_total_value": "45.99",
      "invoice_products_value": "39.99",
      "invoice_cfop": "2890" */

    public $invoice_series;
    public $invoice_number;
    public $invoice_key;
    public $invoice_date;
    public $invoice_total_value;
    public $invoice_products_value;
    public $invoice_cfop;

}
