<?php

require_once '../intelipost.inc.php';

$proxy = new Intelipost\Proxy\CotacaoProxy();
$r = $proxy->ConsultarUmaCotacao(1988160);

if ($r->isSuccess) {    
    var_dump($r->GetResult());
} else {
    echo '<p>Falha</p>';
    echo "<p>$r->message</p>";
}