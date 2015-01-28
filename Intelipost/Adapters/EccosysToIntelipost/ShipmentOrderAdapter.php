<?php

namespace integracao\Logistica\IntegracaoIntelipost\Adapters\EccosysToIntelipost;

final class ShipmentOrderAdapter {

    /**
     * @var \integracao\Logistica\IntegracaoIntelipost\IntelipostModel\shipment_order;
     */
    private $shipment_order;

    /**
     * @param \models\PedidoDeVenda\PedidoModel $pedido
     * @return \integracao\Logistica\IntegracaoIntelipost\IntelipostModel\shipment_order
     */
    public function Adapt(\models\PedidoDeVenda\PedidoModel $pedido) {
        $this->shipment_order = new \integracao\Logistica\IntegracaoIntelipost\IntelipostModel\shipment_order();
        
        
        if(strlen($pedido->idNotaFiscalRef) > 0)
            $cotacao = \integracao\Logistica\IntegracaoIntelipost\Helpers\IntelipostCotacaoRetrieverHelper::GetCotacaoDaNotaFiscal($pedido->idNotaFiscalRef);
        else
            $cotacao = \integracao\Logistica\IntegracaoIntelipost\Helpers\IntelipostCotacaoRetrieverHelper::GetCotacaoDoPedido($pedido->id);
        
        if($cotacao == null)
            $cotacao = \integracao\Logistica\IntegracaoIntelipost\Helpers\IntelipostCotacaoRetrieverHelper::GetCotacaoDoOrcamento($pedido->idOrigem);
        
        if($cotacao == null)
            throw new \integracao\Logistica\IntegracaoIntelipost\IntelipostCotacaoException('Não foi encontrada nenhuma cotação atrelada ao pedido!');


            $this->shipment_order->quote_id = $cotacao->idCotacaoIntelipost;
        $this->shipment_order->delivery_method_id = $cotacao->delivery_method_id;
        $this->shipment_order->order_number = $pedido->numeroPedido;
        $this->shipment_order->estimated_delivery_date = $pedido->dataPrevista;
        if($pedido->dataPrevista == '0000-00-00')
            unset($this->shipment_order->estimated_delivery_date);
        
        $this->DefinirCliente($pedido);
        $this->DefinirEndereco($pedido);        
        
        for($i = 1; $i <= $pedido->qtdVolumes; $i++)
            $this->DefinirVolumes($pedido, $i);
        
        return $this->shipment_order;
    }

    private function DefinirVolumes(\models\PedidoDeVenda\PedidoModel $pedido, $volume) {
        $this->shipment_order->shipment_order_volume_array = array();

        $s = new \integracao\Logistica\IntegracaoIntelipost\IntelipostModel\shipment_order_volume_array();
        $s->shipment_order_volume_number = $volume;
        $s->weight = $pedido->pesoBruto / $pedido->qtdVolumes;
        $s->volume_type_code = 'box'; //?
        
        if($pedido->qtdVolumes > 1)
        {
            $vol = $pedido->dimensaoLargura * $pedido->dimensaoAltura * $pedido->dimensaoComprimento;
            $vol_individual = $vol /$pedido->qtdVolumes;
            
            $s->width = $vol_individual ^ (1/3);
            $s->height = $vol_individual ^ (1/3);
            $s->length = $vol_individual ^ (1/3);
        }
        else {        
            $s->width = $pedido->dimensaoLargura;
            $s->height = $pedido->dimensaoAltura;
            $s->length = $pedido->dimensaoComprimento;        
        }
        
        $s->products_nature = 'Produtos'; //NCM do produto
        $s->tracking_code = $pedido->codigoRastreamento;//   <--- usar os codigos de rastreio das embalagens (caso correios)

        $q = 0;
        foreach ($pedido->_Itens->items as $itm)
            $q += $itm->quantidade;

        $s->products_quantity = $q;
        $s->is_icms_exempt = false; //??
        $s->shipment_order_volume_invoice = new \integracao\Logistica\IntegracaoIntelipost\IntelipostModel\shipment_order_volume_invoice();
        $s->shipment_order_volume_invoice->invoice_cfop = $pedido->_NotaFiscal->cfop;
        $s->shipment_order_volume_invoice->invoice_date = $pedido->_NotaFiscal->dataEmissao;
        $s->shipment_order_volume_invoice->invoice_key = $this->GetNfeChaveAcesso($pedido->_NotaFiscal->id);
        $s->shipment_order_volume_invoice->invoice_number = $pedido->_NotaFiscal->numero;
        $s->shipment_order_volume_invoice->invoice_products_value = $pedido->_NotaFiscal->valorProdutos;
        $s->shipment_order_volume_invoice->invoice_series = $pedido->_NotaFiscal->serie;
        $s->shipment_order_volume_invoice->invoice_total_value = $pedido->_NotaFiscal->valorNota;
        
        array_push($this->shipment_order->shipment_order_volume_array, $s);
    }
    
