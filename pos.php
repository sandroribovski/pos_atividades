<!DOCTYPE html>
<?php 
session_start();
include "conn.php";


if(!empty($_SESSION['usuarioLogado'])){$status = $_SESSION['usuarioLogado'];}else{$status = '0';}

if(!empty($_SESSION['cluster'])){$cluster = $_SESSION['cluster'];}else{$cluster = '0';}

if(!empty($_SESSION['nome'])){$usuario = $_SESSION['nome'];}else{$usuario = '0';}



 ?>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title>Pos Instalações</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link href="navbar-static-top.css" rel="stylesheet">
    <script src="./js/ie-emulation-modes-warning.js"></script>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

    <script>
    $().ready(function() {
        setTimeout(function() {
            $('#ocultar').hide();
        }, 2500);
    });
    </script>
    
</head>


<body>

    <!-- Static navbar -->
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <img style="width:140px;margin-top:12px" src='vivo cabecalho.png'>
            </div>
            <div class="header clearfix">
                <nav style="margin-top:3px">
                    <ul class="nav nav-pills pull-right">
                        <li role="presentation" ><a href="http://10.238.176.70/Pos/pos_bd.php">Pos Defeito</a></li>
                        <li role="presentation" class="active"><a href="http://10.238.176.70/Pos/pos.php">Pos Instalações</a></li>
                        <?php if($status == '0'){ ?>
                        <li role="presentation"><a data-toggle="modal" data-target="#login2"
                            aria-pressed="false" autocomplete="off" href="#">Solicitar Acesso</a></li>
                        <?php }else{ ?>
                    
                    <li role="presentation"><a><b><?php if($status == 1){echo $usuario;} ?></b></a></li>
                    <?php } ?>    
                    </ul>
                </nav>

            </div>
        </div>
    </nav>
    <?php 
	if(isset($_SESSION['solicitacaoEfetuada'])){ ?>
    <div class="alert alert-success" role='alert' id="ocultar">Socitação de Acesso Efetuada com Sucesso
        <i class="glyphicon glyphicon-thumbs-up"></i>
    </div>
    <?php unset($_SESSION['solicitacaoEfetuada']); ?>
    <?php } ?>


    <?php 
	if(isset($_SESSION['loginExistente'])){ ?>
    <div class="alert alert-warning" role='alert' id="ocultar">Usuário ou senha errado
        <i class="glyphicon glyphicon-thumbs-down"></i>
    </div>
    <?php unset($_SESSION['loginExistente']); ?>
    <?php } ?>

    <?php
    if (isset($_SESSION['logadoComSucesso'] )) { ?>
    <div class="alert alert-success" role='alert' id="ocultar">Login Efetuado com Sucesso
        <i class="glyphicon glyphicon-thumbs-up"></i>
    </div>
    <?php unset($_SESSION['logadoComSucesso']); ?>
    <?php } ?>


    <?php
    $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
    //Seta a quantidade de eventos por pagina
    $quantidade_pg = 30;
    //Calcular o inicio da visualizacao
    if($pagina == 0){$inicio = 1;}else{$inicio = ($quantidade_pg * $pagina)-$quantidade_pg;}
   

    ?>


    <div class="container-fluid">
        <table id="tabela" class="table table-striped,table table-bordered, text-align:center">
            <thead>
                <tr style="font-size:12px;color:#e6f9ff;background:#165e6b;text-align:center">
                    <th style='text-align:center'>INSTANCIA</th>
                    <th style='text-align:center'>ATIVIDADE</th>
                    <th style='text-align:center'>DATA INSTALAÇÃO</th>
                    <th style='text-align:center'>STATUS</th>
                    <th style='text-align:center'>CLUSTER</th>
                    <th style='text-align:center'>CIDADE</th>
                    <th style='text-align:center'>ORDEM DE SERVIÇO</th>
                    <th style='text-align:center'><a style="color:#e6f9ff" href="exportar.php?cluster=<?php echo $cluster; ?>"><span style="color:#e6f9ff; font-size: 15px" class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if ($status == '0') {
                        $query = "Select * from  tabela_pos order by DATAEXECUCAO DESC OFFSET 1 ROWS FETCH NEXT 25 ROWS ONLY";
                     }else {
                         $sqlCount = "SELECT * FROM tabela_pos WHERE CLUSTER_ = '$cluster' and OBSERVACAO = '$usuario'";
                         $params = array();
                         $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                         $stmt = sqlsrv_query( $conn, $sqlCount , $params, $options );
                         
                         if (sqlsrv_num_rows($stmt) != 0 ) {
                                $query = "SELECT * FROM tabela_pos WHERE CLUSTER_ = '$cluster' and OBSERVACAO = '$usuario'";
                            } else {
                                $query = "Select * from  tabela_pos where CLUSTER_ = '$cluster' and STATUSTRATATIVA != 'Tratado' order by DATAEXECUCAO DESC OFFSET $inicio ROWS FETCH NEXT $quantidade_pg ROWS ONLY";
                        }
                         
                     }
                    $sql = sqlsrv_query($conn, $query);

                    $resultadoEvento = sqlsrv_query($conn,"Select * from  tabela_pos where CLUSTER_ = '$cluster'");
                    //verifica a quantidade de eventos 
                    $params = array();
                    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                    $stmt = sqlsrv_query( $conn, "Select * from  tabela_pos where CLUSTER_ = '$cluster'" , $params, $options );
                    $quantidadeDeEventos = sqlsrv_num_rows( $stmt );
                
                    //calcular o número de pagina necessárias para apresentar os eventos
                    $num_pagina = ceil($quantidadeDeEventos/$quantidade_pg);
                    
                ?>
                
                <?php while ($obj = sqlsrv_fetch_object($sql)) { ?>
                <tr style="font-size:11px;text-align:center">
                    <th style='text-align:center'><?php echo $obj->INSTANCIA; ?></th>
                    <th style='text-align:center'><?php echo utf8_encode($obj->ATIVIDADE); ?></th>
                    <th style='text-align:center'><?php echo date_format($obj->DATAEXECUCAO,'d/m/Y'); ?></th>
                    <th style='text-align:center'><?php echo $obj->TIMEATIVIDADE; ?></th>
                    <th style='text-align:center'><?php echo $obj->CLUSTER_; ?></th>
                    <th style='text-align:center'><?php echo $obj->CITY; ?></th>
                    <th id="<?php echo $obj->SS; ?>" style='text-align:center'><?php echo $obj->SS; ?></th>
                    <?php if($obj->STATUSTRATATIVA == "PENDENTE"){ ?>
                    <th style='text-align:center'><a href="javascript:;" id="ssId"
                            onclick="chamarSS('<?php $x = ($status == 1) ? $obj->SS : 'login';echo $x; ?>');"><button
                                style="font-size:10px;background:#246ac7" type="button" class="btn btn-primary btn-sm"
                                data-toggle="modal"
                                data-target="#<?php $x = ($status == 1) ? $obj->SS : "login";echo $x; ?>">TRATAR</button></a>
                    </th>
                    <?php }elseif($obj->STATUSTRATATIVA == "EM TRATATIVA" and $obj->OBSERVACAO != $usuario){ ?>
                         
                    <th style='text-align:center'><button type="button"  class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Em Atendimento com: <?php echo $obj->USUARIO; ?>"><span
                                class="glyphicon glyphicon-earphone" aria-hidden="true"></span></button></th>

                    
                    <?php }elseif($obj->STATUSTRATATIVA == "EM TRATATIVA" and $obj->OBSERVACAO == $usuario){ ?>
                         
                        <th style='text-align:center'><a href="javascript:;" id="ssId"
                            onclick="chamarSS('<?php $x = ($status == 1) ? $obj->SS : 'login';echo $x; ?>');"><button
                                style="font-size:10px;background:#246ac7" type="button" class="btn btn-primary btn-sm"
                                data-toggle="modal"
                                data-target="#<?php $x = ($status == 1) ? $obj->SS : "login";echo $x; ?>">TRATAR</button></a>
                    </th>           



                    <?php }elseif($obj->STATUSTRATATIVA == "Tratado"){ ?>

                    <th style='text-align:center'><button type="button" class="btn btn-success btn-sm"><span
                                class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span></button></th>
                    <?php }elseif($obj->STATUSTRATATIVA == "Nao Atende"){ ?>
                    <th style='text-align:center'><a href="javascript:;" id="ssId"
                            onclick="chamarSS('<?php echo $obj->SS; ?>');"><button
                                style="font-size:10px;background:#246ac7" type="button" class="btn btn-warning btn-sm"
                                data-toggle="modal" data-target="#<?php echo $obj->SS; ?>"><span style="color:yellow"
                                    class="glyphicon glyphicon-earphone" aria-hidden="true"></span> TRATAR</button></a>
                    </th>
                    <?php }elseif ($obj->STATUSTRATATIVA == "Retornar") { ?>
                    <th style='text-align:center'><a href="javascript:;" id="ssId"
                            onclick="chamarSS('<?php echo $obj->SS; ?>');"><button
                                style="font-size:10px;background:#246ac7" type="button" class="btn btn-warning btn-sm"
                                data-toggle="modal" data-target="#<?php echo $obj->SS; ?>"><span style="color:yellow"
                                    class="glyphicon glyphicon-earphone" aria-hidden="true"></span>
                                RETORNAR</button></a>
                    </th>
                    <?php } ?>
                </tr>
                <div class="modal fade" id="<?php echo $obj->SS; ?>" data-backdrop="static" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document" style="width:900px">
                        <script>
                        $(document).ready(function() {
                            $('#inputEnviarNegativaEntrevista<?php echo $obj->INSTANCIA; ?>').hide();
                            $('#inputEnviarNegativaEntrevistaObs<?php echo $obj->INSTANCIA; ?>').hide();
                            $('#AceitaEntrevistaNao<?php echo $obj->INSTANCIA; ?>').change(function() {
                                if ($('#AceitaEntrevistaNao<?php echo $obj->INSTANCIA; ?>').val() ==
                                    'nao') {
                                    $('#inputEnviarNegativaEntrevista<?php echo $obj->INSTANCIA; ?>')
                                        .show();
                                    $('#inputEnviarNegativaEntrevistaObs<?php echo $obj->INSTANCIA; ?>')
                                        .show();
                                    $('#inputEnviarNegativaEntrevistaObs<?php echo $obj->INSTANCIA; ?>')
                                        .hide();
                                    $('#inputNome<?php echo $obj->INSTANCIA; ?>').hide();
                                    $('#exibir2<?php echo $obj->INSTANCIA; ?>').hide();
                                    $('#exibir1<?php echo $obj->INSTANCIA; ?>').hide();
                                }
                            });
                        });
                        $(document).ready(function() {
                            $('#exibir1<?php echo $obj->INSTANCIA; ?>').hide();
                            $('#AceitaEntrevistaSim<?php echo $obj->INSTANCIA; ?>').change(function() {
                                if ($('#AceitaEntrevistaSim<?php echo $obj->INSTANCIA; ?>').val() ==
                                    'sim') {
                                    $('#inputEnviarNegativaEntrevista<?php echo $obj->INSTANCIA; ?>')
                                        .hide();
                                    $('#inputEnviarNegativaEntrevista<?php echo $obj->INSTANCIA; ?>')
                                        .hide();
                                    $('#inputNome<?php echo $obj->INSTANCIA; ?>').hide();
                                    $('#exibir2<?php echo $obj->INSTANCIA; ?>').hide();
                                    $('#exibir1<?php echo $obj->INSTANCIA; ?>').show();
                                    $('#inputEnviarNegativaEntrevistaObs<?php echo $obj->INSTANCIA; ?>')
                                        .hide();
                                }
                            });
                        });

                        $(document).ready(function() {
                            $('#AceitaEntrevistaNaoAtende<?php echo $obj->INSTANCIA; ?>').change(function() {
                                if ($('#AceitaEntrevistaNaoAtende<?php echo $obj->INSTANCIA; ?>')
                                    .val() == 'Nao Atende') {
                                    $('#inputEnviarNegativaEntrevistaObs<?php echo $obj->INSTANCIA; ?>')
                                        .show();
                                    $('#inputEnviarNegativaEntrevista<?php echo $obj->INSTANCIA; ?>')
                                        .hide();
                                    $('#exibir1<?php echo $obj->INSTANCIA; ?>').hide();
                                }
                            });
                        });

                        $(document).ready(function() {
                            $('#inputNome<?php echo $obj->INSTANCIA; ?>').hide();
                            $('#exibir2<?php echo $obj->INSTANCIA; ?>').hide();

                            $('#titularLinha<?php echo $obj->INSTANCIA; ?>').change(function() {
                                if ($('#titularLinha<?php echo $obj->INSTANCIA; ?>').val() == 'nao') {
                                    $('#inputNome<?php echo $obj->INSTANCIA; ?>').show();
                                    $('#exibir2<?php echo $obj->INSTANCIA; ?>').show();
                                }
                            });
                        });
                        $(document).ready(function() {
                            $('#titularLinhaSim<?php echo $obj->INSTANCIA; ?>').change(function() {
                                if ($('#titularLinhaSim<?php echo $obj->INSTANCIA; ?>').val() ==
                                    'sim') {
                                    $('#inputNome<?php echo $obj->INSTANCIA; ?>').hide();
                                    $('#exibir2<?php echo $obj->INSTANCIA; ?>').show();
                                }
                            });
                        });


                        $(document).ready(function() {
                            $('#exibir4<?php echo $obj->INSTANCIA; ?>').hide();
                            $('#exibir5<?php echo $obj->INSTANCIA; ?>').hide();
                            $('#utilizouWifiSim<?php echo $obj->INSTANCIA; ?>').change(function() {
                                if ($('#utilizouWifiSim<?php echo $obj->INSTANCIA; ?>').val() ==
                                    'sim') {
                                    $('#exibir4<?php echo $obj->INSTANCIA; ?>').show();
                                    $('#exibir5<?php echo $obj->INSTANCIA; ?>').hide();
                                }
                            });
                        });
                        $(document).ready(function() {
                            $('#utilizouWifiNao<?php echo $obj->INSTANCIA; ?>').change(function() {
                                if ($('#utilizouWifiNao<?php echo $obj->INSTANCIA; ?>').val() ==
                                    'nao') {
                                    $('#exibir4<?php echo $obj->INSTANCIA; ?>').hide();
                                    $('#exibir5<?php echo $obj->INSTANCIA; ?>').show();

                                }
                            });
                        });

                        $(document).ready(function() {
                            $('#exibir6<?php echo $obj->INSTANCIA; ?>').hide();
                            $('#naoPodeTesteWifiMaiorFreqNao<?php echo $obj->INSTANCIA; ?>').change(function() {
                                if ($('#naoPodeTesteWifiMaiorFreqNao<?php echo $obj->INSTANCIA; ?>')
                                    .val() == 'retornar') {
                                    $('#exibir6<?php echo $obj->INSTANCIA; ?>').show();
                                    $('#exibir7<?php echo $obj->INSTANCIA; ?>').hide();
                                }
                            });
                        });
                        $(document).ready(function() {
                            $('#exibir7<?php echo $obj->INSTANCIA; ?>').hide();
                            $('#poderiaTesteWifiMaiorFreqSim<?php echo $obj->INSTANCIA; ?>').change(function() {
                                if ($('#poderiaTesteWifiMaiorFreqSim<?php echo $obj->INSTANCIA; ?>')
                                    .val() == 'sim') {
                                    $('#exibir7<?php echo $obj->INSTANCIA; ?>').show();
                                    $('#exibir6<?php echo $obj->INSTANCIA; ?>').hide();

                                }
                            });
                        });
                        $(document).ready(function() {
                            $('#exibir8<?php echo $obj->INSTANCIA; ?>').hide();
                            $('#edentificouTravamentoQuedasSim<?php echo $obj->INSTANCIA; ?>').change(
                                function() {
                                    if ($('#edentificouTravamentoQuedasSim<?php echo $obj->INSTANCIA; ?>')
                                        .val() == 'sim') {
                                        $('#exibir8<?php echo $obj->INSTANCIA; ?>').show();
                                        $('#exibir9<?php echo $obj->INSTANCIA; ?>').hide();

                                    }
                                });
                        });
                        $(document).ready(function() {
                            $('#exibir9<?php echo $obj->INSTANCIA; ?>').hide();
                            $('#edentificouTravamentoQuedasNao<?php echo $obj->INSTANCIA; ?>').change(
                                function() {
                                    if ($('#edentificouTravamentoQuedasNao<?php echo $obj->INSTANCIA; ?>')
                                        .val() == 'nao') {
                                        $('#exibir9<?php echo $obj->INSTANCIA; ?>').show();
                                        $('#exibir8<?php echo $obj->INSTANCIA; ?>').hide();

                                    }
                                });
                        });


                        $(document).ready(function() {
                            $('#exibir7<?php echo $obj->INSTANCIA; ?>').hide();
                            $('#utilizouWifiMaiorFreqSim<?php echo $obj->INSTANCIA; ?>').change(function() {
                                if ($('#utilizouWifiMaiorFreqSim<?php echo $obj->INSTANCIA; ?>')
                                    .val() == 'sim') {
                                    $('#exibir7<?php echo $obj->INSTANCIA; ?>').show();

                                }
                            });
                        });
                        $(document).ready(function() {

                            $('#utilizouWifiMaiorFreqNao<?php echo $obj->INSTANCIA; ?>').change(function() {
                                if ($('#utilizouWifiMaiorFreqNao<?php echo $obj->INSTANCIA; ?>')
                                    .val() == 'nao') {
                                    $('#exibir7<?php echo $obj->INSTANCIA; ?>').show();

                                }
                            });
                        });
                        </script>
                        <div class="modal-content">
                            <div class="modal-header">
                                <img style="width:140px" src='vivo cabecalho.png'>
                                <button type="button" onclick="limparSS('<?php echo $obj->SS; ?>');" class="close"
                                    data-dismiss="modal" aria-label="Close">
                                    <span style="size:12px" aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-addon">Instancia:</span>
                                                <input name="instancia" class="form-control"
                                                    value="<?php echo $obj->INSTANCIA; ?>">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-addon">Velocidade:</span>
                                                <input name="cliente" class="form-control"
                                                    value="<?php echo utf8_encode($obj->VELOCIDADEADSL); ?>">

                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>


                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-addon">Produto Instalado:</span>
                                                <input name="cliente" class="form-control"
                                                    value="<?php echo utf8_encode($obj->SPECIFICATION_TYPE); ?><?php echo ' '. $obj->SPECIFICATION_PRODUCT; ?>">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-addon">Empresa:</span>
                                                <input name="cliente" class="form-control"
                                                    value="<?php echo $obj->CONTRACTOR; ?>">

                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-addon">Nome do Cliente:</span>
                                                <input style="font-weight:bold;background-color: rgb(235, 235, 224) !important;" name="cliente" class="form-control"
                                                    value="<?php echo $obj->CUSTOMER_NAME; ?>">

                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                <?php if($obj->PORTABILITY_STATUS == 'PN_ATIVADO_NA_GVT'){ ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-addon">Portabilidade:</span>
                                                <input name="cliente" class="form-control"
                                                    value="<?php echo $obj->PORTABILITY_STATUS; ?>">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-addon">Data Janela:</span>
                                                <input name="cliente" class="form-control"
                                                    value="<?php echo date_format($obj->PORTABILITY_END, 'd/m/Y H:i'); ?>">

                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                <?php } ?>
                                <?php if($obj->NUN_TENT_CONTATO != ''){ ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-addon">Numero de Contato:</span>
                                                <input name="cliente" class="form-control"
                                                    value="<?php echo $obj->NUN_TENT_CONTATO; ?>">
                                            </div>
                                            <small style="font-size:9px" class="help-block text-info"><em>Este numero
                                                    foi cadastrado na tentativa de contato anterior</em></small>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>


                                <form action="dados.php" method="post">
                                
                                    <input type="hidden" name="ss" value="<?php echo $obj->SS; ?>">
                                    <!--aceita entrevista:-->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-addon"><b>Aceita Realizar
                                                            Entrevista</b></span>
                                                    <div class="form-control">
                                                        <label class="radio-inline">
                                                            <input
                                                                id="AceitaEntrevistaSim<?php echo $obj->INSTANCIA; ?>"
                                                                type="radio" name="AceitaEntrevista" value="sim"> Sim
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input
                                                                id="AceitaEntrevistaNao<?php echo $obj->INSTANCIA; ?>"
                                                                type="radio" name="AceitaEntrevista" value="nao"> Não
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input
                                                                id="AceitaEntrevistaNaoAtende<?php echo $obj->INSTANCIA; ?>"
                                                                type="radio" name="AceitaEntrevista" value="Nao Atende">
                                                            Não Atente
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="inputEnviarNegativaEntrevista<?php echo $obj->INSTANCIA; ?>" class="row">

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="input-group input-group-sm">

                                                    <input type="submit" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="inputEnviarNegativaEntrevistaObs<?php echo $obj->INSTANCIA; ?>"
                                        class="row">

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-addon">Informe o numero da tentativa de
                                                        contato:</span>
                                                    <input name="numeroTentativaContato" class="form-control">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="input-group input-group-sm">

                                                    <input type="submit" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>







                                    <!--aceita entrevista:-->

                                    <!--informe o Nome:-->
                                    <div id="exibir1<?php echo $obj->INSTANCIA; ?>" class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-addon"><b>O Sr(a) é o titular da
                                                            linha?</b></span>
                                                    <div class="form-control">
                                                        <label class="radio-inline">
                                                            <input id="titularLinhaSim<?php echo $obj->INSTANCIA; ?>"
                                                                type="radio" name="titularLinha" value="sim"> Sim
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input id="titularLinha<?php echo $obj->INSTANCIA; ?>"
                                                                type="radio" name="titularLinha" value="nao"> Não
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="inputNome<?php echo $obj->INSTANCIA; ?>" class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-addon"><b>informe o Nome:</b></span>
                                                    <input name="nomeEntrevistado" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--informe o Nome:-->


                                    <!-- utilizou a banda larga via WIFI-->
                                    <div id="exibir2<?php echo $obj->INSTANCIA; ?>" class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-addon"><b>O Sr(a) já utilizou a banda larga
                                                            via WIFI?</b></span>
                                                    <div class="form-control">
                                                        <label class="radio-inline">
                                                            <input id="utilizouWifiSim<?php echo $obj->INSTANCIA; ?>"
                                                                type="radio" name="utilizouWifi" value="sim"> Sim
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input id="utilizouWifiNao<?php echo $obj->INSTANCIA; ?>"
                                                                type="radio" name="utilizouWifi" value="nao"> Não
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="exibir4<?php echo $obj->INSTANCIA; ?>" class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-addon"><b>O Sr(a) Testou nos ambientes que
                                                            utiliza com maior frequência?</b></span>
                                                    <div class="form-control">
                                                        <label class="radio-inline">
                                                            <input
                                                                id="utilizouWifiMaiorFreqSim<?php echo $obj->INSTANCIA; ?>"
                                                                type="radio" name="utilizouWifiMaiorFreq" value="sim">
                                                            Sim
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input
                                                                id="utilizouWifiMaiorFreqNao<?php echo $obj->INSTANCIA; ?>"
                                                                type="radio" name="utilizouWifiMaiorFreq" value="nao">
                                                            Não
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="exibir5<?php echo $obj->INSTANCIA; ?>" class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-addon"><b>Sr(a) poderia realizar um teste
                                                            neste momento?</b></span>
                                                    <div class="form-control">
                                                        <label class="radio-inline">
                                                            <input
                                                                id="poderiaTesteWifiMaiorFreqSim<?php echo $obj->INSTANCIA; ?>"
                                                                type="radio" name="poderiaTesteWifiMaiorFreq"
                                                                value="sim"> Sim
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input
                                                                id="naoPodeTesteWifiMaiorFreqNao<?php echo $obj->INSTANCIA; ?>"
                                                                type="radio" name="poderiaTesteWifiMaiorFreq"
                                                                value="retornar"> Retornar Ligação Mais Tarde
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="exibir6<?php echo $obj->INSTANCIA; ?>" class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="input-group input-group-sm">

                                                    <input type="submit" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="exibir7<?php echo $obj->INSTANCIA; ?>" class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-addon"><b>O Sr(a) Observou algum problema
                                                            de travamento, quedas ou problemas com a Linha?</b></span>
                                                    <div class="form-control">
                                                        <label class="radio-inline">
                                                            <input
                                                                id="edentificouTravamentoQuedasSim<?php echo $obj->INSTANCIA; ?>"
                                                                type="radio" name="problemasTravamentoQuedas"
                                                                value="sim"> Sim
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input
                                                                id="edentificouTravamentoQuedasNao<?php echo $obj->INSTANCIA; ?>"
                                                                type="radio" name="problemasTravamentoQuedas"
                                                                value="nao"> Nao
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div id="exibir8<?php echo $obj->INSTANCIA; ?>" class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-addon"><b>Agendar Visita Técnica com o
                                                            Cliente: Informe a Data</b></span>
                                                    <input type="Date" name="dataAgendado"
                                                        class="form-control datetime-input">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-addon"><b>Observação:</b></span>
                                                    <textarea rows="3" cols="52" name="observacao" maxlength="155"
                                                        class="input-group input-group-sm"></textarea>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="input-group input-group-sm">
                                                    <input type="submit" class="form-control">

                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div id="exibir9<?php echo $obj->INSTANCIA; ?>" class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="input-group input-group-sm">

                                                    <input type="submit" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                            </div>
                        </div>

                        </form>
                    </div>
                </div>
    </div>


    </div>

    <?php } ?>


    </tbody>
    </table>
    <?php 
		//verificar a pagina anterior e posterior
		$pagina_anterior = $pagina - 1;
		$pagina_posterior = $pagina + 1;
		?>
		<nav aria-label="Page navigation example" class="text-center">
			<ul class="pagination">
				<li class="page-item">
					<?php
					if($pagina_anterior != 0){ ?>
						<a href="pos.php?pagina=<?php echo $pagina_anterior; ?>" aria-label="Previous">
							<span aria-hidden="true">&laquo;</span>
						</a>
					<?php }else{ ?>
						<span aria-hidden="true">&laquo;</span>
				<?php }  ?>
				</li>
				<?php 
				//Apresentar a paginacao
				for($i = 1; $i < $num_pagina + 1; $i++){ ?>
					<li><a href="pos.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
				<?php } ?>
				<li>
					<?php
					if($pagina_posterior <= $num_pagina){ ?>
						<a href="pos.php?pagina=<?php echo $pagina_posterior; ?>" aria-label="Previous">
							<span aria-hidden="true">&raquo;</span>
						</a>
					<?php }else{ ?>
						<span aria-hidden="true">&raquo;</span>
				<?php }  ?>
				</li>
			</ul>
		</nav>

    <div class="modal fade" id="login" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width:900px">


            <div class="modal-content">
                <div class="modal-header">
                    <img style="width:140px" src='vivo cabecalho.png'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span style="size:12px" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid" style="width:50%">

                        <form action="autenticarLogin.php" method="post" class="form-signin">
                            <p><img style="width:140px;margin-left: 120px" src='vivo cabecalho.png'></p>

                            <label for="inputEmail" class="sr-only">Email address</label>
                            <label for="inputEmail" class="sr-only">Nome</label>
                            <input class="form-control" placeholder="Email" name="email" required autofocus><br>

                            <label for="inputPassword" class="sr-only">Password</label>
                            <input type="password" id="inputPassword" class="form-control" placeholder="Password"
                                name="password" required><br>

                            <button style="width:50%;margin-left: 100px" class="btn btn-lg btn-primary btn-block"
                                type="submit">Login</button>
                        </form>

                    </div>
                </div>
            </div>


        </div>



    </div>


    <div class="modal fade" id="login2" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width:900px">


            <div class="modal-content">
                <div class="modal-header">
                    <img style="width:140px" src='vivo cabecalho.png'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span style="size:12px" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid" style="width:50%">

                        <form action="gravarlogin.php" method="post" class="form-signin">
                            <p><img style="width:140px;margin-left: 120px" src='vivo cabecalho.png'></p>

                            <label for="inputEmail" class="sr-only">Nome</label>
                            <input class="form-control" placeholder="Nome" name="nome" required autofocus><br>


                            <label for="inputEmail" class="sr-only">Email address</label>
                            <select class="form-control" name="cluster" required autofocus>
                                <option selected="selected" value="">Selecione um cluster</option>
                                <option>CURITIBA</option>
                                <option>PONTA GROSSA</option>
                                <option>CASCAVEL</option>
                                <option>FOZ DO IGUACU</option>
                                <option>LONDRINA</option>
                                <option>MARINGA</option>
                                <option>BLUMENAU</option>
                                <option>JOINVILLE</option>
                                <option>FLORIANOPOLIS</option>
                                <option>CHAPECO</option>
                                <option>LAGES</option>
                                <option>PORTO ALEGRE</option>
                                <option>CANOAS</option>
                                <option>NOVO HAMBURGO</option>
                                <option>PELOTAS</option>
                                <option>CAXIAS DO SUL</option>
                                <option>SANTA MARIA</option>
                                <option>REGIONAL</option>
                            </select>
                            <br>

                            <label for="inputEmail" class="sr-only">Email address</label>
                            <input type="email" id="inputEmail" class="form-control" placeholder="Email address"
                                name="email" required autofocus><br>

                            <label for="inputPassword" class="sr-only">Password</label>
                            <input type="password" id="inputPassword" class="form-control" placeholder="Password"
                                name="password" required><br>

                            <button style="width:50%;margin-left: 100px" class="btn btn-lg btn-primary btn-block"
                                type="submit">Solicitar Login</button>
                        </form>

                    </div>
                </div>
            </div>


        </div>

    </div> <!-- /container -->
    <script>
    function chamarSS(ssId) {
            if (ssId) {
            console.log("SS: " + ssId);
            var url = 'testepos.php?ss=' + ssId;
            $.get(url, function(dataReturn) {
                $('#resp-emergencia').html(dataReturn);
            });
        }
    }

    function limparSS(ssId) {

        if (ssId) {
            console.log("SSLIMPAR: " + ssId);
            var url = 'testepos.php?sslimpar=' + ssId;
            $.get(url, function(dataReturn) {
                $('#resp-emergencia').html(dataReturn);
            });
        }
    }
    </script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>
    window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')
    </script>
    <script src="./js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="./js/ie10-viewport-bug-workaround.js"></script>

</body>

</html>