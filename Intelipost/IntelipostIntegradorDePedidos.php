<?php

namespace integracao\Logistica\IntegracaoIntelipost;

require_once SYSTEM_DIR . 'integracao/Logistica/IntegracaoIntelipost/Utils/IntelipostSDK/examples/spoon.php';
require_once SYSTEM_DIR . 'integracao/Logistica/IIntegracaoLogisticaProxy.php';

final class IntelipostIntegradorDePedidos implements \IIntegracaoLogisticaProxy {

    public function __construct() {
        
    }

    public function ConsultarStatusPedido() {
        
    }

    public function EnviarPedidosParaPicking(\IntegracaoLogisticaArgs $args) {
        
    }

    public function ConsultarFretes() {
        $intelipost = new \Intelipost(IntelipostConfigurations::Instance()->config->url, IntelipostConfigurations::Instance()->config->apiKey, true);

        $volume1 = new \Intelipost_Model_Volume();
        $volume1->weight = 6.4;
        $volume1->volume_type = "BOX";
        $volume1->cost_of_goods = "800";
        $volume1->width = 22;
        $volume1->height = 35;
        $volume1->length = 21;

        $volume2 = new \Intelipost_Model_Volume();
        $volume2->weight = 3.2;
        $volume2->volume_type = "BOX";
        $volume2->cost_of_goods = "400";
        $volume2->width = 22;
        $volume2->height = 35;
        $volume2->length = 11;

        $request = new \Intelipost_Model_Request();
        $request->origin_zip_code = '04030-002';
        $request->destination_zip_code = '04037-002';
        $request->volumes = array($volume1, $volume2);
        
        $response = new \Intelipost_Model_Response($intelipost->quote($request));
                
        return new Response\IntelipostConsultaFreteResponse($response->status == 'OK', $response->messages, $response->content);
    }

}
