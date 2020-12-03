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
								<i class="fa fa-book color-blue"></i> Processos Seletivos</strong>
							</h1>
						</div>

						<!-- Mensagens de ALERTA -->
						<div id="msgErro" class="alert alert-danger" style="display: none;">
							<strong>Erro</strong>: Favor, preencha o(s) campo(s) obrigat&oacute;rio(s) em destaque
							<a href="javascript: $('#msgErro').hide(500);" class="pull-right"><em class="fa fa-lg fa-times-circle text-danger"></em></a>
						</div>
						<div id="msgSucesso" class="alert alert-success" style="display: none;">
							<strong>Sucesso</strong>: Processo Seletivo exclu&iacute;do!
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
						<?php $q=  mysqli_query($bd, "SELECT * FROM tb_processo_seletivo where status = 'A'"); ?>
						<div class="col-md-12">

							<table class="table table-responsive" id="tabela-processos">
								<thead class="alert-info">
									<tr>
										<th>ID</th>
										<th>Processo</th>
										<th>In&iacute;cio</th>
										<th>Cargo</th>
										<th>A&ccedil;&otilde;es</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$num_rows = mysqli_num_rows($q);
									if ($num_rows == 0){
									?>
										<tr id="tr-<?=$rs['id']?>" class="warning text-warning">
									      <td scope="row" colspan="5" align="center">
									      		<i class="fa fa-exclamation-triangle"></i> Nenhum Processo Seletivo encontrado no sistema!
									      </td>
									    </tr>
								    <?php } ?>
									<?php while ($rs = mysqli_fetch_array($q)){
										// Cargo
										$qCg = mysqli_query($bd, "SELECT * FROM tb_cargo where id = '". $rs['cargo_id'] ."' ");
										$lCg = mysqli_fetch_assoc($qCg);


									?>
								    <tr id="tr-<?=$rs['id']?>">
								      <th scope="row">
								      	<?=$rs['id']?>
								      </th>
								      <td><?=$rs['nome']?></td>
								      <td><?=$rs['data_inicio']?></td>
								      <td><?=$lCg['cargo']?></td>
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
								            <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-info-circle"></i> Detalhes do Processo Seletivo</h4>
								          </div>
								          <div class="modal-body">
								            <p>
								            	<h3 class="text-info">Dados do Processo</h3>
								            	<div>
													- <strong>Nome</strong>: <?=$rs['nome']?><br/>
													- <strong>Cargo</strong>: <?=$lCg['cargo']?><br/>
													- <strong>Data cadastrado</strong>: <?=$rs['data_criacao']?><br/>
													- <strong>Data In&iacute;cio</strong>: <?=$rs['data_inicio']?><br/>
													- <strong>Andamento</strong>: Criado<br/>
												</div>
												<br/>

												<div>
													<h3 class="text-info">Pergustas Selecionadas</h3>
														<?php
														$p = 1;
														$qQ=  mysqli_query($bd, "SELECT * FROM tb_processo_perguntas pp, tb_processo_seletivo ps, tb_perguntas p
																					  where pp.processo_seletivo_id = ps.id
																					  and pp.pergunta_id = p.id
																					  and pp.status = 'A'
																					  and pp.processo_seletivo_id = '".$rs['id']."' ");
														while ($rsQ = mysqli_fetch_array($qQ)){
														?>

														<strong><?=$p?>)</strong> <?=$rsQ['pergunta']?><br/><br/>

														<?php $p++; } ?>
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
								            	Deseja realmente excluir o seguinte Processo Seletivo?
								            	<br/>
								            	<strong><?=$rs['nome']?></strong>
												<div class="clear"></div>

								            </p>
								          </div>
								          <div class="modal-footer">
									          <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="excluir(<?=$rs['id']?>, 'processoSeletivo')">
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

		$("#tabela-processos").DataTable({
            columnDefs: [
                { orderable: false, targets: 4 }
            ]
        });

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
