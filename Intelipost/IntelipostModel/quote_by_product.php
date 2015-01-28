<?php

namespace Intelipost\IntelipostModel;

final class quote_by_product {

     /**
     * @var string
     */
    public $origin_zip_code;
     /**
     * @var string
     */
    public $destination_zip_code;

    /**
     * @var product[]
     * @arrayOf Intelipost\IntelipostModel\product
     */
    public $products;

    /**
     * @var additional_information
     * @objectType Intelipost\IntelipostModel\additional_information
     */
    public $additional_information;
    
    public function __construct() {
        $this->products = array();
        $this->additional_information = new additional_information();
    }
    
    public function AddProduct(product $product)
    {
        array_push($this->products, $product);
    }

}
