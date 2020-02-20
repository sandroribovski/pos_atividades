<?php
session_start();
if(!empty($_SESSION['nome'])){$usuario = $_SESSION['nome'];}else{$usuario = '0';}
include "conn.php";

$id_ss = $_REQUEST['ss'];

$id_ss_limpar = $_REQUEST['sslimpar'];

if (!empty($id_ss)){
    $query = "update tabela_pos set STATUSTRATATIVA = 'EM TRATATIVA', OBSERVACAO = '$usuario',USUARIO = '$usuario' WHERE SS = '$id_ss'";
    $sql = sqlsrv_query($conn, $query);
    


}

if (!empty($id_ss_limpar)) {
    $query = "update tabela_pos set STATUSTRATATIVA = 'PENDENTE', OBSERVACAO = '', USUARIO = ''  WHERE SS = '$id_ss_limpar'";
    $sql = sqlsrv_query($conn, $query);
}



sqlsrv_close($conn);



?>

