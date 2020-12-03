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
			<div class="col-lg-1">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="col-md-12 no-padding">
                            <h1 class="page-header text-center">
                                <i class="fa fa-list-alt color-blue"></i></strong>
                            </h1>
                            <?php include "menu_compacto.php"; ?>
						</div>
					</div>
				</div><!-- /.panel-->
			</div>

			<div class="col-lg-11">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="col-md-12">
							<h1 class="page-header">
								<i class="fa fa-clipboard color-blue"></i> Solicita&ccedil;&otilde;es de Contrata&ccedil;&otilde;es</strong>
							</h1>
						</div>

						<!-- Mensagens de ALERTA -->
						<div id="msgErro" class="alert alert-danger" style="display: none;">
							<strong>Erro</strong>: Favor, preencha o(s) campo(s) obrigat&oacute;rio(s) em destaque
							<a href="javascript: $('#msgErro').hide(500);" class="pull-right"><em class="fa fa-lg fa-times-circle text-danger"></em></a>
						</div>
						<div id="msgSucesso" class="alert alert-success" style="display: none;">
							<strong>Sucesso</strong>: Solicita&ccdeil;&atilde;o de Contrata&ccedil;&atilde;o exclu&iacute;da!
							<a href="javascript: $('#msgSucesso').hide(500);" class="pull-right"><em class="fa fa-lg fa-times-circle text-success"></em></a>
						</div>
						<div class="progress" id="carregando" style="display: none;">
							<div data-percentage="0%" style="width: 100%;" class="progress-bar progress-bar-blue" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
						<p align="center" id="carregandoTexto" style="display: none;" class="text-info"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>
						<!-- FIM Mensagens de ALERTA -->

						<!-- Mensagens de ALERTA -->
						<?php include "alertas.php" ?>
						<!-- FIM Mensagens de ALERTA -->

						<!-- CONTENT -->
						<?php $q=  mysqli_query($bd, "SELECT * FROM tb_solicitar_contratacao where status = 'A'"); ?>
						<div class="col-md-12">

							<table class="table table-responsive" id="tabela-contratacoes">
								<thead class="alert-info">
									<tr>
										<th>ID</th>
										<th>Equipe Solicitante</th>
										<th>Novo Colaborador</th>
										<th>Ponto Focal</th>
										<th>Modalidade</th>
										<th>A&ccedil;&otilde;es</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$num_rows = mysqli_num_rows($q);
									if ($num_rows == 0){
									?>
										<tr id="tr-<?=$rs['id']?>" class="warning text-warning">
									      <td scope="row" colspan="6" align="center">
									      		<i class="fa fa-exclamation-triangle"></i> Nenhuma Solicita&ccedil;&atilde;o de Contrata&ccedil;&atilde;o encontrada no sistema!
									      </td>
									    </tr>
								    <?php } ?>
									<?php while ($rs = mysqli_fetch_array($q)){

										// Equipe Solicitante
										$qEs = mysqli_query($bd, "SELECT * FROM tb_area where id = '". $rs['equipe_id'] ."' ");
										$lEs = mysqli_fetch_assoc($qEs);

										$cargo = "";
										$modalidade = "";
										if ($rs['modalidade_contratacao'] == "F"){
											// Cargo
											$qCg = mysqli_query($bd, "SELECT * FROM tb_cargo where id = '". $rs['cargo_id'] ."' ");
											$lCg = mysqli_fetch_assoc($qCg);

											$cargo = $lCg['cargo'];
											$modalidade = "Funcion&aacute;rio(a)";
										}

										if ($rs['modalidade_contratacao'] == "A"){
											// Cargo
											$cargo = "Advogado(a)";
											$modalidade = "Advogado(a)";
										}

										if ($rs['modalidade_contratacao'] == "E"){
											// Cargo
											$cargo = "Estagi&aacute;rio(a)";
											$modalidade = "Estagi&aacute;rio(a)";
										}

										// Base
										$qBa = mysqli_query($bd, "SELECT * FROM tb_escritorios where id = '". $rs['base_alocacao'] ."' ");
										$lBa = mysqli_fetch_assoc($qBa);


									?>
								    <tr id="tr-<?=$rs['id']?>">
								      <th scope="row">
								      	<?=$rs['id']?>
								      </th>
								      <td><?=$lEs['area']?></td>
								      <td><?=$rs['nome_novo_colaborador']?><br/><small><?=$cargo?></small></td>
								      <td><?=$rs['ponto_focal']?></td>
								      <td><?php echo ($rs['motivo_contratacao'] == "A") ? "Aumento de Quadro" : "Substitui&ccedil;&atilde;o"; ?><br/><small><?=$modalidade?></small></td>
								      <th>
								      	<button type="button" class="btn btn-primary btn-sm" onclick="javascript:$('#modalInfo<?=$rs['id']?>').modal();"><i class="fa fa-info-circle"></i></button>
								      	<button type="button" class="btn btn-danger btn-sm" onclick="javascript:$('#modalExcluir<?=$rs['id']?>').modal();"><i class="fa fa-trash"></i></button>
								      </th>
								    </tr>


								    <!-- Modal Info -->
									<div id="modalInfo<?=$rs['id']?>" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
								      <div class="modal-dialog">
								        <div class="modal-content">

								          <div class="modal-header alert-info">
								            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i></span>
								            </button>
								            <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-clipboard"></i> Detalhes da Solicita&ccedil;&atilde;o</h4>
								          </div>
								          <div class="modal-body">
								            <p>
								            	<h3 class="text-info">Dados do Colaborador</h3>
								            	<div>
													- <strong>Nome</strong>: <?=$rs['nome_novo_colaborador']?><br/>
													- <strong>Sugest&atilde;o de E-mail</strong>: <?=$rs['sugestao_email']?>@rochamarinho.adv.br<br/>
													- <strong>Ponto Focal</strong>: <?=$rs['ponto_focal']?><br/>
													- <strong>Base de Aloca&ccedil;&atilde;o</strong>: <?=$lBa['nome']?><br/>
												</div>

												<h3 class="text-info">Dados do Cargo</h3>
												<div>
													- <strong>Equipe Solicitante</strong>: <?=$lEs['area']?><br/>
													- <strong>Motivo da Contrata&ccedil;&atilde;o</strong>: <?php echo ($rs['motivo_contratacao'] == "A") ? "Aumento de Quadro" : "Substitui&ccedil;&atilde;o"; ?><br/>
													- <strong>Modalidade da Contrata&ccedil;&atilde;o</strong>: <?=$modalidade?><br/>
													- <strong>Cargo</strong>: <?=$cargo?><br/>
													- <strong>In&iacute;cio</strong>: <?=$rs['data_inicio_atividades']?><br/>
													- <strong>Hor&aacute;rio</strong>: <?=$rs['horario_trabalho']?><br/>
												</div>

												<h3 class="text-info">Mais detalhes</h3>
												<div>
													<?=$rs['obs']?>
												</div>

												<div class="clear"></div>

								            </p>
								          </div>
								          <div class="modal-footer">
									          <button type="button" class="btn btn-default" data-dismiss="modal">
									          <i class="fa fa-times"></i> Fechar</button>
								          </div>

								        </div>
								      </div>
								    </div>
								    <!-- Fim Modal Info -->


								    <!-- Modal Info -->
									<div id="modalExcluir<?=$rs['id']?>" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
								      <div class="modal-dialog">
								        <div class="modal-content">

								          <div class="modal-header alert-info">
								            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i></span>
								            </button>
								            <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-trash"></i> Excluir Processo Seletivo</h4>
								          </div>
								          <div class="modal-body">
								            <p>
								            	Deseja realmente excluir esta solicita&ccedil;&atilde;o ?
								            	<br/>
								            	Novo Colaborador: <strong><?=$rs['nome_novo_colaborador']?></strong>
												<div class="clear"></div>

								            </p>
								          </div>
								          <div class="modal-footer">
									          <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="excluir(<?=$rs['id']?>, 'contratacao')">
									          <i class="fa fa-trash"></i> Excluir</button>
									          <button type="button" class="btn btn-default" data-dismiss="modal">
									          <i class="fa fa-times"></i> Voltar</button>
								          </div>

								        </div>
								      </div>
								    </div>
								    <!-- Fim Modal Info -->


								    <?php } ?>
								</tbody>
							</table>

						</div>
						<!-- FIM CONTENT -->

					</div>
				</div><!-- /.panel-->

			</div>
		</div><!--/.row-->


		<div class="row">

			<?php include "../rodape.php";
            mysqli_close($bd);
			?>

		</div><!--/.row-->
	</div>	<!--/.main-->

    <?php include 'scripts.php' ?>
    <script type="text/javascript" src="../dts/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="../js/custom.js"></script>
    <script type="text/javascript" src="../js/admin.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {

            $('[data-toggle=popover]').popover()

            $("#tabela-processos").DataTable();

            $('#cargoSelMenu').change(function() {
                window.location = 'processoCad.php?idCargo='+$("#cargoSelMenu").val();
            });

            $('#cargoSelMenuCan').change(function() {
                window.location = 'candidatoCad.php?idCargo='+$("#cargoSelMenuCan").val();
            });

		});
	</script>

</body>
</html>
