<?php

namespace Intelipost\Proxy;

interface ICEP {
    
    /**
     * @param string $cep
     * @return \Intelipost\Response\IntelipostCepAutoCompleteResponse
     */
    public function AutoComplete($cep);
    
}
