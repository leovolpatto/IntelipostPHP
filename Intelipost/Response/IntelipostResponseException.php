<?php

namespace integracao\Logistica\IntegracaoIntelipost\Response;

final class IntelipostResponseException extends \Exception {
    
    public $data;
    
    public function __construct($message, $data = null) {
        $this->data = $data;
        parent::__construct($message);
    }
    
}
