<?php

namespace Intelipost\Response;

/**
 * @author Leonardo Volpatto <leovolpatto@gmail.com>
 */
final class IntelipostResponseException extends \Exception {
    
    public $data;
    
    public function __construct($message, $data = null) {
        $this->data = $data;
        parent::__construct($message);
    }
    
}
