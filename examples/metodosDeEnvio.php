<?php

require_once './intelipost.inc.php';

$proxy = new Intelipost\IntelipostProxy();
$r = $proxy->ConsultarMetodosDeEnvio();

if ($r->isSuccess) {    
    var_dump($r->GetResult());
} else {
    echo '<p>Falha</p>';
    echo "<p>$r->message</p>";
}