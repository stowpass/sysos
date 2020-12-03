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
	<!--Custom Font-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i">
</head>
<body style="padding-top: 0px;">

	<div class="alert alert-info" style="background-color: #30a5ff; color: #fff;" align="center">
		<h4>Painéis - Gestão de Gente</strong></h4>
		<small><small>ROCHA, MARINHO E SALES ADVOGADOS</small></small>
	</div>

	<div class="col-sm-12 main">

		<div align="center" style="padding-bottom: 20px;" id="botaoPrint">
			<button onclick="printPage()" class="btn btn-success"><i class="fa fa-print"></i> Imprimir Painel</button>&nbsp;&nbsp;&nbsp;&nbsp;
			<button onclick="fecharAba()" class="btn btn-default"><i class="fa fa-times-circle"></i> Fechar</button>
		</div>


		<div class="row">


			<div class="col-lg-12">

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


				<?php if (isset($_GET['tipoPrint']) && $_GET['tipoPrint'] == 'quantitativos'){ ?>
				<!-- ============================ QUANTITATIVOS ============================ -->
				<div class="panel panel-container" id="quantitativos">

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
					</div>

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
				<?php } ?>


				<?php if (isset($_GET['tipoPrint']) && $_GET['tipoPrint'] == 'graficos'){ ?>
				<!-- ============================ GRAFICOS ============================ -->
				<div class="panel panel-container" id="graficos">

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

                                    <strong><?=$rsUnid['base']?></strong>: <?=$lUnidDet['totalCola']?>

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

                                    <strong><?=$rsUnid['base']?></strong>: <?=$lUnidDet['totalVagas']?>

                                    <div class="alert-success" style="width: <?=$percentual?>%">
                                        <strong><?=$percentual?>%</strong>
                                    </div>
                                    <hr/>

                                </div>

                            <?php } ?>
						</div>
					</div><!--/.panel-body-->

				</div>
				<?php } ?>


				<?php if (isset($_GET['tipoPrint']) && $_GET['tipoPrint'] == 'graficosEquipe'){ ?>
				<div class="panel panel-container" id="graficosEquipe">

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

                                    <strong><small><?=$rs['base']?></small></strong>: <?=$lUnidDet['totalCola']?>

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

                                    <strong><?=$rsUnid['equipe']?>  (<small><?=$rs['base']?></small>)</strong>: <?=$lUnidDet['totalVagas']?>

                                    <div class="alert-success" style="width: <?=$percentual?>%">
                                        <strong><?=$percentual?>%</strong>
                                    </div>
                                    <hr/>

                                </div>

                            <?php } ?>
						</div>
					</div><!--/.panel-body-->

				</div>
				<?php } ?>


				<?php if (isset($_GET['tipoPrint']) && $_GET['tipoPrint'] == 'lista'){ ?>
				<!-- ============================ LISTA ============================== -->
				<div class="panel panel-container" id="lista">

					<h2 align="center">Lista Importada</h2>

					<div class="row">
						<div class="col-xs-12 col-md-12 col-lg-12 no-padding">
							<div class="panel panel-teal panel-widget border-right">
								<div class="row no-padding" style="padding: 20px;">
									<!-- CONTENT -->
									<?php $q=  mysqli_query($bd, "SELECT * FROM tb_dashboard_gestao_gente where status = 'A' order by id asc "); ?>
									<div class="col-md-12">

										<table class="table table-responsive">
											<thead class="alert-info">
												<tr>
													<th>Base</th>
													<th>Equipe</th>
													<th>Advogados</th>
													<th>Estagiários</th>
													<th>CLT</th>
													<th>vagas Advogados</th>
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
											    <?php } ?>
												<?php while ($rs = mysqli_fetch_array($q)){

												?>
											    <tr id="tr-<?=$rs['id']?>">
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
									<!-- FIM CONTENT -->

								</div>

							</div>
						</div>
					</div><!--/.row-->

				</div>
				<?php } ?>


			</div>
		</div><!--/.row-->


		<div class="row">

            <?php include '../rodape.php' ?>

		</div><!--/.row-->
	</div>	<!--/.main-->

    <?php include 'scripts.php' ?>
	<script src="../js/custom.js"></script>
	<script src="../js/chart.min.js"></script>
	<script src="../js/easypiechart.js"></script>

	<script type="text/javascript">

		$(document).ready(function() {

		    //self.print();self.close();

		});

		function printPage(){
			$("#botaoPrint").hide();
			self.print();self.close();
		}

		function fecharAba(){
			var tab = window.open(window.location,"_top");
  			tab.close();
		}

	</script>


</body>
</html>
