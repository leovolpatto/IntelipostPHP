<?php
/**
 * @author Intelipost (it@intelipost.com.br)
 *
 */

// $apiUrl = "https://api.intelipost.com.br/api/v1/";
// $apiKey = "d0a3b5f355e17333de5c9a15d665f3d7724b4246540012d5eb851a82db1b95a5";
// require_once ('vendor/autoload.php');

require_once ('spoon.php');

if (!isset($apiUrl)) {
    $apiUrl = Intelipost_Settings::API_URL;
}

if (!isset($apiKey)) {
    $apiKey = Intelipost_Settings::API_KEY;
}

$intelipost = new Intelipost($apiUrl, $apiKey, true);

$volume1 = new Intelipost_Model_Volume();
$volume1->weight = 6.4;
$volume1->volume_type = "BOX";
$volume1->cost_of_goods = "800";
$volume1->width = 22;
$volume1->height = 35;
$volume1->length = 21;

$volume2 = new Intelipost_Model_Volume();
$volume2->weight = 3.2;
$volume2->volume_type = "BOX";
$volume2->cost_of_goods = "400";
$volume2->width = 22;
$volume2->height = 35;
$volume2->length = 11;

$request = new Intelipost_Model_Request();
$request->origin_zip_code = '04030-002';
$request->destination_zip_code = '04037-002';
$request->volumes = array($volume1, $volume2);

// Quote
$response = new Intelipost_Model_Response($intelipost->quote($request));
print_r($response);
