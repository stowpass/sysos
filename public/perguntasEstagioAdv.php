<?php
require_once __DIR__ . '/includes/funcoes.php';
include_once __DIR__ . '/variaveis.php';

session_start();

isSessaoValida('', false);

$bd = abreConn();

date_default_timezone_set('America/Fortaleza');
$diaCorrente = date('d');
$mesCorrente = date('m');
$anoCorrente = date('Y');
$horaCorrente = date('H');
$minutoCorrente = date('i');
$segundoCorrente = date('s');

if (!isset($_SESSION['tempo_inicio']) && !isset($_SESSION['finalizado'])){
    $_SESSION['tempo_inicio'] = $diaCorrente."/".$mesCorrente."/".$anoCorrente." - ".$horaCorrente.":".$minutoCorrente.":".$segundoCorrente;

    if ($_SESSION["primeiraTentativa"] == ""){
        $_SESSION["primeiraTentativa"] = $_SESSION['tempo_inicio'];
        $sql2 = "UPDATE tb_candidato SET primeira_tentativa = '".$_SESSION['primeiraTentativa']."' WHERE id='".$_SESSION["id"]."' ";
        mysqli_query($bd, $sql2);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'head.php' ?>
	<!--Custom Font-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i">
	<style type="text/css">
	  a{
	  	color:#444;
	  	text-decoration: none;
	  }
	  a:hover{
	  	color:#31708f;
	  	text-decoration: none;
	  	font-weight: bold;
	  }
	</style>
</head>
<body style="padding-top: 0px;">

	<?php
	// ATENCAO: Algumas variáves estão no arquivo variaveis.php onde este é chamado dentro das páginas

	if ($_SESSION['cargoId'] != 5){ // Apenas que selecionou o cargo de ESTAGIARIO tem acesso
		redirect("home.php");
	}
	?>

	<?php include "topo.php" ?>

	<div class="col-sm-12 main">
		<div class="row">
			<ol class="breadcrumb alert-info">
				<li><span class="text-warning">ROCHA, MARINHO E SALES ADVOGADOS</span></li>
				<li class="active">Bem-vindo(a) <strong><?=$_SESSION['nome']?></strong></li>
				<li><a href="home.php"><i class="fa fa-arrow-circle-left"></i> Voltar</a></li>
			</ol>
		</div><!--/.row-->

		<?php if (isset($_SESSION['finalizado']) && $_SESSION['finalizado'] == 'S'){
		die("<hr/><div class='alert alert-danger'><i class='fa fa-lg fa-exclamation-triangle'>&nbsp;</i> ATEN&Ccedil;&Atilde;O: Voc&ecirc; j&aacute; envio suas respostas. Aguarde a finaliza&ccedil;&atilde;o do processo! - <a href='logoff.php'>Sair</a></div>");
		} ?>

		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					<i class="fa fa-file-alt color-blue"></i> Avalia&ccedil;&atilde;o de Candidato - <strong class="text-info"><?=$_SESSION['cargo']?></strong>
				</h1>

				<div class="alert alert-warning">
					Esse instrumento tem a finalidade em balizar seus conhecimentos t&eacute;cnicos na &aacute;rea jur&iacute;dica, não sendo permitida a utilização de qualquer fonte de pesquisa. Leia as quest&otilde;es e responda de forma sucinta e fundamentada. Boa prova!!
				</div>

				<?php
					if ($_SESSION["areaAtuacao"] == 'Civil'){
						$mostraBotaoCivil = "block";
						$mostraBotaoTrabalhista = "none";
					}
					if ($_SESSION["areaAtuacao"] == 'Trabalhista'){
						$mostraBotaoCivil = "none";
						$mostraBotaoTrabalhista = "block";
					}
					if ($_SESSION["areaAtuacao"] == 'Ambas'){
						$mostraBotaoCivil = "block";
						$mostraBotaoTrabalhista = "block";
					}
				?>

				<?php if ($_SESSION["areaAtuacao"] == 'Civil'){ ?>
				<div class="row" align="center">
					<div class="col-md-1">
						<button class="btn btn-primary" onclick="javascript:$('#perguntasTrabalhista').hide(); $('#perguntasCivil').show(500); $('#btnEnviar').show(500);" style="display: <?=$mostraBotaoCivil?>"><i class="fa fa-hand-point-right"></i> Quest&otilde;es - &Aacute;rea C&iacute;vil</button>
					</div>
				</div>
				<?php } ?>

				<?php if ($_SESSION["areaAtuacao"] == 'Trabalhista'){ ?>
				<div class="row" align="center">
					<div class="col-md-1">
						<button class="btn btn-primary" onclick="javascript:$('#perguntasCivil').hide(); $('#perguntasTrabalhista').show(500); $('#btnEnviar').show(500);" style="display: <?=$mostraBotaoTrabalhista?>"><i class="fa fa-hand-point-right"></i> Quest&otilde;es - &Aacute;rea Trabalhista</button>
					</div>
				</div>
				<?php } ?>

				<?php if ($_SESSION["areaAtuacao"] == 'Ambas'){ ?>
				<div class="row" align="center">
					<div class="col-md-1">
						<button class="btn btn-primary" onclick="javascript:$('#perguntasTrabalhista').hide(); $('#perguntasCivil').show(500); $('#btnEnviar').show(500);" style="display: <?=$mostraBotaoCivil?>"><i class="fa fa-hand-point-right"></i> Quest&otilde;es - &Aacute;rea C&iacute;vil</button>
					</div><br/><br/>
					<div class="col-md-1">
						<button class="btn btn-primary" onclick="javascript:$('#perguntasCivil').hide(); $('#perguntasTrabalhista').show(500); $('#btnEnviar').show(500);" style="display: <?=$mostraBotaoTrabalhista?>"><i class="fa fa-hand-point-right"></i> Quest&otilde;es - &Aacute;rea Trabalhista</button>
					</div>
				</div>
				<?php } ?>

				<hr/>

				<div class="panel panel-default">
					<div class="panel-body">

						<!-- Mensagens de ALERTA -->
						<?php if ($_SESSION["areaAtuacao"] != 'Ambas'){ ?>
						<div id="msgErro" class="alert alert-danger" style="display: none;">
							<strong>Erro</strong>: Favor, responda &agrave;s quest&otilde;es em destaque
						</div>
						<?php } ?>

						<?php if ($_SESSION["areaAtuacao"] == 'Ambas'){ ?>
						<div id="msgErro" class="alert alert-danger" style="display: none;">
							<strong>Erro</strong>: Favor, responda &agrave;s quest&otilde;es em destaque. Verifique ambas quest&otilde;es (<strong>C&iacute;vil</strong> e <strong>Trabalhista</strong>)
						</div>
						<?php } ?>

						<div class="progress" id="carregando" style="display: none;">
							<div data-percentage="0%" style="width: 100%;" class="progress-bar progress-bar-blue" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
						<p align="center" id="carregandoTexto" style="display: none;" class="text-info"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>
						<!-- FIM Mensagens de ALERTA -->


						<!-- ============================= INICIO CIVIL ============================= -->
						<div class="col-md-12" id="perguntasCivil" style="display:none;">

							<fieldset>
								<legend>Quest&otilde;es - &Aacute;rea C&Iacute;VIL</legend>
							</fieldset>

							<div class="alert alert-warning">
								Responda &agrave;s quest&otilde;es <strong>1</strong> a <strong>9</strong> clicando na op&ccedil;&atilde;o que voc&ecirc; julgar correta.
							</div>

							<form role="form" id="formAval" name="formAval" action="processarEstagioAdv.php" method="post">
								<input type="hidden" name="idCandidato" value="<?=$_SESSION["id"]?>">
								<input type="hidden" name="primeiraTentativa" value="<?=$_SESSION["primeiraTentativa"]?>">
								<input type="hidden" name="areaAtuacaoHidden" value="<?=$_SESSION["areaAtuacao"]?>">

								<div class="form-group">
									<label>1. "<i>Eis, <u class="text-danger">por&eacute;m</u>, que surgem da esquina duas mulheres desavisadas e tranquilas.</i>” Marque a alternativa que substitui o termo destacado sem altera&ccedil;&atilde;o de sentido:<br/></label>
											<br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil1').val('A'); $('#respostaCivil1Txt').html('A');">A) Ent&atilde;o</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil1').val('B'); $('#respostaCivil1Txt').html('B');">B) Pois</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil1').val('C'); $('#respostaCivil1Txt').html('C');">C) Entretanto</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil1').val('D'); $('#respostaCivil1Txt').html('D');">D) Portanto</a><br/>
									<br/>
									<input type="hidden" id="respostaCivil1" name="respostaCivil1" />
									Sua resposta: <strong id="respostaCivil1Txt" class="text-info"></strong>

								</div><br/>


								<div class="form-group">
									<label>2. Nas frases a seguir, observe o uso da crase e marque a alternativa <u>CORRETA</u>:<br/><br/>
											I.	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Toda noite, &agrave; esta hora, fica-se sobressaltado.<br/>
											II. &nbsp;&nbsp;&nbsp;&nbsp;Sinto que quero bem &agrave; minha parceira de infort&uacute;nio.<br/>
											III. &nbsp;&nbsp;&nbsp;&Agrave; sombra dos edif&iacute;cios, os homens se recolhem.<br/>
											IV. &nbsp;&nbsp;&nbsp;Olhos terr&aacute;veis da pol&iacute;cia est&atilde;o &agrave; espreitar aqui e ali.</label>
											<br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil2').val('A'); $('#respostaCivil2Txt').html('A');">A) As frases I e II est&atilde;o certas.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil2').val('B'); $('#respostaCivil2Txt').html('B');">B) O uso da crase na frase II &eacute; facultativo.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil2').val('C'); $('#respostaCivil2Txt').html('C');">C) As frases II e III est&atilde;o incorretas.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil2').val('D'); $('#respostaCivil2Txt').html('D');">D) As frases III e IV est&atilde;o certas.</a><br/>
									<br/>
									<input type="hidden" id="respostaCivil2" name="respostaCivil2" />
									Sua resposta: <strong id="respostaCivil2Txt" class="text-info"></strong>

								</div><br/>


								<div class="form-group">
									<label>3. Em rela&ccedil;&atilde;o ao ato il&iacute;cito, &eacute; <u>INCORRETO</u> afirmar que:<br/></label>
											<br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil3').val('A'); $('#respostaCivil3Txt').html('A');">A) Constitui fonte de obriga&ccedil;&atilde;o.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil3').val('B'); $('#respostaCivil3Txt').html('B');">B) O abuso do direito &eacute; considerado pelo C&oacute;digo Civil como ato il&iacute;cito.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil3').val('C'); $('#respostaCivil3Txt').html('C');">C) Distingue-se responsabilidade subjetiva da responsabilidade objetiva em face da exist&ecirc;ncia do elemento culpa ou dolo na primeira.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil3').val('D'); $('#respostaCivil3Txt').html('D');">D) Basta a viola&ccedil;&atilde;o do direito para gerar o dever de indenizar ou ressarcir o preju&iacute;zo.</a><br/>
									<br/>
									<input type="hidden" id="respostaCivil3" name="respostaCivil3" />
									Sua resposta: <strong id="respostaCivil3Txt" class="text-info"></strong>

								</div><br/>


								<div class="form-group">
									<label>4. Em uma a&ccedil;&atilde;o de conhecimento, ao autor &eacute; permitido:<br/></label>
											<br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil4').val('A'); $('#respostaCivil4Txt').html('A');">A) Alterar o pedido antes da cita&ccedil;&atilde;o do r&eacute;u, independentemente do seu consentimento.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil4').val('B'); $('#respostaCivil4Txt').html('B');">B) Indicar outro r&eacute;u ap&oacute;s a cita&ccedil;&atilde;o do indicado inicialmente, em qualquer circunst&acirc;ncia.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil4').val('C'); $('#respostaCivil4Txt').html('C');">C) Suspender unilateralmente a tramita&ccedil;&atilde;o do processo independentemente do consentimento do r&eacute;u, desde que por prazo inferior a 30 (trinta) dias.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil4').val('D'); $('#respostaCivil4Txt').html('D');">D) Alterar o pedido depois do saneamento do processo, desde que com a concord&acirc;ncia do r&eacute;u.</a><br/>
									<br/>
									<input type="hidden" id="respostaCivil4" name="respostaCivil4" />
									Sua resposta: <strong id="respostaCivil4Txt" class="text-info"></strong>

								</div><br/>


								<div class="form-group">
									<label>5. Em rela&ccedil;&atilde;o aos recursos, &eacute; <u>CORRETO</u> afirmar:<br/></label>
											<br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil5').val('A'); $('#respostaCivil5Txt').html('A');">A) S&atilde;o sempre recebidos no duplo efeito, devolutivo e suspensivo.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil5').val('B'); $('#respostaCivil5Txt').html('B');">B) Das decis&otilde;es interlocut&oacute;rias e dos despachos n&atilde;o cabem recursos.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil5').val('C'); $('#respostaCivil5Txt').html('C');">C) &Eacute; poss&iacute;vel desistir de sua interposi&ccedil;&atilde;o, a qualquer tempo, sem anu&ecirc;ncia do recorrido ou dos litisconsortes.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil5').val('D'); $('#respostaCivil5Txt').html('D');">D) Somente podem ser interpostos pela parte totalmente vencida.</a><br/>
									<br/>
									<input type="hidden" id="respostaCivil5" name="respostaCivil5" />
									Sua resposta: <strong id="respostaCivil5Txt" class="text-info"></strong>

								</div><br/>


								<div class="form-group">
									<label>6. Quais as diferen&ccedil;as entre cita&ccedil;&atilde;o e intima&ccedil;&atilde;o?<br/></label>
											<br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil6').val('A'); $('#respostaCivil6Txt').html('A');">A) Cita&ccedil;&atilde;o &eacute; o ato pelo qual se chama a ju&iacute;zo o r&eacute;u ou o interessado a fim de se defender.; Intima&ccedil;&atilde;o &eacute; o ato pelo qual se d&aacute; ci&ecirc;ncia a algu&eacute;m dos atos e termos do processo, para que fa&ccedil;a ou deixe de fazer alguma coisa.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil6').val('B'); $('#respostaCivil6Txt').html('B');">B) Cita&ccedil;&atilde;o &eacute; o ato pelo qual se d&aacute; ci&ecirc;ncia a algu&eacute;m dos atos e termos do processo, para que fa&ccedil;a ou deixe de fazer alguma coisa; Intima&ccedil;&atilde;o &eacute; ato pelo qual se chama a ju&iacute;zo o r&eacute;u ou o interessado a fim de se defender.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil6').val('C'); $('#respostaCivil6Txt').html('C');">C) Cita&ccedil;&atilde;o &eacute; o ato pelo qual o juiz cita o nome do r&eacute;u na audi&ecirc;ncia; Intima&ccedil;&atilde;o &eacute; o ato pelo qual se chama a ju&iacute;zo o r&eacute;u ou o interessado a fim de se defender.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil6').val('D'); $('#respostaCivil6Txt').html('D');">D) Cita&ccedil;&atilde;o &eacute; o ato pelo qual pode-se dar entrada em um processo; Intima&ccedil;&atilde;o &eacute; o ato pelo qual se chama para responder um processo.</a><br/>
									<br/>
									<input type="hidden" id="respostaCivil6" name="respostaCivil6" />
									Sua resposta: <strong id="respostaCivil6Txt" class="text-info"></strong>

								</div><br/>

								<div class="form-group">
									<label>7. Qual das hip&oacute;teses abaixo n&atilde;o caracteriza forma legal de adimplemento e extin&ccedil;&atilde;o das obriga&ccedil;&otilde;es?<br/></label>
											<br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil7').val('A'); $('#respostaCivil7Txt').html('A');">A) Confus&atilde;o.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil7').val('B'); $('#respostaCivil7Txt').html('B');">B) Remiss&atilde;o de d&iacute;vida.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil7').val('C'); $('#respostaCivil7Txt').html('C');">C) Indeniza&ccedil;&atilde;o.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil7').val('D'); $('#respostaCivil7Txt').html('D');">D) Nova&ccedil;&atilde;o.</a><br/>
									<br/>
									<input type="hidden" id="respostaCivil7" name="respostaCivil7" />
									Sua resposta: <strong id="respostaCivil7Txt" class="text-info"></strong>

								</div><br/>

								<div class="form-group">
									<label>8. Sobre mora &eacute; correto afirmar que:<br/></label>
											<br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil8').val('A'); $('#respostaCivil8Txt').html('A');">A) H&aacute; apenas uma esp&eacute;cie de mora: a do devedor.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil8').val('B'); $('#respostaCivil8Txt').html('B');">B) A mora caracteriza-se apenas pelo inadimplemento do prazo do cumprimento da obriga&ccedil;&atilde;o.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil8').val('C'); $('#respostaCivil8Txt').html('C');">C) Mora n&atilde;o pode, de forma nenhuma, trazer qualquer benef&iacute;cio para o credor.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil8').val('D'); $('#respostaCivil8Txt').html('D');">D) Mora &eacute; o retardamento ou imperfeito cumprimento da obriga&ccedil;&atilde;o.</a><br/>
									<br/>
									<input type="hidden" id="respostaCivil8" name="respostaCivil8" />
									Sua resposta: <strong id="respostaCivil8Txt" class="text-info"></strong>

								</div><br/>

								<div class="form-group">
									<label>9. Durante o curso processual em mat&eacute;ria civil poder&aacute; ser considerado litigante de m&aacute;-f&eacute; aquele que:<br/><br/>
											I.	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Proceder de modo temer&aacute;rio em qualquer incidente ou ato do processo.<br/>
											II. &nbsp;&nbsp;&nbsp;&nbsp;Alterar a verdade dos fatos.<br/>
											III. &nbsp;&nbsp;&nbsp;Deduzir pretens&atilde;o ou defesa contra texto n&atilde;o expresso de lei ou fato controverso.<br/>
											IV. &nbsp;&nbsp;&nbsp;Usar do processo para conseguir objetivo ilegal.</label>
											<br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil9').val('A'); $('#respostaCivil9Txt').html('A');">A) Apenas as assertivas I e III est&atilde;o corretas.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil9').val('B'); $('#respostaCivil9Txt').html('B');">B) As assertivas I, II e IV est&atilde;o corretas.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil9').val('C'); $('#respostaCivil9Txt').html('C');">C) Apenas a assertiva III est&aacute; correta.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil9').val('D'); $('#respostaCivil9Txt').html('D');">D) Apenas as assertivas I e IV est&atilde;o incorretas.</a><br/>
									<br/>
									<input type="hidden" id="respostaCivil9" name="respostaCivil9" />
									Sua resposta: <strong id="respostaCivil9Txt" class="text-info"></strong>

								</div><br/>

								<div class="alert alert-warning">
									A quest&atilde;o <strong>10</strong> &eacute; dissertativa.
								</div>

								<div class="form-group">
									<label>10. Apresentamos dois temas, no qual voc&ecirc; deve escolher e dissertar, colocando seu ponto de vista e argumenta&ccedil;&atilde;o.</label><br/><br/>

									<strong>Suprema Corte dos EUA aprova casamento gay</strong><br/>
									A institucionaliza&ccedil;&atilde;o do casamento homoafetivo promovido pela Suprema Corte dos Estados Unidos reacendeu aqui no Brasil o debate para quest&otilde;es do direito civil de casais gays. Em 2013, o tema esteve quente quando uma resolu&ccedil;&atilde;o do Supremo Tribunal Federal (STF) autorizou a celebra&ccedil;&atilde;o de casamento civil entre pessoas do mesmo sexo nos cart&oacute;rios brasileiros. O tema aparece em novelas e propagandas.

									<br/><br/>
									<strong>Direitos trabalhistas para dom&eacute;sticos s&atilde;o consolidados</strong></br>
									Em vigor desde 2013, as novas regras que garantem melhores condi&ccedil;&otilde;es de trabalho para os profissionais que fazem trabalhos dom&eacute;sticos levantam debates que passam n&atilde;o s&oacute; por quest&otilde;es trabalhistas, mas tamb&eacute;m sociais. O tema virou uma pol&ecirc;mica porque patr&otilde;es alegam que n&atilde;o podem pagar os direitos e os dom&eacute;sticos consideram a PEC uma conquista hist&oacute;rica. Entre os direitos est&atilde;o hora extra e  adicional noturno.<br/><br/>

									<textarea class="form-control" id="respostaCivil10" name="respostaCivil10" rows="16" placeholder="Se n&atilde;o souber a resposta escreva 'N&atilde;o sei'" style="border: 1px solid #999;"></textarea>
								</div>



							</div>
							<!-- ============================= FIM CIVIL ============================= -->



							<!-- ============================= INICIO TRABALHISTA ============================= -->
							<div class="col-md-12" id="perguntasTrabalhista" style="display:none;">

								<fieldset>
									<legend>Quest&otilde;es - &Aacute;rea TRABALHISTA</legend>
								</fieldset>

								<div class="alert alert-warning">
									As quest&otilde;es <strong>1</strong> a <strong>3</strong> s&atilde;o dissertativas.
								</div>

								<div class="form-group">
									<label>1. O Princ&iacute;pio da Prote&ccedil;&atilde;o ao Trabalhador suplanta o princ&iacute;pio da Isonomia das Partes: Explique.<br/>
									</label><br/>
									<textarea class="form-control" id="respostaTrabalhista1" name="respostaTrabalhista1" rows="6" placeholder="Se n&atilde;o souber a resposta escreva 'N&atilde;o sei'" style="border: 1px solid #999;"></textarea>
								</div><br/>

								<div class="form-group">
									<label>2. Cite e discorra acerca dos requisitos para caracteriza&ccedil;&atilde;o do v&iacute;nculo empregat&iacute;cio.<br/>
									</label><br/>
									<textarea class="form-control" id="respostaTrabalhista2" name="respostaTrabalhista2" rows="6" placeholder="Se n&atilde;o souber a resposta escreva 'N&atilde;o sei'" style="border: 1px solid #999;"></textarea>
								</div><br/>

								<div class="form-group">
									<label>3. A empresa Fogo Dourado Ltda costuma terceirizar alguns servi&ccedil;os n&atilde;o relacionadas &agrave; atividade fim da empresa e contratou os empregados da empresa Irm&atilde;os S.A. para prestarem servi&ccedil;os na sede da empresa contratante. Ocorre que a empresa contratada, Irm&atilde;os S.A., inesperadamente encerrou suas atividades sem qualquer comunica&ccedil;&atilde;o previa a seus empregados e empresas contratantes. Com intuito de receber as verbas trabalhistas, os empregados desta empresa ingressaram com reclama&ccedil;&atilde;o trabalhista em face de ambas as empresas.
									<br/><br/>Assim, discorra acerca da possibilidade de terceiriza&ccedil;&atilde;o de servi&ccedil;os, bem como acerca do tipo de responsabilidade da empresa tomadora de servi&ccedil;os.<br/>
									</label><br/>
									<textarea class="form-control" id="respostaTrabalhista3" name="respostaTrabalhista3" rows="6" placeholder="Se n&atilde;o souber a resposta escreva 'N&atilde;o sei'" style="border: 1px solid #999;"></textarea>
								</div><br/>


							</div>
							<!-- ============================= FIM TRABALHISTA ============================= -->

							<?php if ($_SESSION["areaAtuacao"] == 'Civil'){ ?>
							<hr>
							<button type="button" class="btn btn-primary" id="btnEnviar" style="display: none;" onclick="validaRespostasEstagioAdvCivil()">Enviar</button>
							<button type="button" class="btn btn-default" onclick="javascript: $('html, body').animate({scrollTop:0}, 'slow');"><i class="fa fa-arrow-circle-up"></i> Topo</button>
							<?php } ?>

							<?php if ($_SESSION["areaAtuacao"] == 'Trabalhista'){ ?>
							<hr>
							<button type="button" class="btn btn-primary" id="btnEnviar" style="display: none;" onclick="validaRespostasEstagioAdvTrabalhista()">Enviar</button>
							<button type="button" class="btn btn-default" onclick="javascript: $('html, body').animate({scrollTop:0}, 'slow');"><i class="fa fa-arrow-circle-up"></i> Topo</button>
							<?php } ?>

							<?php if ($_SESSION["areaAtuacao"] == 'Ambas'){ ?>
							<hr>
							<button type="button" class="btn btn-primary" id="btnEnviar" style="display: none;" onclick="validaRespostasEstadioAdvAmbas()">Enviar</button>
							<button type="button" class="btn btn-default" onclick="javascript: $('html, body').animate({scrollTop:0}, 'slow');"><i class="fa fa-arrow-circle-up"></i> Topo</button>
							<?php } ?>

						</form>
						</div>
					</div>
				</div><!-- /.panel-->

			</div>
		</div><!--/.row-->

		<!-- Modal Confirmacao -->
		<div id="modalConfirmar" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
	      <div class="modal-dialog">
	        <div class="modal-content">

	          <div class="modal-header alert-info">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i></span>
	            </button>
	            <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-file-alt color-blue"></i> Envio de Avalia&ccedil;&atilde;o T&eacute;cnica </h4>
	          </div>
	          <div class="modal-body">
	            <p>
		            As respostas n&atilde;o poder&atilde;o ser editadas ap&oacute;s envio.
		            <br/><br/>
		            Deseja confirmar o envio de suas respostas?
	        	</p>
	            <p align="center" class="text-info" id="carregandoEnvio" style="display: none;"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>
	          </div>
	          <div class="modal-footer">
		          <button id="botaoConfirmar" type="button" class="btn btn-primary" onclick="javascript: $('#carregandoEnvio').show(); confirmarEnvio();">
		          <i class="fa fa-check"></i> Confirmar</button>
		          <button type="button" class="btn btn-default" onclick="javascript:$('#carregandoEnvio').hide();" data-dismiss="modal">
		          <i class="fa fa-times"></i> Voltar</button>
	          </div>

	        </div>
	      </div>
	    </div>
    	<!-- Fim Modal Confirmacao -->

    	<!-- Modal Alerta Fralde -->
		<div id="modalVacilo" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
	      <div class="modal-dialog">
	        <div class="modal-content">

	          <div class="modal-header alert-danger">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle color-red"></i></span>
	            </button>
	            <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-thumbs-down color-red"></i> A&ccedil;&atilde;o n&atilde;o permitida! </h4>
	          </div>
	          <div class="modal-body">
	            <p>
		            Identificamos a&ccedil;&atilde;o n&atilde;o permitida durante sua avalia&ccedil;&atilde;o.
	        	</p>
	            <p align="center" class="text-info" id="carregandoEnvio" style="display: none;"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>
	          </div>
	          <div class="modal-footer">
		          <button type="button" class="btn btn-danger" onclick="javascript:$('#carregandoEnvio').hide();" data-dismiss="modal">
		          Desculpe-me, n&atilde;o ir&aacute; se repetir</button>
	          </div>

	        </div>
	      </div>
	    </div>
    	<!-- Fim Modal alerta Fralde -->


		<div class="row">

			<?php include "rodape.php";
			mysqli_close($bd);
			?>

		</div><!--/.row-->
	</div>	<!--/.main-->

    <?php include 'scripts.php' ?>
	<script src="js/custom.js"></script>
	<script type="text/javascript" src="js/util.js"></script>

	<script type="text/javascript">

		$(document).ready(function() {

			for (i = 10; i < 11; i++) {
				 $("#respostaCivil"+i).bind({
					paste : function(){
						capturaCopia();
					}
				});
			}

			for (i = 1; i < 4; i++) {
				 $("#respostaTrabalhista"+i).bind({
					paste : function(){
						capturaCopia();
					}
				});
			}

            setInterval(manterVivo, 6e5) // a cada 10 minutos
		});

		function capturaCopia() {
			$.getJSON("capturaCopia.php", function(lista) {
				for (index=0; index < lista.length; index++) {
					if(lista[index].loginOk == "S"){
						/*window.location.href='home.php';*/
					}
				}// if FOR
			});
		}

	</script>

</body>
</html>
