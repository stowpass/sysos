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
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.min.css" />
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
                            <?php include 'menu.php' ?>
						</div>
					</div>
				</div><!-- /.panel-->
			</div>

			<div class="col-lg-9">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="col-md-12">
							<h1 class="page-header">
								<i class="fa fa-user color-blue"></i> Cadastrar Candidato</strong>
							</h1>
							<?php
								if (!isset($_GET['idCargo'])){
									die("<div class='alert alert-danger'><i class='fa fa-exclamation-triangle'></i> Selecione um cargo v&aacute;lido!</div>");
								}
							?>
						</div>

						<!-- Mensagens de ALERTA -->
						<div id="msgErro" class="alert alert-danger" style="display: none;">
							<strong>Erro</strong>: Favor, preencha o(s) campo(s) obrigat&oacute;rio(s) em destaque
							<a href="javascript: $('#msgErro').hide(500);" class="pull-right"><em class="fa fa-lg fa-times-circle text-danger"></em></a>
						</div>
						<div id="msgSucesso" class="alert alert-success" style="display: none;">
							<strong>Sucesso</strong>: Candidato cadastrado!
							<a href="javascript: $('#msgSucesso').hide(500);" class="pull-right"><em class="fa fa-lg fa-times-circle text-success"></em></a>
						</div>
						<div class="progress" id="carregando" style="display: none;">
							<div data-percentage="0%" style="width: 100%;" class="progress-bar progress-bar-blue" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
						<p align="center" id="carregandoTexto" style="display: none;" class="text-info"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>
						<!-- FIM Mensagens de ALERTA -->


						<div class="col-md-12">

							<div class="btn-group btn-group-justified" role="group" aria-label="...">
							  <div class="btn-group" role="group">
							    <button id="btnField1" type="button" class="btn btn-info active" onclick="field1()">Dados Gerais</button>
							  </div>
							  <div class="btn-group" role="group">
							    <button id="btnField2" type="button" class="btn btn-default" onclick="field2()">Dados de Acesso</button>
							  </div>
							  <div class="btn-group" role="group">
							    <button id="btnField3" type="button" class="btn btn-default" onclick="field3()">Outras Informa&ccedil;&otilde;es</button>
							  </div>
							</div>

							<form role="form" id="formCadCandidato">

								<fieldset id="field1" style="display: block; margin-top: 20px;">
									<legend>Dados gerais</legend>
									<div class="form-group" id="divCargo">
										<select disabled="disabled" class="form-control" id="cargo" style="border: 1px solid #999;">
											<?php $q=  mysqli_query($bd, "SELECT * FROM tb_cargo where id = '".$_GET['idCargo']."' and status = 'A' order by cargo");
												  while ($rs = mysqli_fetch_array($q)){ ?>
											<option value="<?=$rs['id']?>"><?=$rs['cargo']?></option>
											<?php } ?>
										</select>
									</div>

									<div class="form-group">
										<input type="text" autofocus="" id="nome" class="form-control" placeholder="*Nome do Candidato">
									</div>

									<div class="form-group">
										<input type="text" id="telefone" class="form-control" placeholder="*Telefone(s)">
									</div>

									<div class="form-group">
										<input type="text" id="nascimento" class="form-control" placeholder="Data Nascimento" data-mask="00/00/0000">
									</div>

									<div class="form-group">
										<input type="text" id="endereco" class="form-control" placeholder="*Endere&ccedil;o">
									</div>

									<div class="form-group">
										<input type="text" id="urlFoto" class="form-control" placeholder="URL da imagem">
									</div>
								</fieldset>

								<!-- DADOS ACESSO -->
								<fieldset id="field2" style="display: none; margin-top: 20px;">
									<legend>Dados de acesso &agrave; avalia&ccedil;&atilde;o</legend>
									<div class="form-group" id="divProcesso">
										<select class="form-control" id="processoSeletivo" style="border: 1px solid #999;">
											<option value="">Processo Seletivo</option>
											<option value=""></option>
											<?php $q=  mysqli_query($bd, "SELECT * FROM tb_processo_seletivo where cargo_id = '".$_GET['idCargo']."' and status = 'A' order by id desc");
												  while ($rs = mysqli_fetch_array($q)){ ?>
											<option value="<?=$rs['id']?>"><?=$rs['nome']?></option>
											<?php } ?>
										</select>
									</div>

									<div class="form-group">
										<input type="text" id="email" class="form-control" placeholder="*E-mail">
										<span id="erroEmailInvalido" class="text-danger" style="display: none;"><i class="fa fa-exclamation-triangle"></i> Informe um e-mail v&aacute;lido</span>
									</div>

									<div class="form-group">
										<input type="text" id="senha" class="form-control" placeholder="*Senha">
									</div>

								</fieldset>
								<!-- FIM DADOS ACESSO -->

								<fieldset id="field3" style="display: none; margin-top: 20px;">
									<legend>Outras Informa&ccedil;&otilde;es</legend>
									<div class="form-group">
										<textarea class="form-control" id="obs" rows="3" placeholder="Observa&ccedil;&otilde;es" style="border: 1px solid #999;"></textarea>
									</div>
								</fieldset>

								<hr>
								<button type="button" class="btn btn-primary" onclick="validaCadCandidato()">Cadastrar</button>
								<button type="button" class="btn btn-default" onclick="limparCadCandidato()">Limpar</button>

							</div>
							</form>
						</div>
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
	            <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-user"></i> Concluir cadastro.</h4>
	          </div>
	          <div class="modal-body">
	             <p id="textoLogoff">Deseja concluir o cadastro deste Candidato?</p>
        		 <p align="center" class="text-info" id="carregandoLogoff" style="display: none;"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>
	          </div>
	          <div class="modal-footer">
		          <button id="botaoConfirmar" type="button" class="btn btn-primary" data-dismiss="modal" onclick="concluirCadCandidato()">
		          <i class="fa fa-check"></i> Concluir</button>
		          <button type="button" class="btn btn-default" onClick="javascript:$('#textoLogoff').show(); $('#carregandoLogoff').hide();" data-dismiss="modal">
		          <i class="fa fa-times"></i> Voltar</button>
	          </div>

	        </div>
	      </div>
	    </div>
    	<!-- Fim Modal Confirmacao -->


		<div class="row">

			<?php include "../rodape.php";
            mysqli_close($bd);
			?>

		</div><!--/.row-->
	</div>	<!--/.main-->

    <?php include 'scripts.php' ?>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
	<script type="text/javascript" src="../js/custom.js"></script>
	<script type="text/javascript" src="../js/admin.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			$('.buttonNext').addClass('btn btn-default');
	        $('.buttonNext').html('Pr&oacute;ximo');
	        $('.buttonPrevious').addClass('btn btn-default');
	        $('.buttonPrevious').html('Anterior');

	        $('.buttonFinish').hide();

			$.datepicker.regional['pt-BR'] = {
                closeText: 'Fechar',
                prevText: 'Anterior',
                nextText: 'Pr&oacute;ximo',
                currentText: 'Hoje',
                monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho',
                'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
                monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun',
                'Jul','Ago','Set','Out','Nov','Dez'],
                dayNames: ['Domingo','Segunda-feira','Ter&ccedil;a-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sabado'],
                dayNamesShort: ['D','S','T','Q','Q','S','S'],
                dayNamesMin: ['D','S','T','Q','Q','S','S'],
                weekHeader: 'Sm',
                dateFormat: 'dd/mm/yy',
                firstDay: 0,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''};
	        $.datepicker.setDefaults($.datepicker.regional['pt-BR']);
	        $("#nascimento").datepicker();
		});
	</script>

</body>
</html>
