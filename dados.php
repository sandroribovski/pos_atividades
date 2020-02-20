<?php
session_start();
$paginaAnterior =  $_SERVER['HTTP_REFERER'];
include "conn.php";

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

var_dump($dados);

if (!empty($dados['numeroTentativaContato'])) {
    $dados['numeroTentativaContato'] = $dados['numeroTentativaContato'];
} else {
    $dados['numeroTentativaContato'] = '';
}

if (!empty($dados['observacao'])) {
    $dados['observacao'] = $dados['observacao'];
} else {
    $dados['observacao'] = '';
}

if (!empty($dados['AceitaEntrevista'])) {
    $dados['AceitaEntrevista'] = $dados['AceitaEntrevista'];
} else {
    $dados['AceitaEntrevista'] = '';
}
if (!empty($dados['titularLinha'])) {
    $dados['titularLinha'] = $dados['titularLinha'];
} else {
    $dados['titularLinha'] = '';
}
if (!empty($dados['nomeEntrevistado'])) {
    $dados['nomeEntrevistado'] = $dados['nomeEntrevistado'];
} else {
    $dados['nomeEntrevistado'] = '';
}
if (!empty($dados['utilizouWifi'])) {
    $dados['utilizouWifi'] = $dados['utilizouWifi'];
} else {
    $dados['utilizouWifi'] = '';
}
if (!empty($dados['utilizouWifiMaiorFreq'])) {
    $dados['utilizouWifiMaiorFreq'] = $dados['utilizouWifiMaiorFreq'];
} else {
    $dados['utilizouWifiMaiorFreq'] = '';
}
if (!empty($dados['poderiaTesteWifiMaiorFreq'])) {
    $dados['poderiaTesteWifiMaiorFreq'] = $dados['poderiaTesteWifiMaiorFreq'];
} else {
    $dados['poderiaTesteWifiMaiorFreq'] = '';
}
if (!empty($dados['problemasTravamentoQuedas'])) {
    $dados['problemasTravamentoQuedas'] = $dados['problemasTravamentoQuedas'];
} else {
    $dados['problemasTravamentoQuedas'] = '';
}
if (!empty($dados['dataAgendado'])) {
    $dados['dataAgendado'] = $dados['dataAgendado'];
} else {
    $dados['dataAgendado'] = '';
}

if ($dados['AceitaEntrevista'] == 'Nao Atende') {    
    $query = "update tabela_pos set STATUSTRATATIVA = 'Nao Atende', NUN_TENT_CONTATO = '" . $dados['numeroTentativaContato'] . "', ACEITA_ENTREVISTA = '" . $dados['AceitaEntrevista'] . "', TITULAR_LINHA = '" . $dados['titularLinha'] . "',NOME_ENTREVISTADO = '" . $dados['nomeEntrevistado'] . "',
    UTILIZOU_WIFI = '" . $dados['utilizouWifi'] . "',UTILIZOU_WIFI_MAIOR_FREQ = '" . $dados['utilizouWifiMaiorFreq'] . "',PODERIA_TESTE_WIFI_MAIOR_FREQ = '" . $dados['poderiaTesteWifiMaiorFreq'] . "', PROBLEMAS_TRAVAMENTO_QUEDAS = '" . $dados['problemasTravamentoQuedas'] . "',
    OBSERVACAO = '" . $dados['observacao'] . "', DATA_AGENDADO = '" . $dados['dataAgendado'] . "', USUARIO = '".$_SESSION['nome']."' Where SS = '" . $dados['ss'] . "'";
} elseif ($dados['poderiaTesteWifiMaiorFreq'] == 'retornar') {
    $query = "update tabela_pos set STATUSTRATATIVA = 'Retornar', ACEITA_ENTREVISTA = '" . $dados['AceitaEntrevista'] . "', TITULAR_LINHA = '" . $dados['titularLinha'] . "',NOME_ENTREVISTADO = '" . $dados['nomeEntrevistado'] . "',
    UTILIZOU_WIFI = '" . $dados['utilizouWifi'] . "',UTILIZOU_WIFI_MAIOR_FREQ = '" . $dados['utilizouWifiMaiorFreq'] . "',PODERIA_TESTE_WIFI_MAIOR_FREQ = '" . $dados['poderiaTesteWifiMaiorFreq'] . "', PROBLEMAS_TRAVAMENTO_QUEDAS = '" . $dados['problemasTravamentoQuedas'] . "',
    OBSERVACAO = '" . $dados['observacao'] . "',DATA_AGENDADO = '" . $dados['dataAgendado'] . "', USUARIO = '".$_SESSION['nome']."' Where SS = '" . $dados['ss'] . "'";
} else {
    $query = "update tabela_pos set STATUSTRATATIVA = 'Tratado', ACEITA_ENTREVISTA = '" . $dados['AceitaEntrevista'] . "', TITULAR_LINHA = '" . $dados['titularLinha'] . "',NOME_ENTREVISTADO = '" . $dados['nomeEntrevistado'] . "',
    UTILIZOU_WIFI = '" . $dados['utilizouWifi'] . "',UTILIZOU_WIFI_MAIOR_FREQ = '" . $dados['utilizouWifiMaiorFreq'] . "',PODERIA_TESTE_WIFI_MAIOR_FREQ = '" . $dados['poderiaTesteWifiMaiorFreq'] . "', PROBLEMAS_TRAVAMENTO_QUEDAS = '" . $dados['problemasTravamentoQuedas'] . "',
    OBSERVACAO = '" . $dados['observacao'] . "', DATA_AGENDADO = '" . $dados['dataAgendado'] . "',USUARIO = '".$_SESSION['nome']."' Where SS = '" . $dados['ss'] . "'";
  
}
ECHO  $query;

$stmt = sqlsrv_query($conn, $query);
if ($stmt === false) {
    if (($errors = sqlsrv_errors()) != null) {
        foreach ($errors as $error) {
            echo "SQLSTATE: " . $error['SQLSTATE'] . "<br />";
            echo "code: " . $error['code'] . "<br />";
            echo "message: " . $error['message'] . "<br />";
        }
    }
} else {    
    header('Location: '.$paginaAnterior.'');
}

sqlsrv_close($conn);
