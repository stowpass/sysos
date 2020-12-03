<?php
require_once __DIR__ . '/../includes/funcoes.php';

if (isSessaoValida())
    redirecionar('admin/home.php');
?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'head.php' ?>
</head>
<body style="padding-top: 0px;">

    <?php include '../topo.php' ?>

	<div class="col-sm-12 main">
		<div class="row">
			<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
				<div id="msgErro" class="alert alert-danger" role="alert" style="display: none;">
					<ul id="tipoErro"></span>
				</div>

                <?php if (isset($_GET['erro']) && !empty($_GET['erro'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <em class="fa fa-lg fa-exclamation-triangle">&nbsp;</em>
                        <span><?=$_GET['erro']?></span>
                    </div>
                <?php } ?>

				<div class="login-panel panel panel-default">
					<div class="panel-heading" align="center">Acesso Restrito ao <strong class="text-info">GDH</strong></div>
					<div class="panel-body">
						<form role="form">
							<fieldset>
								<div class="form-group input-group">
									<span class="input-group-addon">
                                        <i class="fa fa-envelope fa-fw"></i>
                                    </span>
									<input class="form-control" placeholder="E-mail" name="email" type="text" autofocus>
								</div>
								<div class="form-group input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-key fa-fw"></i>
                                    </span>
									<input class="form-control" placeholder="Senha" name="senha" type="password">
								</div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fa fa-sign-in-alt"></i> Entrar
                                        </button>
                                    </div>
                                    <div class="col-md-6">
								        <button type="reset" class="btn btn-default btn-block">Limpar</button>
                                    </div>
                                </div>
							</fieldset>
						</form>
					</div>
				</div>

                <div class="row">
                    <?php include '../rodape.php' ?>
                </div>
			</div><!-- /.col-->
		</div><!-- /.row -->
	</div>

    <?php include 'scripts.php' ?>
	<script src="../js/admin.js"></script>
    <script type="text/javascript" src="../js/util.js"></script>

    <script type="text/javascript">
        const emailRegx = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/

        $(function () {
            $('form').submit(validar)

            $('form').on('reset', limpar)
        })

        function validar() {
            event.preventDefault()

            const $erro = $('#msgErro')
            const $msg = $('#tipoErro').fadeOut(function () {
                $(this).html('')
            })
            const email = this.email.value
            const senha = this.senha.value
            const erros = []

            let validado = true
            this.$erro = $erro

            if (!email) {
                $erro.show(500)
                erros.push('<li>Informe o <strong>e-mail</strong>.</li>')
                $(this.email).css('border', '1px solid red')
                validado = false
            } else if (!emailRegx.test(email)) {
                $erro.show(500)
                erros.push('<li>O email informado é inválido.</li>')
                $(this.email).css('border', '1px solid red')
                validado = false
            } else {
                $(this.email).css('border', '')
            }

            if (!senha) {
                $erro.show(500)
                erros.push('<li>Informe a <strong>senha</strong>.</li>')
                $(this.senha).css('border', '1px solid red')
                validado = false
            } else {
                $(this.senha).css('border', '')
            }

            if (erros.length) {
                $msg.fadeOut(function () {
                    erros.forEach((e) => {
                        $(this).append(e)
                    })
                }).fadeIn()
            }

            if (!validado) return

            const e = enviar.bind(this)

            e()
        }

        function enviar() {
            const button = $(this).find('button').attr('disabled', true).get(0)
            const email = this.email.value
            const senha = this.senha.value

            ativarSpinner(button)

            $.post('loginAjax.php', { email, senha })
                .done((res) => {
                    this.$erro.removeClass('alert-danger')
                        .addClass('alert-success')
                        .html(res.mensagem)
                        .show(200)

                    setTimeout(function () {
                        window.location.reload()
                    }, 800)
                })
                .fail(({ responseJSON }) => {
                    const status = responseJSON.erro ? 'danger' : 'warning'
                    const mensagem = responseJSON.erro || responseJSON.mensagem
                    $('#tipoErro').fadeOut(function () {
                        $(this).html(`<li>${mensagem}</li>`)
                    }).fadeIn()
                    this.$erro.show(500)
                })
                .always(() => {
                    desativarSpinner(button, 'sign-in-alt', 'Entrar')
                    $(this).find('button').attr('disabled', false)
                })
        }

        function limpar() {
            $('#msgErro').hide(500)
            $('#tipoErro').hide(500)

            for (const el of this.elements) {
                $(el).css('border', '')
            }
        }
    </script>

</body>
</html>
