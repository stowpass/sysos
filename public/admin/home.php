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
	<!--Custom Font-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i">
</head>
<body style="padding-top: 0px;">

	<?php

	// Para acessar apenas os dados do escritório - quantitativo vagas e colaboradores
	if ($_SESSION["id"] == 5 || $_SESSION["id"] == 7 || $_SESSION["id"] == 9 || $_SESSION["id"] == 10 || $_SESSION["id"] == 11){
		redirect("gestaoGenteDashboard.php");
	}
	?>

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
						<div class="col-md-12">
							<h1 class="page-header">
								<i class="fa fa-chart-bar color-blue"></i> Dashboard</strong>
							</h1>
						</div>
					</div>
				</div><!-- /.panel-->

				<?php // Total Solicitacoes
				$qTt = mysqli_query($bd, "SELECT count(id) as totalTt FROM tb_solicitar_contratacao where status = 'A' ");
				$lTt = mysqli_fetch_assoc($qTt);

				// Total Processos
				$qAg = mysqli_query($bd, "SELECT count(id) as totalAg FROM tb_processo_seletivo where status = 'A' ");
				$lAg = mysqli_fetch_assoc($qAg);

				// Total Candidatos
				$qCl = mysqli_query($bd, "SELECT count(id) as totalCandidatos FROM tb_candidato where status = 'A' and trabalhe_conosco != 'S' ");
				$lCl = mysqli_fetch_assoc($qCl);

				// Total Candidatos
				$qTb = mysqli_query($bd, "SELECT count(id) as totalTrabalheConosco FROM tb_candidato where status = 'A' and trabalhe_conosco = 'S' ");
				$lTb = mysqli_fetch_assoc($qTb);?>

				<div class="panel panel-container">
					<div class="row">
						<div class="col-xs-3 col-md-4 col-lg-3 no-padding">
							<div class="panel panel-blue panel-widget border-right">
								<div class="row no-padding"><em class="fa fa-3x fa-book color-blue"></em>
									<div class="large"><?=$lAg['totalAg']?></div>
									<div class="text-muted">Processos Seletivos</div>
								</div>
							</div>
						</div>
						<div class="col-xs-3 col-md-4 col-lg-3 no-padding">
							<div class="panel panel-orange panel-widget border-right">
								<div class="row no-padding"><em class="fa fa-3x fa-user color-blue"></em>
									<div class="large"><?=$lCl['totalCandidatos']?></div>
									<div class="text-muted">Candidatos</div>
								</div>
							</div>
						</div>
						<div class="col-xs-3 col-md-4 col-lg-3 no-padding">
							<div class="panel panel-orange panel-widget border-right">
								<div class="row no-padding"><em class="fa fa-3x fa-briefcase color-blue"></em>
									<div class="large"><?=$lTb['totalTrabalheConosco']?></div>
									<div class="text-muted">Trabalhe Conosco</div>
								</div>
							</div>
						</div>
						<div class="col-xs-3 col-md-4 col-lg-3 no-padding">
							<div class="panel panel-teal panel-widget border-right">
								<div class="row no-padding"><em class="fa fa-3x fa-clipboard color-blue"></em>
									<div class="large"><?=$lTt['totalTt']?></div>
									<div class="text-muted">Solicita&ccedil;&otilde;es de Contrata&ccedil;&otilde;es</div>
								</div>
							</div>
						</div>
					</div><!--/.row-->
				</div>

				<div class="panel panel-container" id="divsCandidatos" style="display:block;">
					<div class="row">
						<div class="col-xs-12 col-md-12 col-lg-12 no-padding">
							<div class="panel panel-blue panel-widget border-right">
								<div class="row no-padding">
									<h4>Gráficos - <strong>CANDIDATOS</strong></h4>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6 col-md-4 col-lg-3 no-padding">
							<div class="panel panel-blue panel-widget border-right">
                                <div class="text-muted" style="margin-bottom: 1rem">Estado Civil</div>
                                <div class="canvas-wrapper">
                                    <canvas class="chart" id="graph1" ></canvas>
                                </div>
							</div>
						</div>
						<div class="col-xs-6 col-md-4 col-lg-3 no-padding">
							<div class="panel panel-orange panel-widget border-right">
                                <div class="text-muted" style="margin-bottom: 1rem">Vaga Pleiteada</div>
                                <div class="canvas-wrapper">
                                    <canvas class="chart" id="graph2" ></canvas>
                                </div>
							</div>
						</div>
						<div class="col-xs-6 col-md-4 col-lg-3 no-padding">
							<div class="panel panel-orange panel-widget border-right">
                                <div class="text-muted" style="margin-bottom: 1rem">Área de Atuação</div>
                                <div class="canvas-wrapper">
                                    <canvas class="chart" id="graph3" ></canvas>
                                </div>
							</div>
						</div>
						<div class="col-xs-6 col-md-4 col-lg-3 no-padding">
							<div class="panel panel-teal panel-widget">
                                <div class="text-muted" style="margin-bottom: 1rem">Índice de Aprovação</div>
                                <div class="canvas-wrapper">
                                    <canvas class="chart" id="graph4" ></canvas>
                                </div>
							</div>
						</div>
					</div><!--/.row-->
				</div>

			</div>
		</div><!--/.row-->

		<!-- ====================================== GRAFICOS ==============================-->
		<?php // Total Casado(a)
		$qCs = mysqli_query($bd, "SELECT count(id) as totalCasados FROM tb_candidato where estado_civil = 'Casado(a)' and status = 'A' and trabalhe_conosco != 'S' ");
		$lCs = mysqli_fetch_assoc($qCs);

		// Total Solteiro(a)
		$qSl = mysqli_query($bd, "SELECT count(id) as totalSolteiros FROM tb_candidato where estado_civil = 'Solteiro(a)' and status = 'A' and trabalhe_conosco != 'S' ");
		$lSl = mysqli_fetch_assoc($qSl);

		// Total Separado(a)
		$qSp = mysqli_query($bd, "SELECT count(id) as totalSeperados FROM tb_candidato where estado_civil = 'Separado(a)' and status = 'A' and trabalhe_conosco != 'S' ");
		$lSp = mysqli_fetch_assoc($qSp);

		// Total Divorciado(a)
		$qDv = mysqli_query($bd, "SELECT count(id) as totalDivorciados FROM tb_candidato where estado_civil = 'Divorciado(a)' and status = 'A' and trabalhe_conosco != 'S' ");
		$lDv = mysqli_fetch_assoc($qDv);

		// Total Viuvo(a)
		$qVv = mysqli_query($bd, "SELECT count(id) as totalViuvos FROM tb_candidato where estado_civil = 'Viuvo(a)' and status = 'A' and trabalhe_conosco != 'S' ");
		$lVv = mysqli_fetch_assoc($qVv);?>


		<?php // Total Civil
		$qCv = mysqli_query($bd, "SELECT count(id) as totalCivil FROM tb_candidato where area_atuacao = 'Civil' and status = 'A' and trabalhe_conosco != 'S' ");
		$lCv = mysqli_fetch_assoc($qCv);

		// Total Trabalhista
		$qTb = mysqli_query($bd, "SELECT count(id) as totalTrabalhista FROM tb_candidato where area_atuacao = 'Trabalhista' and status = 'A' and trabalhe_conosco != 'S' ");
		$lTb = mysqli_fetch_assoc($qTb);

		// Total Ambas
		$qAb = mysqli_query($bd, "SELECT count(id) as totalAmbas FROM tb_candidato where area_atuacao = 'Ambas' and status = 'A' and trabalhe_conosco != 'S' ");
		$lAb = mysqli_fetch_assoc($qAb);?>


		<?php // Total Adv
		$qAdv = mysqli_query($bd, "SELECT count(id) as totalAdv FROM tb_candidato where cargo_id = '3' and status = 'A' and trabalhe_conosco != 'S' ");
		$lAdv = mysqli_fetch_assoc($qAdv);

		// Total Estagiario
		$qEs = mysqli_query($bd, "SELECT count(id) as totalEstagiarios FROM tb_candidato where cargo_id = '5' and status = 'A' and trabalhe_conosco != 'S' ");
		$lEs = mysqli_fetch_assoc($qEs);

		// Total Outros
		$qOu = mysqli_query($bd, "SELECT count(id) as total FROM tb_candidato where status = 'A' and trabalhe_conosco != 'S' ");
		$lOu = mysqli_fetch_assoc($qOu);

		$totalOutros = $lOu['total'] - $lEs['totalEstagiarios'] - $lAdv['totalAdv'];?>



		<?php // Total Aprovado
		$qApr = mysqli_query($bd, "SELECT count(id) as totalAprovados FROM tb_candidato where aprovado = 'S' and status = 'A' and trabalhe_conosco != 'S' ");
		$lApr = mysqli_fetch_assoc($qApr);

		// Total Reprovado
		$qRep = mysqli_query($bd, "SELECT count(id) as totalReprovados FROM tb_candidato where aprovado = 'N' and status = 'A' and trabalhe_conosco != 'S' ");
		$lRep = mysqli_fetch_assoc($qRep);

		// Total Banco Vagas
		$qBv = mysqli_query($bd, "SELECT count(id) as totalBancoVagas FROM tb_candidato where aprovado = 'B' and status = 'A' and trabalhe_conosco != 'S' ");
		$lBv = mysqli_fetch_assoc($qBv);

		// Total Aguardando
		$qAgu = mysqli_query($bd, "SELECT count(id) as totalAguardando FROM tb_candidato where aprovado = '' and status = 'A' and trabalhe_conosco != 'S' ");
		$lAgu = mysqli_fetch_assoc($qAgu);?>


		<div class="row">

			<?php include "../rodape.php";
            mysqli_close($bd);
			?>

		</div><!--/.row-->
	</div>	<!--/.main-->

    <?php include 'scripts.php' ?>
	<script src="../js/custom.js"></script>
	<script src="../js/chart.min.js"></script>
	<script src="../js/easypiechart.js"></script>
	<script src="../js/admin.js"></script>


	<script type="text/javascript">
		$(document).ready(function() {
			showGraph1();
            showGraph2();
            showGraph3();
            showGraph4();
			$("#divsCandidatos").show();
		});

		function showGraph1 (){
			var chart1 = document.getElementById("graph1").getContext("2d");
			window.myDoughnut = new Chart(chart1).Doughnut(doughnutData1, {
			responsive: true,
			segmentShowStroke: false
			});
		}

		var doughnutData1 = [
			{
				value: <?=$lCs['totalCasados']?>,
				color:"#f9243f",
				highlight: "#f6495f",
				label: "Casado(a)"
			},
			{
				value: <?=$lDv['totalDivorciados']?>,
				color: "#ffb53e",
				highlight: "#fac878",
				label: "Divorciado(a)"
			},
			{
				value: <?=$lSp['totalSeperados']?>,
				color: "#1ebfae",
				highlight: "#3cdfce",
				label: "Separado(a)"
			},
			{
				value: <?=$lSl['totalSolteiros']?>,
				color: "#30a5ff",
				highlight: "#62b9fb",
				label: "Solteiro(a)"
			},
			{
				value: <?=$lVv['totalViuvos']?>,
				color: "#004d00",
				highlight: "#006600",
				label: "Viuvo(a)"
			}

		];


		function showGraph2 (){
			var chart2 = document.getElementById("graph2").getContext("2d");
			window.myDoughnut = new Chart(chart2).Doughnut(doughnutData2, {
			responsive: true,
			segmentShowStroke: false
			});
		}

		var doughnutData2 = [
			{
				value: <?=$lAdv['totalAdv']?>,
				color:"#30a5ff",
				highlight: "#62b9fb",
				label: "Advogado(a)"
			},
			{
				value: <?=$totalOutros?>,
				color: "#004d00",
				highlight: "#006600",
				label: "Outros"
			},
			{
				value: <?=$lEs['totalEstagiarios']?>,
				color: "#1ebfae",
				highlight: "#3cdfce",
				label: "Estagiario(a)"
			}

		];


		function showGraph3 (){
			var chart3 = document.getElementById("graph3").getContext("2d");
			window.myDoughnut = new Chart(chart3).Doughnut(doughnutData3, {
			responsive: true,
			segmentShowStroke: false
			});
		}

		var doughnutData3 = [
			{
				value: <?=$lCv['totalCivil']?>,
				color:"#30a5ff",
				highlight: "#62b9fb",
				label: "Apenas Civil"
			},
			{
				value: <?=$lTb['totalTrabalhista']?>,
				color: "#004d00",
				highlight: "#006600",
				label: "Apenas Trabalhista"
			},
			{
				value: <?=$lAb['totalAmbas']?>,
				color: "#1ebfae",
				highlight: "#3cdfce",
				label: "Ambas"
			}

		];


		function showGraph4 (){
			var chart4 = document.getElementById("graph4").getContext("2d");
			window.myDoughnut = new Chart(chart4).Doughnut(doughnutData4, {
			responsive: true,
			segmentShowStroke: false
			});
		}

		var doughnutData4 = [
			{
				value: <?=$lApr['totalAprovados']?>,
				color: "#004d00",
				highlight: "#006600",
				label: "Aprovados"
			},
			{
				value: <?=$lBv['totalBancoVagas']?>,
				color: "#ffb53e",
				highlight: "#fac878",
				label: "Banco de Vagas"
			},
			{
				value: <?=$lAgu['totalAguardando']?>,
				color:"#30a5ff",
				highlight: "#62b9fb",
				label: "Aguardando Avaliacao"
			},
			{
				value: <?=$lRep['totalReprovados']?>,
				color: "#f9243f",
				highlight: "#f6495f",
				label: "Reprovados"
			}

		];
	</script>

</body>
</html>
