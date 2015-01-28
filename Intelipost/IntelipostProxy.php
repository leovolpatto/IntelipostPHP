<?php

namespace integracao\Logistica\IntegracaoIntelipost;

final class IntelipostProxy {

    /**
     * @var \integracoesProxies\util\CurlWrapper
     */
    private $_curl;
    private $_baseURL;
    
    public function Cotar()
    {
        
    }
    
    /**
     * @return \integracao\Logistica\IntegracaoIntelipost\Response\IntelipostMetodosDeEnvioResponse
     */
    public function ConsultarMetodosDeEnvio()
    {
        $this->_curl = new \integracoesProxies\util\CurlWrapper('GET');
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
     * @return \integracao\Logistica\IntegracaoIntelipost\Response\IntelipostCotacaoSemVolumeResponse
     */
    public function ConsultarCotacao($idCotacaoIntelipost)
    {
        $this->_curl = new \integracoesProxies\util\CurlWrapper('GET');
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
     * @param \integracao\Logistica\IntegracaoIntelipost\IntelipostModel\quote_by_product $req
     * @return \integracao\Logistica\IntegracaoIntelipost\Response\IntelipostCotacaoSemVolumeResponse
     * @throws IntelipostCotacaoException
     */
    public function CotarSemVolumes(IntelipostModel\quote_by_product $req)
    {
        $req->destination_zip_code = str_replace('.', '', $req->destination_zip_code);
        $req->origin_zip_code = str_replace('.', '', $req->origin_zip_code);
                
        $this->_curl = new \integracoesProxies\util\CurlWrapper('');
        $this->_curl->SetHttpHeaders("Accept: application/json");
        $this->_curl->SetHttpHeaders("Content-Type: application/json");
        //$this->_curl->SetHttpHeaders("api_key: " . IntelipostConfigurations::Instance()->config->apiKey);
        $this->_curl->SetHttpHeaders("api_key: 8b86b0686b56682a8433f7d0fff6871d18fc7005d9c214332ceafa02b25ccec4");// <--- Remover após testes
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
        
        /*
                API Key que funciona com essa funcionalidade: 8b86b0686b56682a8433f7d0fff6871d18fc7005d9c214332ceafa02b25ccec4 

                URL: https://api.intelipost.com.br/api/v1/quote_by_product 

                JSON:

                {
                    "origin_zip_code": "01311-000",
                    "destination_zip_code": "06396-200",
                    "products": [
                        {
                            "weight": 10,
                            "cost_of_goods": 200.0,
                            "width": 3,
                            "height": 3.0,
                            "length": 3.0,
                            "quantity": 3,
                            "sku_id": "1234xpto",
                            "description": "produto pesado"
                        }

                    ],
                    "additional_information": {
                        "free_shipping": false,
                        "extra_cost_absolute": 2.5,
                        "lead_time_business_days": 2,
                       "delivery_method_id": 22
                    }
                }
         */
    }
    
    private function CarregarDadosParaEnvio(\models\PedidoDeVenda\PedidoModel $pedido)
    {
        \models\EntityProxy\ModelEntitiesProxy::CreateProxy(\Auth::$idEmpresa);
        $nota = \models\EntityProxy\ModelEntitiesProxy::GetProxy()->LoadByID($pedido->idNotaFiscalRef, new \models\NotaFiscal\NotaFiscalModel());
        
        $pedido->_NotaFiscal = $nota;
    }
    
    private function ValidarDadosParaEnvio(\models\PedidoDeVenda\PedidoModel $pedido)
    {
        //caso alguma informação estiver faltando, disparar exception com os detalhes dentro.
    }
    
    /**
     * @param type $numeroDoPedido
     * @return \integracao\Logistica\IntegracaoIntelipost\Response\IntelipostPedidoMarcadoComoProntoResponse
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
     * @return \integracao\Logistica\IntegracaoIntelipost\Response\IntelipostPedidoMarcadoComoEnviadoResponse
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
     * @return \integracao\Logistica\IntegracaoIntelipost\Response\IntelipostCancelamentoPedidoResponse
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
     * @return \integracao\Logistica\IntegracaoIntelipost\Response\IntelipostConsultaPedidoResponse
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
     * @param \models\PedidoDeVenda\PedidoModel $pedido
     * @return \integracao\Logistica\IntegracaoIntelipost\Response\IntelipostEnvioPedidoResponse
     * @throws IntelipostEnvioPedidoException
     */
    public function EnviarPedido(\models\PedidoDeVenda\PedidoModel $pedido)
    {
        $this->CarregarDadosParaEnvio($pedido);
        $this->ValidarDadosParaEnvio($pedido);
        $adapter = new Adapters\EccosysToIntelipost\ShipmentOrderAdapter();
        $shipment_order = $adapter->Adapt($pedido);        
        
        $this->_curl = new \integracoesProxies\util\CurlWrapper('');
        $this->_curl->SetHttpHeaders("Accept: application/json");
        $this->_curl->SetHttpHeaders("Content-Type: application/json");
        $this->_curl->SetHttpHeaders("api_key: " . IntelipostConfigurations::Instance()->config->apiKey);        
        $this->_baseURL = IntelipostConfigurations::Instance()->config->url;
        
        $this->_curl->SetIncludeHeader(false);
        $this->_curl->SetCustomRequest("POST");
        
        //$shipment_order->quote_id = 1770195;
        
        $json = json_encode($shipment_order);
        
        if(json_last_error() > 0)
            throw new IntelipostEnvioPedidoException("Problema ao converter para json", $shipment_order);
        
        $this->_curl->SetPost($json);
        $this->_curl->CreateCurl($this->_baseURL . "/shipment_order");
        $res = $this->_curl->GetResult();
        return new Response\IntelipostEnvioPedidoResponse($res);        
    }
    
}
