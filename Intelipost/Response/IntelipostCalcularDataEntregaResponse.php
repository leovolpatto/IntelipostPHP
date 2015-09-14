<?php

namespace Intelipost\Response;

use Intelipost\Response\IntelipostResponseBase;
use Intelipost\IntelipostModel\business_days;

final class IntelipostCalcularDataEntregaResponse extends IntelipostResponseBase {
        
    /**
     * @var \Intelipost\IntelipostModel\business_days;
     */
    private $business_days;
    
    private function CreateTypedResponse()
    {
        $p = new \Intelipost\Utils\JSONParser();
        $this->business_days = $p->parseFromStdClass(new business_days(), $this->resultObj);
    }
    
    protected function ProcessResponse() {
        parent::ProcessResponse();
        $this->CreateTypedResponse();
    }
    
    /**
     * @return \Intelipost\IntelipostModel\business_days;
     */
    public function GetResult() {
        
        return $this->business_days;

    }
    
}
