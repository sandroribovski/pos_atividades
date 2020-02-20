<?php
$paginaAnterior =  $_SERVER['HTTP_REFERER'];
session_start();
include "conn.php";

$Dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
var_dump($Dados);

$result_usuario = "SELECT * from usuarios WHERE email = '".addslashes($Dados['email'])."' and senha = '".addslashes($Dados['password'])."'";

$resultado_usuario = sqlsrv_query($conn, $result_usuario);

$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query( $conn, $result_usuario , $params, $options );
$row_count = sqlsrv_num_rows($stmt);

while ($row = sqlsrv_fetch_array( $resultado_usuario, SQLSRV_FETCH_ASSOC)) {
    $_SESSION['usuarioLogado'] = "1";
    $_SESSION['cluster'] = $row['cluster'];
    $_SESSION['nome'] = $row['nome'];
}


if ($row_count > 0) {
    $_SESSION['logadoComSucesso'] = "logado Ok";
    header('Location: '.$_SERVER['HTTP_REFERER'].'');
}elseif (condition) {
    $_SESSION['loginExistente'] = "Usuário ou senha errado!";
    header('Location: '.$_SERVER['HTTP_REFERER'].'');
}

?>