<?php

namespace integracao\Logistica\IntegracaoIntelipost;

final class IntelipostEnvioPedidoException extends \Exception {
    
    public $obj;
    
    public function __construct($message, $obj) {
        $this->obj = $obj;
        parent::__construct($message);
    }
    
}
