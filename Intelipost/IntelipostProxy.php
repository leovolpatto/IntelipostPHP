<?php

namespace Intelipost;

final class IntelipostProxy {

    /**
     * @var Utils\CurlWrapper
     */
    private $_curl;
    private $_baseURL;
    
    /**
     * @return Response\IntelipostMetodosDeEnvioResponse
     */
    public function ConsultarMetodosDeEnvio()
    {
        $this->_curl = new Utils\CurlWrapper('GET');
        $this->_curl->SetHttpHeaders("Accept: application/json");
        $this->_curl->SetHttpHeaders("Content-Type: application/json");
        $this->_curl->SetHttpHeaders("api_key: " . IntelipostConfigurations::Instance()->config->apiKey);        
        $this->_curl->SetReturnTransfer(true);
        $this->_baseURL = IntelipostConfigurations::Instance()->config->url;
        
        $this->_curl->SetIncludeHeader(false);
        $this->_curl->SetCustomRequest("GET");
        
        $this->_curl->CreateCurl($this->_baseURL . "/info");
        
        return new Response\IntelipostMetodosDeEnvioResponse($this->_curl->GetResult());        
    }
    
    /**
     * @param int $idCotacaoIntelipost
     * @return Response\IntelipostCotacaoSemVolumeResponse
     */
    public function ConsultarCotacao($idCotacaoIntelipost)
    {
        $this->_curl = new Utils\CurlWrapper('GET');
        $this->_curl->SetHttpHeaders("Accept: application/json");
        $this->_curl->SetHttpHeaders("Content-Type: application/json");
        $this->_curl->SetHttpHeaders("api_key: " . IntelipostConfigurations::Instance()->config->apiKey);        
        $this->_curl->SetReturnTransfer(true);
        $this->_baseURL = IntelipostConfigurations::Instance()->config->url;
        
        $this->_curl->SetIncludeHeader(false);
        $this->_curl->SetCustomRequest("GET");
        
        $this->_curl->CreateCurl($this->_baseURL . "/quote/$idCotacaoIntelipost");
        
        return new Response\IntelipostCotacaoSemVolumeResponse($this->_curl->GetResult());         
    }
    
    /**
     * @param IntelipostModel\quote_by_product $req
     * @return Response\IntelipostCotacaoSemVolumeResponse
     * @throws IntelipostCotacaoException
     */
    public function CotarSemVolumes(IntelipostModel\quote_by_product $req)
    {
        $req->destination_zip_code = str_replace('.', '', $req->destination_zip_code);
        $req->origin_zip_code = str_replace('.', '', $req->origin_zip_code);
                
        $this->_curl = new Utils\CurlWrapper('');
        $this->_curl->SetHttpHeaders("Accept: application/json");
        $this->_curl->SetHttpHeaders("Content-Type: application/json");
        $this->_curl->SetHttpHeaders("api_key: " . IntelipostConfigurations::Instance()->config->apiKey);
        $this->_baseURL = IntelipostConfigurations::Instance()->config->url;
        
        $this->_curl->SetIncludeHeader(false);
        $this->_curl->SetCustomRequest("POST");
        
        $json = json_encode($req);
                
        if(json_last_error() > 0)
            throw new IntelipostCotacaoException("Problema ao converter para json", $req);
        if(count($req->products) == 0)
            throw new IntelipostCotacaoException("Produtos não informados", $req);
        
        $this->_curl->SetPost($json);
        $this->_curl->CreateCurl($this->_baseURL . "/quote_by_product");
        $res = $this->_curl->GetResult();
        
        return new Response\IntelipostCotacaoSemVolumeResponse($res);
    }
    
    /**
     * @param int $numeroDoPedido
     * @return \Intelipost\Response\IntelipostPedidoMarcadoComoProntoResponse
     */
    public function MarcarPedidoParaProntoParaEnvio($numeroDoPedido)
    {
        $this->_curl = new \integracoesProxies\util\CurlWrapper('');
        $this->_curl->SetHttpHeaders("Accept: application/json");
        $this->_curl->SetHttpHeaders("Content-Type: application/json");
        $this->_curl->SetHttpHeaders("api_key: " . IntelipostConfigurations::Instance()->config->apiKey);
        $this->_baseURL = IntelipostConfigurations::Instance()->config->url;
        
        $this->_curl->SetIncludeHeader(false);
        $this->_curl->SetCustomRequest("POST");
        
        $rq = array();
        $rq['order_number'] = $numeroDoPedido;
        $this->_curl->SetPost(json_encode($rq));
        $this->_curl->CreateCurl($this->_baseURL . "/shipment_order/ready_for_shipment");
        
        $res = $this->_curl->GetResult();
        return new Response\IntelipostPedidoMarcadoComoProntoResponse($res);
    }
    