    private function DefinirCliente(\models\PedidoDeVenda\PedidoModel $pedido) {
        $this->shipment_order->end_customer = new \integracao\Logistica\IntegracaoIntelipost\IntelipostModel\end_customer();
        $this->shipment_order->end_customer->first_name =  $this->GetNomeContato($pedido->_Contato->nome);
        $this->shipment_order->end_customer->last_name = $this->GetSobrenomeContato($pedido->_Contato->nome);
        $this->shipment_order->end_customer->email = $pedido->_Contato->email;
        $this->shipment_order->end_customer->phone = $this->RemoverFormatacaoFone($pedido->_Contato->fone);
        $this->shipment_order->end_customer->cellphone = $this->RemoverFormatacaoFone($pedido->_Contato->celular);
        $this->shipment_order->end_customer->is_company = $pedido->_Contato->tipo == 'J';
        $this->shipment_order->end_customer->federal_tax_payer_id = $this->RemoverFormatacaoCnpj($pedido->_Contato->cnpj);
        $this->shipment_order->end_customer->state_tax_payer_id = $pedido->_Contato->ie;
    }

    private function DefinirEndereco(\models\PedidoDeVenda\PedidoModel $pedido) {
        
        if ($pedido->opcEnderecoDiferente == 'S') {
            $this->shipment_order->end_customer->shipping_address = $pedido->_OutroEndereco->endereco;
            $this->shipment_order->end_customer->shipping_number = $pedido->_OutroEndereco->enderecoNro;
            $this->shipment_order->end_customer->shipping_additional = $pedido->_OutroEndereco->complemento;
            $this->shipment_order->end_customer->shipping_reference = '';
            $this->shipment_order->end_customer->shipping_quarter = $pedido->_OutroEndereco->bairro;
            $this->shipment_order->end_customer->shipping_city = $pedido->_OutroEndereco->cidade;
            $this->shipment_order->end_customer->shipping_state = $pedido->_OutroEndereco->uf;
            $this->shipment_order->end_customer->shipping_zip_code = $this->RemoverFormatacaoCep($pedido->_OutroEndereco->cep);
            $this->shipment_order->end_customer->shipping_country = 'BR';// $pedido->_OutroEndereco->nomePais;
        } else {

            $this->shipment_order->end_customer->shipping_address = $pedido->_Contato->endereco;
            $this->shipment_order->end_customer->shipping_number = $pedido->_Contato->enderecoNro;
            $this->shipment_order->end_customer->shipping_additional = $pedido->_Contato->complemento;
            $this->shipment_order->end_customer->shipping_reference = '';
            $this->shipment_order->end_customer->shipping_quarter = $pedido->_Contato->bairro;
            $this->shipment_order->end_customer->shipping_city = $pedido->_Contato->cidade;
            $this->shipment_order->end_customer->shipping_state = $pedido->_Contato->uf;
            $this->shipment_order->end_customer->shipping_zip_code = $this->RemoverFormatacaoCep($pedido->_Contato->cep);
            $this->shipment_order->end_customer->shipping_country = 'BR';//$pedido->_Contato->nomePais;
        }
    }
    
    private function GetNfeChaveAcesso($idNota)
    {
        $q = 'SELECT chave_acesso FROM nfe_nota WHERE id_nota = ' . $idNota;
        $res = \cron\Util\MySQL::Instance()->Select($q);
        if(!$res->isSuccess())
            return '';
        
        $r = $res->getResult();
        if(count($r) == 0)
            return '';
        
        return $r[0]['chave_acesso'];
    }
    
    private function RemoverFormatacaoCep($cep) {
        $c = str_replace('.', '', $cep);
        $c = str_replace('/', '', $c);
        $c = str_replace('-', '', $c);
        return $c;
    }
    
    private function RemoverFormatacaoCnpj($cnpj) {
        $c = str_replace('.', '', $cnpj);
        $c = str_replace('/', '', $c);
        $c = str_replace('-', '', $c);
        return $c;
    }

    private function RemoverFormatacaoFone($fone) {
        $c = str_replace('(', '', $fone);
        $c = str_replace(')', '', $c);
        $c = str_replace('-', '', $c);
        $c = str_replace(' ', '', $c);
        return $c;
    }   
    
    private function GetNomeContato($contato){
        $array = explode(' ', $contato);
        
        return $array[0];
    }
    
    private function GetSobrenomeContato($contato){
        $position = strpos(' ', $contato);
        
        if($position === FALSE){
            return "";
        }
        else{
            return substr($contato, $position);
        }
    }

}
