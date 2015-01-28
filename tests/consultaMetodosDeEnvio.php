<?php

error_reporting(E_ALL);

require_once '../../../../cron/CronConfigs.php';
require_once '../../../../services/Restful/AutoLoader.php';
require_once SYSTEM_DIR . 'utils/functions.php';
require_once SYSTEM_DIR . 'integracao/Logistica/IIntegracaoLogisticaProxy.php';

Auth::$idEmpresa = 828487;

$proxy = new integracao\Logistica\IntegracaoIntelipost\IntelipostProxy();
$r = $proxy->ConsultarMetodosDeEnvio();


print_r($r->GetResult());

