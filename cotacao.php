<?php

require_once 'intelipost.inc.php';


$req = new \Intelipost\IntelipostModel\quote_by_product();
$req->destination_zip_code = '95700-000';
$req->origin_zip_code = '95720-000';
$req->additional_information = new Intelipost\IntelipostModel\additional_information();

$produto = new \Intelipost\IntelipostModel\product();
$produto->cost_of_goods = 500;
$produto->description = "TV LCD";
$produto->height = 12;
$produto->length = 10;
$produto->quantity = 1;
$produto->sku_id = "1234xpto";
$produto->weight = 10;
$produto->width = 20;
$req->AddProduct($produto);

$proxy = new Intelipost\IntelipostProxy();
$resEnvio = $proxy->CotarSemVolumes($req);
if ($resEnvio->isSuccess) {
    echo '<p>Pedido Enviado</p>';
    var_dump($resEnvio->GetResult());
} else {
    echo '<p>Falha</p>';
    echo "<p>$resEnvio->message</p>";
}


/*
{
  "origin_zip_code": "01311-000",
  "destination_zip_code": "06396-200",
  "products": [
    {
      "weight": 10,
      "cost_of_goods": 200,
      "width": 30,
      "height": 30,
      "length": 30,
      "quantity": 1,
      "sku_id": "1234xpto",
      "description": "TV LCD",
      "can_group": false
    },
    {
      "weight": 10,
      "cost_of_goods": 200,
      "width": 30,
      "height": 30,
      "length": 30,
      "quantity": 1,
      "sku_id": "12345xpto",
      "description": "Conjunto de facas"
    }
  ],
  "additional_information": {
    "free_shipping": false,
    "extra_cost_absolute": 2.5,
    "lead_time_business_days": 2,
    "delivery_method_id": [
      22,
      3,
      4
    ]
  }
}

 */