<?php
 
function calcularSaldoEsperado($valor, $taxa) : float
{
    $total_transacao = calcularTransacao($valor, $taxa);
    
    return round(100 - $total_transacao, 2);
    
}

function calcularTransacao($valor, $taxa) : float
{
    return round($valor + ($valor * $taxa), 2);
}