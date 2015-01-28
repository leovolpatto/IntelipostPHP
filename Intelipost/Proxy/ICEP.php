<?php

namespace Intelipost\Proxy;

/**
 * @author Leonardo Volpatto <leovolpatto@gmail.com>
 */
interface ICEP {
    
    /**
     * @param string $cep
     * @return \Intelipost\Response\IntelipostCepAutoCompleteResponse
     */
    public function AutoComplete($cep);
    
}
