<?php include_once __DIR__ . '/variaveis.php';
  session_start();
  require_once __DIR__ . '/includes/funcoes.php';

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
	<link rel="stylesheet" href="css/datepicker3.css">
	<!--Custom Font-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.min.css" />
</head>
<body style="padding-top: 0px; overflow-x: hidden;">

	<?php include "topo.php"; ?>

	<div class="col-sm-12 main">
		<div class="row">
			<ol class="breadcrumb alert-info">
				<li><span class="text-warning">ROCHA, MARINHO E SALES ADVOGADOS</span></li>
				<li class="active">Bem-vindo(a), candidato(a)</li>
			</ol>
		</div><!--/.row-->


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

		<div class="row">

            <hr />

            <?php if (isset($_GET["sucesso"]) && $_GET["sucesso"] == "0"){ ?>
            <div id="msgErroCad" class="alert alert-danger">
                <strong>Erro</strong>: Tente realizar seu cadastro novamente!
            </div>
            <?php } ?>

            <?php if (isset($_GET["sucesso"]) && $_GET["sucesso"] == "1"){ ?>
            <div id="msgSucessoCad" class="alert alert-info">
                <strong>Sucesso</strong>: Cadastro realizado!
            </div>

            <div align="center">
                <a class="btn btn-primary btn-lg" href="./">
                    <i class="fa fa-file-alt"></i> Clique aqui para acessar a sua avalia&ccedil;&atilde;o
                </a>
            </div>

            <hr/>

            <?php } ?>

            <?php if (!isset($_GET["sucesso"])) { ?>

			<div class="col-lg-12">

				<!--<div align="center">
					<h1>Selecione o tipo da Vaga</h1>
					<button class="btn btn-primary btn-lg" onclick="advOutros()"><i class="fa fa-file-alt"></i> Advogado(a) / Outros Cargos</button>
					<button class="btn btn-success btn-lg" onclick="estagiario()"><i class="fa fa-file-alt"></i> Estagi&aacute;rio de Direito</button>
					<hr/>
				</div>-->

				<hr/>

				<div class="panel panel-default" style="display: block;" id="divForm">
					<div class="panel-body">
						<div class="col-md-12" align="center">
							<h1 class="page-header">
								<i class="fa fa-user color-blue"></i> Cadastramento de Candidato</strong>
								<br/><small><small>Campos com (*) s&atilde;o de preenchimento obrigat&oacute;rio</small></small>
							</h1>
						</div>

						<div class="col-md-1">
						</div>


						<div class="col-md-10">

							<!-- Mensagens de ALERTA -->
						<div id="msgErro" class="alert alert-danger" style="display: none;">
							<strong>Erro</strong>: Favor, preencha o(s) campo(s) obrigat&oacute;rio(s) em destaque.
						</div>
						<div id="msgSucesso" class="alert alert-success" style="display: none;">
							<strong>Sucesso</strong>: Candidato cadastrado!
						</div>
						<div class="progress" id="carregando" style="display: none;">
							<div data-percentage="0%" style="width: 100%;" class="progress-bar progress-bar-blue" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
						<p align="center" id="carregandoTexto" style="display: none;" class="text-info"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>
						<!-- FIM Mensagens de ALERTA -->

							<div class="btn-group btn-group-justified" role="group" aria-label="...">
							  <div class="btn-group" role="group">
							    <button id="btnField1" type="button" class="btn btn-primary active" >Dados Gerais</button>
							  </div>
							  <div class="btn-group" role="group">
							    <button id="btnField2" type="button" class="btn btn-default" >Dados Profissionais</button>
							  </div>
							  <div class="btn-group" role="group">
							    <button id="btnField3" type="button" class="btn btn-default" >Outras Informa&ccedil;&otilde;es</button>
							  </div>
							</div>

							<form role="form" id="formCadCandidatoExt" action="cadastroCandidatoSave.php" method="post">

								<fieldset id="field1" style="display: block; margin-top: 20px;">
									<legend>Dados gerais</legend>

									<div class="form-group">
										 <label>*Nome Completo</label>
										<input type="text" autofocus="" id="nome" name="nome" class="form-control" placeholder="Nome do Candidato">
									</div>

									<div class="row">
							            <div class="form-group col-md-4">
							              <label>*CPF</label>
							              <input type="text" id="cpf" name="cpf" class="form-control" placeholder="CPF" data-mask="000.000.000-00">
							              <span id="cpfDuplicado" class="text-danger"></span>
							            </div>
							            <div class="form-group col-md-4">
							               <label>*RG</label>
							              <input type="text" id="rg" name="rg" class="form-control" placeholder="RG" onkeyup="somenteNumeros(this);">
							            </div>
							            <div class="form-group col-md-4">
							               <label>*&Oacute;rg&atilde;o Expedidor</label>
							              <input type="text" id="expRg" name="expRg" class="form-control" placeholder="Org. Exp">
							            </div>
						            </div>

									<div class="row">
							            <div class="form-group col-md-4">
							              <label>*E-mail</label>
							              <input type="text" id="email" name="email" class="form-control" placeholder="E-mail">
							            </div>
							            <div class="form-group col-md-4">
							               <label>Telefone Residencial</label>
							              <input type="text" id="telefone" name="telefone" class="form-control" placeholder="Telefone Residencial" data-mask="(00) 0000-0000">
							            </div>
							            <div class="form-group col-md-4">
							               <label>*Telefone Celular</label>
							              <input type="text" id="celular" name="celular" class="form-control" placeholder="Telefone Celular" data-mask="(00) 00000-0000">
							            </div>
						            </div>

						            <div class="row">
							            <div class="form-group col-md-4">
							              <label>*Nascimento</label>
							              <input type="text" id="nascimento" name="nascimento" class="form-control" placeholder="Digite ou Selecione a Data de Nascimento" data-mask="00/00/0000">
							            </div>
							             <div class="form-group col-md-4">
							               <label>*UF</label>
								             <select class="form-control" id="uf" name="uf" style="border: 1px solid #999; height: 46px;">
												<option value="">Selecione</option>
												<option value="">-------------------</option>
												<?php $uF=  mysqli_query($bd, "SELECT * FROM estado order by nome");
													   while ($rsUf = mysqli_fetch_array($uF)){ ?>
												<option value="<?=$rsUf['id']?>"><?=$rsUf['nome']?> (<?=$rsUf['uf']?>)</option>
												<?PHP } ?>
											</select>
							            </div>
							            <div class="form-group col-md-4">
							               <label>*Cidade <span id="carregandoCidades"></span></label>
							               <select disabled="disabled" class="form-control" id="cidadeSelecionada" name="cidadeSelecionada" style="border: 1px solid #999; height: 46px;">
							               </select>
							            </div>
						            </div>

						            <div class="row">
							            <div class="form-group col-md-8">
										<input type="hidden" id="coord1" name="coord1" value="" class="form-control">
										<input type="hidden" id="coord2" name="coord2" value="" class="form-control">
										<label>*Endere&ccedil;o</label>
										<input type="text" id="endereco" name="endereco" class="form-control" placeholder="Endere&ccedil;o">
										<p align="center" id="carregandoTextoMaps" style="display: none;" class="text-info">
											<i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i>
										</p>
									</div>
							            <div class="form-group col-md-4">
							               <label>Complemento</label>
							              <input type="text" id="complemento" name="complemento" class="form-control" placeholder="Se possuir">
							            </div>
						            </div>

									<div class="row">
							            <div class="form-group col-md-6">
							              <label>*Estado Civil</label>
							              <select class="form-control" id="estadoCivil" name="estadoCivil" style="border: 1px solid #999; height: 46px;">
											<option value="">Selecione</option>
											<option value="">-------------------</option>
											<option value="Casado(a)">Casado(a)</option>
											<option value="Divorciado(a)">Divorciado(a)</option>
											<option value="Separado(a)">Separado(a)</option>
											<option value="Solteiro(a)">Solteiro(a)</option>
											<option value="Viuvo(a)">Vi&uacute;vo(a)</option>
										</select>
							            </div>
							            <div class="form-group col-md-6">
							               <label>Conjugue</label>
							              <input type="text" id="conjugue" name="conjugue" class="form-control" placeholder="Se possuir">
							            </div>
						            </div>

						            <div class="row">
							            <div class="form-group col-md-6">
							              <label>*Filia&ccedil;&atilde;o</label>
							              <input type="text" id="filiacao" name="filiacao" class="form-control" placeholder="Nome da M&atilde;e e Nome do Pai">
							            </div>
							            <div class="form-group col-md-6">
							               <label>Dependentes</label>
							              <input type="text" id="dependentes" name="dependentes" class="form-control" placeholder="Se possuir">
							            </div>
						            </div>

						            <div class="row">
							            <div class="form-group col-md-4">
							              <label>*T&iacute;tulo Eleitoral</label>
							              <input type="text" onkeyup="somenteNumeros(this);" id="tituloEleitor" name="tituloEleitor" class="form-control" placeholder="">
							            </div>
							            <div class="form-group col-md-1">
							              <label>*Zona</label>
							              <input type="text" onkeyup="somenteNumeros(this);" id="tituloEleitorZona" name="tituloEleitorZona" class="form-control" placeholder="" data-mask="000">
							            </div>
							            <div class="form-group col-md-1">
							              <label>*Se&ccedil;&atilde;o</label>
							              <input type="text" onkeyup="somenteNumeros(this);" id="tituloEleitorSecao" name="tituloEleitorSecao" class="form-control" placeholder="" data-mask="0000">
							            </div>
							            <div class="form-group col-md-6">
							               <label>Reservista</label>
							              <input type="text" id="reservista" name="reservista" class="form-control" placeholder="Se possuir">
							            </div>
						            </div>

						            <hr>
									<button id="cadcanExt" type="button" class="btn btn-primary" onclick="abreAba2()">Continuar</button>

								</fieldset>

								<!-- DADOS PROFISSIONAIS -->
								<fieldset id="field2" style="display: none; margin-top: 20px;">
									<legend>Dados Profissionais</legend>

									<div class="row">
							            <div class="form-group col-md-6">
							              <label>*Vaga Pretendida</label>
							             	<select class="form-control" id="cargo" name="cargo" style="border: 1px solid #999; height: 46px;">
												<option value="">Selecione</option>
												<option value="">-------------------</option>
												<option value="3">Advogado</option>
												<option value="5">Estagi&aacute;rio</option>
												<option value="1">Outros</option>
											</select>
							            </div>
							            <div class="form-group col-md-6">
							               <label>*Forma&ccedil;&atilde;o - Institui&ccedil;&atilde;o de Ensino</label>
							              <input type="text" id="formacao" name="formacao" class="form-control" placeholder="Forma&ccedil;&atilde;o - Institui&ccedil;&atilde;o de Ensino">
							            </div>
						            </div>

						            <fieldset id="advogadoSec" style="display: none;">
							            <div class="row">
								            <div class="form-group col-md-6">
								              <label>*N&uacute;mero OAB / Data Insc.</label>
								              <input type="text" id="numOab" name="numOab" class="form-control" placeholder="N&uacute;mero OAB / Data Inscri&ccedil;&atilde;o">
								            </div>
								            <div class="form-group col-md-6">
								               <label>*&Aacute;rea de Atua&ccedil;&atilde;o </label>
									              <select class="form-control" id="areaAtuacao" name="areaAtuacao" style="border: 1px solid #999; height: 46px;">
													<option value="">Selecione</option>
													<option value="">-------------------</option>
													<option value="Civil">C&iacute;vel</option>
													<option value="Trabalhista">Trabalhista</option>
													<option value="Ambas">Ambas</option>
												</select>
								            </div>
								        </div>
							        </fieldset>

							        <fieldset id="estagiarioSec" style="display: none;">
								        <div class="row">
								            <div class="form-group col-md-4">
								              <label>*Semestre Oficial</label>
								              <input type="text" onkeyup="somenteNumeros(this);" id="semestreEstagio" name="semestreEstagio" class="form-control" placeholder="Semestre">
								            </div>
								            <div class="form-group col-md-4">
								              <label>*Num. Matr&iacute;cula</label>
								              <input type="text" id="matriculaEstagio" name="matriculaEstagio" class="form-control" placeholder="Matr&iacute;cula">
								            </div>
								            <div class="form-group col-md-4">
								               <label>*&Aacute;rea de Atua&ccedil;&atilde;o </label>
									              <select class="form-control" id="areaAtuacaoEstagio" name="areaAtuacaoEstagio" style="border: 1px solid #999; height: 46px;">
													<option value="">Selecione</option>
													<option value="">-------------------</option>
													<option value="Civil">C&iacute;vel</option>
													<option value="Trabalhista">Trabalhista</option>
													<option value="Ambas">Ambas</option>
												</select>
								            </div>
							            </div>

							            <div class="row">
								            <div class="form-group col-md-4">
								              <label>*Disponibilidade de Hor&aacute;rio</label>
								               <select class="form-control" id="dipoHorarioEstagio" name="dipoHorarioEstagio" style="border: 1px solid #999; height: 46px;">
													<option value="">Selecione</option>
													<option value="">-------------------</option>
													<option value="Manha">Manh&atilde;</option>
													<option value="Tarde">Tarde</option>
													<option value="Ambas">Ambas</option>
												</select>
								            </div>
								            <div class="form-group col-md-4">
								              <label>*Possui Transporte Pr&oacute;prio</label>
								              <select class="form-control" id="possuiTransporteEstagio" name="possuiTransporteEstagio" style="border: 1px solid #999; height: 46px;">
													<option value="">Selecione</option>
													<option value="">-------------------</option>
													<option value="S">Sim</option>
													<option value="N">N&atilde;o</option>
												</select>
								            </div>
								            <div class="form-group col-md-4">
								              <label>*Participou de Audi&ecirc;ncias</label>
								              <select class="form-control" id="partAudienciaEstagio" name="partAudienciaEstagio" style="border: 1px solid #999; height: 46px;">
													<option value="">Selecione</option>
													<option value="">-------------------</option>
													<option value="S">Sim</option>
													<option value="N">N&atilde;o</option>
												</select>
								            </div>
							            </div>
						            </fieldset>

						            <div class="row">
							            <div class="form-group col-md-5">
							              <label>Ag&ecirc;ncia - <span class="text-danger">Bradesco</span></label>
							              <input type="text" onkeyup="somenteNumeros(this);" id="agenciaBradesco" name="agenciaBradesco" class="form-control" placeholder="Ag&ecirc;ncia">
							            </div>
							            <div class="form-group col-md-1">
							              <label>D&iacute;gito</label>
							              <input type="text" id="agenciaBradescoDigito" name="agenciaBradescoDigito" class="form-control" data-mask="0">
							            </div>
							            <div class="form-group col-md-5">
							               <label>Conta Corrente - <span class="text-danger">Bradesco</span></label>
							              <input type="text" onkeyup="somenteNumeros(this);" id="contaBradesco" name="contaBradesco" class="form-control" placeholder="Conta Corrente" >
							            </div>
							            <div class="form-group col-md-1">
							              <label>D&iacute;gito</label>
							              <input type="text" id="contaBradescoDigito" name="contaBradescoDigito" class="form-control" data-mask="0">
							            </div>
						            </div>

						            <hr>
									<button type="button" class="btn btn-primary" onclick="abreAba3()">Continuar</button>
									<button type="button" class="btn btn-default" onclick="abreAba1()">Voltar</button>

								</fieldset>
								<!-- FIM DADOS PROFISSIONAIS -->

								<fieldset id="field3" style="display: none; margin-top: 20px;">
									<legend>Outras Informa&ccedil;&otilde;es</legend>
									<div class="form-group">
										<label>Perfil do Linked In</label>
										<input type="text" id="linkedIn" name="linkedIn" placeholder="Informe a URL do seu perfil" class="form-control">
									</div>

									<div class="form-group">
										<label>Curr&iacute;culo Lattes</label>
										<input type="text" id="cvLattes" name="cvLattes" placeholder="Informe a URL do seu Curr&iacute;culo Lattes" class="form-control">
									</div>

									<div class="form-group">
										<label>Outras Informa&ccedil;&otilde;es</label>
										<textarea class="form-control" id="obs" name="obs" rows="3" placeholder="Outras Informa&ccedil;&otilde;es sobre o candidato" style="border: 1px solid #999;"></textarea>
									</div>

									<hr>
								<button type="button" class="btn btn-primary" onclick="validaCadCandidatoExt()">Cadastrar</button>
								<button type="button" class="btn btn-default" onclick="abreAba2()">Voltar</button>
								<!--<button type="button" class="btn btn-default" onclick="limparCadCandidatoExt()">Limpar</button>-->
								</fieldset>

							</div>
							</form>
						</div>

						<div class="col-md-1">
						</div>

					</div>

					</div>
				</div><!-- /.panel-->

            </div>

            <?php } ?>
		</div><!--/.row-->


		<!-- Modal Confirmacao -->
		<div id="modalConfirmar" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
	      <div class="modal-dialog">
	        <div class="modal-content">

	          <div class="modal-header alert-info">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i></span>
	            </button>
	            <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-user"></i> Concluir cadastro</h4>
	          </div>
	          <div class="modal-body">
	             <p id="textoLogoff">Deseja concluir seu Cadastro?</p>
        		 <p align="center" class="text-info" id="carregandoLogoff" style="display: none;"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>
	          </div>
	          <div class="modal-footer">
		          <button id="botaoConfirmar" type="button" class="btn btn-primary" data-dismiss="modal" onclick="confirmarCandidatoExt()">
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

    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.mask.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/fontawesome-all.min.js"></script>
	<script type="text/javascript" src="js/admin.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>

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
