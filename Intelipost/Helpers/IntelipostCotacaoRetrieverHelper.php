<?php

namespace integracao\Logistica\IntegracaoIntelipost\Helpers;

use models\EntityModels\IntelipostCotacao\FreteCotacaoIntelipostModel;

final class IntelipostCotacaoRetrieverHelper {
    
    /**
     * @return \models\EntityModels\IntelipostCotacao\FreteCotacaoIntelipostModel
     */
    private static function GetCotacao($tipoObj, $idObj)
    {
        $q = "SELECT * FROM frete_cotacao_intelipost WHERE tipoObj = '$tipoObj' AND idObj = '$idObj' ORDER BY data DESC";
        $res = \cron\Util\MySQL::Instance()->Select($q);
        if(!$res->isSuccess())
            return null;
        
        $data = $res->getResult();
        $lastQuote = $data[0];
        $model = new FreteCotacaoIntelipostModel();
        $model->SetValues($lastQuote);
        
        return $model;
    }
    
    /**
     * @param int $idPedido
     * @return \models\EntityModels\IntelipostCotacao\FreteCotacaoIntelipostModel
     */
    public static function GetCotacaoDoPedido($idPedido)
    {
        return self::GetCotacao(FreteCotacaoIntelipostModel::COTACAO_TIPO_PEDIDO, $idPedido);
    }
    
    /**
     * @param int $idNF
     * @return \models\EntityModels\IntelipostCotacao\FreteCotacaoIntelipostModel
     */
    public static function GetCotacaoDaNotaFiscal($idNF)
    {
        return self::GetCotacao(FreteCotacaoIntelipostModel::COTACAO_TIPO_NF, $idNF);
    }
    
    /**
     * @param int $idOrcamento
     * @return \models\EntityModels\IntelipostCotacao\FreteCotacaoIntelipostModel
     */
    public static function GetCotacaoDoOrcamento($idOrcamento)
    {
        return self::GetCotacao(FreteCotacaoIntelipostModel::COTACAO_TIPO_ORCAMENTO, $idOrcamento);
    }
    
}
