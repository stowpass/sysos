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

	<?php

	// Quantidade total
	$qu=  mysqli_query($bd, "SELECT * FROM tb_candidato where status != 'E' and trabalhe_conosco = 'S' order by id desc");
	$num_rows_total = mysqli_num_rows($qu);
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
			<div class="col-lg-1">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="col-md-12 no-padding">
                            <h1 class="page-header text-center">
                                <i class="fa fa-list-alt color-blue"></i></strong>
                            </h1>
                            <?php include 'menu_compacto.php' ?>
						</div>
					</div>
				</div><!-- /.panel-->
			</div>

			<?php

			// Media de cadastros por dia
			$qMc = mysqli_query($bd, "SELECT count(id) as totalCadastrosComCv FROM tb_candidato where status != 'E' and trabalhe_conosco = 'S' and arquivo_cv != '' ");
			$lMc = mysqli_fetch_assoc($qMc);

		 	$data1 = new DateTime( $anoCorrente."-".$mesCorrente."-".$diaCorrente );
			$data2 = new DateTime( '2019-03-27' ); // Primeiro CV cadastrado

			$intervalo = $data1->diff( $data2 );

			$mediaCadastros = $lMc['totalCadastrosComCv']/$intervalo->days;

			?>

			<div class="col-lg-11">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="col-md-12">
							<h1 class="page-header">
								<i class="fa fa-briefcase color-blue"></i> Trabalhe Conosco <small>(<?=$num_rows_total?>)<small>| <strong><?php echo round($mediaCadastros, 2)?></strong> cadastros por dia</small></small></strong>
							</h1>
						</div>

						<!-- Mensagens de ALERTA -->
						<div id="msgErro" class="alert alert-danger" style="display: none;">
							<strong>Erro</strong>: Favor, preencha o(s) campo(s) obrigat&oacute;rio(s) em destaque
							<a href="javascript: $('#msgErro').hide(500);" class="pull-right"><em class="fa fa-lg fa-times-circle text-danger"></em></a>
						</div>
						<div id="msgSucesso" class="alert alert-success" style="display: none;">
							<strong>Sucesso</strong>: Candidato exclu&iacute;do!
							<a href="javascript: $('#msgSucesso').hide(500);" class="pull-right"><em class="fa fa-lg fa-times-circle text-success"></em></a>
						</div>
						<div class="progress" id="carregando" style="display: none;">
							<div data-percentage="0%" style="width: 100%;" class="progress-bar progress-bar-blue" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
						<p align="center" id="carregandoTexto" style="display: none;" class="text-info"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>
						<!-- FIM Mensagens de ALERTA -->

						<!-- CONTENT -->
						<?php $q=  mysqli_query($bd, "SELECT * FROM tb_candidato where status != 'E' and trabalhe_conosco = 'S' order by id desc limit 0,500"); ?>
						<div class="col-md-12">

							<table class="table table-responsive" id="tabela-candidatos">
								<thead class="alert-info">
									<tr>
										<th>ID</th>
										<th>Nome</th>
										<th>Detalhes</th>
										<th>Cargo Pleiteado</th>
										<th>Currículo</th>
										<th>Ações</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$num_rows = mysqli_num_rows($q);
									if ($num_rows == 0){
									?>
										<tr id="tr-<?=$rs['id']?>" class="warning text-warning">
									      <td scope="row" colspan="7" align="center">
									      		<i class="fa fa-exclamation-triangle"></i> Nenhum candidato encontrado no sistema!
									      </td>
									    </tr>
								    <?php } ?>
									<?php while ($rs = mysqli_fetch_array($q)){
										// Processo Seletivo
										$qPs = mysqli_query($bd, "SELECT * FROM tb_processo_seletivo where id = '". $rs['processo_atual_id'] ."' ");
										$lPs = mysqli_fetch_assoc($qPs);

										// Cargo
										$qCg = mysqli_query($bd, "SELECT * FROM tb_cargo where id = '". $lPs['cargo_id'] ."' ");
										$lCg = mysqli_fetch_assoc($qCg);


										if ($rs['foto'] == ""){
											$foto = "https://lh3.googleusercontent.com/uFp_tsTJboUY7kue5XAsGA=s120";
										} else {
											$foto = $rs['foto'];
										}


										$telefone = explode(" ", $rs['celular']);

										$telefone2 = explode("-", $telefone[1]);

										$telefone3 = $telefone2[0]."".$telefone2[1];


										$ddd = explode(" ", $rs['celular']);

										$ddd1 = explode(")", $ddd[0]);

										$ddd2 = explode("(", $ddd1[0]);

										$ddd3 = $ddd2[1];


										// Estado - via DDD
										$qD = mysqli_query($bd, "SELECT * FROM ddd where ddd = '". $ddd3 ."' ");
										$lD = mysqli_fetch_assoc($qD);


										$detalhe = "";

										if ($rs['cargo_id'] == 3){ // Advogado
											$detalhe = "Área de Atuação:<br/>".$rs['area_atuacao'];
										}

										if ($rs['cargo_id'] == 5){ // Estagiario
											$detalhe = "Semestre atual:<br/>".$rs['estagio_semestre']."º";
										}


									?>
								    <tr id="tr-<?=$rs['id']?>">
								      <td><?=$rs['id']?></td>
								      <td><?=$rs['nome']?><br/><small><?=$lD['estado']?></small></td>
								      <td><?=$detalhe?></td>
								      <td><?=$lCg['cargo']?><br/><small>Processo Seletivo: <?=$lPs['nome']?></small></td>
								      <td class="text-center"><?php if ($rs['arquivo_cv'] != ''){?><a href="../cvs/<?=$rs['arquivo_cv']?>" target="_Blank"><i class="fa fa-file fa-2x"></i></a><?php } ?></td>
								      <th>
								      	<?php if ( $rs['celular'] != ""){ ?>
								      	<a href="https://web.whatsapp.com/send?phone=55<?=$ddd3?><?=$telefone3?>&text=Ol&aacute;, <?=$rs['nome']?>" class="btn btn-success btn-sm" target="_Blank" title="Conversar via WhatsApp"><i class="fa fa-phone"></i></a>
								      	<?php } ?>
								      	<button type="button" class="btn btn-primary btn-sm" onclick="javascript:$('#modalInfo<?=$rs['id']?>').modal();"><i class="fa fa-info-circle"></i></button>
								      	<?php if ($rs['finalizado'] == 'S'){ ?>
								      	<!--<a title="Exportar respostas PDF" class="btn btn-success btn-sm" href="exportarRespostaUnico.php?id=<?=$rs['id']?>" target="_Blank"><i class="fa fa-file-pdf"></i></a>-->
								      	<?php } ?>

								      	<?php if (isset($_SESSION['id'])){ ?>
								      	<button type="button" class="btn btn-danger btn-sm" onclick="javascript:$('#modalExcluir<?=$rs['id']?>').modal();"><i class="fa fa-trash"></i></button>
								      	<?php } ?>
								      </th>
								    </tr>


								    <!-- Modal Info -->
									<div id="modalInfo<?=$rs['id']?>" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
								      <div class="modal-dialog">
								        <div class="modal-content">

								          <div class="modal-header alert-info">
								            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i></span>
								            </button>
								            <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-info-circle"></i> Detalhes do Candidato</h4>
								          </div>
								          <div class="modal-body">


								          	<div class='alert alert-warning' id="candBanco-<?=$rs['id']?>">Candidato adicionado &agrave; <strong>BASE DE CURR&Iacute;CULOS</strong></div>


								          	<p align="center" id="carregandoTextoAprovDesaprov" style="display: none;" class="text-info"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>

								          	<div id="moveToCandidate-<?=$rs['id']?>" style="display: none;"></div>

								            <p>
								            	<button type="button" class="btn btn-success" onclick="autorizarAvaliacao(<?=$rs['id']?>)"><i class="fa fa-thumbs-up"></i> Autorizar Avalia&ccedil;&atilde;o</button>
								            	<button type="button" class="btn btn-danger" onclick="negarAvaliacao(<?=$rs['id']?>)"><i class="fa fa-thumbs-down"></i> Negar Avalia&ccedil;&atilde;o</button>
								            </p>

								            <p>
								            	<h3 class="text-info">Dados do Candidato</h3>
								            	<div>
													<img src="<?=$foto?>" width="90" height="90" class="img-responsive" alt="" />
													<br/>
													<h4 class="text-primary"><strong><?=$rs['nome']?></strong></h4>
													<hr/>
													- <strong>Processo Seletivo</strong>: <?=$lPs['nome']?><br/>
													- <strong>E-mail</strong>: <?=$rs['email']?><br/>
													- <strong>Telefone</strong>: <?=$rs['celular']?><br/>
													- <strong>Cargo</strong>: <?=$lCg['cargo']?><br/>
													- <strong>Forma&ccedil;&atilde;o</strong>: <?=$rs['formacao']?><br/>

													<?php if ($rs['cargo_id'] == 5){ // Caso seja estagiario ?>
													- <strong>Semestre</strong>: <?=$rs['estagio_semestre']?> <br/>
													- <strong>Num. Matr&iacute;cula</strong>: <?=$rs['estagio_matricula']?> <br/>
													- <strong>Disponibilidade de Hor&aacute;rio</strong>: <?=$rs['estagio_horario']?>
													<?php } ?>
												</div>
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


								    <!-- Modal Confirmacao Exclusao -->
									<div id="modalExcluir<?=$rs['id']?>" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
								      <div class="modal-dialog">
								        <div class="modal-content">

								          <div class="modal-header alert-info">
								            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i></span>
								            </button>
								            <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-user"></i> Excluir Candidato</h4>
								          </div>
								          <div class="modal-body">
								            <p>Deseja realmente excluir o Candidato <strong><?=$rs['nome']?></strong> ?</p>
								          </div>
								          <div class="modal-footer">
									          <button id="botaoConfirmar" type="button" class="btn btn-danger" onclick="excluir(<?=$rs['id']?>, 'candidato')" data-dismiss="modal">
									          <i class="fa fa-trash"></i> Excluir</button>
									          <button type="button" class="btn btn-default" data-dismiss="modal">
									          <i class="fa fa-times"></i> Cancelar</button>
								          </div>

								        </div>
								      </div>
								    </div>
								    <!-- Fim Modal Confirmacao Exclusao -->


								    <?php } ?>
								</tbody>
							</table>

							<hr/>
							<!--<button type="button" class="btn btn-primary" onclick="verificaMarcados()"> <i class="fa fa-check-square"></i> Avaliar Candidato(s) </button>
							<span id="erroMultiplos" class="text-danger" style="display: none;"><i class="fa fa-exclamation-triangle"></i> Marque pelo menos um Candidato!</span>-->

							<!-- Modal Confirmacao Exclusao -->
							<div id="modalVarios" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
						      <div class="modal-dialog">
						        <div class="modal-content">

						          <div class="modal-header alert-info">
						            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i></span>
						            </button>
						            <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-users"></i> Candidato(s) Marcados(s)</h4>
						          </div>
						          <div class="modal-body">
						            <p>Qual a&ccedil;&atilde;o voc&ecirc; deseja realizar ?</p>
						            <p>
						            	<button type="button" class="btn btn-success" id="aprovarVarios"><i class="fa fa-thumbs-up"></i> APROVAR marcado(s)</button>
						            	<button type="button" class="btn btn-warning" id="bancoVarios"><i class="fa fa-address-book"></i> Banco de Vagas</button>
						            	<button type="button" class="btn btn-danger" id="reprovarVarios"><i class="fa fa-thumbs-down"></i> REPROVAR marcado(s)</button>
						            </p>
						          </div>
						          <div class="modal-footer">
							          <button type="button" class="btn btn-default" data-dismiss="modal">
							          <i class="fa fa-times"></i> Fechar</button>
						          </div>

						        </div>
						      </div>
						    </div>
						    <!-- Fim Modal Confirmacao Exclusao -->

						</div>
						<!-- FIM CONTENT -->

					</div>
				</div><!-- /.panel-->

			</div>
		</div><!--/.row-->


		<div class="row">

            <?php
            include "../rodape.php";
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

            $('[data-toggle="popover"]').popover()

			$('#tabela-candidatos').DataTable( {
		        "order": [[ 0, "desc" ]],
                "columnDefs": [
                    { "width": "100px", "targets": 5},
                    { "orderable": false, "targets": [4, 5]}
                ]
		    } );

			$('#cargoSelMenu').change(function() {
			    window.location = 'processoCad.php?idCargo='+$("#cargoSelMenu").val();
			});

			$('#cargoSelMenuCan').change(function() {
			    window.location = 'candidatoCad.php?idCargo='+$("#cargoSelMenuCan").val();
			});

			// Reprovar varios candidatos
			$("#reprovarVarios").click(function(e) {
			    $.each(($("input[type=checkbox]:checked")), function(index, obj){
			        console.log(obj.value);
			        ReprovaCandidato(obj.value);
			    });
			});

			// Aprovar varios candidatos
			$("#aprovarVarios").click(function(e) {
			    $.each(($("input[type=checkbox]:checked")), function(index, obj){
			        console.log(obj.value);
			        aprovaCandidato(obj.value);
			    });
			});

			// Aprovar varios candidatos
			$("#bancoVarios").click(function(e) {
			    $.each(($("input[type=checkbox]:checked")), function(index, obj){
			        console.log(obj.value);
			        BandoDeVagas(obj.value);
			    });
			});

		});

		// Marcar/Desmarcar todos da mesma pagina
		function marcardesmarcar() {
			if ($('#checkMaster').is(":checked")){
		        $('.marcar').each(function () {
			        this.checked = true;
		    	});
	        } else {
	        	 $('.marcar').each(function () {
			        this.checked = false;
		    	});
	        }
		}


		// Checa se tem pelo menos 1 marcado
		function verificaMarcados (){
			var checkbox = $('input:checkbox[name^=check_list]:checked');

		    if(checkbox.length > 0){
		    	$("#erroMultiplos").hide(500);
		    	$('#modalVarios').modal();
		    } else {
		    	$("#erroMultiplos").show(500);
		    }
		}

	</script>

</body>
</html>
