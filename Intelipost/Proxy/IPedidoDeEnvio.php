<?php

namespace Intelipost\Proxy;

/**
 * @author Leonardo Volpatto <leovolpatto@gmail.com>
 */
interface IPedidoDeEnvio {
    
    /**
     * @param \Intelipost\IntelipostModel\shipment_order $shipment_order
     * @return \Intelipost\Response\IntelipostEnvioPedidoResponse
     * @throws \Intelipost\IntelipostEnvioPedidoException
     */    
    public function CriarPedidoDeEnvio(\Intelipost\IntelipostModel\shipment_order $shipment_order);
    
    /**
     * @param int $numeroDoPedido
     * @return \Intelipost\Response\IntelipostCancelamentoPedidoResponse
     */    
    public function ConsultarPedidoDeEnvio($numeroDoPedido);
    
    /**
     * @param int $numeroDoPedido
     * @return \Intelipost\Proxy\Response\IntelipostCancelamentoPedidoResponse
     */    
    public function CancelarPedidoDeEnvio($numeroDoPedido);

    /**
     * @param int $numeroDoPedido
     * @return \Intelipost\Proxy\Response\IntelipostPedidoMarcadoComoProntoResponse
     */    
    public function MarcarPedidoComoProntoParaEnvio($numeroDoPedido);

    /**
     * @param int $numeroDoPedido
     * @return \Intelipost\Proxy\Response\IntelipostPedidoMarcadoComoEnviadoResponse
     */    
    public function MarcarPedidoComoEnviado($numeroDoPedido);

    /**
     * @param Arguments\MarcarDiversosPedidoComoEnviadoArgs $args
     * @return \Intelipost\Proxy\Response\IntelipostPedidoMarcadoComoEnviadoResponse
     */    
    public function MarcarDiversosPedidosComoEnviado(Arguments\MarcarDiversosPedidoComoEnviadoArgs $args);
    
    /**
     * @param array $pedidos
     * @return \Intelipost\Proxy\Response\IntelipostPedidoMarcadoComoProntoResponse[]
     */
    public function MarcarDiversosPedidosParaProntoParaEnvio(array $pedidos);
        
    public function ImpressaoDasEtiquetas();
    
    public function ConsultarDadosDoDestinatario();
    
    public function ConsultarNotasFiscais();
    
    public function ConsultarEtiquetas();
    
    public function ConsultarVolumesCaixas();
    
    public function ConsultarStatus();
    
    public function AtualizarNotasFiscais();
    
    public function AtualizarDadosDeRastreamento();
    
    public function AtualizarVolumesDeUmPedido();    
    
}
