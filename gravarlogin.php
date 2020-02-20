<?php
session_start();
$paginaAnterior =  $_SERVER['HTTP_REFERER'];
include "conn.php";
echo '<pre>';
$Dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//var_dump($Dados);

$result_usuario = "SELECT * from usuarios WHERE nome = '".addslashes($Dados['nome'])."'";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query( $conn, $result_usuario , $params, $options );
$row_count = sqlsrv_num_rows($stmt);


if ($row_count > 0){
  $_SESSION['loginExistente'] = "Esse Usuário ja possui Acesso!";
  header('Location: '.$paginaAnterior.'');
  exit;
}else{
   $sqlAdd = "INSERT INTO [dbo].usuarios VALUES('".addslashes($Dados['nome'])."','".addslashes($Dados['email'])."','".addslashes($Dados['password'])."','".$Dados['cluster']."')";
  }

  if(sqlsrv_query($conn, $sqlAdd)){
    $_SESSION['solicitacaoEfetuada'] = "Acesso solicitado";
    header('Location: '.$paginaAnterior.'');
  }else {
    $_SESSION['loginErro'] = "Erro! Usuário invalido";
    header('Location: '.$paginaAnterior.'');
  }

?>