<?php

namespace Intelipost;

/**
 * @author Leonardo Volpatto <leovolpatto@gmail.com>
 */
final class IntelipostCotacaoException extends \Exception {
    
    public $obj;
    
    public function __construct($message, $obj) {
        $this->obj = $obj;
        parent::__construct($message);
    }
    
}
