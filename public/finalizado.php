<?php
require_once __DIR__ . '/includes/funcoes.php';
include_once __DIR__ . '/variaveis.php';

session_start();

isSessaoValida('', false);

$bd = abreConn();
?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'head.php' ?>
	<link rel="stylesheet" href="css/datepicker3.css">
	<!--Custom Font-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i">
</head>
<body style="padding-top: 0px;">

	<?php include "topo.php" ?>

	<div class="col-sm-12 main">
		<div class="row">
			<ol class="breadcrumb alert-info">
				<li><span class="text-warning">ROCHA, MARINHO E SALES ADVOGADOS</span></li>
				<li class="active">Bem-vindo(a) <strong><?=$_SESSION['nome']?></strong></li>
			</ol>
		</div><!--/.row-->

		<?php
		if (!isset($_SESSION['finalizado']) || !isset($_SESSION['tempo_envio'])){
	  	 die("<hr/><div class='alert alert-danger'><i class='fa fa-exclamation-triangle'></i> Acesso negado! O que voc&ecirc; est&aacute; fazendo aqui?? - <a href='logoff.php'>Sair</a></div>");
	  	}
		?>

		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					<i class="fa fa-file-alt color-blue"></i> Avalia&ccedil;&atilde;o de Candidato - <strong class="text-info"><?=$_SESSION['cargo']?></strong>
				</h1>

				<div class="panel panel-default">
					<div class="panel-body">
						<div class="col-md-12">

							<?php if ($_SESSION['finalizado'] == 'S'){ ?>
							<div class="alert alert-info">
								<i class="fa fa-check fa-2x"></i> Sua avalia&ccedil;&atilde;o foi finalizada e enviada com sucesso!<br/><br/>
								<i class="fa fa-check"></i> In&iacute;cio da avalia&ccedil;&atilde;o: <strong><?=$_SESSION["primeiraTentativa"]?></strong><br/>
								<i class="fa fa-check"></i> T&eacute;rmino da avalia&ccedil;&atilde;o: <strong><?=$_SESSION['tempo_envio']?></strong>
								<br/><br/>
								Aguarde o contato do GDH.
							</div>
							<div class="alert alert-info">
								Voc&ecirc; poder&aacute; acessar as suas respostas efetuando novamente login no sistema.
							</div>
							<?php } else { ?>
							<div class="alert alert-danger"><i class="fa fa-times"></i> Ocorreu um erro ao enviar as suas respostas. Tente novamente!</div>
							<?php } ?>

							<h3><i class="fa fa-user-circle color-blue"></i> Seus Dados</h3>
							<hr/>
							<!--<img src="<?=$_SESSION['foto']?>" class="img img-thumbnail" /><br/><br/>-->
							<strong>Nome Completo</strong>: <?=$_SESSION['nome']?><br/>
							<strong>Idade</strong>: <?=$_SESSION['nascimento']?><br/>
							<strong>E-mail</strong>: <?=$_SESSION['email']?><br/>
							<strong>Telefone</strong>: <?=$_SESSION['telefone']?><br/>
							<strong>Endere&ccedil;o</strong>: <?=$_SESSION['endereco']?><br/>
							<strong>Cargo Pleiteado</strong>: <?=$_SESSION['cargo']?><br/>

							<?php if($_SESSION["areaAtuacao"] != ""){
							// Para Advogados
							if ($_SESSION["areaAtuacao"] == 'Ambas'){
								$areaAtuacao = "C&iacute;vil e Trabalhista";
							} else {
								$areaAtuacao = $_SESSION["areaAtuacao"];
							}
							?>
							<strong>Forma&ccedil;&atilde;o</strong>: <?=$_SESSION["formacao"]?><br/>
							<strong>&Aacute;rea de Atua&ccedil;&atilde;o</strong>: <?=$areaAtuacao?><br/>
							<?php if ($_SESSION["cargoId"] == 3){ // Apenas Advogado ?>
								<strong>Num. OAB / Data Insc.</strong>: <?=$_SESSION["numOab"]?><br/>
							<?php } } ?>

							<?php if ($_SESSION['finalizado'] == 'S'){ ?>
							<hr/>
							<div align="center">
							<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='logoff.php';">
								<i class="fa fa-sign-out-alt"></i> Sair com Seguran&ccedil;a
							</button>
							</div>
							<?php } else { ?>
							<hr/>
							<div align="center">
							<button type="button" class="btn btn-danger" onclick="javascript:window.location.href='perguntas.php';">
								Retornar &agrave; p&aacute;gina de avalia&ccedil;&atilde;o
							</button>
							</div>
							<?php } ?>

						</div>
					</div>
				</div><!-- /.panel-->

			</div>
		</div><!--/.row-->

		<div class="row">

			<?php include "rodape.php";
            mysqli_close($bd);
			?>

		</div><!--/.row-->
	</div>	<!--/.main-->

    <?php include 'scripts.php' ?>
	<script src="js/custom.js"></script>

</body>
</html>
