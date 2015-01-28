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

$req = new integracao\Logistica\IntegracaoIntelipost\IntelipostModel\quote_by_product();
$req->destination_zip_code = $pedido->_Contato->cep;
$req->origin_zip_code = Auth::GetEmpresaModel(Auth::$idEmpresa)->cep;
$req->additional_information = new \integracao\Logistica\IntegracaoIntelipost\IntelipostModel\additional_information();
$req->additional_information->delivery_method_id = 22;
$req->additional_information->lead_time_business_days = 2;
$req->additional_information->extra_cost_absolute = 2.5;
$req->additional_information->free_shipping = false;

foreach($pedido->_Itens->items as $p)
{    
    $produto = new integracao\Logistica\IntegracaoIntelipost\IntelipostModel\product();
    $produto->cost_of_goods = $p->valor;
    $produto->description = $p->descricao;
    $produto->height = 2;//$p->_Produto->altura;
    $produto->length = 2;//$p->_Produto->comprimento;
    $produto->quantity = floatval($p->quantidade);
    $produto->sku_id = $p->_Produto->codigo;
    $produto->weight = 1;//$p->_Produto->peso;
    $produto->width = 2;//$p->_Produto->largura;
    $req->AddProduct($produto);
}

$proxy = new integracao\Logistica\IntegracaoIntelipost\IntelipostProxy();
$resEnvio = $proxy->CotarSemVolumes($req);
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



/*
require_once '../../../../services/Restful/AutoLoader.php';

$i = new integracao\Logistica\IntegracaoIntelipost\IntelipostIntegradorDePedidos();

var_dump($i->ConsultarFretes()->GetResponse());
 * 
 */
