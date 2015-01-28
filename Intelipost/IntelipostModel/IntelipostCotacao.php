<?php

namespace integracao\Logistica\IntegracaoIntelipost\IntelipostModel;

final class IntelipostCotacao {
    
    /**
     * @var int
     */
    public $id;
    /**
     * @var int
     */
    public $client_id;
    /**
     * @var int
     */
    public $origin_zip_code;
    /**
     * @var int
     */    
    public $destination_zip_code;
    /**
     * @var int
     */    
    public $created;
    /**
     * @var int
     */    
    public $created_iso;
    /**
     * @var int
     */    
    public $additional_information;
    
    /**
     * @var DeliveryOption[]
     * @arrayOf integracao\Logistica\IntegracaoIntelipost\IntelipostModel\DeliveryOption
     */
    public $delivery_options = array();
    
}


