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
    <link rel="stylesheet" type="text/css" href="../dts/css/jquery.dataTables.css">
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
                                <i class="fa fa-list-alt color-blue"></i>
                            </h1>
                            <?php include 'menu_compacto.php' ?>
						</div>
					</div>
				</div><!-- /.panel-->
			</div>

			<div class="col-lg-11">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="col-md-12">
							<h1 class="page-header">
								<i class="fa fa-user color-blue"></i> Candidatos <small name="candidatos_total">(#)</small>
                                <!-- <small><a href="maps.php"><i class="fa fa-map-marker-alt"></i></a></small> -->

                                <span style="float: right;">
                                    <span id="erroMultiplos" class="text-danger" style="display: none; font-size:  16px">
                                        <i class="fa fa-exclamation-triangle"></i> Marque pelo menos um candidato!
                                    </span>
                                    <button type="button" class="btn btn-primary" onclick="verificaMarcados()"> <i class="fa fa-check-square"></i> Avaliar Candidato(s) </button>
                                </span>

								<!-- <small>
                                    <span style="float: right;">
                                        <button class="btn btn-primary" onclick="javascript:$('#modalFicha').modal();"><i class="fa fa-folder-open"></i> Ficha individual</button>
                                        </span>
                                </small> -->
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
						<div class="col-md-12">

							<table class="table table-responsive" id="tabela-candidatos">
								<thead class="alert-info">
									<tr>
										<th>
											<div align="center">
												<input id="checkMaster" style="height: 20px; width: 20px; background-color: #eee;" type="checkbox" onclick="marcardesmarcar()" />
											</div>
                                        </th>
										<th></th>
										<th>Nome</th>
										<th>Cargo Pleiteado</th>
										<th>Avalia&ccedil;&atilde;o</th>
										<th>A&ccedil;&otilde;es</th>
									</tr>
								</thead>
							</table>

							<!-- <hr/>

							<button type="button" class="btn btn-primary" onclick="verificaMarcados()"> <i class="fa fa-check-square"></i> Avaliar Candidato(s) </button>
							<span id="erroMultiplos" class="text-danger" style="display: none;"><i class="fa fa-exclamation-triangle"></i> Marque pelo menos um Candidato!</span> -->

							<!-- Modal Confirmacao Exclusao -->
							<div id="modalVarios" class="modal fade" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <div class="modal-header alert-info">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i></span>
                                            </button>
                                            <h4 class="modal-title">
                                                <i class="fa fa-users"></i> Candidato(s) Marcado(s) (<small name="marcados">#</small>)
                                            </h4>
                                        </div>

                                        <div class="modal-body">
                                            <p>Qual a&ccedil;&atilde;o voc&ecirc; deseja realizar ?</p>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <button type="button" class="btn btn-success btn-block" id="aprovarVarios"><i class="fa fa-thumbs-up"></i> Aprovar</button>
                                                </div>
                                                <div class="col-sm-4">
                                                    <button type="button" class="btn btn-warning btn-block" id="bancoVarios"><i class="fa fa-address-book"></i> Banco de Vagas</button>
                                                </div>
                                                <div class="col-sm-4">
                                                    <button type="button" class="btn btn-danger btn-block" id="reprovarVarios"><i class="fa fa-thumbs-down"></i> Reprovar</button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
						    </div>
						    <!-- Fim Modal Confirmacao Exclusao -->

						    <!-- Modal Ficha invividual -->
							<!-- <div id="modalFicha" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
						      <div class="modal-dialog">
						        <div class="modal-content">

						          <div class="modal-header alert-info">
						            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i></span>
						            </button>
						            <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-folder-open"></i> Ficha Direta</h4>
						          </div>
						          <div class="modal-body">

						          	<div id="novaProvaConfirmar" style="display: none;">
                                        <p class="alert alert-warning">
                                            Deseja criar uma nova prova para este candidato? Ao confirmar, as respostas às avaliações antigas serão descartadas.<br/><br/>
                                            <button id="btnConfirmarNovaProva" type="button" class="btn btn-warning">Confirmar</button> <button type="button" class="btn btn-default" onclick="javascript: $('#novaProvaConfirmar').hide(500);">Cancelar</button>
                                        </p>
						        	</div>

						        	<div id="novaProvaSucesso" style="display: none;">
							            <p class="alert alert-success">
							            	<strong>SUCESSO</strong>: Candidato habilidato para nova avaliação!
						            	</p>
						        	</div>

						            <p>Busque o candidato pelo nome</p>
						            <p>
						            	<input type="text" id="inputFichaNome" onkeypress="autocompleteFicha()" class="form-control" placeholder="Nome do Candidato">
						            </p>

						            <p id="autocompleteFicha">

						            </p>

						            <p id="dadosFicha">

						            </p>

						          </div>
						          <div class="modal-footer">
							          <button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpaFicha()">
							          <i class="fa fa-times"></i> Fechar</button>
						          </div>

						        </div>
						      </div>
						    </div> -->

                            <!-- Modal Info -->
                            <div id="modalInfo" class="modal fade" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <div class="modal-header alert-info">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <i class="fa fa-times-circle"></i>
                                            </button>
                                            <h4 class="modal-title">
                                                <i class="fa fa-info-circle"></i> Detalhes do Candidato
                                            </h4>
                                        </div>

                                        <div class="modal-body" style="overflow: auto;">
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- Fim Modal Info -->

                            <!-- Modal Confirmacao Exclusao -->
                            <div id="modalExcluir" class="modal fade" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <div class="modal-header alert-info">
                                            <h4 class="modal-title">
                                                <i class="fa fa-user"></i> Excluir Candidato
                                            </h4>
                                        </div>

                                        <div class="modal-body">
                                        </div>

                                        <div class="modal-footer">
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

			<?php include "../rodape.php"; ?>

		</div><!--/.row-->
	</div>	<!--/.main-->

    <?php include 'scripts.php' ?>
	<script type="text/javascript" src="../dts/js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../js/custom.js"></script>
	<script type="text/javascript" src="../js/admin.js"></script>
	<script type="text/javascript" src="../js/util.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {

            $('[data-toggle=popover]').popover()

			$('#tabela-candidatos').DataTable( {
                "processing": true,
                "serverSide": true,
                "ordering": false,
                "searchDelay": 800,
                "ajax": consultaCandidatos,
                "columns": [
                    { "data": "marcar" },
                    { "data": "aprovado" },
                    { "data": "nome" },
                    { "data": "processo_seletivo" },
                    { "data": "finalizado" },
                    { "data": "acoes" },
                ],
                "columnDefs": [
                    { "width": "100px", "targets": 5},
                    { "searchable": false, "targets": [0, 1, 5]}
                ]
		    } );

            // Aprovar varios candidatos
            $("#aprovarVarios").click(() => atualizarCandidatoHandler());

			// Reprovar varios candidatos
			$("#reprovarVarios").click(() => atualizarCandidatoHandler('desaprovar'));

			// Aprovar varios candidatos
			$("#bancoVarios").click(() => atualizarCandidatoHandler('banco'));

		});

		// Marcar/Desmarcar todos da mesma pagina
		function marcardesmarcar() {
			if ($('#checkMaster').is(":checked")){
		        $('input:checkbox[name^=candidato]').each(function () {
			        this.checked = true;
		    	});
	        } else {
                $('input:checkbox[name^=candidato]').each(function () {
			        this.checked = false;
		    	});
	        }
		}

		// Checa se tem pelo menos 1 marcado
		function verificaMarcados (){
			const checkbox = $('input:checkbox[name^=candidato]:checked').length;

		    if(checkbox > 0){
		    	$("#erroMultiplos").hide(500);
		    	$('#modalVarios').modal().find('[name=marcados]').html(checkbox);
		    } else {
		    	$("#erroMultiplos").show(500);
		    }
		}

		// Autocomplete Ficha
		function autocompleteFicha(){
			if ($("#inputFichaNome").val() == ""){
				$("#autocompleteFicha").hide(500);
				$("#autocompleteFicha").html('');
			}
			$.getJSON('fichaAutoComplete.php?candidato='+$("#inputFichaNome").val(), function(lista) {
				for (index=0; index < lista.length; index++) {
					if(lista[index].achou == "S"){
						$("#autocompleteFicha").html('');
						$("#autocompleteFicha").show(500);
						$("#autocompleteFicha").append("<button type='button' class='btn btn-primary' onclick='preencheFicha("+lista[index].id+")'> <i class='fa fa-user'></i> "+lista[index].nomeCompleto+" </button> <button type='button' class='btn btn-danger' onclick='limpaFicha()'> <i class='fa fa-times-circle'></i> </button><br/>");
					} else {
						$("#autocompleteFicha").hide(500);
					}
				}// if FOR
			});
		}

		function limpaFicha(){
			$("#inputFichaNome").val('');
			$("#autocompleteFicha").hide();
			$("#dadosFicha").hide();
			$("#novaProvaConfirmar").hide();
			$("#novaProvaSucesso").hide();
		}

		function preencheFicha(id){
			$("#dadosFicha").html('');
			limpaFicha();
			$.getJSON('fichaBuscaCandidato.php?id='+id, function(lista) {
				for (index=0; index < lista.length; index++) {
					if(lista[index].achou == "S"){
						$("#dadosFicha").html('');
						$("#dadosFicha").show(500);
						$("#dadosFicha").html("<hr/><strong>Nome</strong>: "+lista[index].nomeCompleto+"<br/> <strong>E-mail</strong>: "+lista[index].email
												+"<br/> <strong>Telefone</strong>: "+lista[index].celular+"<br/> <strong>CPF // RG</strong>: "+lista[index].cpf+" // "+lista[index].rg
												+"<br/> <strong>Nascimento</strong>: "+lista[index].nascimento
												+"<hr/> <strong>Cargo</strong>: "+lista[index].cargo+"<br/> <strong>Área Atuação</strong>: "+lista[index].areaAtuacao
												+"<br/> <strong>Semestre (Se for estágio)</strong>: "+lista[index].estagioSemestre+"<br/> <strong>Matrícula (Se for estágio)</strong>: "+lista[index].estagioMatricula+"<br/> <strong>Horário (Se for estágio)</strong>: "+lista[index].estagioHorario
												+"<hr/> <strong>Status Prova</strong>: "+lista[index].finalizado+"<br/> <strong>Status Candidatura</strong>: "+lista[index].aprovado
												+"<hr/> <a class='btn btn-primary' href='dadosCadastrais.php?id="+lista[index].id+"' target='_Blank'><i class='fa fa-user'></i> Imprimir Dados</a> "
												+"<a class='btn btn-primary' href='respostasCandidato.php?id="+lista[index].id+"' target='_Blank'><i class='fa fa-edit'></i> Imprimir Respostas</a> "
												+"<button type='button' class='btn btn-warning' onclick='novaProva("+lista[index].id+")'><i class='fa fa-file'></i> Nova Prova</button>"
												);
					} else {
						$("#dadosFicha").hide(500);
					}
				}// if FOR
			});
		}

		// Nova prova
		function novaProva(idCand){
			$("#btnConfirmarNovaProva").attr("onclick", "confirmarNovaProva("+idCand+")");
			$("#novaProvaConfirmar").show(500);
		}

		function confirmarNovaProva(idCand){
			$.getJSON('excluir.php?id='+idCand+"&area=novaProva", function(lista) {
				for (index=0; index < lista.length; index++) {
					if(lista[index].novaProva == "S"){
						$("#novaProvaConfirmar").hide();
						$("#autocompleteFicha").hide();
						$("#dadosFicha").hide();
						$("#novaProvaSucesso").show(500);
					}
				}// if FOR
			});
		}

        function consultaCandidatos(o, callback) {
            const payload = {}
            const nome = o.search.value
            const max = o.length
            const offset = o.start + max
            const pagina = offset / max
            const tabela = o.draw

            if (nome) Object.assign(payload, { nome })

            Object.assign(payload, { pagina, max, tabela })

            $.get('listarCandidatosAjax.php', payload)
                .done(({ tabela, filtrado, total, pagina, anterior, proxima, limite, resultados }) => {
                    const candidatos = resultados.map((c) => {
                        c.marcar = `
                            <div align="center">
                                <input style="height: 20px; width: 20px; background-color: #eee;" type="checkbox" name="candidato" value="${c.id}">
                            </div>
                        `

                        c.acoes = ''

                        if (c.celular) {
                            c.celular = c.celular.match(/\d+/g).join('')
                            c.acoes += `
                                <a href="https://web.whatsapp.com/send?phone=55${c.celular}&text=Olá, ${c.nome}" class="btn btn-success btn-sm" target="_blank" title="Conversar via WhatsApp">
                                    <i class="fa fa-phone"></i>
                                </a>
                            `
                        }

                        c.acoes += `
                            <button type="button" class="btn btn-primary btn-sm" onclick="javascript:modalInfo(${c.id});">
                                <i class="fa fa-info-circle"></i>
                            </button>
                        `
                        c.acoes += `
                            <button type="button" class="btn btn-danger btn-sm" onclick="javascript:abrirModalExcluir({ id: ${c.id}, nome: '${c.nome}' });">
                                <i class="fa fa-trash"></i>
                            </button>
                        `

                        c.processo_seletivo = `
                            ${c.cargo}<br/>
                            <small>Processo Seletivo: ${c.processo_seletivo}</small>
                        `

                        c.aprovado = tabelaStatusIcon(c.aprovado)

                        if (c.finalizado === 'S') {
                            c.finalizado = '<span class="text-success">Finalizada</span>'
                        } else {
                            c.finalizado = '<span class="text-danger">Não Finalizada</span>'
                        }

                        return c
                    })

                    $('[name=candidatos_total]').text(`(${total})`)

                    callback({
                        draw: tabela,
                        recordsTotal: total,
                        recordsFiltered: filtrado,
                        data: candidatos
                    })
                })
        }

        function modalInfo (id) {
            const { target } = event
            ativarSpinner(target)

            consultaCandidato(id, abrirModalInfo)
                .always(() => {
                    desativarSpinner(target)
                })
        }

        function modalExcluir (id) {
            const { target } = event
            ativarSpinner(target)

            consultaCandidato(id, abrirModalExcluir)
                .always(() => {
                    desativarSpinner(target, 'trash')
                })
        }

        function consultaCandidato(id, callback) {
            return $.get('candidatoAjax.php', { id })
                .done((data) => {
                    callback(data)
                })
        }

        function abrirModalInfo (dados) {
            const $aprovar = $(`
                <button type="button" class="btn btn-success btn-block"${dados.aprovado === 'S' ? ' disabled': ''}>
                    <i class="fa fa-thumbs-up"></i> Aprovar
                </button>
            `)
            const $banco = $(`
                <button type="button" class="btn btn-warning btn-block"${dados.aprovado === 'B' ? ' disabled': ''}>
                    <i class="fa fa-address-book"></i> Banco de Vagas
                </button>
            `)
            const $desaprovar = $(`
                <button type="button" class="btn btn-danger btn-block"${dados.aprovado === 'N' ? ' disabled': ''}>
                    <i class="fa fa-thumbs-down"></i> Reprovar
                </button>
            `)

            /* dados.aprovado !== 'S' &&  */$aprovar.click(() => {
                atualizarCandidato(dados.id)
                    .then(() => atualizarTabela())
            })
            /* dados.aprovado !== 'B' &&  */$banco.click(() => {
                atualizarCandidato(dados.id, 'banco')
                    .then(() => atualizarTabela())
            })
            /* dados.aprovado !== 'N' &&  */$desaprovar.click(() => {
                atualizarCandidato(dados.id, 'desaprovar')
                    .then(() => atualizarTabela())
            })

            const $acoes = $('<div class="row"></div>')
            const $col4  = $('<div class="col-sm-4"></div>')
            const $col6  = $('<div class="col-sm-6"></div>')

            $acoes.html([
                $col4.clone().html($aprovar),
                $col4.clone().html($banco),
                $col4.clone().html($desaprovar),
            ])

            const $candidato = $(`
                <div>
                    <!-- <img src="" width="90" height="90" class="img-responsive" alt="" /> -->

                    <h4>- ${dados.nome} (<small>${dados.nascimento}</small>)</h4>
                    - <strong>Processo Seletivo</strong>: ${dados.processo_atual}<br/>
                    - <strong>E-mail</strong>: ${dados.email}<br/>
                    - <strong>Telefone</strong>: ${dados.telefone ? dados.telefone + ' / ' : ''}${dados.celular}<br/>
                    - <strong>Endereço</strong>: <small>${dados.endereco}</small><br/>
                </div>
            `)
            const $avaliacao = $(`
                <div>
                    - <strong>Cargo Pleiteado</strong>: ${dados.cargo}<br/>
                    - <strong>In&iacute;cio da Prova</strong>: ${dados.primeira_tentativa || '(sem informações)'}<br/>
                    - <strong>Conclusão da Prova</strong>: ${dados.tempo_envio || '(sem informações)'}<br/>
                    - <strong>Infrações</strong>: ${dados.infracoes}<br/>
                </div>
            `)
            const finalizado = dados.finalizado === 'S' ? '<span class="text-success">Finalizada</span>' : '<span class="text-danger">Não finalizada</span>'

            const $right = $('<span style="float: right"></span>')
            const $h3c = $('<h3 class="text-info">Dados do Candidato</h3>')
            const $h3a = $(`<h3 class="text-info">Dados da Avaliação <small>(${finalizado})</small></h3>`)
                .append($right)
            const $hr  = $('<hr />')
            const $respostas = $('<a class="btn btn-primary btn-block"></a>')

            $respostas.append('<i class="fa fa-print"></i> Imprimir Respostas')

            if (dados.finalizado === 'S') {
                $respostas.attr('href', `respostasCandidato.php?id=${dados.id}`)
                $respostas.attr('target', '_blank')
            } else {
                $respostas.attr('disabled', true)
                $respostas.click(function (e) {
                    e.preventDefault()
                })
            }

            const candidato = `
                <a class="btn btn-primary btn-block" href="dadosCadastrais.php?id=${dados.id}" target="_blank">
                    <i class="fa fa-print"></i> Imprimir Dados Cadastrais
                </a>
            `

            const $novaProva = $(`
                <button type="button" class="btn btn-warning" name="novaprova" data-toggle="popover">
                    <i class="fa fa-file"></i> Nova Prova
                </button>
            `)

            const $popoverButton = $(`
                <button class="btn btn-primary btn-sm btn-block">Sim</button>
            `)

            const $popoverFooter = $(`
                <div class="text-center">
                </div>
            `)
                .html($popoverButton)

            const $popoverQuestion = $('<p>Deseja criar uma nova prova para este candidato?</p>')
            const $popoverLegend = $(`
                <small>
                    <p class="text-muted">Ao confirmar, as respostas às avaliações antigas serão descartadas.</p>
                </small>
            `)

            const $popoverContent = $(`<div></div>`)
                .html([$popoverQuestion, $popoverLegend, $popoverFooter])

            $popoverButton.click(() => solicitarNovaProva(dados.id))

            $($novaProva).popover({
                html: true,
                placement: 'top',
                content: $popoverContent
            })

            const $footer = $('<div class="row"></div>')
                .html([
                    $col6.clone().html(candidato),
                    $col6.clone().append($respostas)
                ])

            const $alert = aprovarAlertBody(dados.aprovado)
            $alert && $alert.show(0)
            $right.append($novaProva)

            $('#modalInfo')
                .modal()
                .find('.modal-body')
                .html([
                    $alert,
                    $acoes,
                    $h3c,
                    $candidato,
                    $h3a,
                    $avaliacao,
                    $hr,
                    $footer
                ])
        }

        function abrirModalExcluir ({ id, nome }) {
            const $modal = $('#modalExcluir').modal()
            const $excluir = $(`
                <button type="button" class="btn btn-danger">
                    <i class="fa fa-trash"></i> Excluir
                </button>
            `)
            const $fechar = $(
                `<button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-times"></i> Cancelar
                </button>
            `)
            $excluir.click(() => { excluir(id) })
            $modal.find('.modal-body')
                .html(`
                    <p>Deseja realmente excluir o candidato <strong>${nome}</strong>?</p>
                `)
            $modal.find('.modal-footer')
                .html([$excluir, $fechar])
        }

        function atualizarCandidato (id, acao = 'aprovar') {
            const { target } = event
            const iconText = atualizarAcaoIcon(acao)
            const button = ativarSpinner(target)

            const $row = $(target.parentNode.parentNode)
            $row.find('button').prop('disabled', true)

            return $.post(`candidatoAjax.php?id=${id}&acao=${acao}`)
                .done(({ aprovado }) => {
                    const $modal = $('#modalInfo')
                    $modal
                        .find('div.alert')
                        .hide(500)
                        .remove()
                    $modal
                        .find('.modal-body')
                        .prepend(
                            (aprovarAlertBody(aprovado)).show(500)
                        )

                    desativarSpinner(button, ...iconText)

                    $(button).prop('disabled', true)/* .off() */
                    $row.find('button').each(function () {
                        if (this !== button)
                            this.disabled = false
                    })
                })
                .fail(() => {
                    $row.find('button').prop('disabled', false)
                })
        }

        function solicitarNovaProva (id) {
            const { target } = event
            const button = ativarSpinner(target)
            $.post(`candidatoAjax.php?id=${id}&acao=novaprova`)
                .done(() => {
                    const $modal = $('#modalInfo')
                    $modal.find('div.alert').remove()
                    $modal.find('.modal-body').prepend(`
                        <div class="alert alert-success">
                            <strong>SUCESSO</strong>: Candidato habilidato para nova avaliação!
                        </div>
                    `)
                    atualizarTabela()
                })
                .always(() => {
                    desativarSpinner(button, false, 'Sim')
                    $('[name=novaprova]').popover('hide')
                })
        }

        function excluir (id) {
            const { target } = event
            const button = ativarSpinner(target)

            $.ajax({
                method: 'DELETE',
                url: `candidatoAjax.php?id=${id}`,
            })
                .done(() => {
                    $('#modalExcluir').modal('hide')
                    atualizarTabela()
                })
                .always(() => {
                    desativarSpinner(button, 'trash', 'Excluir')
                })
        }

        function atualizarAcaoIcon (acao) {
            switch (acao) {
                case 'aprovar':
                    return ['thumbs-up', 'Aprovar Candidato']
                case 'desaprovar':
                    return ['thumbs-down', 'Reprovar Candidato']
                case 'banco':
                    return ['address-book', 'Banco de Vagas']
            }
        }

        function aprovarAlertBody (tipo) {
            switch (tipo) {
                case 'S':
                    return $('<div class="alert alert-success" style="display: none">Candidato <strong>APROVADO</strong> no Processo Seletivo!</div>')
                case 'N':
                    return $('<div class="alert alert-danger" style="display: none">Candidato <strong>REPROVADO</strong> no Processo Seletivo!</div>')
                case 'B':
                    return $('<div class="alert alert-warning" style="display: none">Candidato adicionado ao <strong>BANCO DE VAGAS</strong>!</div>')
            }
        }

        function tabelaStatusIcon (tipo) {
            switch (tipo) {
                case 'S':
                    return '<i class="text-success fa fa-thumbs-up fa-lg" title="Aprovado"></i>'
                case 'N':
                    return '<i class="text-danger fa fa-thumbs-down fa-lg" title="Reprovado"></i>'
                case 'B':
                    return '<i class="text-warning fa fa-address-book fa-lg" title="Banco de Vagas"></i>'
                default:
                    return '<i class="text-info fa fa-clock fa-lg" title="Aguardando Avaliação"></i>'
            }
        }

        function atualizarTabela (callback = null) {
            $('#tabela-candidatos')
                .DataTable()
                .ajax
                .reload(callback, false)
        }

        function atualizarCandidatoHandler (acao = 'aprovar') {
            const ids = $('input:checkbox[name^=candidato]:checked').map(function (_, { value }){
                return value
            }).get();

            Promise.all([
                ...ids.map((id) => atualizarCandidato(id, acao))
            ])
                .then(() => {
                    atualizarTabela(() => {
                        ids.forEach((value) => {
                            $(`input:checkbox[name^=candidato][value=${value}]`).click()
                        })
                    })
                })
        }
	</script>

</body>
</html>