    /**
     * @param int $numeroDoPedido
     * @return \Intelipost\Response\IntelipostPedidoMarcadoComoEnviadoResponse
     */
    public function MarcarPedidoParaEnviado($numeroDoPedido)
    {
        $this->_curl = new \integracoesProxies\util\CurlWrapper('');
        $this->_curl->SetHttpHeaders("Accept: application/json");
        $this->_curl->SetHttpHeaders("Content-Type: application/json");
        $this->_curl->SetHttpHeaders("api_key: " . IntelipostConfigurations::Instance()->config->apiKey);
        $this->_baseURL = IntelipostConfigurations::Instance()->config->url;
        
        $this->_curl->SetIncludeHeader(false);
        $this->_curl->SetCustomRequest("POST");
        
        $rq = array();
        $rq['order_number'] = $numeroDoPedido;
        $this->_curl->SetPost(json_encode($rq));
        $this->_curl->CreateCurl($this->_baseURL . "/shipment_order/shipped");
        
        $res = $this->_curl->GetResult();
        return new Response\IntelipostPedidoMarcadoComoEnviadoResponse($res);
    }
    
    /**
     * @param int $numeroDoPedido
     * @return \Intelipost\Response\IntelipostCancelamentoPedidoResponse
     */
    public function CancelarPedidoEnviado($numeroDoPedido)
    {
        $this->_curl = new \integracoesProxies\util\CurlWrapper('');
        $this->_curl->SetHttpHeaders("Accept: application/json");
        $this->_curl->SetHttpHeaders("Content-Type: application/json");
        $this->_curl->SetHttpHeaders("api_key: " . IntelipostConfigurations::Instance()->config->apiKey);
        $this->_baseURL = IntelipostConfigurations::Instance()->config->url;
        
        $this->_curl->SetIncludeHeader(false);
        $this->_curl->SetCustomRequest("POST");
        
        $rq = array();
        $rq['order_number'] = $numeroDoPedido;
        $this->_curl->SetPost(json_encode($rq));
        $this->_curl->CreateCurl($this->_baseURL . "/shipment_order/cancel_shipment_order");
        
        $res = $this->_curl->GetResult();
        return new Response\IntelipostCancelamentoPedidoResponse($res);
    }    

    /**
     * @param int $numeroDoPedido
     * @return \Intelipost\Response\IntelipostConsultaPedidoResponse
     */
    public function ConsultarPedidoEnviado($numeroDoPedido)
    {
        $this->_curl = new \integracoesProxies\util\CurlWrapper('GET');
        $this->_curl->SetHttpHeaders("Accept: application/json");
        $this->_curl->SetHttpHeaders("Content-Type: application/json");
        $this->_curl->SetHttpHeaders("api_key: " . IntelipostConfigurations::Instance()->config->apiKey);        
        $this->_curl->SetReturnTransfer(true);
        $this->_baseURL = IntelipostConfigurations::Instance()->config->url;
        
        $this->_curl->SetIncludeHeader(false);
        $this->_curl->SetCustomRequest("GET");
        
        $this->_curl->CreateCurl($this->_baseURL . "/shipment_order/$numeroDoPedido");
        
        return new Response\IntelipostConsultaPedidoResponse($this->_curl->GetResult());
    }

    /**
     * @param \Intelipost\IntelipostModel\shipment_order $shipment_order
     * @return \Intelipost\Response\IntelipostEnvioPedidoResponse
     * @throws IntelipostEnvioPedidoException
     */
    public function EnviarPedido(IntelipostModel\shipment_order $shipment_order)
    {   
        $this->_curl = new Utils\CurlWrapper('');
        $this->_curl->SetHttpHeaders("Accept: application/json");
        $this->_curl->SetHttpHeaders("Content-Type: application/json");
        $this->_curl->SetHttpHeaders("api_key: " . IntelipostConfigurations::Instance()->config->apiKey);        
        $this->_baseURL = IntelipostConfigurations::Instance()->config->url;
        
        $this->_curl->SetIncludeHeader(false);
        $this->_curl->SetCustomRequest("POST");
        
        $json = json_encode($shipment_order);
        
        if(json_last_error() > 0)
            throw new IntelipostEnvioPedidoException("Problema ao converter para json", $shipment_order);
        
        $this->_curl->SetPost($json);
        $this->_curl->CreateCurl($this->_baseURL . "/shipment_order");
        $res = $this->_curl->GetResult();
        return new Response\IntelipostEnvioPedidoResponse($res);
    }
    
}
