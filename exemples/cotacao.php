<?php

require_once '../intelipost.inc.php';


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
    var_dump($resEnvio->GetResult());
} else {
    echo '<p>Falha</p>';
    echo "<p>$resEnvio->message</p>";
}