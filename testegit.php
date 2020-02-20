<?php
include "conn.php";
$arquivo = 'Pos.xls';
$cluster = $_GET['cluster'];
// Criamos uma tabela HTML com o formato da planilha para excel
$tabela = '<table>';

$tabela .= '<tr>';
$tabela .= '<td><b>NOME DO CLIENTE</b></td>';
$tabela .= '<td><b>INSTANCIA</b></td>';
$tabela .= '<td><b>SS</b></td>';
$tabela .= '<td><b>CIDADE</b></td>';
$tabela .= '<td><b>CLUSTER</b></td>';
$tabela .= '<td><b>ATIVIDADE</b></td>';
$tabela .= '<td><b>TIME</b></td>';
$tabela .= '<td><b>STATUS POS</b></td>';
$tabela .= '<td><b>NUMERO DE CONTATO</b></td>';
$tabela .= '<td><b>ACEITOU ENTREVISTA</b></td>';
$tabela .= '<td><b>TITULAR LINHA</b></td>';
$tabela .= '<td><b>NOME DO ENTREVISTADO</b></td>';
$tabela .= '<td><b>UTILIZOU WIFI</b></td>';
$tabela .= '<td><b>UTILIZOU WIFI MAIOR FREQ</b></td>';
$tabela .= '<td><b>PROBLEMAS DE TRAVAMENTO/QUEDAS</b></td>';
$tabela .= '<td><b>OBSERVACAO</b></td>';
$tabela .= '<td><b>DATA AGENDADO</b></td>';
$tabela .= '<td><b>USUARIO</b></td>';

$tabela .= '</tr>';
	$resultado = sqlsrv_query($conn, "select * from tabela_pos 
	where CLUSTER_ = '$cluster'");



while($dados = sqlsrv_fetch_array($resultado)){

	$tabela .= '<tr>';
	    $tabela .= '<td>'.@$dados['CUSTOMER_NAME'].'</td>';
		$tabela .= '<td>'.@$dados['INSTANCIA'].'</td>';
        $tabela .= '<td>'.@$dados['SS'].'</td>';
        $tabela .= '<td>'.@$dados['CITY'].'</td>';
        $tabela .= '<td>'.@$dados['CLUSTER_'].'</td>';
        $tabela .= '<td>'.@$dados['ATIVIDADE'].'</td>';
        $tabela .= '<td>'.@$dados['TIMEATIVIDADE'].'</td>';
        $tabela .= '<td>'.@$dados['STATUSTRATATIVA'].'</td>';
        $tabela .= '<td>'.@$dados['NUN_TENT_CONTATO'].'</td>';
        $tabela .= '<td>'.@$dados['ACEITA_ENTREVISTA'].'</td>';
        $tabela .= '<td>'.@$dados['TITULAR_LINHA'].'</td>';
        $tabela .= '<td>'.@$dados['NOME_ENTREVISTADO'].'</td>';
        $tabela .= '<td>'.@$dados['UTILIZOU_WIFI'].'</td>';
        $tabela .= '<td>'.@$dados['UTILIZOU_WIFI_MAIOR_FREQ'].'</td>';
        $tabela .= '<td>'.@$dados['PROBLEMAS_TRAVAMENTO_QUEDAS'].'</td>';
        $tabela .= '<td>'.utf8_decode(@$dados['OBSERVACAO']).'</td>';
        $tabela .= '<td>'.@date_format($dados['DATA_AGENDADO'],'d/m/Y h:i').'</td>';
        $tabela .= '<td>'.@$dados['USUARIO'].'</td>';
        $tabela .= '</tr>';
        
    }
    
$tabela .= '</table>';

// Força o Download do Arquivo Gerado
header ('Cache-Control: no-cache, must-revalidate,charset=utf-8');
header ('Pragma: no-cache,charset=utf-8');
header('Content-Type: application/x-msexcel,charset=utf-8');
header ("Content-Disposition: attachment; filename=\"{$arquivo}\"");
echo $tabela;

sqlsrv_close($conn);
?>