<?php

namespace Intelipost\Proxy;

use Intelipost\Response;
use Intelipost\IntelipostModel;

/**
 * @author Leonardo Volpatto <leovolpatto@gmail.com>
 */
final class CotacaoProxy extends ProxyBase implements ICotacao {
    
    /**
     * @return \Intelipost\Response\IntelipostMetodosDeEnvioResponse
     */
    public function ConsultarMetodosDeEnvio() {
        $this->InitializeDefaultCurl();        
        
        $this->_curl->SetCustomRequest("GET");        
        $this->_curl->CreateCurl($this->_baseURL . "/info");
        
        return new Response\IntelipostMetodosDeEnvioResponse($this->_curl->GetResult());        
    }

    /**
     * @param int $idCotacaoIntelipost
     * @return \Intelipost\Response\IntelipostCotacaoSemVolumeResponse
     */
    public function ConsultarUmaCotacao($idCotacaoIntelipost) {
        $this->InitializeDefaultCurl();
        
        $this->_curl->SetCustomRequest("GET");        
        $this->_curl->CreateCurl($this->_baseURL . "/quote/$idCotacaoIntelipost");
        
        return new Response\IntelipostCotacaoSemVolumeResponse($this->_curl->GetResult());          
    }

    /**
     * @param \Intelipost\IntelipostModel\quote_by_product $req
     * @return \Intelipost\Response\IntelipostCotacaoSemVolumeResponse
     * @throws \Intelipost\IntelipostCotacaoException
     */
    public function CriarCotacaoPorProduto(IntelipostModel\quote_by_product $req) {        
        $json = json_encode($req);
                
        if(json_last_error() > 0)
            throw new \Intelipost\IntelipostCotacaoException("json encode error", $req);

        $this->InitializeDefaultCurl();
        $this->_curl->SetCustomRequest("POST");        
        $this->_curl->SetPost($json);
        $this->_curl->CreateCurl($this->_baseURL . "/quote_by_product");
        $res = $this->_curl->GetResult();
        
        return new Response\IntelipostCotacaoSemVolumeResponse($res);        
    }

    public function CriarCotacaoPorVolume() {
        
        throw new \Exception("Not implemented yet");
    
    }

}
