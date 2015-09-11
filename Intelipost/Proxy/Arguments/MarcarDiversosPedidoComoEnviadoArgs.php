<?php

namespace Intelipost\Proxy\Arguments;

/**
 * @author Leonardo Volpatto <leovolpatto@gmail.com>
 */
final class MarcarDiversosPedidoComoEnviadoArgs {
    
    /**
     * @var MarcarDiversosPedidoComoEnviadoArg[]
     */
    private $orders;
    
    public function __construct() {
        $this->orders = array();
    }
    
    public function Add($order_number, $eventDate)
    {
        array_push($this->orders, new MarcarDiversosPedidoComoEnviadoArg($order_number, $eventDate));
    }
    
    /**
     * @return MarcarDiversosPedidoComoEnviadoArg[]
     */
    public function GetOrders()
    {
        return $this->orders;
    }
    
}

final class MarcarDiversosPedidoComoEnviadoArg {
    
    public $order_number;
    public $event_date;
    
    public function __construct($order_number, $eventDate) {
        $this->order_number = $order_number;
        $this->event_date = $eventDate;
    }
    
}
