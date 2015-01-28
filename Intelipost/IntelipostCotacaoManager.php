<?php

namespace integracao\Logistica\IntegracaoIntelipost;

final class IntelipostCotacaoManager {
    
    /**
     * @param int $id
     * @param string $tipoObj
     * @param \integracao\Logistica\IntegracaoIntelipost\IntelipostModel\quote $q
     * @param \integracao\Logistica\IntegracaoIntelipost\IntelipostModel\DeliveryOption $selectedDeliveryOption
     * @return \cron\Util\DataBaseResult
     */
    private function SalvarCotacao($id, $tipoObj, IntelipostModel\quote $q, IntelipostModel\DeliveryOption $selectedDeliveryOption)
    {
        $model = new \models\EntityModels\IntelipostCotacao\FreteCotacaoIntelipostModel();
        $model->id = \cron\Util\MySQL::Instance()->GetNextID('frete_cotacao_intelipost');
        $model->data = date("Y-m-d H:i:s");
        $model->cotacaoJson = json_encode($q);
        $model->idCotacaoIntelipost = $q->id;
        $model->idEmpresa = \Auth::$idEmpresa;
        $model->idObj = $id;
        $model->tipoObj = $tipoObj;        
        $model->logistic_provider_name = $selectedDeliveryOption->logistic_provider_name;
        $model->provider_shipping_cost = $selectedDeliveryOption->provider_shipping_cost;
        $model->delivery_estimate_business_days = $selectedDeliveryOption->delivery_estimate_business_days;
        $model->delivery_method_id = $selectedDeliveryOption->delivery_method_id;
        $model->delivery_method_name = $selectedDeliveryOption->delivery_method_name;
        $model->delivery_method_type = $selectedDeliveryOption->delivery_method_type;
        $model->delivery_note = $selectedDeliveryOption->delivery_note;
        $model->description = $selectedDeliveryOption->description;
        $model->final_shipping_cost = $selectedDeliveryOption->final_shipping_cost;
        
        \models\EntityProxy\ModelEntitiesProxy::CreateProxy(\Auth::$idEmpresa);
        return \models\EntityProxy\ModelEntitiesProxy::GetProxy()->InsertEntity($model);        
    }

    /**
     * @param int $idCotacao
     * @return Response\IntelipostCotacaoSemVolumeResponse
     */
    public function ConsultarUmaCotacaoDaIntelipost($idCotacao)
    {
        $p = new IntelipostProxy();
        $q = $p->ConsultarCotacao($idCotacao);
        return $q;
    }
    
    /**
     * @param \integracao\Logistica\IntegracaoIntelipost\IntelipostModel\quote_by_product $req
     * @return \integracao\Logistica\IntegracaoIntelipost\Response\IntelipostCotacaoSemVolumeResponse
     * @throws IntelipostCotacaoException
     */
    public function CotarSemVolumes(IntelipostModel\quote_by_product $req)
    {
        $proxy = new IntelipostProxy();
        return $proxy->CotarSemVolumes($req);
    }
    
    /**
     * @return \cron\Util\DataBaseResult
     */
    public function SalvarCotacaoDeOrcamento($idOrcamento, IntelipostModel\quote $q, IntelipostModel\DeliveryOption $selectedDeliveryOption)
    {
        return $this->SalvarCotacao($idOrcamento, \models\EntityModels\IntelipostCotacao\FreteCotacaoIntelipostModel::COTACAO_TIPO_ORCAMENTO, $q, $selectedDeliveryOption);
    }
    
    /**
     * @return \cron\Util\DataBaseResult
     */    
    public function SalvarCotacaoDePedido($idPedido, IntelipostModel\quote $q, IntelipostModel\DeliveryOption $selectedDeliveryOption)
    {
         return $this->SalvarCotacao($idPedido, \models\EntityModels\IntelipostCotacao\FreteCotacaoIntelipostModel::COTACAO_TIPO_PEDIDO, $q, $selectedDeliveryOption);
    }
    
    /**
     * @return \cron\Util\DataBaseResult
     */    
    public function SalvarCotacaoDeFaturamento($idNF, IntelipostModel\quote $q, IntelipostModel\DeliveryOption $selectedDeliveryOption)
    {
        return $this->SalvarCotacao($idNF, \models\EntityModels\IntelipostCotacao\FreteCotacaoIntelipostModel::COTACAO_TIPO_NF, $q, $selectedDeliveryOption);
    }
    
    /**
     * @return \models\EntityModels\IntelipostCotacao\FreteCotacaoIntelipostModel
     */
    private function CarregarCotacao($idObj, $tipoObj)
    {
        $idEmpresa = \Auth::$idEmpresa;        
        $q = "SELECT * FROM frete_cotacao_intelipost WHERE idEmpresa = $idEmpresa AND idObj = $idObj AND tipoObj = '$tipoObj' ORDER BY data DESC;";
        $res = \cron\Util\MySQL::Instance()->Select($q);
        if(!$res->isSuccess())
            return null;
        
        $rst = $res->getResult();
        if(count($rst) == 0)
            return null;
        
        $rst = $rst[0];        
        $model = new \models\EntityModels\IntelipostCotacao\FreteCotacaoIntelipostModel();
        $model->SetValues($rst);
        return $model;        
    }
    
    /**
     * @return \models\EntityModels\IntelipostCotacao\FreteCotacaoIntelipostModel
     */    
    public function CarregarCotacaoDeOrcamento($idOrcamento)
    {
        return $this->CarregarCotacao($idOrcamento, \models\EntityModels\IntelipostCotacao\FreteCotacaoIntelipostModel::COTACAO_TIPO_ORCAMENTO);
    }
    
    /**
     * @return \models\EntityModels\IntelipostCotacao\FreteCotacaoIntelipostModel
     */    
    public function CarregarCotacaoDePedido($idPedido)
    {
        return $this->CarregarCotacao($idPedido, \models\EntityModels\IntelipostCotacao\FreteCotacaoIntelipostModel::COTACAO_TIPO_PEDIDO);
    }
    
    /**
     * @return \models\EntityModels\IntelipostCotacao\FreteCotacaoIntelipostModel
     */    
    public function CarregarCotacaoDeNotaFiscal($idNF)
    {
        return $this->CarregarCotacao($idNF, \models\EntityModels\IntelipostCotacao\FreteCotacaoIntelipostModel::COTACAO_TIPO_NF);
    }
}

