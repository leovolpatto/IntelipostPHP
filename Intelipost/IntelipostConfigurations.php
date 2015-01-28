<?php

namespace integracao\Logistica\IntegracaoIntelipost;

/**
 * Singleton para busca das configurações da integração
 */
final class IntelipostConfigurations {
    
    /**
     * @var IntelipostConfigurations
     */
    private static $confs;
    
    /**
     * @return IntelipostConfigurations
     */
    public static function Instance()
    {
        if(self::$confs == null)
            self::$confs = new IntelipostConfigurations();
        
        return self::$confs;
    }
    
    /**
     * @var IntelipostConfig
     */
    public $config;
    
    public function __construct() {

        $this->LoadConfigs();
        
    }
    
    private function LoadConfigs()
    {
        $this->config = new IntelipostConfig();
        $this->config->url = "https://api.intelipost.com.br/api/v1";
        $this->config->apiKey = "23f576789197e75054ec92ad4e11e18a470186c420142470131a831955beb2b8";        
        
        //$this->config->login = \cron\Util\ParameterEngineNew::getParameter('pier8Logistica:login', \Auth::$idEmpresa);
        //$this->config->wsdlUrl = \cron\Util\ParameterEngineNew::getParameter('pier8Logistica:wsdlUrl', \Auth::$idEmpresa);
    
    }    
    
}
