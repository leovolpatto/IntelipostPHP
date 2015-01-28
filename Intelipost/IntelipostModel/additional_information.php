<?php

namespace Intelipost\IntelipostModel;

/**
 * @author Leonardo Volpatto <leovolpatto@gmail.com>
 */
final class additional_information {

    /**
     * @var boolean
     */    
    public $free_shipping = false;
    /**
     * @var float
     */    
    public $extra_cost_absolute;
    /**
     * @var int
     */    
    public $lead_time_business_days;
    /**
     * @var int
     */    
    public $delivery_method_id;

}
