<?php

namespace Intelipost;

final class IntelipostProxy {

    /**
     * @var Utils\CurlWrapper
     */
    private $_curl;
    private $_baseURL;
    
    private function InitializeDefaultCurl()
    {
        $this->_curl = new Utils\CurlWrapper();
        $this->_curl->SetHttpHeaders("Accept: application/json");
        $this->_curl->SetHttpHeaders("Content-Type: application/json");
        $this->_curl->SetHttpHeaders("api_key: " . IntelipostConfigurations::Instance()->config->apiKey);        
        $this->_curl->SetReturnTransfer(true);
        $this->_curl->SetIncludeHeader(false);
        $this->_baseURL = IntelipostConfigurations::Instance()->config->url;        
    }
    
    /**
     * @return Response\IntelipostMetodosDeEnvioResponse
     */
    public function ConsultarMetodosDeEnvio()
    {
        $this->InitializeDefaultCurl();        
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
        $this->InitializeDefaultCurl();
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
        $this->InitializeDefaultCurl();
        $this->_curl->SetCustomRequest("POST");
        
        $json = json_encode($req);
                
        if(json_last_error() > 0)
            throw new IntelipostCotacaoException("json encode error", $req);
        
        $this->_curl->SetPost($json);
        $this->_curl->CreateCurl($this->_baseURL . "/quote_by_product");
        $res = $this->_curl->GetResult();
        
        return new Response\IntelipostCotacaoSemVolumeResponse($res);
    }
    
    /**
     * @param int $numeroDoPedido
     * @return \Intelipost\Response\IntelipostPedidoMarcadoComoProntoResponse
     */
    public function MarcarPedidoComoProntoParaEnvio($numeroDoPedido)
    {
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
    public function MarcarPedidoComoEnviado($numeroDoPedido)
    {
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
    public function CancelarPedidoDeEnvio($numeroDoPedido)
    {
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
    public function ConsultarPedidoDeEnvio($numeroDoPedido)
    {
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
    public function CriarPedidoDeEnvio(IntelipostModel\shipment_order $shipment_order)
    {   
        $this->_curl->SetIncludeHeader(false);
        $this->_curl->SetCustomRequest("POST");
        
        $json = json_encode($shipment_order);
        
        if(json_last_error() > 0)
            throw new IntelipostEnvioPedidoException("json encode error", $shipment_order);
        
        $this->_curl->SetPost($json);
        $this->_curl->CreateCurl($this->_baseURL . "/shipment_order");
        $res = $this->_curl->GetResult();
        return new Response\IntelipostEnvioPedidoResponse($res);
    }
    
}