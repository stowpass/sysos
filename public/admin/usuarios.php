<?php

require_once __DIR__ . '/../includes/funcoes.php';

isSessaoValida('admin/');

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include 'head.php' ?>
        <link rel="stylesheet" type="text/css" href="../dts/css/jquery.dataTables.css">
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
                                    <i class="fa fa-user color-blue"></i> Usuários do sistema
                                    <span style="float: right">
                                        <button class="btn btn-success btn-lg" data-toggle="modal" data-target="#modalNovo">
                                            <i class="fa fa-plus"></i> Novo
                                        </button>
                                    </span>
                                </h1>
                            </div>

                            <div class="col-md-12">
                                <div class="alert alert-success" style="display: none"></div>
                            </div>

                            <!-- CONTENT -->
                            <div class="col-md-12">
                                <table class="table table-responsive">
                                    <thead class="alert-info">
                                        <tr>
                                            <th>Nome</th>
                                            <th>E-mail</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <!-- FIM CONTENT -->
                        </div>
                    </div><!-- /.panel-->

                </div>
            </div><!--/.row-->

            <div id="modalNovo" class="modal fade" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header alert-info">
                            <h4 class="modal-title">
                                Novo usuário externo
                            </h4>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-warning" style="display: none">
                                        <ul></ul>
                                    </div>
                                    <form id="cadastrar">
                                        <div class="form-group">
                                            <label>Nome</label>
                                            <input type="text" name="nome" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>E-mail</label>
                                            <input type="text" name="email" class="form-control">
                                        </div>
                                    </form>
                                    <p>Obs.: Será definida uma senha padrão automaticamente.</p>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" form="cadastrar" class="btn btn-primary">Cadastrar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Confirmacao Exclusao -->
            <div id="modalExcluir" class="modal fade" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header alert-info">
                            <h4 class="modal-title">
                            </h4>
                        </div>

                        <div class="modal-body" style="display: none"></div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger">Sim</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fim Modal Confirmacao Exclusao -->


            <div class="row">
                <?php include "../rodape.php"; ?>
            </div><!--/.row-->
        </div>	<!--/.main-->

        <?php include 'scripts.php' ?>
	    <script type="text/javascript" src="../dts/js/jquery.dataTables.js"></script>
	    <script type="text/javascript" src="../js/util.js"></script>

        <script type="text/javascript">
            const emailRegx = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/

            $(function () {
                $('[data-toggle=popover]').popover()

                const $tabela = $('table').first().DataTable({
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    searchDelay: 800,
                    ajax: consultaUsuarios,
                    columns: [
                        { data: 'nome' },
                        { data: 'email' },
                    ],
                    columnDefs: [
                        { searchable: false, targets: [1, 2]},
                        {
                            data: null,
                            targets: 2,
                            defaultContent: '<button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>'
                        }
                    ]
                })

                $('#modalNovo form').submit(validar)
                $('#modalNovo form').on('reset', limpar)

                $('table tbody').on('click', '.btn-danger', function () {
                    abrirModalExcluir($tabela.row($(this).parents('tr')).data())
                })
            })

            function consultaUsuarios (o, callback) {
                const payload = {}
                const nome = o.search.value
                const max = o.length
                const offset = o.start + max
                const pagina = offset / max
                const tabela = o.draw

                if (nome) Object.assign(payload, { nome })

                Object.assign(payload, { pagina, max, tabela })

                $.get('usuariosAjax.php', payload)
                    .done(({ tabela, filtrado, total, pagina, anterior, proxima, limite, resultados }) => {
                        callback({
                            draw: tabela,
                            recordsTotal: total,
                            recordsFiltered: filtrado,
                            data: resultados
                        })
                    })
            }

            function validar () {
                event.preventDefault()

                $('.panel-body .alert-success')
                    .html('')
                    .hide(300)

                const $alert = $(this).prev()
                const $msg = $alert.children().first().fadeOut(function () {
                    $(this).html('')
                })

                const nome = this.nome.value
                const email = this.email.value
                const erros = []

                let validado = true
                this.$alert = $alert

                if (!nome) {
                    erros.push('<li>Informe o <strong>nome</strong>.</li>')
                    $(this.nome).css('border', '1px solid red')
                    validado = false
                } else {
                    $(this.nome).css('border', '')
                }

                if (!email) {
                    erros.push('<li>Informe o <strong>e-mail</strong>.</li>')
                    $(this.email).css('border', '1px solid red')
                    validado = false
                } else if (!emailRegx.test(email)) {
                    erros.push('<li>O email informado é inválido.</li>')
                    $(this.email).css('border', '1px solid red')
                    validado = false
                } else {
                    $(this.email).css('border', '')
                }

                if (erros.length) {
                    $alert.show(500)
                    $msg.fadeOut(function () {
                        erros.forEach((e) => {
                            $(this).append(e)
                        })
                    }).fadeIn()
                }

                if (!validado) return

                const c = cadastrar.bind(this)

                c()
            }

            function cadastrar () {
                const button = $('#modalNovo .modal-footer').find('button').attr('disabled', true).get(0)
                const nome = this.nome.value
                const email = this.email.value

                ativarSpinner(button)

                $.post('usuariosAjax.php', { nome, email })
                    .done((res) => {
                        $('#modalNovo').modal('hide')
                        $('.panel-body .alert-success')
                            .html(res.mensagem)
                            .show(500)
                        this.$alert.hide(200)
                        this.reset()
                        atualizarTabela()
                    })
                    .fail(({ responseJSON }) => {
                        // const status = responseJSON.erro ? 'danger' : 'warning'
                        const mensagem = responseJSON.erro || responseJSON.mensagem
                        this.$alert.children().first().fadeOut(function () {
                            $(this).html(`<li>${mensagem}</li>`)
                        }).fadeIn()
                        this.$alert.show(500)
                    })
                    .always(() => {
                        desativarSpinner(button, null, 'Cadastrar')
                        $('#modalNovo .modal-footer').find('button').attr('disabled', false)
                    })
            }

            function limpar () {
                $(this).prev().hide(500).find('ul').html('')

                for (const el of this.elements) {
                    $(el).css('border', '')
                }
            }

            function abrirModalExcluir ({ id, nome }) {
                const $modal = $('#modalExcluir').modal()
                $('.panel-body .alert-success')
                    .html('')
                    .hide(300)

                $modal.find('.modal-body').hide(100)
                $modal.find('.modal-title').html(`Desativar "${nome}"?`)
                $modal.find('.modal-footer .btn-danger')
                    .click(function () {
                        const d = desativar.bind(this)
                        d(id, nome)
                    })
            }

            function desativar (id, nome) {
                const $modal = $('#modalExcluir')
                const button = ativarSpinner(this)

                $modal.find('.modal-footer button').attr('disabled', true)

                $.ajax({
                    method: 'DELETE',
                    url: `usuariosAjax.php?id=${id}`,
                })
                    .done(() => {
                        $('.panel-body .alert-success')
                            .html(`Usuário ${nome} foi desativado.`)
                            .show(500)
                        $modal.modal('hide')
                        atualizarTabela()
                    })
                    .fail(({ responseJSON }) => {
                        const mensagem = responseJSON.mensagem || responseJSON.erro
                        $modal.find('.modal-body').show(200).html(
                            `<div class="alert alert-warning">${mensagem}</div>`
                        )
                    })
                    .always(() => {
                        desativarSpinner(button, null, 'Sim')
                        $modal.find('.modal-footer button').attr('disabled', false)
                    })
            }

            function atualizarTabela (callback = null) {
                $('table').first()
                    .DataTable()
                    .ajax
                    .reload(callback, false)
            }
        </script>
    </body>
</html>
