<?php
require_once __DIR__ . '/includes/funcoes.php';
include_once __DIR__ . '/variaveis.php';

session_start();

isSessaoValida('', false);

$bd = abreConn();

date_default_timezone_set('America/Fortaleza');
$diaCorrente = date('d');
$mesCorrente = date('m');
$anoCorrente = date('Y');
$horaCorrente = date('H');
$minutoCorrente = date('i');
$segundoCorrente = date('s');

if (!isset($_SESSION['tempo_inicio']) && !isset($_SESSION['finalizado'])){
    $_SESSION['tempo_inicio'] = $diaCorrente."/".$mesCorrente."/".$anoCorrente." - ".$horaCorrente.":".$minutoCorrente.":".$segundoCorrente;

    if ($_SESSION["primeiraTentativa"] == ""){
        $_SESSION["primeiraTentativa"] = $_SESSION['tempo_inicio'];
        $sql2 = "UPDATE tb_candidato SET primeira_tentativa = '".$_SESSION['primeiraTentativa']."' WHERE id='".$_SESSION["id"]."' ";
        mysqli_query($bd, $sql2);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'head.php' ?>
	<!--Custom Font-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i">
	<style type="text/css">
	  a{
	  	color:#444;
	  	text-decoration: none;
	  }
	  a:hover{
	  	color:#31708f;
	  	text-decoration: none;
	  	font-weight: bold;
	  }
	</style>
</head>
<body style="padding-top: 0px;">

	<?php
	// ATENCAO: Algumas variáves estão no arquivo variaveis.php onde este é chamado dentro das páginas

	if ($_SESSION['cargoId'] != 3){ // Apenas que selecionou o cargo de ADVOGADO tem acesso
		redirect("home.php");
	}
	?>

	<?php include "topo.php" ?>

	<div class="col-sm-12 main">
		<div class="row">
			<ol class="breadcrumb alert-info">
				<li><span class="text-warning">ROCHA, MARINHO E SALES ADVOGADOS</span></li>
				<li class="active">Bem-vindo(A) <strong><?=$_SESSION['nome']?></strong></li>
				<li><a href="home.php"><i class="fa fa-arrow-circle-left"></i> Voltar</a></li>
			</ol>
		</div><!--/.row-->

		<?php if (isset($_SESSION['finalizado']) && $_SESSION['finalizado'] == 'S'){
		die("<hr/><div class='alert alert-danger'><i class='fa fa-lg fa-exclamation-triangle'>&nbsp;</i> ATENçãO: Você já envio suas respostas. Aguarde a finalização do processo! - <a href='logoff.php'>Sair</a></div>");
		} ?>

		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					<i class="fa fa-file-alt color-blue"></i> Avaliação de Candidato - <strong class="text-info"><?=$_SESSION['cargo']?></strong>
				</h1>

				<div class="alert alert-warning">
					Esse instrumento tem a finalidade em balizar seus conhecimentos técnicos na área jurídica, não sendo permitida a utilização de qualquer fonte de pesquisa. Leia as questões e responda de forma sucinta e fundamentada. Boa prova!!
				</div>

				<?php
					if ($_SESSION["areaAtuacao"] == 'Civil'){
						$mostraBotaoCivil = "block";
						$mostraBotaoTrabalhista = "none";
					}
					if ($_SESSION["areaAtuacao"] == 'Trabalhista'){
						$mostraBotaoCivil = "none";
						$mostraBotaoTrabalhista = "block";
					}
					if ($_SESSION["areaAtuacao"] == 'Ambas'){
						$mostraBotaoCivil = "block";
						$mostraBotaoTrabalhista = "block";
					}
				?>

				<?php if ($_SESSION["areaAtuacao"] == 'Civil'){ ?>
				<div class="row" align="center">
					<div class="col-md-1">
						<button class="btn btn-primary" onclick="javascript:$('#perguntasTrabalhista').hide(); $('#perguntasCivil').show(500); $('#btnEnviar').show(500);" style="display: <?=$mostraBotaoCivil?>"><i class="fa fa-hand-point-right"></i> Questões - Área Cível</button>
					</div>
				</div>
				<?php } ?>

				<?php if ($_SESSION["areaAtuacao"] == 'Trabalhista'){ ?>
				<div class="row" align="center">
					<div class="col-md-1">
						<button class="btn btn-primary" onclick="javascript:$('#perguntasCivil').hide(); $('#perguntasTrabalhista').show(500); $('#btnEnviar').show(500);" style="display: <?=$mostraBotaoTrabalhista?>"><i class="fa fa-hand-point-right"></i> Questões - Área Trabalhista</button>
					</div>
				</div>
				<?php } ?>

				<?php if ($_SESSION["areaAtuacao"] == 'Ambas'){ ?>
				<div class="row" align="center">
					<div class="col-md-1">
						<button class="btn btn-primary" onclick="javascript:$('#perguntasTrabalhista').hide(); $('#perguntasCivil').show(500); $('#btnEnviar').show(500);" style="display: <?=$mostraBotaoCivil?>"><i class="fa fa-hand-point-right"></i> Questões - Área Cível</button>
					</div><br/><br/>
					<div class="col-md-1">
						<button class="btn btn-primary" onclick="javascript:$('#perguntasCivil').hide(); $('#perguntasTrabalhista').show(500); $('#btnEnviar').show(500);" style="display: <?=$mostraBotaoTrabalhista?>"><i class="fa fa-hand-point-right"></i> Questões - Área Trabalhista</button>
					</div>
				</div>
				<?php } ?>

				<hr/>

				<div class="panel panel-default">
					<div class="panel-body">

						<!-- Mensagens de ALERTA -->
						<?php if ($_SESSION["areaAtuacao"] != 'Ambas'){ ?>
						<div id="msgErro" class="alert alert-danger" style="display: none;">
							<strong>Erro</strong>: Favor, responda às questões em destaque
						</div>
						<?php } ?>

						<?php if ($_SESSION["areaAtuacao"] == 'Ambas'){ ?>
						<div id="msgErro" class="alert alert-danger" style="display: none;">
							<strong>Erro</strong>: Favor, responda às questões em destaque. Verifique ambas questões (<strong>Cível</strong> e <strong>Trabalhista</strong>)
						</div>
						<?php } ?>

						<div class="progress" id="carregando" style="display: none;">
							<div data-percentage="0%" style="width: 100%;" class="progress-bar progress-bar-blue" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
						<p align="center" id="carregandoTexto" style="display: none;" class="text-info"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>
						<!-- FIM Mensagens de ALERTA -->


						<!-- ============================= INICIO CIVIL ============================= -->
						<div class="col-md-12" id="perguntasCivil" style="display:none;">

							<fieldset>
								<legend>Questões - Área CíVEL</legend>
							</fieldset>

							<div class="alert alert-warning">
								Responda às questões <strong>1</strong> a <strong>6</strong> clicando na opção que você julgar correta.
							</div>

							<form role="form" id="formAval" name="formAval" action="processarAdv.php" method="post">
								<input type="hidden" name="idCandidato" value="<?=$_SESSION["id"]?>">
								<input type="hidden" name="primeiraTentativa" value="<?=$_SESSION["primeiraTentativa"]?>">
								<input type="hidden" name="areaAtuacaoHidden" value="<?=$_SESSION["areaAtuacao"]?>">

								<div class="form-group">
									<label>1. Acerca da revelia, é correto afirmar que:<br/></label>
											<br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil1').val('A'); $('#respostaCivil1Txt').html('A');">A) A revelia se dá com a não apresentação de exceção ou de reconvenção no prazo da resposta.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil1').val('B'); $('#respostaCivil1Txt').html('B');">B) Ainda que o litígio verse sobre direitos indisponíveis, a revelia produz seus efeitos normalmente.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil1').val('C'); $('#respostaCivil1Txt').html('C');">C) O revel pode intervir no processo em qualquer fase, recebendo-o no estado em que se encontrar.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil1').val('D'); $('#respostaCivil1Txt').html('D');">D) Contra o revel, ainda que tenha patrono constituído nos autos, correrão os prazos independentemente de intimação.</a><br/>
									<br/>
									<input type="hidden" id="respostaCivil1" name="respostaCivil1" />
									Sua resposta: <strong id="respostaCivil1Txt" class="text-info"></strong>

								</div><br/>


								<div class="form-group">
									<label>2. Sobre a suspensão e interrupção de prazo pode se dizer que:<br/></label>
											<br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil2').val('A'); $('#respostaCivil2Txt').html('A');">A) São institutos de direito processual com a mesma consequência.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil2').val('B'); $('#respostaCivil2Txt').html('B');">B) Na suspensão o prazo é integralmente devolvido à parte.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil2').val('C'); $('#respostaCivil2Txt').html('C');">C) Na interrupção o prazo retoma a contagem a partir do dia que foi interrompido.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil2').val('D'); $('#respostaCivil2Txt').html('D');">D) Na interrupção o prazo é integralmente devolvido à parte.</a><br/>
									<br/>
									<input type="hidden" id="respostaCivil2" name="respostaCivil2" />
									Sua resposta: <strong id="respostaCivil2Txt" class="text-info"></strong>

								</div><br/>


								<div class="form-group">
									<label>3. Sobre prescrição e decadência, é correto afirmar que:<br/></label>
											<br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil3').val('A'); $('#respostaCivil3Txt').html('A');">A) O juiz não pode reconhecer de ofício a prescrição.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil3').val('B'); $('#respostaCivil3Txt').html('B');">B) O Código Civil permite a alegação de prescrição em qualquer grau de Jurisdição.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil3').val('C'); $('#respostaCivil3Txt').html('C');">C) A prescrição da pretensão do autor não pode ser alegada somente em apelação.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil3').val('D'); $('#respostaCivil3Txt').html('D');">D) Prescrição e decadência são institutos sem qualquer distinção.</a><br/>
									<br/>
									<input type="hidden" id="respostaCivil3" name="respostaCivil3" />
									Sua resposta: <strong id="respostaCivil3Txt" class="text-info"></strong>

								</div><br/>


								<div class="form-group">
									<label>4. Quanto ao cumprimento da sentença, é correto dizer que:<br/></label>
											<br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil4').val('A'); $('#respostaCivil4Txt').html('A');">A) A impugnação ao cumprimento da sentença será recebida como regra geral nos efeitos devolutivo e suspensivo, podendo o juiz atribuir somente efeito devolutivo se do duplo efeito advier prejuízo irreparável ou de difícil reparação ao credor.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil4').val('B'); $('#respostaCivil4Txt').html('B');">B) é definitiva a execução da sentença transitada em julgada e provisória quando se tratar de sentença impugnada mediante recurso recebido nos efeitos devolutivo e suspensivo.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil4').val('C'); $('#respostaCivil4Txt').html('C');">C) Na impugnação ao cumprimento da sentença, se o executado alegar que o exequente, em excesso de execução, pleiteia  quantia superior à condenação, deverá declarar de imediato o valor que entende correto, sob pena de rejeição liminar dessa impugnação.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil4').val('D'); $('#respostaCivil4Txt').html('D');">D) A decisão que resolver a impugnação ao cumprimento de sentença é recorrível sempre por meio de agravo de  instrumento.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil4').val('E'); $('#respostaCivil4Txt').html('E');">E) A execução provisória ocorre por conta e risco do credor, devendo os atos que importem levantamento de depósito em dinheiro ou alienação de propriedade serem precedidos necessariamente de caução id&ocirc;nea, sem exceção.</a><br/>
									<br/>
									<input type="hidden" id="respostaCivil4" name="respostaCivil4" />
									Sua resposta: <strong id="respostaCivil4Txt" class="text-info"></strong>

								</div><br/>


								<div class="form-group">
									<label>5. Sobre pagamento em consignação, assinale a alternativa incorreta:<br/></label>
											<br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil5').val('A'); $('#respostaCivil5Txt').html('A');">A) Considera-se pagamento, e extingue a obrigação, o depósito judicial ou em estabelecimento bancário da coisa devida, nos casos e forma legais.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil5').val('B'); $('#respostaCivil5Txt').html('B');">B) Julgado procedente o depósito, o devedor já não poderá levantá-lo, embora o credor consinta, senão de acordo com os outros devedores e fiadores.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil5').val('C'); $('#respostaCivil5Txt').html('C');">C) Se a coisa devida for imóvel, não poderá ser objeto de pagamento em consignação, pois não poderá ser depositada em juízo.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil5').val('D'); $('#respostaCivil5Txt').html('D');">D) Para que a consignação tenha força de pagamento, será mister concorram, em relação às pessoas, ao objeto, modo e tempo, todos os requisitos sem os quais náo é válido o pagamento.</a><br/>
									<br/>
									<input type="hidden" id="respostaCivil5" name="respostaCivil5" />
									Sua resposta: <strong id="respostaCivil5Txt" class="text-info"></strong>

								</div><br/>


								<div class="form-group">
									<label>6. Estipulando o doador que os bens doados reverterão ao seu patrimônio se o donatário vier a falecer antes dele, ter-se-á doação:<br/></label>
											<br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil6').val('A'); $('#respostaCivil6Txt').html('A');">A) Com cláusula de reversão.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil6').val('B'); $('#respostaCivil6Txt').html('B');">B) Conjuntiva inoficiosa.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil6').val('C'); $('#respostaCivil6Txt').html('C');">C) Sob condição suspensiva expressa.</a><br/>
											<a href="javascript:void(0);" onclick="javascript:$('#respostaCivil6').val('D'); $('#respostaCivil6Txt').html('D');">D) Nenhuma das alternativas.</a><br/>
									<br/>
									<input type="hidden" id="respostaCivil6" name="respostaCivil6" />
									Sua resposta: <strong id="respostaCivil6Txt" class="text-info"></strong>

								</div><br/>

								<div class="alert alert-warning">
									As questões <strong>7</strong> a <strong>9</strong> são dissertativas.
								</div>

								<div class="form-group">
									<label>7. Discorra sobre os Princípios da Impugnação Específica e da Eventualidade, mencionando: Quando se aplicam; A quem se destina e Quais os efeitos decorrentes da sua inobserv&acirc;ncia<br/>
									</label>
									<textarea class="form-control" id="respostaCivil7" name="respostaCivil7" rows="6" placeholder="Se não souber a resposta escreva 'Não sei'" style="border: 1px solid #999;"></textarea>
								</div><br/>

								<div class="form-group">
									<label>8. Hipoteticamente, você é o(a) único(a) advogado(a) associado(a) do escritório presente no momento e todos os demais estão temporariamente impossibilitados de serem contratados. Eis que um dos advogados contratados como correspondente de uma comarca interiorana, atuando em favor de um dos clientes do escritório, em um processo físico tramitando sob o rito da Lei número 9.099/95, entra em contato informando que a contestação e os documentos para sua habilitação não estão nos autos. Ocorre que a Audiência de Instrução ocorrerá em 20 minutos. Quais são suas providências?<br/>
									</label>
									<textarea class="form-control" id="respostaCivil8" name="respostaCivil8" rows="6" placeholder="Se não souber a resposta escreva 'Não sei'" style="border: 1px solid #999;"></textarea>
								</div><br/>

								<div class="form-group">
									<label>9. Durante uma audiência de instrução em processo cível, com tr&acirc;mite no rito ordinário, o juiz decide antecipar, de ofício, os efeitos da tutela de mérito, deferindo liminar em desfavor de seu cliente. Qual é o procedimento cabível nesta hipótese? E se a decisão fosse proferida em sede de juizado especial, o procedimento seria o mesmo? Justifique<br/>
									</label>
									<textarea class="form-control" id="respostaCivil9" name="respostaCivil9" rows="6" placeholder="Se não souber a resposta escreva 'Não sei'" style="border: 1px solid #999;"></textarea>
								</div>

						</div>
						<!-- ============================= FIM CIVIL ============================= -->


						<!-- ============================= INICIO TRABALHISTA ============================= -->
						<div class="col-md-12" id="perguntasTrabalhista" style="display:none;">

							<fieldset>
								<legend>Questões - Área TRABALHISTA</legend>
							</fieldset>

							<!-- <div class="alert alert-warning">
								Responda às questões <b>1</b> a <b>2</b> clicando na opção que você julgar correta.
							</div> -->

                            <div class="form-group">
                                <label>
                                    1. Em uma audiência trabalhista, cada parte conduz suas testemunhas, que, inicialmente, são ouvidas pelo juiz, começando pelas do autor. Após o magistrado fazer as perguntas que deseja, abre oportunidade para que os advogados façam suas indagações, começando pelo patrono do autor, que faz suas perguntas diretamente à testemunha, contra o que se opõe o juiz, afirmando que as perguntas deveriam ser feitas a ele, que, em seguida, perguntaria à testemunha. Diante do incidente instalado e de acordo com o regramento da CLT, <u>assinale a afirmativa correta</u>:
                                </label>
                                <br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista1').val('A'); $('#respostaTrabalhista1Txt').html('A');">A) Correto o advogado, pois, de acordo com o CPC, o advogado fará perguntas diretamente à testemunha.</a><br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista1').val('B'); $('#respostaTrabalhista1Txt').html('B');">B) A CLT não tem dispositivo próprio, daí porque poderia ser admitido tanto o sistema direto quanto o indireto.</a><br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista1').val('C'); $('#respostaTrabalhista1Txt').html('C');">C) Correto o magistrado, pois a CLT determina que o sistema seja indireto ou presidencial.</a><br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista1').val('D'); $('#respostaTrabalhista1Txt').html('D');">D) A CLT determina que o sistema seja híbrido, intercalando perguntas feitas diretamente pelo advogado, com indagações realizadas pelo juiz.</a>
                                <br/><br/>
                                <input type="hidden" id="respostaTrabalhista1" name="respostaTrabalhista1" />
                                Sua resposta: <b id="respostaTrabalhista1Txt" class="text-info"></b>

                            </div><br/>

                            <div class="form-group">
                                <label>
                                    2. É <u>correto</u> afirmar que:
                                </label>
                                <br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista2').val('A'); $('#respostaTrabalhista2Txt').html('A');">A) O juiz não pode reconhecer de ofício a prescrição.</a><br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista2').val('B'); $('#respostaTrabalhista2Txt').html('B');">B) O não comparecimento do reclamante à audiência importa no arquivamento da reclamação, e o não comparecimento do reclamado importa revelia, além de confissão quanto à matéria de fato.</a><br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista2').val('C'); $('#respostaTrabalhista2Txt').html('C');">C) O aviso prévio sempre prorroga o contrato de trabalho por mais 30 dias.</a><br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista2').val('D'); $('#respostaTrabalhista2Txt').html('D');">D) É obrigatório que o preposto da empresa reclamada seja seu empregado.</a>
                                <br/><br/>
                                <input type="hidden" id="respostaTrabalhista2" name="respostaTrabalhista2" />
                                Sua resposta: <b id="respostaTrabalhista2Txt" class="text-info"></b>

                            </div><br/>

                            <!-- <div class="alert alert-warning">
                                As questões <strong>3</strong> a <strong>9</strong> são dissertativas.
                            </div> -->

                            <div class="form-group">
                                <label>
                                    3. Assinale as assertivas como <u>VERDADEIRAS ou FALSAS</u>, corrigindo estas últimas:
                                </label>
                                <br/>
                                A) Todos os prazos recursais trabalhistas são de 8 dias.<br/>
                                B) A oposição de Embargos Declaratórios suspende o prazo recursal.<br/>
                                C) A sociedade de advogados pode associar-se com advogados, sem vínculo de emprego, para participação nos resultados.<br/>
                                D) O marco inicial para contagem do prazo de embargos à execução é o recebimento da notificação para pagar ou para garantir a execução.
                                <br/><br/>
                                <textarea class="form-control" id="respostaTrabalhista3" name="respostaTrabalhista3" rows="6" placeholder="Se não souber a resposta escreva 'Não sei'" style="border: 1px solid #999;"></textarea>
                            </div><br/>

                            <div class="form-group">
                                <label>
                                    4. A empregada X ingressou com reclamação trabalhista, requerendo indenização em danos morais, pois alega que adquiriu doença acidentária na empresa Y, dentre outros pedidos. No momento da audiência, o Juiz, ao deferir a realização de perícia, arbitrou valor à título de honorários periciais em quantia flagrantemente excessiva, bem como condicionou a realização de citada perícia à antecipação integral do referido valor por parte da reclamada. Ciente dos ditames que versam sobre a sucumbência do objeto da perícia, comente a atitude do Juiz, bem como indique se há alguma manifestação a ser suscitada pelo patrono da parte reclamada.
                                </label>
                                <br/>
                                <textarea class="form-control" id="respostaTrabalhista4" name="respostaTrabalhista4" rows="6" placeholder="Se não souber a resposta escreva 'Não sei'" style="border: 1px solid #999;"></textarea>
                            </div><br/>

                            <div class="form-group">
                                <label>
                                    5. Z, ex-empregado da empresa W, há mais de 10 anos foi demitido sem justa causa e mediante o cumprimento de aviso prévio indenizado. Por conta da sua demissão, a empresa quitou as suas verbas rescisórias no prazo de 5 dias após a sua demissão, por meio de depósito na sua conta bancária, embora a homologação sindical da sua rescisão tenha ocorrido apenas após 30 dias da sua demissão. Diante dessa situação, questiona-se: o pagamento da rescisão obreira ocorreu de forma regular e tempestiva? A homologação da rescisão do contrato de trabalho obreiro por parte do sindicato é imprescindível para validar a extinção do contrato de trabalho em questão? Quais as consequências que essa rescisão pode trazer ao empregado?
                                </label>
                                <br/>
                                <textarea class="form-control" id="respostaTrabalhista5" name="respostaTrabalhista5" rows="6" placeholder="Se não souber a resposta escreva 'Não sei'" style="border: 1px solid #999;"></textarea>
                            </div><br/>

							<div class="form-group">
								<label>
									6. A empresa M costuma terceirizar alguns serviços não relacionados à sua atividade fim e contratou empregados da empresa N para prestarem serviços na sede da empresa contratante. Ocorre que a empresa contratada, inesperadamente, encerrou suas atividades, sem qualquer comunicação prévia aos seus empregados ou mesmo às empresas que a contrataram, a exemplo da empresa M. Com intuito de receber as verbas trabalhistas a que faziam jus, os empregados da empresa N ingressaram com ação trabalhista em face de ambas as empresas. <u>Nesse contexto, discorra acerca da possibilidade de terceirização de serviços entre empresas, bem como acerca do tipo de responsabilidade da empresa tomadora de serviços.</u>
								</label>
								<br/>
								<textarea class="form-control" id="respostaTrabalhista6" name="respostaTrabalhista6" rows="6" placeholder="Se não souber a resposta escreva 'Não sei'" style="border: 1px solid #999;"></textarea>
							</div><br/>

							<div class="form-group">
								<label>
									7. Quais os requisitos para que seja deferida a equiparação salarial entre empregados, após a Reforma Trabalhista (Lei número 13.467/2017)?
								</label>
								<br/>
								<textarea class="form-control" id="respostaTrabalhista7" name="respostaTrabalhista7" rows="6" placeholder="Se não souber a resposta escreva 'Não sei'" style="border: 1px solid #999;"></textarea>
							</div><br/>

							<div class="form-group">
								<label>
									8. Você foi contratado pelo dono da empresa F para resolver a seguinte situação: a empregada T foi demitida hoje por abando de emprego, haja vista que esta está há mais de 30 dias faltando ao trabalho e sem dar qualquer justificativa, não obstante as inúmeras e comprovadas tentativas da empresa em contatá-la. Diante dessa situação, o dono da empresa disse que não tinha como pagar as verbas rescisórias da ex-empregada, uma vez que esta não tinha qualquer conta bancária. Ante à tal situação, apresente uma solução para contornar o problema relatado pelo seu cliente.
								</label>
								<br/><br/>
								<textarea class="form-control" id="respostaTrabalhista8" name="respostaTrabalhista8" rows="6" placeholder="Se não souber a resposta escreva 'Não sei'" style="border: 1px solid #999;"></textarea>
							</div><br/>

							<div class="form-group">
								<label>
                                    9. Sobre a competência da Justiça do Trabalho, a Consolidação das Leis do Trabalho e as Súmulas do Tribunal Superior do Trabalho estabelecem:
								</label>
								<br/>
								<a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista9').val('A'); $('#respostaTrabalhista9Txt').html('A');">A) A competência territorial das Varas do Trabalho é determinada pela localidade onde o empregado prestar serviços ao empregador, ainda que tenha sido contratado noutro local ou no estrangeiro, desde que seja o autor da ação.</a><br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista9').val('B'); $('#respostaTrabalhista9Txt').html('B');">B) Quando for parte de dissídio agente ou viajante comercial, a competência será da Vara da localidade em que a empresa tenha agência ou filial e a esta o empregado esteja subordinado e, na falta, será competente a Vara do domicílio do empregado ou a da localidade mais próxima.</a><br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista9').val('C'); $('#respostaTrabalhista9Txt').html('C');">C) Se o empregado for brasileiro, a Justiça do Trabalho brasileira tem competência para processar e julgar os dissídios ocorridos em agência ou filial no estrangeiro, ainda que haja convenção internacional dispondo em contrário.</a><br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista9').val('D'); $('#respostaTrabalhista9Txt').html('D');">D) A Justiça do Trabalho é competente para determinar o recolhimento das contribuições previdenciárias, em relação às sentenças condenatórias em pecúnia que proferir e aos valores, objeto de acordo homologado, que integram o salário de contribuição, inclusive, no caso de reconhecimento de vínculo empregatício, quanto aos salários pagos durante a contratualidade.</a><br/>
								<a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista9').val('E'); $('#respostaTrabalhista9Txt').html('E');">E) A Justiça do Trabalho é competente para processar e julgar as ações de indenização por danos morais e materiais decorrentes da relação de trabalho, inclusive as oriundas de acidente de trabalho e doenças a ele equiparados, ainda que propostas pelos dependentes, desde que habilitados no Instituto Nacional do Seguro Social ou sucessores do trabalhador falecido.</a>
                                <br/><br/>
								<input type="hidden" id="respostaTrabalhista9" name="respostaTrabalhista9" />
                                Sua resposta: <b id="respostaTrabalhista9Txt" class="text-info"></b>
							</div><br/>

							<div class="form-group">
								<label>
									10. Sobre a execução na Justiça do Trabalho, é correto afirmar:
								</label>
								<br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista10').val('A'); $('#respostaTrabalhista10Txt').html('A');">A) O cheque emitido em reconhecimento de saldo de salários, férias e gratificação de natal não pode ser executado diretamente na Justiça do Trabalho.</a><br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista10').val('B'); $('#respostaTrabalhista10Txt').html('B');">B) O agravo de petição só será recebido quando o agravante delimitar, justificadamente, as matérias e os valores impugnados, permitida a execução provisória da parte remanescente, nos próprios autos ou por carta de sentença.</a><br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista10').val('C'); $('#respostaTrabalhista10Txt').html('C');">C) Elaborada a conta e tornada líquida, o juiz poderá abrir às partes prazo sucessivo de dez dias para impugnação fundamentada com a indicação dos itens e valores objeto da discordância, e procederá à intimação da União para manifestação, no mesmo prazo, sob pena de preclusão.</a><br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista10').val('D'); $('#respostaTrabalhista10Txt').html('D');">D) O exequente tem preferência em relação à arrematação para pedir adjudicação, devendo depositar de imediato a diferença, quando o valor do crédito for inferior ao valor dos bens, cujo preço não pode ser inferior ao do melhor lance de arrematação.</a><br/>
								<a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista10').val('E'); $('#respostaTrabalhista10Txt').html('E');">E) O arrematante deverá garantir o lance com o sinal correspondente a 20% do seu valor, podendo levantá-lo se não complementar o valor remanescente da arrematação, no prazo de vinte e quatro horas, caso em que os bens executados voltarão à praça.</a>
                                <br/><br/>
								<input type="hidden" id="respostaTrabalhista10" name="respostaTrabalhista10" />
                                Sua resposta: <b id="respostaTrabalhista10Txt" class="text-info"></b>
							</div><br/>

							<div class="form-group">
								<label>
									11. A chamada Reforma Trabalhista, estabelecida pela Lei nº 13.467/2017, trouxe algumas mudanças importantes, entre elas, assuntos que já se encontravam na Consolidação das Leis do Trabalho e outros assuntos que foram incorporados na CLT com a reforma. Considerando as novidades que foram incorporadas pela Lei nº 13.467/2017 na CLT quanto aos dissídios individuais, assinale a alternativa correta.
								</label>
								<br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista11').val('A'); $('#respostaTrabalhista11Txt').html('A');">A) Previu que as nulidades não serão declaradas senão mediante provocação das partes, na primeira oportunidade processual.</a><br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista11').val('B'); $('#respostaTrabalhista11Txt').html('B');">B) No processo de execução, são devidas custas, de responsabilidade do executado, e pagas ao final do processo.</a><br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista11').val('C'); $('#respostaTrabalhista11Txt').html('C');">C) As testemunhas comparecerão à audiência de instrução independentemente de notificação ou intimação.</a><br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista11').val('D'); $('#respostaTrabalhista11Txt').html('D');">D) Nas ações com procedimento sumaríssimo, o pedido deverá ser certo ou determinado, com indicação do valor correspondente.</a><br/>
								<a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista11').val('E'); $('#respostaTrabalhista11Txt').html('E');">E) Será responsável por perdas e danos aquele que litigar de má-fé, seja reclamante, reclamado ou interveniente.</a>
                                <br/><br/>
								<input type="hidden" id="respostaTrabalhista11" name="respostaTrabalhista11" />
                                Sua resposta: <b id="respostaTrabalhista11Txt" class="text-info"></b>
							</div><br/>

							<div class="form-group">
								<label>
									12. Seu escritório foi contratado pela empresa Alumínio Brilhante Ltda. para assisti-la juridicamente em uma audiência. Você foi designado(a) para a audiência. Forneceram-lhe cópia da defesa e dos documentos, e afirmaram que tudo já havia sido juntado aos autos do processo eletrônico. Na hora da audiência, tendo sido aberta esta, bem como os autos eletrônicos do processo, o juiz constatou que a defesa não estava nos autos, mas apenas os documentos. Diante disso, o juiz facultou-lhe a opção de apresentar defesa.
								</label>
								<br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista12').val('A'); $('#respostaTrabalhista12Txt').html('A');">A) Nos exatos termos previstos na CLT, você deverá entregar a cópia da defesa escrita que está em sua posse, e requerer juntada dos documentos posteriormente.</a><br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista12').val('B'); $('#respostaTrabalhista12Txt').html('B');">B) Aduzir defesa oral em 20 minutos e requerer prazo para juntada posterior dos documentos.</a><br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista12').val('C'); $('#respostaTrabalhista12Txt').html('C');">C) Requerer o adiamento da audiência para posterior entrega da defesa e documentos.</a><br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista12').val('D'); $('#respostaTrabalhista12Txt').html('D');">D) Requerer a digitalização da sua defesa para juntada imediata aos autos e requerer prazo para a juntada dos documentos no processo.</a>
                                <br/><br/>
								<input type="hidden" id="respostaTrabalhista12" name="respostaTrabalhista12" />
                                Sua resposta: <b id="respostaTrabalhista12Txt" class="text-info"></b>
							</div><br/>

							<div class="form-group">
								<label>
									13. Carla Lopes ajuizou reclamação trabalhista contra sua ex-empregadora, Supermercados Onofre, que, há seis meses, demitiu três de seus dezoito empregados, entre eles, Carla. Em sua petição inicial, ela requereu valores devidos em razão de verbas rescisórias pagas a menor, adicional de insalubridade nunca pago ao longo do contrato de trabalho e danos morais decorrentes de assédio moral. Nessa reclamatória, foi atribuído como valor da causa o importe de cinquenta mil reais. Acerca dessa situação hipotética, julgue o item que segue.
								</label>
                                <br/>
								<p>
									Carla poderá indicar como testemunhas ex-empregados da empresa. No entanto, a testemunha que tiver ajuizado ação contra a mesma reclamada poderá ser contraditada pela parte contrária e seu depoimento poderá ser tomado apenas na condição de informante do juízo.
								</p>
								<textarea class="form-control" id="respostaTrabalhista13" name="respostaTrabalhista13" rows="6" placeholder="Se não souber a resposta escreva 'Não sei'" style="border: 1px solid #999;"></textarea>
							</div><br/>

							<div class="form-group">
								<label>
									14. Analise as assertivas abaixo:
								</label>
                                <br/>
								<p>
									I – O depoimento da parte é uma das provas mais importantes, razão pela qual a parte jamais deverá renunciar à oportunidade de expor oralmente em audiência a sua versão para o juiz.
								</p>
								<p>
									II – Para que o depoimento pessoal seja válido como meio de prova é imprescindível que o magistrado colha do depoente o compromisso de falar a verdade, sob pena de se sujeitar ao rigor da lei.
								</p>
								<p>
									III - Consoante entendimento majoritário nos tribunais pátrios, o juiz deve ouvir todas as testemunhas levadas pela parte, caracterizando cerceamento de defesa a dispensa de duas testemunhas após a oitiva da primeira, em razão de ser direito da parte ouvir até três testemunhas, nas ações que tramitem pelo rito ordinário.
								</p>
								<p>
									Assinale a alternativa <b>CORRETA</b>:
								</p>
								<a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista14').val('A'); $('#respostaTrabalhista14Txt').html('A');">A) Apenas a assertiva I está correta.</a><br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista14').val('B'); $('#respostaTrabalhista14Txt').html('B');">B) Apenas as assertivas II e III estão corretas.</a><br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista14').val('C'); $('#respostaTrabalhista14Txt').html('C');">C) Todas as assertivas estão incorretas.</a>
                                <br/><br/>
								<input type="hidden" id="respostaTrabalhista14" name="respostaTrabalhista14" />
                                Sua resposta: <b id="respostaTrabalhista14Txt" class="text-info"></b>
							</div><br/>

							<div class="form-group">
								<label>
									15. Na audiência UNA da reclamação trabalhista movida por Ana Maria em face da empresa de laticínios Via Láctea Ltda., o preposto chegou 20 minutos atrasado, alegando que o pneu de seu carro havia furado a caminho do Fórum. A audiência não tinha se encerrado, sendo que a advogada da Reclamada tinha comparecido no horário, apresentado Defesa com documentos, mas não havia proposta para acordo, sendo que o juiz estava marcando perícia para apuração de insalubridade no ambiente de trabalho. Neste momento, a advogada da Reclamada requereu que não fossem aplicados os efeitos da revelia e confissão, tendo em vista que o preposto esteve presente à audiência antes de seu término. Diante dos fatos narrados e, de acordo com a lei e a orientação jurisprudencial do Tribunal Superior do Trabalho, é correto afirmar que:
								</label>
								<br/>
                                <a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista15').val('A'); $('#respostaTrabalhista15Txt').html('A');">A) não existe previsão legal tolerando atraso no horário de comparecimento da parte na audiência, sendo aplicados os efeitos da revelia e confissão à Reclamada, entretanto, presente a advogada, serão aceitos a contestação e os documentos apresentados.</a><br/>
								<a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista15').val('B'); $('#respostaTrabalhista15Txt').html('B');">B) assiste razão à Reclamada, tendo em vista que o preposto esteve presente à audiência antes de seu término, razão pela qual não serão aplicados os efeitos da revelia e confissão à empresa.</a><br/>
								<a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista15').val('C'); $('#respostaTrabalhista15Txt').html('C');">C) apesar de não existir previsão legal tolerando atrasos no horário de comparecimento da parte na audiência, tendo o preposto comparecido e apresentado justificativa para o seu atraso, deverá o juiz afastar os efeitos da revelia e confissão à Reclamada.</a><br/>
								<a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista15').val('D'); $('#respostaTrabalhista15Txt').html('D');">D) assiste razão à Reclamada, mas não porque o preposto chegou atrasado antes do término da audiência, mas, sim, porque a advogada esteve presente pontualmente.</a><br/>
								<a href="javascript:void(0);" onclick="javascript:$('#respostaTrabalhista15').val('E'); $('#respostaTrabalhista15Txt').html('E');">E) não existe previsão legal tolerando atraso no horário de comparecimento da parte na audiência, sendo aplicados os efeitos da revelia e confissão à Reclamada, ainda, que presente a advogada, não serão aceitos a contestação e os documentos apresentados.</a>
                                <br/><br/>
								<input type="hidden" id="respostaTrabalhista15" name="respostaTrabalhista15" />
								Sua resposta: <b id="respostaTrabalhista15Txt" class="text-info"></b>
							</div><br/>

							<div class="form-group">
								<label>
									16. O que lhe motivou a querer atuar como advogado(a) associado(a) ao Rocha, Marinho e Sales e no que você pode contribuir para dar um melhor atendimento aos clientes comuns?
								</label>
                                <br/>
								<textarea class="form-control" id="respostaTrabalhista16" name="respostaTrabalhista16" rows="6" placeholder="Se não souber a resposta escreva 'Não sei'" style="border: 1px solid #999;"></textarea>
							</div><br/>

						</div>
						<!-- ============================= FIM TRABALHISTA ============================= -->

							<?php if ($_SESSION["areaAtuacao"] == 'Civil'){ ?>
							<hr>
							<button type="button" class="btn btn-primary" id="btnEnviar" style="display: none;" onclick="validaRespostasAdvCivil()">Enviar</button>
							<button type="button" class="btn btn-default" onclick="javascript: $('html, body').animate({scrollTop:0}, 'slow');"><i class="fa fa-arrow-circle-up"></i> Topo</button>
							<?php } ?>

							<?php if ($_SESSION["areaAtuacao"] == 'Trabalhista'){ ?>
							<hr>
							<button type="button" class="btn btn-primary" id="btnEnviar" style="display: none;" onclick="validaRespostasAdvTrabalhista()">Enviar</button>
							<button type="button" class="btn btn-default" onclick="javascript: $('html, body').animate({scrollTop:0}, 'slow');"><i class="fa fa-arrow-circle-up"></i> Topo</button>
							<?php } ?>

							<?php if ($_SESSION["areaAtuacao"] == 'Ambas'){ ?>
							<hr>
							<button type="button" class="btn btn-primary" id="btnEnviar" style="display: none;" onclick="validaRespostasAdvAmbas()">Enviar</button>
							<button type="button" class="btn btn-default" onclick="javascript: $('html, body').animate({scrollTop:0}, 'slow');"><i class="fa fa-arrow-circle-up"></i> Topo</button>
							<?php } ?>

						</form>
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
	            <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-file-alt color-blue"></i> Envio de Avaliação Técnica </h4>
	          </div>
	          <div class="modal-body">
	            <p>
		            As respostas não poderão ser editadas após envio.
		            <br/><br/>
		            Deseja confirmar o envio de suas respostas?
	        	</p>
	            <p align="center" class="text-info" id="carregandoEnvio" style="display: none;"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>
	          </div>
	          <div class="modal-footer">
		          <button id="botaoConfirmar" type="button" class="btn btn-primary" onclick="javascript: $('#carregandoEnvio').show(); confirmarEnvio();">
		          <i class="fa fa-check"></i> Confirmar</button>
		          <button type="button" class="btn btn-default" onclick="javascript:$('#carregandoEnvio').hide();" data-dismiss="modal">
		          <i class="fa fa-times"></i> Voltar</button>
	          </div>

	        </div>
	      </div>
	    </div>
    	<!-- Fim Modal Confirmacao -->

    	<!-- Modal Alerta Fralde -->
		<div id="modalVacilo" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
	      <div class="modal-dialog">
	        <div class="modal-content">

	          <div class="modal-header alert-danger">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle color-red"></i></span>
	            </button>
	            <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-thumbs-down color-red"></i> Ação não permitida! </h4>
	          </div>
	          <div class="modal-body">
	            <p>
		            Identificamos ação não permitida durante sua avaliação.
	        	</p>
	            <p align="center" class="text-info" id="carregandoEnvio" style="display: none;"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>
	          </div>
	          <div class="modal-footer">
		          <button type="button" class="btn btn-danger" onclick="javascript:$('#carregandoEnvio').hide();" data-dismiss="modal">
		          Desculpe-me, não irá se repetir</button>
	          </div>

	        </div>
	      </div>
	    </div>
    	<!-- Fim Modal alerta Fralde -->


		<div class="row">

			<?php include "rodape.php";
            mysqli_close($bd);
			?>

		</div><!--/.row-->
	</div>	<!--/.main-->

    <?php include 'scripts.php' ?>
	<script src="js/custom.js"></script>
	<script type="text/javascript" src="js/util.js"></script>

	<script type="text/javascript">

		$(document).ready(function() {

			for (i = 6; i < 10; i++) {
                $("#respostaCivil"+i).bind({
					paste : function(){
						capturaCopia();
					}
				});
			}

			for (i = 3; i < 9; i++) {
                $("#respostaTrabalhista"+i).bind({
					paste : function(){
						capturaCopia();
					}
				});
			}

            $("#respostaTrabalhista13").bind({
                paste : function(){
                    capturaCopia();
                }
            });

            $("#respostaTrabalhista16").bind({
                paste : function(){
                    capturaCopia();
                }
            });

            setInterval(manterVivo, 6e5) // a cada 10 minutos
		});

		function capturaCopia() {
			$.getJSON("capturaCopia.php", function(lista) {
				for (index=0; index < lista.length; index++) {
					if(lista[index].loginOk == "S"){
						/*window.location.href='home.php';*/
					}
				}// if FOR
			});
		}

	</script>

</body>
</html>
