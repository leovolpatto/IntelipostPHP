<?php

require_once 'intelipost.inc.php';

$c = new \Intelipost\Proxy\CepProxy();
$x = $c->AutoComplete('95700000');

var_dump($x->GetResult());