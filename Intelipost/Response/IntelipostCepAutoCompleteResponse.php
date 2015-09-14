<?php

namespace Intelipost\Response;

/**
 * @author Leonardo Volpatto <leovolpatto@gmail.com>
 */
final class IntelipostCepAutoCompleteResponse extends IntelipostResponseBase {
    
    /**
     * @var \Intelipost\IntelipostModel\address_complete
     */
    private $address;
    
    private function CreateTypedResponse()
    {
        $p = new \Intelipost\Utils\JSONParser();
        $this->address = $p->parseFromStdClass(new \Intelipost\IntelipostModel\address_complete(), $this->resultObj);
    }
    
    protected function ProcessResponse() {
        parent::ProcessResponse();
        $this->CreateTypedResponse();
    }
    
    /**
     * @return \Intelipost\IntelipostModel\address_complete
     */
    public function GetResult() {
        return $this->address;
    }
    
}
