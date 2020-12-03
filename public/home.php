<?php
require_once __DIR__ . '/includes/funcoes.php';
include_once __DIR__ . '/variaveis.php';

session_start();

isSessaoValida('', false);

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
	<link href="css/datepicker3.css" rel="stylesheet">
	<!--Custom Font-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
</head>
<body style="padding-top: 0px;">

	<?php include "topo.php" ?>

	<div class="col-sm-12 main">
		<div class="row">
			<ol class="breadcrumb alert-info">
				<li><span class="text-warning">ROCHA, MARINHO E SALES ADVOGADOS</span></li>
				<li class="active">Bem-vindo(a) <strong><?=$_SESSION['nome']?></strong></li>
				<li><a href="logoff.php"><i class="fa fa-sign-out-alt"></i> Sair</a></li>
			</ol>
		</div><!--/.row-->

		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					<i class="fa fa-file-alt color-blue"></i> Avalia&ccedil;&atilde;o de Candidato - <strong class="text-info"><?=$_SESSION['cargo']?></strong>
				</h1>

				<div class="panel panel-default">
					<div class="panel-body">
						<?php if (isset($_SESSION['tempo_inicio'])){ ?>
							<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> Voc&ecirc; iniciou sua avalia&ccedil;&atilde;o em <strong><?=$_SESSION['tempo_inicio']?></strong> ! <a href="javascript:void(0);" onclick="javascript:$('#modalEntraAval').modal();">Clique aqui para retornar &agrave; p&aacute;gina de avalia&ccedil;&atilde;o</a></div>
						<?php } ?>

						<div class="row">
							<div class="col-md-4">
								<h3><i class="fa fa-user-circle color-blue"></i> Seus Dados</h3>
								<hr/>
								<!--<img src="<?=$_SESSION['foto']?>" class="img img-thumbnail" /><br/><br/>-->
								<strong>Nome Completo</strong>: <?=$_SESSION['nome']?><br/>
								<strong>Nascimento</strong>: <?=$_SESSION['nascimento']?><br/>
								<strong>E-mail</strong>: <?=$_SESSION['email']?><br/>
								<strong>Telefone Residencial</strong>: <?=$_SESSION['telefone']?><br/>
								<strong>Telefone Celular</strong>: <?=$_SESSION['celular']?><br/>
								<strong>Endere&ccedil;o</strong>: <?=$_SESSION['endereco']?><br/>
								<strong>Cargo Pleiteado</strong>: <?=$_SESSION['cargo']?><br/>

								<?php if($_SESSION["areaAtuacao"] != ""){
								// Para Advogados e Estagiario
								if ($_SESSION["areaAtuacao"] == 'Ambas'){
									$areaAtuacao = "C&iacute;vel e Trabalhista";
									$tempoProva = "90";
								} else {
									$areaAtuacao = $_SESSION["areaAtuacao"];
									if ($areaAtuacao == 'Civil'){
										$areaAtuacao = 'C&iacute;vel';
									}
									$tempoProva = "60";
								}
								?>
								<strong>Forma&ccedil;&atilde;o</strong>: <?=$_SESSION["formacao"]?><br/>
								<strong>&Aacute;rea de Atua&ccedil;&atilde;o</strong>: <?=$areaAtuacao?><br/>
								<?php if ($_SESSION["cargoId"] == 3){ // Apenas Advogado ?>
								<strong>Num. OAB / Data Insc.</strong>: <?=$_SESSION["numOab"]?><br/>
								<?php } } ?>

								<?php if (!isset($_SESSION['finalizado']) || $_SESSION['finalizado'] == 'N'){ ?>
								<hr/>
								<button type="button" class="btn btn-primary" onclick="javascript:$('#modalEntraAval').modal();">
									<i class="fa fa-clock"></i> Iniciar Avalia&ccedil;&atilde;o T&eacute;cnica
								</button>
								<?php } ?>
							</div>

							<?php if ( !isset($_SESSION['finalizado']) ){ ?>
							<div class="col-md-8">
								<h3><i class="fa fa-exclamation-triangle text-warning"></i> Atente aos seguintes avisos:</h3>
								<hr/>
								<i class="fa fa-check"></i> Sugerimos separar <?=$tempoProva?> minutos para a resolu&ccedil;&atilde;o das quest&otilde;es;
								<br/>
								<?php if($_SESSION["areaAtuacao"] != ""){
								// Para Advogados
									if ($_SESSION["areaAtuacao"] == 'Ambas'){?>
										<i class="fa fa-check"></i> Para concluir sua avalia&ccedil;&atilde;o responda &agrave;s quest&otilde;es das &aacute;reas <strong>C&Iacute;VEL</strong> e <strong>TRABALHISTA</strong>;
										<br/>
										<i class="fa fa-check"></i> <strong class="text-danger">N&Atilde;O SER&Aacute; PERMITIDO</strong> realizar apenas uma das duas provas;
										<br/>
										<i class="fa fa-check"></i> <span class="text-danger">Se voc&ecirc; se inscreveu para <strong>AMBAS</strong> ent&atilde;o finalize as duas avalia&ccedil;&otilde;es (<strong>C&Iacute;VEL</strong> e <strong>TRABALHISTA</strong>)</span>;
										<br/>
									<?php } else {?>
										<i class="fa fa-check"></i> Responda &agrave;s quest&otilde;es para poder concluir sua avalia&ccedil;&atilde;o;
										<br/>
									<?php } ?>

								<?php } else { ?>
									<i class="fa fa-check"></i> Responda &agrave;s quest&otilde;es para poder concluir sua avalia&ccedil;&atilde;o;
									<br/>
								<?php } ?>
								<i class="fa fa-check"></i> O sistema armazenar&aacute; os hor&aacute;rios de in&iacute;cio e fim de sua avalia&ccedil;&atilde;o;
								<br/>
								<i class="fa fa-check"></i> N&atilde;o ser&aacute; permitida a utiliza&ccedil;&atilde;o de qualquer fonte de pesquisa;
								<br/>
								<i class="fa fa-check"></i> O sistema detectar&aacute; a&ccedil;&otilde;es como "copiar e colar";
								<br/>
								<i class="fa fa-check"></i> Ap&oacute;s enviadas as respostas, n&atilde;o ser&aacute; poss&iacute;vel edit&aacute;-las;
								<br/>
								<i class="fa fa-check"></i> Ap&oacute;s o t&eacute;rmino de sua avalia&ccedil;&atilde;o, voc&ecirc; poder&aacute; consultar suas respostas;
								<br/>
							</div>
							<?php } ?>
						</div> <!-- /.row -->
					</div>
				</div><!-- /.panel-->

			</div>
		</div><!--/.row-->

		<?php if (isset($_SESSION['finalizado']) && $_SESSION['finalizado'] == 'S'){ ?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="col-md-12">
							<?php if (isset($_SESSION['aprovado']) && $_SESSION['aprovado'] == 'S'){ ?>
							<h3 class="alert alert-success"><i class="fa fa-hand-peace"></i> <strong>PARAB&Eacute;NS!!</strong> Voc&ecirc; foi aprovado em nosso Processo Seletivo! Aguarde o contato do GDH.</h3>
							<?php } ?>
							<?php if (isset($_SESSION['aprovado']) && $_SESSION['aprovado'] == 'N'){ ?>
							<h3 class="alert alert-warning"><i class="fa fa-info-circle"></i> Agradecemos sua participa&ccedil;&atilde;o em nosso Processo Seletivo, mas nossa vaga foi preenchida!</h3>
							<?php } ?>
							<?php if (isset($_SESSION['aprovado']) && $_SESSION['aprovado'] == 'B'){ ?>
							<h3 class="alert alert-warning"><i class="fa fa-info-circle"></i> Agradecemos sua participa&ccedil;&atilde;o em nosso Processo Seletivo, mas nossa vaga foi preenchida! <br/><small>Seus dados foram cadastrados em nosso <strong>Banco de Vagas</strong>, e t&atilde;o logo seja aberto um novo proceso seletivo, entraremos em contato.</small> </h3>
							<?php } ?>
							<?php if (!isset($_SESSION['aprovado'])){ ?>
							<h3><i class="fa fa-info-circle color-blue"></i> Sua avalia&ccedil;&atilde;o j&aacute; foi enviada. Aguarde a finaliza&ccedil;&atilde;o do processo!</h3>
							<?php } ?>
							<hr/>

							<?php if ($_SESSION["areaAtuacao"] == NULL){ // ================== OUTROS CARGOS ?>
							<div class="alert alert-info"><button type="button" class="btn btn-primary" onclick="javascript: window.location.href='logoff.php'">Ok, entendi!</button>
								 <button id="hideResp" type="button" class="close" onclick="hideRespostas()"><i class="fa fa-caret-square-up"></i></button>
								<div id="minhasRespostas">
									<hr/>
									<?php
										$q = mysqli_query($bd, "SELECT * FROM tb_respostas where id_candidato = '" . $_SESSION['id'] . "' and status = 'A' order by id desc limit 0,1 ");
										$l = mysqli_fetch_assoc($q);
										mysqli_free_result($q);
									?>

									*Suas respostas:
									<br/><br/>
									<strong>1) Em poucas palavras, disserte sobre a linguagem <em>UML</em></strong>
									<br/>
									R: <em>" <?=$l['resposta_1']?> "</em>
									<br/><br/>
									<strong>2) Sobre metodologia &aacute;gil, explique o porqu&ecirc; de sua import&acirc;ncia na gest&atilde;o de projetos</strong>
									<br/>
									R: <em>" <?=$l['resposta_2']?> "</em>
									<br/><br/>
									<strong>3) As principais atividades de um Analista de Requisitos/Neg&oacute;cios s&atilde;o: <em>Elicitar</em> e <em>Analisar</em> Requisitos. Explique a(s) diferen&ccedil;a(s) entre estas duas atividades</strong>
									<br/>
									R: <em>" <?=$l['resposta_3']?> "</em>
									<br/><br/>
									<strong>4) Explique em poucas palavras, o fluxo que voc&ecirc; costuma utilizar para elaborar um Documento de Requisitos</strong>
									<br/>
									R: <em>" <?=$l['resposta_4']?> "</em>
									<br/><br/>
									<strong>5) "<em>Programa&ccedil;&atilde;o Orientada a Objetos (POO) &eacute; um conceito que pode ser aplicado &agrave; linguagem JAVA, por&eacute;m não &agrave; linguagem PHP</em>". Explique o porquê desta afirma&ccedil;&atilde;o ser verdadeira ou falsa, deixando claro o significado de POO</strong>
									<br/>
									R: <em>" <?=$l['resposta_5']?> "</em>
									<br/><br/>
									<small>*Suas respostas n&atilde;o podem ser editadas.</small>
								</div>
							</div>
							<?php } ?>


							<?php if ($_SESSION["areaAtuacao"] != NULL){ // ================== ADV CIVEL ?>
							<div class="alert alert-info"><button type="button" class="btn btn-primary" onclick="javascript: window.location.href='logoff.php'">Ok, entendi!</button>
								 <button id="hideResp" type="button" class="close" onclick="hideRespostas()"><i class="fa fa-caret-square-up"></i></button>
								<div id="minhasRespostas">
									<hr/>
									<?php if ($_SESSION["cargoId"] == 3){ ?>
									Voc&ecirc; pode consultar suas respostas clicando <strong><a href="perguntasAdvSalvas.php" >aqui</a></strong>
									<?php } ?>
									<?php if ($_SESSION["cargoId"] == 5){ ?>
									Voc&ecirc; pode consultar suas respostas clicando <strong><a href="perguntasEstagioAdvSalvas.php" >aqui</a></strong>
									<?php } ?>

								</div>
							</div>
							<?php } ?>



						</div>

					</div>
				</div><!-- /.panel-->
			</div>
		</div><!--/.row-->
		<?php } ?>

		<!-- Modal Confirmacao -->
		<div id="modalEntraAval" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
	      <div class="modal-dialog">
	        <div class="modal-content">

	          <div class="modal-header alert-info">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i></span>
	            </button>
	            <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-info-circle color-blue"></i> Iniciar Avalia&ccedil;&atilde;o T&eacute;cnica </h4>
	          </div>
	          <div class="modal-body">
	            <p id="textoLogoff">
		            <div>
		            	LEMBRE-SE:<br/><br/>
		            	<i class="fa fa-check"></i> Ap&oacute;s enviadas as respostas, <strong class="text-danger">N&Atilde;O</strong> ser&aacute; poss&iacute;vel edit&aacute;-las.
		            </div><br/>
		            BOA PROVA !
	        	</p>
	            <p align="center" class="text-info" id="carregandoLogoff" style="display: none;"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>
	          </div>
	          <div class="modal-footer">
		          <?php if($_SESSION["areaAtuacao"] != NULL){ ?>
			          <?php if ($_SESSION["cargoId"] == 3){ // ADVOGADO?>
			          	<button id="botaoConfirmar" type="button" class="btn btn-primary" onClick="javascript:window.location.href='perguntasAdv.php'; $('#textoLogoff').hide(); $('#carregandoLogoff').show();">
			         	<i class="fa fa-check"></i> Iniciar</button>
			      	  <?php } ?>
			      	  <?php if ($_SESSION["cargoId"] == 5){ // ESTAGIARIO?>
			          	<button id="botaoConfirmar" type="button" class="btn btn-primary" onClick="javascript:window.location.href='perguntasEstagioAdv.php'; $('#textoLogoff').hide(); $('#carregandoLogoff').show();">
			         	<i class="fa fa-check"></i> Iniciar</button>
			      	  <?php } ?>
		          <?php } else { // OUTROS ?>
			          <button id="botaoConfirmar" type="button" class="btn btn-primary" onClick="javascript:window.location.href='perguntas.php'; $('#textoLogoff').hide(); $('#carregandoLogoff').show();">
			          <i class="fa fa-check"></i> Iniciar</button>
		          <?php } ?>
		          <button type="button" class="btn btn-default" onClick="javascript:$('#textoLogoff').show(); $('#carregandoLogoff').hide();" data-dismiss="modal">
		          <i class="fa fa-times"></i> Voltar</button>
	          </div>

	        </div>
	      </div>
	    </div>
    	<!-- Fim Modal Confirmacao -->


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
