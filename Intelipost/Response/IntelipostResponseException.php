<?php

namespace Intelipost\Response;

final class IntelipostResponseException extends \Exception {
    
    public $data;
    
    public function __construct($message, $data = null) {
        $this->data = $data;
        parent::__construct($message);
    }
    
}
