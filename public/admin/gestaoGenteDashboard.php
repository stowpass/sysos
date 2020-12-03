<?php
require_once __DIR__ . '/../includes/funcoes.php';
include_once __DIR__ . '/../variaveis.php';

session_start();

isSessaoValida('admin/');

date_default_timezone_set('America/Fortaleza');
$diaCorrente = date('d');
$mesCorrente = date('m');
$anoCorrente = date('Y');

$bd = abreConn();
?>

<!DOCTYPE html>
<html>
<head>
	<?php include 'head.php' ?>
	<link rel="stylesheet" href="../css/datepicker3.css">
    <link rel="stylesheet" href="../dts/css/jquery.dataTables.css">
	<!--Custom Font-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i">
</head>
<body style="padding-top: 0px;">

	<?php include "topo.php"; ?>

	<div class="col-sm-12 main">
		<div class="row">
			<ol class="breadcrumb alert-info">
				<li><span class="text-warning">ROCHA, MARINHO E SALES ADVOGADOS</span></li>
				<li class="active">Bem-vindo(a) <strong><?=$_SESSION['nome']?></strong></li>
			</ol>
		</div><!--/.row-->

		<hr/>

		<div class="row">
			<div class="col-lg-3">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="col-md-12">
                            <h1 class="page-header">
                                <i class="fa fa-list-alt color-blue"></i> Menu</strong>
                            </h1>
                            <?php include "menu.php"; ?>
						</div>
					</div>
				</div><!-- /.panel-->
			</div>

			<div class="col-lg-9">
				<div class="panel panel-default">
					<div class="panel-body">

						<?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == '1'){ ?>
							<div id="msgErro" class="alert alert-success"><i class="fa fa-check-circle"></i>
								<strong>SUCESSO</strong>: Arquivo importado e dados cadastrados no sistema!
								<a href="javascript: $('#msgErro').hide(500);" class="pull-right"><em class="fa fa-lg fa-times-circle text-success"></em></a>
							</div>
						<?php } ?>

						<?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == '2'){ ?>
							<div id="msgErro" class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i>
								<strong>ERRO</strong>: Importação não realizada. Verifique o arquivo selecionado!
								<a href="javascript: $('#msgErro').hide(500);" class="pull-right"><em class="fa fa-lg fa-times-circle text-danger"></em></a>
							</div>
						<?php } ?>

						<?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == '0'){ ?>
							<div id="msgErro" class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i>
								<strong>ERRO</strong>: Tamanho do arquivo <strong>ultrapassa 2 Mb</strong> ou o formato está diferente - <strong>permitido apenas .CSV</strong>!
								<a href="javascript: $('#msgErro').hide(500);" class="pull-right"><em class="fa fa-lg fa-times-circle text-danger"></em></a>
							</div>
						<?php } ?>

						<?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == '3'){ ?>
							<div id="msgErro" class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i>
								<strong>ERRO</strong>: Selecione um arquivo!
								<a href="javascript: $('#msgErro').hide(500);" class="pull-right"><em class="fa fa-lg fa-times-circle text-danger"></em></a>
							</div>
						<?php } ?>

						<div class="col-md-12">
							<h1 class="page-header">
								<i class="fa fa-chart-pie color-blue"></i>Dashboard - Gente e Gestão</strong>
								<span style="float:right"><button class="btn btn-primary" onclick="javascript:$('#modalImportFile').modal();"><i class="fa fa-upload"></i> Importar Arquivo</button></span>
							</h1>
						</div>

					</div>
				</div><!-- /.panel-->

				<?php // Total Advogados
				$qTt = mysqli_query($bd, "SELECT sum(qntd_advogados) as totalAdv FROM tb_dashboard_gestao_gente where status = 'A' ");
				$lTt = mysqli_fetch_assoc($qTt);

				// Total Estagiarios
				$qAg = mysqli_query($bd, "SELECT sum(qntd_estagiarios) as totalEst FROM tb_dashboard_gestao_gente where status = 'A' ");
				$lAg = mysqli_fetch_assoc($qAg);

				// Total Clt
				$qCl = mysqli_query($bd, "SELECT sum(qntd_clt) as totalClt FROM tb_dashboard_gestao_gente where status = 'A' ");
				$lCl = mysqli_fetch_assoc($qCl);

				// Total Colaboradores
				$totalColab = $lTt['totalAdv'] + $lAg['totalEst'] + $lCl['totalClt'];
				?>



				<?php // Total Advogados
				$qvTt = mysqli_query($bd, "SELECT sum(vagas_abertas_adv) as totaVagalAdv FROM tb_dashboard_gestao_gente where status = 'A' ");
				$lvTt = mysqli_fetch_assoc($qvTt);

				// Total Estagiarios
				$qvAg = mysqli_query($bd, "SELECT sum(vagas_abertas_estagio) as totaVagalEst FROM tb_dashboard_gestao_gente where status = 'A' ");
				$lvAg = mysqli_fetch_assoc($qvAg);

				// Total Clt
				$qvCl = mysqli_query($bd, "SELECT sum(vagas_abertas_clt) as totaVagalClt FROM tb_dashboard_gestao_gente where status = 'A' ");
				$lvCl = mysqli_fetch_assoc($qvCl);

				// Total Colaboradores
				$totaVagalColab = $lvTt['totaVagalAdv'] + $lvAg['totaVagalEst'] + $lvCl['totaVagalClt'];
				?>


				<?php $qB = mysqli_query($bd, "SELECT count(id) as totalRegistros FROM tb_dashboard_gestao_gente where status = 'A' ");
				$lB = mysqli_fetch_assoc($qB);?>


				<?php

				$botaoDisabled = "";

				if($lB['totalRegistros'] > 0){
					$botaoDisabled = "";
				} else {
					$botaoDisabled = "disabled='disabled'";
				}
				?>


				<div class="btn-group">
                    <button type="button" <?=$botaoDisabled?> class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                      <i class="fa fa-users"></i> Visualizar
                      <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                      <li><a href="#" onclick="mostraQuantitativo()"><i class="fa fa-users"></i> Quantitativos - Geral</a></li>
                      <li><a href="#" onclick="mostraGrafico()"><i class="fa fa-chart-pie"></i> Gráficos - Bases</a></li>
                      <li><a href="#" onclick="mostraGraficoEquipe()"><i class="fa fa-chart-pie"></i> Gráficos - Equipes</a></li>
                      <li><a href="#" onclick="mostraLista()"><i class="fa fa-list-alt"></i> Lista - Geral</a></li>
                    </ul>
                </div>

                <a id="btnPrint" class="btn btn-success" target="_Blank" <?=$botaoDisabled?>><i class="fa fa-print"></i> Imprimir</a>

				<hr/>


				<!-- ============================ QUANTITATIVOS ============================ -->
				<div class="panel panel-container" id="quantitativos" style="display: none;">
					<div class="row">
						<div class="col-xs-3 col-md-3 col-lg-3 no-padding">
							<div class="panel panel-teal panel-widget border-right">
								<div class="row no-padding"><em class="fa fa-3x fa-users text-primary"></em>
									<div class="large"><?=$totalColab?></div>
									<div class="text-muted">Colaboradores</div>
								</div>
							</div>
						</div>
						<div class="col-xs-3 col-md-3 col-lg-3 no-padding">
							<div class="panel panel-blue panel-widget border-right">
								<div class="row no-padding"><em class="fa fa-3x fa-users text-success"></em>
									<div class="large"><?=$lTt['totalAdv']?></div>
									<div class="text-muted">Advogados</div>
								</div>
							</div>
						</div>
						<div class="col-xs-3 col-md-3 col-lg-3 no-padding">
							<div class="panel panel-orange panel-widget border-right">
								<div class="row no-padding"><em class="fa fa-3x fa-users text-warning"></em>
									<div class="large"><?=$lAg['totalEst']?></div>
									<div class="text-muted">Estagiários</div>
								</div>
							</div>
						</div>
						<div class="col-xs-3 col-md-3 col-lg-3 no-padding">
							<div class="panel panel-orange panel-widget border-right">
								<div class="row no-padding"><em class="fa fa-3x fa-users text-info"></em>
									<div class="large"><?=$lCl['totalClt']?></div>
									<div class="text-muted">CLT</div>
								</div>
							</div>
						</div>
					</div><!--/.row-->

					<hr/>

					<div class="row">
						<div class="col-xs-3 col-md-3 col-lg-3 no-padding">
							<div class="panel panel-teal panel-widget border-right">
								<div class="row no-padding"><em class="fa fa-3x fa-clipboard text-primary"></em>
									<div class="large"><?=$totaVagalColab?></div>
									<div class="text-muted">Vagas Abertas</div>
								</div>
							</div>
						</div>
						<div class="col-xs-3 col-md-3 col-lg-3 no-padding">
							<div class="panel panel-blue panel-widget border-right">
								<div class="row no-padding"><em class="fa fa-3x fa-clipboard text-success"></em>
									<div class="large"><?=$lvTt['totaVagalAdv']?></div>
									<div class="text-muted">Vagas Advogados</div>
								</div>
							</div>
						</div>
						<div class="col-xs-3 col-md-3 col-lg-3 no-padding">
							<div class="panel panel-orange panel-widget border-right">
								<div class="row no-padding"><em class="fa fa-3x fa-clipboard text-warning"></em>
									<div class="large"><?=$lvAg['totaVagalEst']?></div>
									<div class="text-muted">Vagas Estagiários</div>
								</div>
							</div>
						</div>
						<div class="col-xs-3 col-md-3 col-lg-3 no-padding">
							<div class="panel panel-orange panel-widget border-right">
								<div class="row no-padding"><em class="fa fa-3x fa-clipboard text-info"></em>
									<div class="large"><?=$lvCl['totaVagalClt']?></div>
									<div class="text-muted">Vagas CLT</div>
								</div>
							</div>
						</div>
					</div><!--/.row-->

				</div>


				<!-- ============================ GRAFICOS ============================ -->
				<div class="panel panel-container" id="graficos" style="display: none;">
					<div class="panel-body">

						<div class="col-xs-6 col-md-6 col-lg-6 border-right">

                            <h3 class="text-primary">
                                <strong>Colaboradores</strong> por Base (<?=$totalColab?>)
                            </h3>
                            <hr/>
                            <?php
                            $qUnid = mysqli_query($bd, "SELECT distinct base FROM tb_dashboard_gestao_gente where status = 'A'");
                            while ($rsUnid = mysqli_fetch_array($qUnid)){
                                $qUnidDet = mysqli_query($bd, "SELECT sum(qntd_advogados + qntd_estagiarios + qntd_clt) as totalCola FROM tb_dashboard_gestao_gente where base = '".$rsUnid['base']."' and status = 'A' ");
                                $lUnidDet = mysqli_fetch_assoc($qUnidDet);

                                // === Adv | Estagiario | CLT
                                $qUnidAdv = mysqli_query($bd, "SELECT sum(qntd_advogados) as totalUnidAdv FROM tb_dashboard_gestao_gente where base = '".$rsUnid['base']."' and status = 'A' ");
                                $lUnidAdv = mysqli_fetch_assoc($qUnidAdv);

                                $qUnidEst = mysqli_query($bd, "SELECT sum(qntd_estagiarios) as totalUnidEst FROM tb_dashboard_gestao_gente where base = '".$rsUnid['base']."' and status = 'A' ");
                                $lUnidEst = mysqli_fetch_assoc($qUnidEst);

                                $qUnidClt = mysqli_query($bd, "SELECT sum(qntd_clt) as totalUnidClt FROM tb_dashboard_gestao_gente where base = '".$rsUnid['base']."' and status = 'A' ");
                                $lUnidClt = mysqli_fetch_assoc($qUnidClt);
                                // === Fim

                                // Percentual
                                $percentual = (100*$lUnidDet['totalCola'])/$totalColab;
                                $percentual = round($percentual, 2); ?>

                                <div class="text-primary">

                                    <strong>
                                        <a href="javascript:;" onclick="modalInfoColab(<?=$lUnidAdv['totalUnidAdv']?>, <?=$lUnidEst['totalUnidEst']?>, <?=$lUnidClt['totalUnidClt']?>)"><?=$rsUnid['base']?></a>
                                    </strong>: <?=$lUnidDet['totalCola']?>

                                    <div class="alert-info" style="width: <?=$percentual?>%">
                                        <strong><?=$percentual?>%</strong>
                                    </div>
                                    <hr/>

                                </div>

                            <?php } ?>
                        </div>
                        <div class="col-xs-6 col-md-6 col-lg-6">

                            <h3 class="text-success">
                                <strong>Vagas Abertas</strong> por Base (<?=$totaVagalColab?>)
                            </h3>
                            <hr/>
                            <?php
                            $qUnid = mysqli_query($bd, "SELECT distinct base FROM tb_dashboard_gestao_gente where status = 'A'");
                            while ($rsUnid = mysqli_fetch_array($qUnid)){

                                $qUnidDet = mysqli_query($bd, "SELECT sum(vagas_abertas_adv + vagas_abertas_estagio + vagas_abertas_clt) as totalVagas FROM tb_dashboard_gestao_gente where base = '".$rsUnid['base']."' and status = 'A' ");
                                $lUnidDet = mysqli_fetch_assoc($qUnidDet);

                                // === Adv | Estagiario | CLT
                                $qUnidAdv = mysqli_query($bd, "SELECT sum(vagas_abertas_adv) as totalVagasUnidAdv FROM tb_dashboard_gestao_gente where base = '".$rsUnid['base']."' and status = 'A' ");
                                $lUnidAdv = mysqli_fetch_assoc($qUnidAdv);

                                $qUnidEst = mysqli_query($bd, "SELECT sum(vagas_abertas_estagio) as totalVagasUnidEst FROM tb_dashboard_gestao_gente where base = '".$rsUnid['base']."' and status = 'A' ");
                                $lUnidEst = mysqli_fetch_assoc($qUnidEst);

                                $qUnidClt = mysqli_query($bd, "SELECT sum(vagas_abertas_clt) as totalVagasUnidClt FROM tb_dashboard_gestao_gente where base = '".$rsUnid['base']."' and status = 'A' ");
                                $lUnidClt = mysqli_fetch_assoc($qUnidClt);
                                // === Fim

                                // Percentual
                                $percentual = (100*$lUnidDet['totalVagas'])/$totaVagalColab;
                                $percentual = round($percentual, 2); ?>

                                <div class="text-success">

                                    <strong>
                                        <a class="text-success" href="javascript:;" onclick="modalInfoVagas(<?=$lUnidAdv['totalVagasUnidAdv']?>, <?=$lUnidEst['totalVagasUnidEst']?>, <?=$lUnidClt['totalVagasUnidClt']?>)"><?=$rsUnid['base']?></a>
                                    </strong>: <?=$lUnidDet['totalVagas']?>

                                    <div class="alert-success" style="width: <?=$percentual?>%">
                                        <strong><?=$percentual?>%</strong>
                                    </div>
                                    <hr/>

                                </div>

                            <?php } ?>
                        </div>

                    </div>

					<hr/>
				</div>


				<div class="panel panel-container" id="graficosEquipe" style="display: none;">
					<div class="panel-body">

						<div class="col-xs-6 col-md-6 col-lg-6 border-right">
                            <h3 class="text-primary">
                                <strong>Colaboradores</strong> por Equipe (<?=$totalColab?>)
                            </h3>
                            <hr/>
                            <?php
                            $qUnid = mysqli_query($bd, "SELECT distinct equipe FROM tb_dashboard_gestao_gente where status = 'A' ");
                            while ($rsUnid = mysqli_fetch_array($qUnid)){

                                $qUnidDet = mysqli_query($bd, "SELECT sum(qntd_advogados + qntd_estagiarios + qntd_clt) as totalCola FROM tb_dashboard_gestao_gente where equipe = '".$rsUnid['equipe']."' and status = 'A' ");
                                $lUnidDet = mysqli_fetch_assoc($qUnidDet);

                                // === Adv | Estagiario | CLT
                                $qUnidAdv = mysqli_query($bd, "SELECT sum(qntd_advogados) as totalUnidAdv FROM tb_dashboard_gestao_gente where equipe = '".$rsUnid['equipe']."' and status = 'A' ");
                                $lUnidAdv = mysqli_fetch_assoc($qUnidAdv);

                                $qUnidEst = mysqli_query($bd, "SELECT sum(qntd_estagiarios) as totalUnidEst FROM tb_dashboard_gestao_gente where equipe = '".$rsUnid['equipe']."' and status = 'A' ");
                                $lUnidEst = mysqli_fetch_assoc($qUnidEst);

                                $qUnidClt = mysqli_query($bd, "SELECT sum(qntd_clt) as totalUnidClt FROM tb_dashboard_gestao_gente where equipe = '".$rsUnid['equipe']."' and status = 'A' ");
                                $lUnidClt = mysqli_fetch_assoc($qUnidClt);
                                // === Fim

                                $q = mysqli_query($bd, "SELECT base FROM tb_dashboard_gestao_gente where equipe = '".$rsUnid['equipe']."' and status = 'A' ");
                                $rs = mysqli_fetch_assoc($q);

                                // Percentual
                                $percentual = (100*$lUnidDet['totalCola'])/$totalColab;
                                $percentual = round($percentual, 2); ?>

                                <div class="text-primary">

                                    <strong>
                                        <a href="javascript:;" onclick="modalInfoColab(<?=$lUnidAdv['totalUnidAdv']?>, <?=$lUnidEst['totalUnidEst']?>, <?=$lUnidClt['totalUnidClt']?>)">
                                            <?=$rsUnid['equipe']?> (<small><?=$rs['base']?></small>)
                                        </a>
                                    </strong>: <?=$lUnidDet['totalCola']?>

                                    <div class="alert-info" style="width: <?=$percentual?>%">
                                        <strong><?=$percentual?>%</strong>
                                    </div><hr/>

                                </div>

                            <?php } ?>
						</div>
						<div class="col-xs-6 col-md-6 col-lg-6">
                            <h3 class="text-success">
                                <strong>Vagas Abertas</strong> por Equipe (<?=$totaVagalColab?>)
                            </h3>
                            <hr/>
                            <?php
                            $qUnid = mysqli_query($bd, "SELECT distinct equipe FROM tb_dashboard_gestao_gente where status = 'A' ");
                            while ($rsUnid = mysqli_fetch_array($qUnid)){

                                $qUnidDet = mysqli_query($bd, "SELECT sum(vagas_abertas_adv + vagas_abertas_estagio + vagas_abertas_clt) as totalVagas FROM tb_dashboard_gestao_gente where equipe = '".$rsUnid['equipe']."' and status = 'A' ");
                                $lUnidDet = mysqli_fetch_assoc($qUnidDet);

                                // === Adv | Estagiario | CLT
                                $qUnidAdv = mysqli_query($bd, "SELECT sum(vagas_abertas_adv) as totalVagasUnidAdv FROM tb_dashboard_gestao_gente where equipe = '".$rsUnid['equipe']."' and status = 'A' ");
                                $lUnidAdv = mysqli_fetch_assoc($qUnidAdv);

                                $qUnidEst = mysqli_query($bd, "SELECT sum(vagas_abertas_estagio) as totalVagasUnidEst FROM tb_dashboard_gestao_gente where equipe = '".$rsUnid['equipe']."' and status = 'A' ");
                                $lUnidEst = mysqli_fetch_assoc($qUnidEst);

                                $qUnidClt = mysqli_query($bd, "SELECT sum(vagas_abertas_clt) as totalVagasUnidClt FROM tb_dashboard_gestao_gente where equipe = '".$rsUnid['equipe']."' and status = 'A' ");
                                $lUnidClt = mysqli_fetch_assoc($qUnidClt);
                                // === Fim

                                $q = mysqli_query($bd, "SELECT base FROM tb_dashboard_gestao_gente where equipe = '".$rsUnid['equipe']."' and status = 'A' ");
                                $rs = mysqli_fetch_assoc($q);

                                // Percentual
                                $percentual = (100*$lUnidDet['totalVagas'])/$totaVagalColab;
                                $percentual = round($percentual, 2); ?>

                                <div class="text-success">

                                    <strong>
                                        <a class="text-success" href="javascript:;" onclick="modalInfoVagas(<?=$lUnidAdv['totalVagasUnidAdv']?>, <?=$lUnidEst['totalVagasUnidEst']?>, <?=$lUnidClt['totalVagasUnidClt']?>)">
                                            <?=$rsUnid['equipe']?>  (<small><?=$rs['base']?></small>)
                                        </a>
                                    </strong>: <?=$lUnidDet['totalVagas']?>

                                    <div class="alert-success" style="width: <?=$percentual?>%">
                                        <strong><?=$percentual?>%</strong>
                                    </div>
                                    <hr/>

                                </div>

                            <?php } ?>
						</div>

					</div>

                    <hr/>
				</div>


				<!-- ============================ LISTA ============================== -->
				<div class="panel panel-container" id="lista" style="display: none;">
					<div class="panel-body">
						<div class="col-xs-12 col-md-12 col-lg-12">
                            <!-- CONTENT -->
                            <?php $q=  mysqli_query($bd, "SELECT * FROM tb_dashboard_gestao_gente where status = 'A' order by id asc "); ?>

                            <div class="table-responsive">
                                <table class="table" id="tabela-importada">
                                    <thead class="alert-info">
                                        <tr>
                                            <th>ID</th>
                                            <th>Base</th>
                                            <th>Equipe</th>
                                            <th>Advogados</th>
                                            <th>Estagiários</th>
                                            <th>CLT</th>
                                            <th>Vagas Advogados</th>
                                            <th>Vagas Estagiários</th>
                                            <th>Vagas CLT</th>
                                            <th>Justificativa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $num_rows = mysqli_num_rows($q);
                                        if ($num_rows == 0){
                                        ?>
                                            <tr id="tr-<?=$rs['id']?>">
                                                <td scope="row" colspan="10" align="center">
                                                    <i class="fa fa-exclamation-triangle"></i> Nenhum Registro encontrado no sistema!
                                                </td>
                                            </tr>
                                        <?php }

                                        while ($rs = mysqli_fetch_array($q)){

                                        ?>
                                            <tr id="tr-<?=$rs['id']?>">
                                                <td><?=$rs['id']?></td>
                                                <td><?=$rs['base']?></td>
                                                <td><?=$rs['equipe']?></td>
                                                <td><?=$rs['qntd_advogados']?></td>
                                                <td><?=$rs['qntd_estagiarios']?></td>
                                                <td><?=$rs['qntd_clt']?></td>
                                                <td><?=$rs['vagas_abertas_adv']?></td>
                                                <td><?=$rs['vagas_abertas_estagio']?></td>
                                                <td><?=$rs['vagas_abertas_clt']?></td>
                                                <td><?=$rs['justificativas']?></td>
                                            </tr>

                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                            <hr/>

                            <?php

                            $qAr = mysqli_query($bd, "SELECT arquivo_enviado FROM tb_dashboard_gestao_gente where status = 'A' order by id limit 0,1 ");
                            $lAr = mysqli_fetch_assoc($qAr);

                            ?>

                            <?php
                            if ($num_rows > 0){
                            ?>
                                <div align="left">
                                    <a href="../arquivos_gente_gestao/<?=$lAr['arquivo_enviado']?>" class="btn btn-success"><i class="fa fa-download"></i> Baixar arquivo original</a>
                                </div>
                            <?php } else { ?>
                                <div align="left">
                                    <buttom class="btn btn-success" disabled="disabled"><i class="fa fa-download"></i> Baixar arquivo original</buttom>
                                </div>
                            <?php } ?>

                            </div>
                            <!-- FIM CONTENT -->

                        </div>
					</div><!--/.panel-body-->

					<hr/>
				</div>


			</div>
		</div><!--/.row-->


		<div class="row">

			<?php include "../rodape.php";
            mysqli_close($bd);
			?>

		</div><!--/.row-->
	</div>	<!--/.main-->


	<!-- Modal Importar Arquivo -->
	<div id="modalImportFile" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">

	      <div class="modal-header alert-info">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i></span>
	        </button>
	        <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-upload"></i> Importar Arquivo</h4>
	      </div>
	      <div class="modal-body">
	        <p id="textoLogoff">Selecione o arquivo</p>

	        <form role="form" id="formImportFile" action="gestaoGenteImportacaoAction.php" method="post" enctype="multipart/form-data">
				<input type="file" id="arquivo" name="arquivo" class="form-control">
				<br/><br/>
				<button type="submit" class="btn btn-primary" onclick="javascript:$('#carregandoEnvio').show(500);"><i class="fa fa-check-circle"></i> Enviar</button>
	          	<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Voltar</button>
	        </form>

	        <p align="center" class="text-primary" id="carregandoEnvio" style="display: none;"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i> Aguarde...</p>
	      </div>

	    </div>
	  </div>
	</div>
	<!-- Fim Modal Confirmacao Sair -->


	<!-- Modal Importar Arquivo -->
	<div id="modalInfo" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">

	      <div class="modal-header alert-info">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i></span>
	        </button>
	        <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-info"></i> Informações</h4>
	      </div>
	      <div class="modal-body">
	        <p id="textoLogoff">Dados quantitativos</p>

	        <span id="qntdAdv"></span><br/>
	        <span id="qntdEst"></span><br/>
	        <span id="qntdClt"></span>

	      </div>
	      <div class="modal-footer">
          <button type="button" class="btn btn-default" onClick="javascript:$('#textoLogoff').show(); $('#carregandoLogoff').hide();" data-dismiss="modal">
          <i class="fa fa-times"></i> Voltar</button>
      </div>

	    </div>
	  </div>
	</div>
	<!-- Fim Modal Confirmacao Sair -->

    <?php include 'scripts.php' ?>
	<script src="../js/custom.js"></script>
	<script src="../js/chart.min.js"></script>
	<script src="../js/easypiechart.js"></script>
	<script src="../js/admin.js"></script>


	<!-- DATATABLES -->
	<script type="text/javascript" src="../dts/js/jquery.dataTables.js"></script>
	<!-- FIM DATATABLES -->


	<script type="text/javascript">

		$(document).ready(function() {

			$('#tabela-importada').DataTable( {
                "order": [[ 0, "asc" ]],
                "columnDefs": [
                    { "orderable": false, targets: 9 }
                ]
		    } );

		});

		function mostraQuantitativo(){
			$("#graficosEquipe").hide();
			$("#graficos").hide();
			$("#lista").hide();
			$("#quantitativos").show(500);

			$("#btnPrint").removeAttr("href");
			$("#btnPrint").attr("href","gestaoGenteDashBoardPrint.php?tipoPrint=quantitativos");
		}

		function mostraGrafico(){
			$("#graficosEquipe").hide();
			$("#quantitativos").hide();
			$("#lista").hide();
			$("#graficos").show(500);

			$("#btnPrint").removeAttr("href");
			$("#btnPrint").attr("href","gestaoGenteDashBoardPrint.php?tipoPrint=graficos");
		}

		function mostraGraficoEquipe(){
			$("#lista").hide();
			$("#graficos").hide();
			$("#quantitativos").hide();
			$("#graficosEquipe").show(500);

			$("#btnPrint").removeAttr("href");
			$("#btnPrint").attr("href","gestaoGenteDashBoardPrint.php?tipoPrint=graficosEquipe");
		}

		function mostraLista(){
			$("#graficosEquipe").hide();
			$("#graficos").hide();
			$("#quantitativos").hide();
			$("#lista").show(500);

			$("#btnPrint").removeAttr("href");
			$("#btnPrint").attr("href","gestaoGenteDashBoardPrint.php?tipoPrint=lista");
		}

		function round(value, precision) {
		    var multiplier = Math.pow(10, precision || 0);
		    return Math.round(value * multiplier) / multiplier;
		}

		function modalInfoColab(qntdAdv, qntdEst, qntdClt){

			var total = qntdAdv + qntdEst + qntdClt;
			var percentualAdv = (100*qntdAdv)/total;
			var percentualEst = (100*qntdEst)/total;
			var percentualClt = (100*qntdClt)/total;

			if (qntdAdv == 0 && qntdEst == 0 && qntdClt == 0){

				//$("#modalInfo").modal();

			} else {
				$("#qntdAdv").html("<strong>Advogados</strong>: <strong class='text-info'>"+qntdAdv+"</strong> <div class='alert-info' style='width:"+round(percentualAdv, 2)+"%;'><strong>"+round(percentualAdv, 2)+"%</strong></div>");
				$("#qntdEst").html("<strong>Estagiarios</strong>: <strong class='text-info'>"+qntdEst+"</strong> <div class='alert-info' style='width:"+round(percentualEst, 2)+"%;'><strong>"+round(percentualEst, 2)+"%</strong></div>");
				$("#qntdClt").html("<strong>CLT</strong>: <strong class='text-info'>"+qntdClt+"</strong> <div class='alert-info' style='width:"+round(percentualClt, 2)+"%;'><strong>"+round(percentualClt, 2)+"%</strong></div>");
				$("#modalInfo").modal();
			}

		}

		function modalInfoVagas(qntdAdv, qntdEst, qntdClt){

			var total = qntdAdv + qntdEst + qntdClt;
			var percentualAdv = (100*qntdAdv)/total;
			var percentualEst = (100*qntdEst)/total;
			var percentualClt = (100*qntdClt)/total;

			if (qntdAdv == 0 && qntdEst == 0 && qntdClt == 0){

				//$("#modalInfo").modal();

			} else {

				$("#qntdAdv").html("<strong>Advogados</strong>: <strong class='text-success'>"+qntdAdv+"</strong> <div class='alert-success' style='width:"+round(percentualAdv, 2)+"%;'><strong>"+round(percentualAdv, 2)+"%</strong></div>");
				$("#qntdEst").html("<strong>Estagiarios</strong>: <strong class='text-success'>"+qntdEst+"</strong> <div class='alert-success' style='width:"+round(percentualEst, 2)+"%;'><strong>"+round(percentualEst, 2)+"%</strong></div>");
				$("#qntdClt").html("<strong>CLT</strong>: <strong class='text-success'>"+qntdClt+"</strong> <div class='alert-success' style='width:"+round(percentualClt, 2)+"%;'><strong>"+round(percentualClt, 2)+"%</strong></div>");
				$("#modalInfo").modal();
			}

		}

	</script>


</body>
</html>
