<?php

namespace integracao\Logistica\IntegracaoIntelipost\Response;

final class IntelipostConsultaFreteResponse extends IntelipostResponse {
    
    /**
     * @var \integracao\Logistica\IntegracaoIntelipost\IntelipostModel\IntelipostCotacao
     */
    public $cotacao;
    
    /**
     * @return \integracao\Logistica\IntegracaoIntelipost\IntelipostModel\IntelipostCotacao
     */
    public function GetResponse() {
        
        $cotacao = new \integracao\Logistica\IntegracaoIntelipost\IntelipostModel\IntelipostCotacao();
        
        $var = new \integracoesProxies\util\JSONParser();
        $var->parseFromStdClass($this->result, $cotacao);
        
        
        return $cotacao;
        
        
    }
    
}
