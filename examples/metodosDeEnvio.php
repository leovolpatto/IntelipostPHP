<?php

require_once '../intelipost.inc.php';

$proxy = new \Intelipost\Intelipost();
$r = $proxy->GetCotacao()->ConsultarMetodosDeEnvio();

if ($r->isSuccess) {    
    var_dump($r->GetResult());
} else {
    echo '<p>Falha</p>';
    echo "<p>$r->message</p>";
}