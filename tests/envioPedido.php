<?php


error_reporting(E_ALL);

require_once '../../../../cron/CronConfigs.php';
require_once '../../../../services/Restful/AutoLoader.php';
require_once SYSTEM_DIR . 'utils/functions.php';
require_once SYSTEM_DIR . 'integracao/Logistica/IIntegracaoLogisticaProxy.php';

Auth::$idEmpresa = 828487;

$idPedido = 14817978;

require_once '../../../../services/Restful/AutoLoader.php';

$pedidoBuilder = new services\Objetos\Builders\PedidoDeVendaBuilder();
cron\Util\MySQL::Instance()->SetCharset('utf8');
$pedido = $pedidoBuilder->BuildFromID($idPedido);

$proxy = new integracao\Logistica\IntegracaoIntelipost\IntelipostProxy();
$pedido->numeroPedido = 9;
$resEnvio = $proxy->EnviarPedido($pedido);
if($resEnvio->isSuccess)
{
    echo '<p>Pedido Enviado</p>';
    var_dump($resEnvio->GetResult());
}
else
{
    echo '<p>Falha</p>';
    echo "<p>$resEnvio->message</p>";    
}

echo '<h3>Consulta do envio:</h3>';
$resC = $proxy->ConsultarPedidoEnviado($pedido->numeroPedido);
if($resC->isSuccess)
{
    echo '<p>Pedido Consultado</p>';
    var_dump($resC->GetResult());    
}
else    
{
    echo '<p>Falha</p>';
    echo "<p>$resC->message</p>";    
}

echo '<h3>Marcar para pronto para envio:</h3>';
$resP = $proxy->MarcarPedidoParaProntoParaEnvio($pedido->numeroPedido);
if($resP->isSuccess)
{
    echo '<p>Pedido pronto para envio</p>';
    var_dump($resP->GetResult());    
}
else    
{
    echo '<p>Falha</p>';
    echo "<p>$resP->message</p>";    
}


echo '<h3>Marcar como enviado:</h3>';
$resE = $proxy->MarcarPedidoParaEnviado($pedido->numeroPedido);
if($resE->isSuccess)
{
    echo '<p>Pedido pronto para envio</p>';
    var_dump($resE->GetResult());    
}
else    
{
    echo '<p>Falha</p>';
    echo "<p>$resE->message</p>";    
}

echo '<h3>Cancelamento do Pedido</h3>';
$resCan = $proxy->CancelarPedidoEnviado($pedido->numeroPedido);
if($resCan->isSuccess)
{
    echo '<p>Pedido Consultado</p>';
    var_dump($resCan->GetResult());    
}
else    
{
    echo '<p>Falha</p>';
    echo "<p>$resCan->message</p>";    
}