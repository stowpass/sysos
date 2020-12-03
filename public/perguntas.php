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
</head>
<body style="padding-top: 0px;">

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
					<i class="fa fa-exclamation-triangle"></i> O nosso sistema identifica a&ccedil;&otilde;es como "copiar/recortar + colar" portanto, <strong>seja honesto consigo mesmo</strong>!!
				</div>


				<div class="panel panel-default">
					<div class="panel-body">

						<!-- Mensagens de ALERTA -->
						<div id="msgErro" class="alert alert-danger" style="display: none;">
							<strong>Erro</strong>: Favor, responda &agrave;(s) pergunta(s) em destaque
							<a href="javascript: $('#msgErro').hide(500);" class="pull-right"><em class="fa fa-lg fa-times-circle text-danger"></em></a>
						</div>
						<div class="progress" id="carregando" style="display: none;">
							<div data-percentage="0%" style="width: 100%;" class="progress-bar progress-bar-blue" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
						<p align="center" id="carregandoTexto" style="display: none;" class="text-info"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>
						<!-- FIM Mensagens de ALERTA -->


						<div class="col-md-12">
							<form role="form" id="formAval" name="formAval" action="processar.php" method="post">
								<input type="hidden" name="idCandidato" value="<?=$_SESSION["id"]?>">
								<input type="hidden" name="primeiraTentativa" value="<?=$_SESSION["primeiraTentativa"]?>">

								<div class="form-group">
									<label>1) Disserte sobre a linguagem <em>UML</em><br/>
										<button id="br1" style="display: none;" type="button" class="btn btn-danger btn-sm" onclick="javascript: $('#br1').hide(500); $('#resposta1').val(''); $('#resposta1').removeAttr('disabled');"><i class='fa fa-spinner fa-check'></i> Clique para Reabilitar o campo de resposta</button>
									</label>
									<textarea class="form-control" id="resposta1" name="resposta1" rows="3" placeholder="Se n&atilde;o souber a resposta escreva 'N&atilde;o sei'" style="border: 1px solid #999;"></textarea>
								</div>

								<div class="form-group">
									<label>2) Sobre metodologia &aacute;gil, explique o porqu&ecirc; de sua import&acirc;ncia na gest&atilde;o de projetos<br/>
										<button id="br2" style="display: none;" type="button" class="btn btn-danger btn-sm" onclick="javascript: $('#br2').hide(500); $('#resposta2').val(''); $('#resposta2').removeAttr('disabled');"><i class='fa fa-spinner fa-check'></i> Clique para Reabilitar o campo de resposta</button>
									</label>
									<textarea class="form-control" id="resposta2" name="resposta2" rows="3" placeholder="Se n&atilde;o souber a resposta escreva 'N&atilde;o sei'" style="border: 1px solid #999;"></textarea>
								</div>

								<div class="form-group">
									<label>3) As principais atividades de um Analista de Requisitos/Neg&oacute;cios s&atilde;o: <em>Elicitar</em> e <em>Analisar</em> Requisitos. Explique a(s) diferen&ccedil;a(s) entre estas duas atividades<br/>
										<button id="br3" style="display: none;" type="button" class="btn btn-danger btn-sm" onclick="javascript: $('#br3').hide(500); $('#resposta3').val(''); $('#resposta3').removeAttr('disabled');"><i class='fa fa-spinner fa-check'></i> Clique para Reabilitar o campo de resposta</button>
									</label>
									<textarea class="form-control" id="resposta3" name="resposta3" rows="3" placeholder="Se n&atilde;o souber a resposta escreva 'N&atilde;o sei'" style="border: 1px solid #999;"></textarea>
								</div>

								<div class="form-group">
									<label>4) Explique o fluxo que voc&ecirc; costuma utilizar para elaborar um Documento de Requisitos<br/>
										<button id="br4" style="display: none;" type="button" class="btn btn-danger btn-sm" onclick="javascript: $('#br4').hide(500); $('#resposta4').val(''); $('#resposta4').removeAttr('disabled');"><i class='fa fa-spinner fa-check'></i> Clique para Reabilitar o campo de resposta</button>
									</label>
									<textarea class="form-control" id="resposta4" name="resposta4" rows="3" placeholder="Se n&atilde;o souber a resposta escreva 'N&atilde;o sei'" style="border: 1px solid #999;"></textarea>
								</div>

								<div class="form-group">
									<label>5) "<em>Programa&ccedil;&atilde;o Orientada a Objetos (POO) &eacute; um conceito que pode ser aplicado &agrave; linguagem JAVA, por&eacute;m não &agrave; linguagem PHP</em>". Explique o porquê desta afirma&ccedil;&atilde;o ser verdadeira ou falsa, deixando claro o significado de POO<br/>
										<button id="br5" style="display: none;" type="button" class="btn btn-danger btn-sm" onclick="javascript: $('#br5').hide(500); $('#resposta5').val(''); $('#resposta5').removeAttr('disabled');"><i class='fa fa-spinner fa-check'></i> Clique para Reabilitar o campo de resposta</button>
									</label>
									<textarea class="form-control" id="resposta5" name="resposta5" rows="3" placeholder="Se n&atilde;o souber a resposta escreva 'N&atilde;o sei'" style="border: 1px solid #999;"></textarea>
								</div>

								<hr>
								<button type="button" class="btn btn-primary" onclick="validaRespostas()">Enviar</button>

						</div>
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

			$("#resposta1").bind({
				copy : function(){
					$('#modalVacilo').modal();
					$('#resposta1').val('TEXTO COPIADO >>>>> ');
					$('#resposta1').attr('disabled', 'disabled');
					$('#br1').show();
					capturaCopia();
				},
				paste : function(){
					$('#modalVacilo').modal();
					$('#resposta1').val('TEXTO COPIADO >>>>> ');
					$('#resposta1').attr('disabled', 'disabled');
					$('#br1').show();
					capturaCopia();
				},
				cut : function(){
					$('#modalVacilo').modal();
					$('#resposta1').val('TEXTO COPIADO >>>>> ');
					$('#resposta1').attr('disabled', 'disabled');
					$('#br1').show();
					capturaCopia();
				}
			});

			$("#resposta2").bind({
				copy : function(){
					$('#modalVacilo').modal();
					$('#resposta2').val('TEXTO COPIADO >>>>> ');
					$('#resposta2').attr('disabled', 'disabled');
					$('#br2').show();
					capturaCopia();
				},
				paste : function(){
					$('#modalVacilo').modal();
					$('#resposta2').val('TEXTO COPIADO >>>>> ');
					$('#resposta2').attr('disabled', 'disabled');
					$('#br2').show();
					capturaCopia();
				},
				cut : function(){
					$('#modalVacilo').modal();
					$('#resposta2').val('TEXTO COPIADO >>>>> ');
					$('#resposta2').attr('disabled', 'disabled');
					$('#br2').show();
					capturaCopia();
				}
			});

			$("#resposta3").bind({
				copy : function(){
					$('#modalVacilo').modal();
					$('#resposta3').val('TEXTO COPIADO >>>>> ');
					$('#resposta3').attr('disabled', 'disabled');
					$('#br3').show();
					capturaCopia();
				},
				paste : function(){
					$('#modalVacilo').modal();
					$('#resposta3').val('TEXTO COPIADO >>>>> ');
					$('#resposta3').attr('disabled', 'disabled');
					$('#br3').show();
					capturaCopia();
				},
				cut : function(){
					$('#modalVacilo').modal();
					$('#resposta3').val('TEXTO COPIADO >>>>> ');
					$('#resposta3').attr('disabled', 'disabled');
					$('#br3').show();
					capturaCopia();
				}
			});

			$("#resposta4").bind({
				copy : function(){
					$('#modalVacilo').modal();
					$('#resposta4').val('TEXTO COPIADO >>>>> ');
					$('#resposta4').attr('disabled', 'disabled');
					$('#br4').show();
					capturaCopia();
				},
				paste : function(){
					$('#modalVacilo').modal();
					$('#resposta4').val('TEXTO COPIADO >>>>> ');
					$('#resposta4').attr('disabled', 'disabled');
					$('#br4').show();
					capturaCopia();
				},
				cut : function(){
					$('#modalVacilo').modal();
					$('#resposta4').val('TEXTO COPIADO >>>>> ');
					$('#resposta4').attr('disabled', 'disabled');
					$('#br4').show();
					capturaCopia();
				}
			});

			$("#resposta5").bind({
				copy : function(){
					$('#modalVacilo').modal();
					$('#resposta5').val('TEXTO COPIADO >>>>> ');
					$('#resposta5').attr('disabled', 'disabled');
					$('#br5').show();
					capturaCopia();
				},
				paste : function(){
					$('#modalVacilo').modal();
					$('#resposta5').val('TEXTO COPIADO >>>>> ');
					$('#resposta5').attr('disabled', 'disabled');
					$('#br5').show();
					capturaCopia();
				},
				cut : function(){
					$('#modalVacilo').modal();
					$('#resposta5').val('TEXTO COPIADO >>>>> ');
					$('#resposta5').attr('disabled', 'disabled');
					$('#br5').show();
					capturaCopia();
				}
            });

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
