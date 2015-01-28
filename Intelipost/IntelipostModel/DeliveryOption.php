<?php

namespace integracao\Logistica\IntegracaoIntelipost\IntelipostModel;

final class DeliveryOption {

     /**
     * @var int
     */        
    public $delivery_method_id;
    /**
     * @var int
     */            
    public $delivery_estimate_business_days;
    /**
     * @var float
     */            
    public $provider_shipping_cost;
    /**
     * @var float
     */            
    public $final_shipping_cost;
    /**
     * @var string
     */            
    public $description;
    /**
     * @var string
     */            
    public $delivery_note;
    /**
     * @var string
     */            
    public $delivery_method_type;
    /**
     * @var string
     */            
    public $delivery_method_name;
    /**
     * @var string
     */            
    public $logistic_provider_name;
    /**
     * @var string
     */        
    public $classIconCheck = 'icon-none';

}
