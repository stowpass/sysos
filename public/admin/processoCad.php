<?php include_once __DIR__ . '/../variaveis.php';
  session_start();
  require_once __DIR__ . '/../includes/funcoes.php';

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
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
</head>
<body style="padding-top: 0px;">

	<?php
	// ATENCAO: Algumas variáves estão no arquivo variaveis.php onde este é chamado dentro das páginas

	// Verifica usuario logado
	if (!isset($_SESSION['id'])){
		redirect("logoff.php");
		die();
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
			<div class="col-lg-2">
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

			<div class="col-lg-10">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="col-md-12">
							<h1 class="page-header">
								<i class="fa fa-book color-blue"></i> Criar Processo Seletivo</strong>
							</h1>
						</div>

						<!-- Mensagens de ALERTA -->
						<div id="msgErro" class="alert alert-danger" style="display: none;">
							<strong>Erro</strong>: Favor, preencha o(s) campo(s) obrigat&oacute;rio(s) em destaque
							<a href="javascript: $('#msgErro').hide(500);" class="pull-right"><em class="fa fa-lg fa-times-circle text-danger"></em></a>
						</div>
						<div id="msgSucesso" class="alert alert-success" style="display: none;">
							<strong>Sucesso</strong>: Processo Seletivo cadastrado!
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
							    <button id="btnField2" type="button" class="btn btn-default" onclick="field2()">Quest&otilde;es da Avalia&ccedil;&atilde;o</button>
							  </div>
							  <div class="btn-group" role="group">
							    <button id="btnField3" type="button" class="btn btn-default" onclick="field3()">Outras Informa&ccedil;&otilde;es</button>
							  </div>
							</div>

							<form role="form" id="formProcessoSeletivo">

								<fieldset id="field1" style="display: block; margin-top: 20px;">
									<legend>Dados gerais</legend>

									<div class="form-group">
										<input type="text" id="nomeProcesso" autofocus="" onblur="verificaDuplicidade()" class="form-control" placeholder="*Nome do Processo">
									</div>

									<div class="form-group" id="divCargo">
										<select disabled="disabled" class="form-control" id="cargo" style="border: 1px solid #999;">
											<?php $q=  mysqli_query($bd, "SELECT * FROM tb_cargo where status = 'A' and id = '".$_GET['idCargo']."' order by cargo");
												  while ($rs = mysqli_fetch_array($q)){ ?>
											<option value="<?=$rs['id']?>"><?=$rs['cargo']?></option>
											<?php } ?>
										</select>
									</div>

									<div class="form-group">
										<input type="text" id="dataInicio" class="form-control" placeholder="*Data in&iacute;cio do Processo">
									</div>

								</fieldset>

								<!-- QUESTOES -->
								<fieldset id="field2" style="display: none; margin-top: 20px;">
									<legend>Selecionar quest&otilde;es da avalia&ccedil;&atilde;o deste Processo Seletivo</legend>

									<!-- contador escolhidas -->
									<input type="hidden" name="quantEscolhas" id="quantEscolhas">

									<!-- Respostas Selecionadas -->
									<input type="hidden" name="pergunta1" id="pergunta1">
									<input type="hidden" name="pergunta2" id="pergunta2">
									<input type="hidden" name="pergunta3" id="pergunta3">
									<input type="hidden" name="pergunta4" id="pergunta4">
									<input type="hidden" name="pergunta5" id="pergunta5">

									<?php $q=  mysqli_query($bd, "SELECT * FROM tb_perguntas where cargo = '".$_GET['idCargo']."' and status = 'A'"); ?>
									<table id="listaPerguntas" class="table table-responsive">
										<thead class="alert-info">
											<tr>
												<th>ID</th>
												<th>Tag</th>
												<th>Pergunta</th>
												<th><button type="button" class="btn btn-info" onclick="desmarcaPergunta()"> <i class="fa fa-undo-alt"></i> </button></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$num_rows = mysqli_num_rows($q);
											if ($num_rows == 0){
											?>
												<tr id="tr-<?=$rs['id']?>" class="warning text-warning">
											      <td scope="row" colspan="7" align="center">
											      		<i class="fa fa-exclamation-triangle"></i> Nenhuma pergunta encontrada no sistema para o cargo selecionado!
											      </td>
											    </tr>
										    <?php } ?>

											<?php while ($rs = mysqli_fetch_array($q)){

												$nivel = "";

												if ($rs['nivel'] == "F"){
													$nivel = "F&aacute;cil";
												}
												if ($rs['nivel'] == "I"){
													$nivel = "Intermedi&aacute;rio";
												}
												if ($rs['nivel'] == "D"){
													$nivel = "Dif&iacute;cil";
												}
											?>
										    <tr id="tr-<?=$rs['id']?>">
										      <th scope="row">
										      	#<?=$rs['id']?>
										      </th>
										      <td><?=$rs['palavra_chave']?></td>
										      <td><?=$rs['pergunta']?></td>
										      <th>
										      	<button id="btnPerg-<?=$rs['id']?>" title="Clique para selecionar esta pergunta" type="button" class="btn btn-default semClasse" onclick="selecionarPergunta(<?=$rs['id']?>)"><i class="fa fa-minus"></i></button>
										      </th>
										    </tr>

										    <?php } ?>
										</tbody>
									</table>

								</fieldset>
								<!-- FIM QUESTOES -->

								<fieldset id="field3" style="display: none; margin-top: 20px;">
									<legend>Outras Informa&ccedil;&otilde;es</legend>
									<div class="form-group">
										<textarea class="form-control" id="obs" rows="3" placeholder="Observa&ccedil;&otilde;es" style="border: 1px solid #999;"></textarea>
									</div>
								</fieldset>

								<hr>
								<button type="button" class="btn btn-primary" onclick="validaProcessoSeletivo()">Cadastrar</button>
								<button type="button" class="btn btn-default" onclick="limparFormProcessoSeletivo()">Limpar</button>

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
	            <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-book"></i> Concluir cadastro. </h4>
	          </div>
	          <div class="modal-body">
	             <p id="textoLogoff">Deseja concluir o cadastro deste Processo Seletivo?</p>
        		 <p align="center" class="text-info" id="carregandoLogoff" style="display: none;"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>
	          </div>
	          <div class="modal-footer">
		          <button id="botaoConfirmar" type="button" class="btn btn-primary" data-dismiss="modal" onclick="concluirCadastroProcessoSeletivo()">
		          <i class="fa fa-check"></i> Concluir</button>
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
    <!-- <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script> -->
	<script type="text/javascript" src="../dts/js/jquery.dataTables.js"></script>
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
	        $("#dataInicio").datepicker();

	        $("#listaPerguntas").DataTable();
		});
	</script>

</body>
</html>
