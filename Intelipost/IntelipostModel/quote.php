<?php

namespace Intelipost\IntelipostModel;

/**
 * @author Leonardo Volpatto <leovolpatto@gmail.com>
 */
final class quote {
    
    /**
     * @var int
     */      
    public $id;
    /**
     * @var int
     */
    public $client_id;
    /**
     * @var string
     */      
    public $origin_zip_code;
    /**
     * @var string
     */      
    public $destination_zip_code;
    /**
     * @var string
     */      
    public $created;
    /**
     * @var string
     */
    public $created_iso;
    /**
     * @var additional_information
     * @objectType Intelipost\IntelipostModel\additional_information
     */
    public $additional_information;
    /**
     * @var DeliveryOption[]
     * @arrayOf Intelipost\IntelipostModel\DeliveryOption
     */
    public $delivery_options;
    /**
     * @var volume[]
     */
    public $volumes;
}
