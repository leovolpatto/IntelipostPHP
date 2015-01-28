<?php

namespace Intelipost\Proxy;

/**
 * @author Leonardo Volpatto <leovolpatto@gmail.com>
 */
interface ICotacao {
    
    /**
     * @return \Intelipost\Response\IntelipostMetodosDeEnvioResponse
     */    
    public function ConsultarMetodosDeEnvio();
    
    /**
     * @param \Intelipost\IntelipostModel\quote_by_product $req
     * @return \Intelipost\Response\IntelipostCotacaoSemVolumeResponse
     * @throws \Intelipost\IntelipostCotacaoException
     */    
    public function CriarCotacaoPorProduto(\Intelipost\IntelipostModel\quote_by_product $req);
    
    public function CriarCotacaoPorVolume();
    
    /**
     * @param int $idCotacaoIntelipost
     * @return \Intelipost\Response\IntelipostCotacaoSemVolumeResponse
     */    
    public function ConsultarUmaCotacao($idCotacaoIntelipost);    
    
}
