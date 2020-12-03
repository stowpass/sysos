<?php
require_once __DIR__ . '/includes/funcoes.php';

if (isSessaoValida(null, false))
    redirecionar('home.php');
?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'head.php' ?>
</head>
<body style="padding-top: 0px;">
    <?php include 'topo.php' ?>

	<?php
		$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
		$ipad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
		$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
		$palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
		$berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
		$ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
		$symbian =  strpos($_SERVER['HTTP_USER_AGENT'],"Symbian");

		if ($iphone || $ipad || $android || $palmpre || $ipod || $berry || $symbian == true){ // Verifica se for acessado via dispositivo mobile
			die("<div class='alert alert-warning' align='center'><i class='fa fa-exclamation-triangle'></i> Utilize apenas Computador ou Laptop</div><hr><div align='center'><img class='img img-thumbnail' src='img/telefone.png' height='100' /></div>");
		}
	?>

	<div class="col-sm-12 main">
		<div class="row">
			<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
				<div id="msgErro" class="alert alert-danger" role="alert" style="display: none;"><em class="fa fa-lg fa-exclamation-triangle">&nbsp;</em>
					<span id="tipoErro"></span>
					<a href="javascript: $('#msgErro').hide(500);" class="pull-right"><em class="fa fa-lg fa-times-circle text-danger"></em></a>
				</div>

                <?php if (isset($_GET['erro']) && !empty($_GET['erro'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <em class="fa fa-lg fa-exclamation-triangle">&nbsp;</em>
                        <span><?=$_GET['erro']?></span>
                    </div>
                <?php } ?>

				<p align="center" id="carregandoTexto" style="display: none;" class="text-info"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>

				<div class="login-panel panel panel-default">
					<div class="panel-heading" align="center">Acesse sua Avalia&ccedil;&atilde;o</div>
					<div class="panel-body">
						<form id="formLogin" name="formLogin" action="loginPadrao.php" method="post" role="form">
							<fieldset>
								<label>CPF</label>
								<div class="form-group input-group">
									<span class="input-group-addon"><i class="fa fa-user-circle fa-lg fa-fw"></i></span>
									<input class="form-control" id="cpf" placeholder="O mesmo CPF informado em seu cadastro" name="cpf" type="text" autofocus="" data-mask="000.000.000-00">
								</div>

								<button type="button" class="btn btn-primary" onclick="validarLogin()"><i class="fa fa-sign-in-alt"></i> Entrar</button>
							</fieldset>
						</form>

						<p align="center">
							<hr/>
							<a href="./cadastro/" ><i class="fa fa-user"></i> Ainda n&atilde;o possui cadastro? Clique aqui</a>
						</p>

					</div>
				</div>

				<div class="alert" align="center">
				<img src="img/chrome.png" width="32" /> <small>Utilize do navegador Google Chrome para acessar a avalia&ccedil;&atilde;o.
				<br/><a href="https://www.google.com/chrome/" target="_Blank">Clique aqui para baixar</a></small>
				</div>

                <div class="row">
                    <?php include 'rodape.php' ?>
                </div>
			</div><!-- /.col-->
		</div><!-- /.row -->
	</div>

    <?php include 'scripts.php' ?>

</body>
</html>
