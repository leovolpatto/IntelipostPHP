<?php

require_once '../intelipost.inc.php';

$c = new \Intelipost\Intelipost();
$x = $c->GetCEP()->AutoComplete('95700000');

var_dump($x->GetResult());