<?php

namespace integracao\Logistica\IntegracaoIntelipost\Response;

class IntelipostResponse extends \IntegracaoLogisticaResult {
    
    public $isSuccess;
    public $msg;
    public $result;
    
    public function __construct($success, $msg, $result = null) {
        $this->isSuccess = $success;
        $this->msg = $msg;
        $this->result = $result;
    }
    
    public function GetResponse()
    {
        return $this->result;
    }
    
}
