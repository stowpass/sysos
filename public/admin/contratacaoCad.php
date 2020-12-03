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
	<link href="../css/datepicker3.css" rel="stylesheet">
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
			<div class="col-lg-2">
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

			<div class="col-lg-10">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="col-md-12">
							<h1 class="page-header">
								<i class="fa fa-clipboard color-blue"></i> Solicitar Contrata&ccedil;&atilde;o</strong>
							</h1>
						</div>

						<!-- Mensagens de ALERTA -->
						<div id="msgErro" class="alert alert-danger" style="display: none;">
							<strong>Erro</strong>: Favor, preencha o(s) campo(s) obrigat&oacute;rio(s) em destaque
							<a href="javascript: $('#msgErro').hide(500);" class="pull-right"><em class="fa fa-lg fa-times-circle text-danger"></em></a>
						</div>
						<div id="msgSucesso" class="alert alert-success" style="display: none;">
							<strong>Sucesso</strong>: Solicita&ccedil;&atilde;o cadastrada!
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
							    <button id="btnField2" type="button" class="btn btn-default" onclick="field2()">Campos para uso do GDH</button>
							  </div>
							  <div class="btn-group" role="group">
							    <button id="btnField3" type="button" class="btn btn-default" onclick="field3()">Outras Informa&ccedil;&otilde;es</button>
							  </div>
							</div>

							<form role="form" id="formSolicitaContratacao">

								<fieldset id="field1" style="display: block; margin-top: 20px;">
									<legend>Dados gerais</legend>
									<div class="form-group">
										<select class="form-control" autofocus="" id="equipe" style="border: 1px solid #999;">
											<option value="">*Equipe Solicitante</option>
											<?php $q=  mysqli_query($bd, "SELECT * FROM tb_area where status = 'A' order by area");
												  while ($rs = mysqli_fetch_array($q)){ ?>
											<option value="<?=$rs['id']?>"><?=$rs['area']?></option>
											<?php } ?>
										</select>
									</div>

									<div class="form-group">
										<input type="text" id="pontoFocal" class="form-control" placeholder="*Ponto Focal/Gestor">
									</div>

									<div class="form-group">
										<input type="hidden" name="" id="motivoContratacao">
										<label id="lblMotivo">*Motivo da Contrata&ccedil;&atilde;o</label>
										<div class="radio">
											<button id="btnAumentoQuadro" type="button" class="btn btn-default" onclick="selAumentoQuadro()">
												<i id="iAumentoQuadro" style="display: none;" class="fa fa-check"></i> Aumento de Quadro
											</button> ou
											<button id="btnSubstituicao" type="button" class="btn btn-default" onclick="selSubstituicao()">
												<i id="iSubstituicao" style="display: none;" class="fa fa-check"></i> Substitui&ccedil;&atilde;o
											</button>
										</div>
									</div>
									<div class="form-group" id="divColabSubs" style="display: none;">
										<input type="text" id="colaboradorSubstituido" class="form-control" placeholder="*Indicar colaborador a ser substitu&iacute;do">
									</div>

									<div class="form-group">
										<input type="hidden" name="" id="modalidadeContratacao">
										<label id="lblModalidade">*Modalidade de Contrata&ccedil;&atilde;o</label>
										<div class="radio">
											<button id="btnAdv" type="button" class="btn btn-default" onclick="selAdv()">
												<i id="iAdv" style="display: none;" class="fa fa-check"></i> Advogado(a)
											</button> ou
											<button id="btnEstagio" type="button" class="btn btn-default" onclick="selEstagio()">
												<i id="iEstagio" style="display: none;" class="fa fa-check"></i> Estagi&aacute;rio(a)
											</button> ou
											<button id="btnFunc" type="button" class="btn btn-default" onclick="selFunc()">
												<i id="iFunc" style="display: none;" class="fa fa-check"></i> Funcion&aacute;rio(a)
											</button>
										</div>
									</div>
									<div class="form-group" id="divCargo" style="display: none;">
										<select class="form-control" autofocus="" id="cargo" style="border: 1px solid #999;">
											<option value="">*Especifique o Cargo</option>
											<?php $q=  mysqli_query($bd, "SELECT * FROM tb_cargo where status = 'A' order by cargo");
												  while ($rs = mysqli_fetch_array($q)){ ?>
											<option value="<?=$rs['id']?>"><?=$rs['cargo']?></option>
											<?php } ?>
										</select>
									</div>

									<div class="form-group">
										<input type="text" id="horarioTrabalho" class="form-control" placeholder="Hor&aacute;rio de Trabalho" data-mask="00:00 - 00:00">
									</div>

									<div class="form-group">
										<input type="text" id="grupoEmails" class="form-control" placeholder="Grupo de e-mails necess&aacute;rios">
									</div>

									<div class="form-group">
										<input type="text" id="pastaRede" class="form-control" placeholder="Pastas de rede necess&aacute;rias">
									</div>
								</fieldset>

								<!-- USO GDH -->
								<fieldset id="field2" style="display: none; margin-top: 20px;">
									<legend>Campos para uso do GDH</legend>
									<div class="form-group">
										<input type="text" id="nomeNovoColaborador" class="form-control" placeholder="*Nome do novo colaborador">
									</div>

									<div class="form-group">
										<div class="input-group">
									      <input type="text" id="emailSugerido" class="form-control" placeholder="*Sugest&atilde;o de E-mail">
									      <div class="input-group-addon">@rochamarinho.adv.br</div>
									    </div>
									</div>

									<div class="form-group">
										<button type="button" class="btn btn-default" onclick="javascript:$('#modalBase').modal();"><i class="fa fa-building"></i></button>
									</div>

									<div class="form-group">
										<input type="hidden" id="baseAlocacaoSelecionada">
										<input type="text" id="baseAlocacao" class="form-control" placeholder="Base de aloca&ccedil;&atilde;o">
									</div>

									<div class="form-group">
										<input type="text" id="dataInicio" class="form-control" placeholder="Data in&iacute;cio das atividades">
									</div>
								</fieldset>
								<!-- FIM USO GDH -->

								<fieldset id="field3" style="display: none; margin-top: 20px;">
									<legend>Outras Informa&ccedil;&otilde;es</legend>
									<div class="form-group">
										<textarea class="form-control" id="obs" rows="3" placeholder="Observa&ccedil;&otilde;es" style="border: 1px solid #999;"></textarea>
									</div>
								</fieldset>

								<hr>
								<button type="button" class="btn btn-primary" onclick="validaSolicitarContratacao()">Solicitar</button>
								<button type="button" class="btn btn-default" onclick="limparContratacao()">Limpar</button>

							</div>
							</form>
						</div>
					</div>

					</div>
				</div><!-- /.panel-->

			</div>
		</div><!--/.row-->


		<!-- Modal Motivos -->
		<div id="modalBase" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
	      <div class="modal-dialog">
	        <div class="modal-content">

	          <div class="modal-header alert-info">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle-o"></i></span>
	            </button>
	            <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-building"></i> Selecionar Base de Aloca&ccedil;&atilde;o</h4>
	          </div>
	          <div class="modal-body">
	            <table class="table table-responsive">
	            	<thead>
	            		<tr class="text-info">
	            			<th>Descri&ccedil;&atilde;o</th>
	            		</tr>
	            		<tbody>
	            			<?php $qM=  mysqli_query($bd, "SELECT * FROM tb_escritorios where status = 'A' order by nome");
	            			 while ($rsM = mysqli_fetch_array($qM)){ ?>
	            			 <tr>
	            			 	<td><a class="btn" href="javascript:void(0);" onclick="$('#baseAlocacao').focus(); $('#baseAlocacao').val('<?=$rsM['nome']?>'); $('#baseAlocacaoSelecionada').val('<?=$rsM['id']?>'); $('#modalBase').modal('hide');"><i class="fa fa-building"></i>&nbsp; <?=$rsM['nome']?></a></td>
	            			 </tr>
	            			 <?php } ?>
	            		</tbody>
	            	</thead>
	            </table>
	          </div>
	          <div class="modal-footer">
		          <button type="button" class="btn btn-default" data-dismiss="modal">
		          <i class="fa fa-times"></i> Fechar</button>
	          </div>

	        </div>
	      </div>
	    </div>
	    <!-- Fim Modal Motivos -->


		<!-- Modal Confirmacao -->
		<div id="modalConfirmar" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
	      <div class="modal-dialog">
	        <div class="modal-content">

	          <div class="modal-header alert-info">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i></span>
	            </button>
	            <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-clipboard"></i> Concluir cadastro.</h4>
	          </div>
	          <div class="modal-body">
	             <p id="textoLogoff">Deseja concluir o cadastro desta Solicita&ccedil;&atilde;o de Contrata&ccedil;&atilde;o?</p>
        		 <p align="center" class="text-info" id="carregandoLogoff" style="display: none;"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>
	          </div>
	          <div class="modal-footer">
		          <button id="botaoConfirmar" type="button" class="btn btn-primary" data-dismiss="modal" onclick="concluirCadastroSolicitacao()">
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
	        $("#dataInicio").datepicker();
		});
	</script>

</body>
</html>
