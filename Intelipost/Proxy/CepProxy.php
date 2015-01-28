<?php

namespace Intelipost\Proxy;

/**
 * @author Leonardo Volpatto <leovolpatto@gmail.com>
 */
final class CepProxy extends ProxyBase implements ICEP
{
    /**
     * @param string $cep
     * @return \Intelipost\Response\IntelipostCepAutoCompleteResponse
     */
    public function AutoComplete($cep) {
        $this->InitializeDefaultCurl();
        
        $this->_curl->SetCustomRequest("GET");
        $this->_curl->CreateCurl($this->_baseURL . "/cep_location/address_complete/$cep");
        
        return new \Intelipost\Response\IntelipostCepAutoCompleteResponse($this->_curl->GetResult());                
    }

}
