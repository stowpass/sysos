<?php
require_once __DIR__ . '/includes/funcoes.php';
include_once __DIR__ . '/variaveis.php';

session_start();

isSessaoValida('', false);

$bd = abreConn();
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
				<li class="active">Bem-vindo(a) <strong><?=$_SESSION['nome']?></strong></li>
				<li><a href="home.php"><i class="fa fa-arrow-circle-left"></i> Voltar</a></li>
			</ol>
		</div><!--/.row-->

		<?php if (!isset($_SESSION['finalizado']) && $_SESSION['finalizado'] != 'S'){
		die("<hr/><div class='alert alert-danger'><i class='fa fa-lg fa-exclamation-triangle'>&nbsp;</i> ATEN&Ccedil;&Atilde;O: Voc&ecirc; j&aacute; envio suas respostas. Aguarde a finaliza&ccedil;&atilde;o do processo! - <a href='logoff.php'>Sair</a></div>");
		} ?>

		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					<i class="fa fa-file-alt color-blue"></i> Avalia&ccedil;&atilde;o de Candidato - <strong class="text-info"><?=$_SESSION['cargo']?></strong>
				</h1>

				<div class="alert alert-warning">
					Suas respostas N&Atilde;O podem ser editadas!
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
						<button class="btn btn-primary" onclick="javascript:$('#perguntasTrabalhista').hide(); $('#perguntasCivil').show(500); $('#btnEnviar').show(500);" style="display: <?=$mostraBotaoCivil?>"><i class="fa fa-hand-point-right"></i> Quest&otilde;es - &Aacute;rea C&iacute;vel</button>
					</div>
				</div>
				<?php } ?>

				<?php if ($_SESSION["areaAtuacao"] == 'Trabalhista'){ ?>
				<div class="row" align="center">
					<div class="col-md-1">
						<button class="btn btn-primary" onclick="javascript:$('#perguntasCivil').hide(); $('#perguntasTrabalhista').show(500); $('#btnEnviar').show(500);" style="display: <?=$mostraBotaoTrabalhista?>"><i class="fa fa-hand-point-right"></i> Quest&otilde;es - &Aacute;rea Trabalhista</button>
					</div>
				</div>
				<?php } ?>

				<?php if ($_SESSION["areaAtuacao"] == 'Ambas'){ ?>
				<div class="row" align="center">
					<div class="col-md-1">
						<button class="btn btn-primary" onclick="javascript:$('#perguntasTrabalhista').hide(); $('#perguntasCivil').show(500); $('#btnEnviar').show(500);" style="display: <?=$mostraBotaoCivil?>"><i class="fa fa-hand-point-right"></i> Quest&otilde;es - &Aacute;rea C&iacute;vel</button>
					</div><br/><br/>
					<div class="col-md-1">
						<button class="btn btn-primary" onclick="javascript:$('#perguntasCivil').hide(); $('#perguntasTrabalhista').show(500); $('#btnEnviar').show(500);" style="display: <?=$mostraBotaoTrabalhista?>"><i class="fa fa-hand-point-right"></i> Quest&otilde;es - &Aacute;rea Trabalhista</button>
					</div>
				</div>
				<?php } ?>

				<hr/>

				<div class="panel panel-default">
					<div class="panel-body">

						<!-- Mensagens de ALERTA -->
						<?php if ($_SESSION["areaAtuacao"] != 'Ambas'){ ?>
						<div id="msgErro" class="alert alert-danger" style="display: none;">
							<strong>Erro</strong>: Favor, responda &agrave;s quest&otilde;es em destaque
						</div>
						<?php } ?>

						<?php if ($_SESSION["areaAtuacao"] == 'Ambas'){ ?>
						<div id="msgErro" class="alert alert-danger" style="display: none;">
							<strong>Erro</strong>: Favor, responda &agrave;s quest&otilde;es em destaque. Verifique ambas quest&otilde;es (<strong>C&iacute;vel</strong> e <strong>Trabalhista</strong>)
						</div>
						<?php } ?>

						<div class="progress" id="carregando" style="display: none;">
							<div data-percentage="0%" style="width: 100%;" class="progress-bar progress-bar-blue" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
						<p align="center" id="carregandoTexto" style="display: none;" class="text-info"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>
						<!-- FIM Mensagens de ALERTA -->


						<!-- ============================= INICIO CIVIL ============================= -->
						<div class="col-md-12" id="perguntasCivil" style="display:none;">

							<?php
								$qC = mysqli_query($bd, "SELECT * FROM tb_respostas where id_candidato = '" . $_SESSION['id'] . "' and status = 'A' and area_atuacao = 'Civil' order by id desc limit 0,1 ");
								$lC = mysqli_fetch_assoc($qC);
								mysqli_free_result($qC);
							?>

							<fieldset>
								<legend>Quest&otilde;es - &Aacute;rea C&Iacute;VEL</legend>
							</fieldset>

                            <div class="form-group">
                                <label>1. Acerca da revelia, &eacute; correto afirmar que:<br/></label>
                                        <br/>
                                        A) A revelia se dá com a não apresentação de exceção ou de reconvenção no prazo da resposta.<br/>
                                        B) Ainda que o litígio verse sobre direitos indisponíveis, a revelia produz seus efeitos normalmente.<br/>
                                        C) O revel pode intervir no processo em qualquer fase, recebendo-o no estado em que se encontrar.<br/>
                                        D) Contra o revel, ainda que tenha patrono constituído nos autos, correrão os prazos independentemente de intimação.<br/>
                                <br/>
                                Sua resposta: <strong id="respostaCivil1Txt" class="text-info"><?=$lC['resposta_1']?></strong>

                            </div><br/>


                            <div class="form-group">
                                <label>2. Sobre a suspens&atilde;o e interrup&ccedil;&atilde;o de prazo pode se dizer que:<br/></label>
                                        <br/>
                                        A) S&atilde;o institutos de direito processual com a mesma consequ&ecirc;ncia.<br/>
                                        B) Na suspens&atilde;o o prazo &eacute; integralmente devolvido &agrave; parte.<br/>
                                        C) Na interrup&ccedil;&atilde;o o prazo retoma a contagem a partir do dia que foi interrompido.<br/>
                                        D) Na interrup&ccedil;&atilde;o o prazo &eacute; integralmente devolvido &agrave; parte.<br/>
                                <br/>
                                Sua resposta: <strong id="respostaCivil2Txt" class="text-info"><?=$lC['resposta_2']?></strong>

                            </div><br/>


                            <div class="form-group">
                                <label>3. Sobre prescri&ccedil;&atilde;o e decad&ecirc;ncia, &eacute; correto afirmar que:<br/></label>
                                        <br/>
                                        A) O juiz n&atilde;o pode reconhecer de of&iacute;cio a prescri&ccedil;&atilde;o.<br/>
                                        B) O C&oacute;digo Civil permite a alega&ccedil;&atilde;o de prescri&ccedil;&atilde;o em qualquer grau de Jurisdi&ccedil;&atilde;o.<br/>
                                        C) A prescri&ccedil;&atilde;o da pretens&atilde;o do autor n&atilde;o pode ser alegada somente em apela&ccedil;&atilde;o.<br/>
                                        D) Prescri&ccedil;&atilde;o e decad&ecirc;ncia s&atilde;o institutos sem qualquer distin&ccedil;&atilde;o.<br/>
                                <br/>
                                Sua resposta: <strong id="respostaCivil3Txt" class="text-info"><?=$lC['resposta_3']?></strong>

                            </div><br/>


                            <div class="form-group">
                                <label>4. Quanto ao cumprimento da senten&ccedil;a, &eacute; correto dizer que:<br/></label>
                                        <br/>
                                        A) A impugna&ccedil;&atilde;o ao cumprimento da senten&ccedil;a ser&aacute; recebida como regra geral nos efeitos devolutivo e suspensivo, podendo o juiz atribuir somente efeito devolutivo se do duplo efeito advier preju&iacute;zo irrepar&aacute;vel ou de dif&iacute;cil repara&ccedil;&atilde;o ao credor.<br/>
                                        B) &Eacute; definitiva a execu&ccedil;&atilde;o da senten&ccedil;a transitada em julgada e provis&oacute;ria quando se tratar de senten&ccedil;a impugnada mediante recurso recebido nos efeitos devolutivo e suspensivo.<br/>
                                        C) Na impugna&ccedil;&atilde;o ao cumprimento da senten&ccedil;a, se o executado alegar que o exequente, em excesso de execu&ccedil;&atilde;o, pleiteia  quantia superior &agrave; condena&ccedil;&atilde;o, dever&aacute; declarar de imediato o valor que entende correto, sob pena de rejei&ccedil;&atilde;o liminar dessa impugna&ccedil;&atilde;o.<br/>
                                        D) A decis&atilde;o que resolver a impugna&ccedil;&atilde;o ao cumprimento de senten&ccedil;a &eacute; recorr&acute;vel sempre por meio de agravo de  instrumento.<br/>
                                        E) A execu&ccedil;&atilde;o provis&oacute;ria ocorre por conta e risco do credor, devendo os atos que importem levantamento de dep&oacute;sito em dinheiro ou aliena&ccedil;&atilde;o de propriedade serem precedidos necessariamente de cau&ccedil;&atilde;o id&ocirc;nea, sem exce&ccedil;&atilde;o.<br/>
                                <br/>
                                Sua resposta: <strong id="respostaCivil4Txt" class="text-info"><?=$lC['resposta_4']?></strong>

                            </div><br/>


                            <div class="form-group">
                                <label>5. Sobre pagamento em consigna&ccedil;&atilde;o, assinale a alternativa incorreta:<br/></label>
                                        <br/>
                                        A) Considera-se pagamento, e extingue a obriga&ccedil;&atilde;o, o dep&oacute;sito judicial ou em estabelecimento banc&aacute;rio da coisa devida, nos casos e forma legais.<br/>
                                        B) Julgado procedente o dep&oacute;sito, o devedor j&aacute; n&atilde;o poder&aacute; levant&aacute;-lo, embora o credor consinta, sen&atilde;o de acordo com os outros devedores e fiadores.<br/>
                                        C) Se a coisa devida for im&oacute;vel, n&atilde;o poder&aacute; ser objeto de pagamento em consigna&ccedil;&atilde;o, pois n&atilde;o poder&aacute; ser depositada em ju&iacute;zo.<br/>
                                        D) Para que a consigna&ccedil;&atilde;o tenha for&ccedil;a de pagamento, ser&aacute; mister concorram, em rela&ccedil;&atilde;o &agrave;s pessoas, ao objeto, modo e tempo, todos os requisitos sem os quais n&aacute;o &eacute; v&aacute;lido o pagamento.<br/>
                                <br/>
                                Sua resposta: <strong id="respostaCivil5Txt" class="text-info"><?=$lC['resposta_5']?></strong>

                            </div><br/>


                            <div class="form-group">
                                <label>6. Estipulando o doador que os bens doados reverterão ao seu patrimônio se o donat&aacute;rio vier a falecer antes dele, ter-se-&aacute; doa&ccedil;&atilde;o:<br/></label>
                                        <br/>
                                        A) Com cl&aacute;usula de revers&atilde;o.<br/>
                                        B) Conjuntiva inoficiosa.<br/>
                                        C) Sob condi&ccedil;&atilde;o suspensiva expressa.<br/>
                                        D) Nenhuma das alternativas.<br/>
                                <br/>
                                Sua resposta: <strong id="respostaCivil6Txt" class="text-info"><?=$lC['resposta_6']?></strong>

                            </div><br/>


                            <div class="form-group">
                                <label>7. Discorra sobre os Princ&iacute;pios da Impugna&ccedil;&atilde;o Espec&iacute;fica e da Eventualidade, mencionando: Quando se aplicam; A quem se destina e Quais os efeitos decorrentes da sua inobserv&acirc;ncia<br/>
                                </label>
                                <textarea disabled="disabled" class="form-control" id="respostaCivil7" name="respostaCivil7" rows="6" placeholder="Se n&atilde;o souber a resposta escreva 'N&atilde;o sei'" style="border: 1px solid #999;">Sua Resposta: <?=$lC['resposta_7']?></textarea>
                            </div><br/>

                            <div class="form-group">
                                <label>8. Hipoteticamente, voc&ecirc; &eacute; o(a) &uacute;nico(a) advogado(a) associado(a) do escrit&oacute;rio presente no momento e todos os demais est&atilde;o temporariamente impossibilitados de serem contratados. Eis que um dos advogados contratados como correspondente de uma comarca interiorana, atuando em favor de um dos clientes do escrit&oacute;rio, em um processo f&iacute;sico tramitando sob o rito da Lei n&uacute;mero 9.099/95, entra em contato informando que a contesta&ccedil;&atilde;o e os documentos para sua habilita&ccedil;&atilde;o n&atilde;o est&atilde;o nos autos. Ocorre que a Audi&ecirc;ncia de Instru&ccedil;&atilde;o ocorrer&aacute; em 20 minutos. Quais s&atilde;o suas provid&ecirc;ncias?<br/>
                                </label>
                                <textarea disabled="disabled" class="form-control" id="respostaCivil8" name="respostaCivil8" rows="6" placeholder="Se n&atilde;o souber a resposta escreva 'N&atilde;o sei'" style="border: 1px solid #999;">Sua Resposta: <?=$lC['resposta_8']?></textarea>
                            </div><br/>

                            <div class="form-group">
                                <label>9. Durante uma audi&ecirc;ncia de instru&ccedil;&atilde;o em processo c&iacute;vel, com tr&acirc;mite no rito ordin&aacute;rio, o juiz decide antecipar, de of&iacute;cio, os efeitos da tutela de m&eacute;rito, deferindo liminar em desfavor de seu cliente. Qual &eacute; o procedimento cab&iacute;vel nesta hip&oacute;tese? E se a decisão fosse proferida em sede de juizado especial, o procedimento seria o mesmo? Justifique<br/>
                                </label>
                                <textarea disabled="disabled" class="form-control" id="respostaCivil9" name="respostaCivil9" rows="6" placeholder="Se n&atilde;o souber a resposta escreva 'N&atilde;o sei'" style="border: 1px solid #999;">Sua Resposta: <?=$lC['resposta_9']?></textarea>
                            </div><br/>

                            <button type="button" class="btn btn-default" onclick="javascript: $('html, body').animate({scrollTop:0}, 'slow');"><i class="fa fa-arrow-circle-up"></i> Topo</button>

						</div>
						<!-- ============================= FIM CIVIL ============================= -->



						<!-- ============================= INICIO TRABALHISTA ============================= -->
						<div class="col-md-12" id="perguntasTrabalhista" style="display:none;">

							<fieldset>
								<legend>Quest&otilde;es - &Aacute;rea TRABALHISTA</legend>
							</fieldset>

							<?php
								$qT = mysqli_query($bd, "SELECT * FROM tb_respostas where id_candidato = '" . $_SESSION['id'] . "' and status = 'A' and area_atuacao = 'Trabalhista' order by id desc limit 0,1 ");
								$lT = mysqli_fetch_assoc($qT);
								mysqli_free_result($qT);
							?>

                            <div class="form-group">
                                <label>
                                    1. Em uma audiência trabalhista, cada parte conduz suas testemunhas, que, inicialmente, são ouvidas pelo juiz, começando pelas do autor. Após o magistrado fazer as perguntas que deseja, abre oportunidade para que os advogados façam suas indagações, começando pelo patrono do autor, que faz suas perguntas diretamente à testemunha, contra o que se opõe o juiz, afirmando que as perguntas deveriam ser feitas a ele, que, em seguida, perguntaria à testemunha. Diante do incidente instalado e de acordo com o regramento da CLT, <u>assinale a afirmativa correta</u>:
                                </label>
                                <br/>
                                A) Correto o advogado, pois, de acordo com o CPC, o advogado fará perguntas diretamente à testemunha.<br/>

                                B) A CLT não tem dispositivo próprio, daí porque poderia ser admitido tanto o sistema direto quanto o indireto.<br/>

                                C) Correto o magistrado, pois a CLT determina que o sistema seja indireto ou presidencial.<br/>

                                D) A CLT determina que o sistema seja híbrido, intercalando perguntas feitas diretamente pelo advogado, com indagações realizadas pelo juiz.
                                <br/><br/>
                                Sua resposta: <b class="text-info"><?=$lT['resposta_1']?></b>

                            </div><br/>

                            <div class="form-group">
                                <label>
                                    2. É <u>correto</u> afirmar que:
                                </label>
                                <br/>
                                A) O juiz não pode reconhecer de ofício a prescrição.<br/>

                                B) O não comparecimento do reclamante à audiência importa no arquivamento da reclamação, e o não comparecimento do reclamado importa revelia, além de confissão quanto à matéria de fato.<br/>

                                C) O aviso prévio sempre prorroga o contrato de trabalho por mais 30 dias.<br/>

                                D) É obrigatório que o preposto da empresa reclamada seja seu empregado.
                                <br/><br/>
                                Sua resposta: <b class="text-info"><?=$lT['resposta_2']?></b>

                            </div><br/>

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
                                <p>Sua resposta:</p>
                                <textarea class="form-control" rows="6" style="border: 1px solid #999;" disabled><?=$lT['resposta_3']?></textarea>
                            </div><br/>

                            <div class="form-group">
                                <label>
                                    4. A empregada X ingressou com reclamação trabalhista, requerendo indenização em danos morais, pois alega que adquiriu doença acidentária na empresa Y, dentre outros pedidos. No momento da audiência, o Juiz, ao deferir a realização de perícia, arbitrou valor à título de honorários periciais em quantia flagrantemente excessiva, bem como condicionou a realização de citada perícia à antecipação integral do referido valor por parte da reclamada. Ciente dos ditames que versam sobre a sucumbência do objeto da perícia, comente a atitude do Juiz, bem como indique se há alguma manifestação a ser suscitada pelo patrono da parte reclamada.
                                </label>
                                <br/>
                                <p>Sua resposta:</p>
                                <textarea class="form-control" rows="6" style="border: 1px solid #999;" disabled><?=$lT['resposta_4']?></textarea>
                            </div><br/>

                            <div class="form-group">
                                <label>
                                    5. Z, ex-empregado da empresa W, há mais de 10 anos foi demitido sem justa causa e mediante o cumprimento de aviso prévio indenizado. Por conta da sua demissão, a empresa quitou as suas verbas rescisórias no prazo de 5 dias após a sua demissão, por meio de depósito na sua conta bancária, embora a homologação sindical da sua rescisão tenha ocorrido apenas após 30 dias da sua demissão. Diante dessa situação, questiona-se: o pagamento da rescisão obreira ocorreu de forma regular e tempestiva? A homologação da rescisão do contrato de trabalho obreiro por parte do sindicato é imprescindível para validar a extinção do contrato de trabalho em questão? Quais as consequências que essa rescisão pode trazer ao empregado?
                                </label>
                                <br/>
                                <p>Sua resposta:</p>
                                <textarea class="form-control" rows="6" style="border: 1px solid #999;" disabled><?=$lT['resposta_5']?></textarea>
                            </div><br/>

							<div class="form-group">
								<label>
									6. A empresa M costuma terceirizar alguns serviços não relacionados à sua atividade fim e contratou empregados da empresa N para prestarem serviços na sede da empresa contratante. Ocorre que a empresa contratada, inesperadamente, encerrou suas atividades, sem qualquer comunicação prévia aos seus empregados ou mesmo às empresas que a contrataram, a exemplo da empresa M. Com intuito de receber as verbas trabalhistas a que faziam jus, os empregados da empresa N ingressaram com ação trabalhista em face de ambas as empresas. <u>Nesse contexto, discorra acerca da possibilidade de terceirização de serviços entre empresas, bem como acerca do tipo de responsabilidade da empresa tomadora de serviços.</u>
								</label>
								<br/>
                                <p>Sua resposta:</p>
								<textarea class="form-control" rows="6" style="border: 1px solid #999;" disabled><?=$lT['resposta_6']?></textarea>
							</div><br/>

							<div class="form-group">
								<label>
									7. Quais os requisitos para que seja deferida a equiparação salarial entre empregados, após a Reforma Trabalhista (Lei número 13.467/2017)?
								</label>
								<br/><br/>
                                <p>Sua resposta:</p>
								<textarea class="form-control" rows="6" style="border: 1px solid #999;" disabled><?=$lT['resposta_7']?></textarea>
							</div><br/>

							<div class="form-group">
								<label>
									8. Você foi contratado pelo dono da empresa F para resolver a seguinte situação: a empregada T foi demitida hoje por abando de emprego, haja vista que esta está há mais de 30 dias faltando ao trabalho e sem dar qualquer justificativa, não obstante as inúmeras e comprovadas tentativas da empresa em contatá-la. Diante dessa situação, o dono da empresa disse que não tinha como pagar as verbas rescisórias da ex-empregada, uma vez que esta não tinha qualquer conta bancária. Ante à tal situação, apresente uma solução para contornar o problema relatado pelo seu cliente.
								</label>
								<br/>
                                <p>Sua resposta:</p>
								<textarea class="form-control" rows="6" style="border: 1px solid #999;" disabled><?=$lT['resposta_8']?></textarea>
							</div><br/>

							<div class="form-group">
								<label>
									9. Sobre a competência da Justiça do Trabalho, a Consolidação das Leis do Trabalho e as Súmulas do Tribunal Superior do Trabalho estabelecem:
								</label>
								<br/>
								A) A competência territorial das Varas do Trabalho é determinada pela localidade onde o empregado prestar serviços ao empregador, ainda que tenha sido contratado noutro local ou no estrangeiro, desde que seja o autor da ação.<br/>

                                B) Quando for parte de dissídio agente ou viajante comercial, a competência será da Vara da localidade em que a empresa tenha agência ou filial e a esta o empregado esteja subordinado e, na falta, será competente a Vara do domicílio do empregado ou a da localidade mais próxima.<br/>

                                C) Se o empregado for brasileiro, a Justiça do Trabalho brasileira tem competência para processar e julgar os dissídios ocorridos em agência ou filial no estrangeiro, ainda que haja convenção internacional dispondo em contrário.<br/>

                                D) A Justiça do Trabalho é competente para determinar o recolhimento das contribuições previdenciárias, em relação às sentenças condenatórias em pecúnia que proferir e aos valores, objeto de acordo homologado, que integram o salário de contribuição, inclusive, no caso de reconhecimento de vínculo empregatício, quanto aos salários pagos durante a contratualidade.<br/>

								E) A Justiça do Trabalho é competente para processar e julgar as ações de indenização por danos morais e materiais decorrentes da relação de trabalho, inclusive as oriundas de acidente de trabalho e doenças a ele equiparados, ainda que propostas pelos dependentes, desde que habilitados no Instituto Nacional do Seguro Social ou sucessores do trabalhador falecido.
                                <br/><br/>
                                Sua resposta: <b class="text-info"><?=$lT['resposta_9']?></b>
							</div><br/>

							<div class="form-group">
								<label>
									10. Sobre a execução na Justiça do Trabalho, é correto afirmar:
								</label>
								<br/>
                                A) O cheque emitido em reconhecimento de saldo de salários, férias e gratificação de natal não pode ser executado diretamente na Justiça do Trabalho.<br/>

                                B) O agravo de petição só será recebido quando o agravante delimitar, justificadamente, as matérias e os valores impugnados, permitida a execução provisória da parte remanescente, nos próprios autos ou por carta de sentença.<br/>

                                C) Elaborada a conta e tornada líquida, o juiz poderá abrir às partes prazo sucessivo de dez dias para impugnação fundamentada com a indicação dos itens e valores objeto da discordância, e procederá à intimação da União para manifestação, no mesmo prazo, sob pena de preclusão.<br/>

                                D) O exequente tem preferência em relação à arrematação para pedir adjudicação, devendo depositar de imediato a diferença, quando o valor do crédito for inferior ao valor dos bens, cujo preço não pode ser inferior ao do melhor lance de arrematação.<br/>

								E) O arrematante deverá garantir o lance com o sinal correspondente a 20% do seu valor, podendo levantá-lo se não complementar o valor remanescente da arrematação, no prazo de vinte e quatro horas, caso em que os bens executados voltarão à praça.
                                <br/><br/>
                                Sua resposta: <b class="text-info"><?=$lT['resposta_10']?></b>
							</div><br/>

							<div class="form-group">
								<label>
									11. A chamada Reforma Trabalhista, estabelecida pela Lei nº 13.467/2017, trouxe algumas mudanças importantes, entre elas, assuntos que já se encontravam na Consolidação das Leis do Trabalho e outros assuntos que foram incorporados na CLT com a reforma. Considerando as novidades que foram incorporadas pela Lei nº 13.467/2017 na CLT quanto aos dissídios individuais, assinale a alternativa correta.
								</label>
								<br/>
                                A) Previu que as nulidades não serão declaradas senão mediante provocação das partes, na primeira oportunidade processual.<br/>

                                B) No processo de execução, são devidas custas, de responsabilidade do executado, e pagas ao final do processo.<br/>

                                C) As testemunhas comparecerão à audiência de instrução independentemente de notificação ou intimação.<br/>

                                D) Nas ações com procedimento sumaríssimo, o pedido deverá ser certo ou determinado, com indicação do valor correspondente.<br/>

								E) Será responsável por perdas e danos aquele que litigar de má-fé, seja reclamante, reclamado ou interveniente.
                                <br/><br/>
                                Sua resposta: <b class="text-info"><?=$lT['resposta_11']?></b>
							</div><br/>

							<div class="form-group">
								<label>
									12. Seu escritório foi contratado pela empresa Alumínio Brilhante Ltda. para assisti-la juridicamente em uma audiência. Você foi designado(a) para a audiência. Forneceram-lhe cópia da defesa e dos documentos, e afirmaram que tudo já havia sido juntado aos autos do processo eletrônico. Na hora da audiência, tendo sido aberta esta, bem como os autos eletrônicos do processo, o juiz constatou que a defesa não estava nos autos, mas apenas os documentos. Diante disso, o juiz facultou-lhe a opção de apresentar defesa.
								</label>
								<br/>
                                A) Nos exatos termos previstos na CLT, você deverá entregar a cópia da defesa escrita que está em sua posse, e requerer juntada dos documentos posteriormente.<br/>

                                B) Aduzir defesa oral em 20 minutos e requerer prazo para juntada posterior dos documentos.<br/>

                                C) Requerer o adiamento da audiência para posterior entrega da defesa e documentos.<br/>

                                D) Requerer a digitalização da sua defesa para juntada imediata aos autos e requerer prazo para a juntada dos documentos no processo.
                                <br/><br/>
                                Sua resposta: <b class="text-info"><?=$lT['resposta_12']?></b>
							</div><br/>

							<div class="form-group">
								<label>
									13. Carla Lopes ajuizou reclamação trabalhista contra sua ex-empregadora, Supermercados Onofre, que, há seis meses, demitiu três de seus dezoito empregados, entre eles, Carla. Em sua petição inicial, ela requereu valores devidos em razão de verbas rescisórias pagas a menor, adicional de insalubridade nunca pago ao longo do contrato de trabalho e danos morais decorrentes de assédio moral. Nessa reclamatória, foi atribuído como valor da causa o importe de cinquenta mil reais. Acerca dessa situação hipotética, julgue o item que segue.
								</label>
                                <br/>
								<p>
									Carla poderá indicar como testemunhas ex-empregados da empresa. No entanto, a testemunha que tiver ajuizado ação contra a mesma reclamada poderá ser contraditada pela parte contrária e seu depoimento poderá ser tomado apenas na condição de informante do juízo
								</p>
                                <p>Sua resposta:</p>
								<textarea class="form-control" rows="6" style="border: 1px solid #999;" disabled><?=$lT['resposta_13']?></textarea>
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
								A) Apenas a assertiva I está correta.<br/>

                                B) Apenas as assertivas II e III estão corretas.<br/>

                                C) Todas as assertivas estão incorretas.
                                <br/><br/>
                                Sua resposta: <b class="text-info"><?=$lT['resposta_14']?></b>
							</div><br/>

							<div class="form-group">
								<label>
									15. Na audiência UNA da reclamação trabalhista movida por Ana Maria em face da empresa de laticínios Via Láctea Ltda., o preposto chegou 20 minutos atrasado, alegando que o pneu de seu carro havia furado a caminho do Fórum. A audiência não tinha se encerrado, sendo que a advogada da Reclamada tinha comparecido no horário, apresentado Defesa com documentos, mas não havia proposta para acordo, sendo que o juiz estava marcando perícia para apuração de insalubridade no ambiente de trabalho. Neste momento, a advogada da Reclamada requereu que não fossem aplicados os efeitos da revelia e confissão, tendo em vista que o preposto esteve presente à audiência antes de seu término. Diante dos fatos narrados e, de acordo com a lei e a orientação jurisprudencial do Tribunal Superior do Trabalho, é correto afirmar que:
								</label>
								<br/>
                                A) não existe previsão legal tolerando atraso no horário de comparecimento da parte na audiência, sendo aplicados os efeitos da revelia e confissão à Reclamada, entretanto, presente a advogada, serão aceitos a contestação e os documentos apresentados.<br/>

								B) assiste razão à Reclamada, tendo em vista que o preposto esteve presente à audiência antes de seu término, razão pela qual não serão aplicados os efeitos da revelia e confissão à empresa.<br/>

								C) apesar de não existir previsão legal tolerando atrasos no horário de comparecimento da parte na audiência, tendo o preposto comparecido e apresentado justificativa para o seu atraso, deverá o juiz afastar os efeitos da revelia e confissão à Reclamada.<br/>

								D) assiste razão à Reclamada, mas não porque o preposto chegou atrasado antes do término da audiência, mas, sim, porque a advogada esteve presente pontualmente.<br/>

								E) não existe previsão legal tolerando atraso no horário de comparecimento da parte na audiência, sendo aplicados os efeitos da revelia e confissão à Reclamada, ainda, que presente a advogada, não serão aceitos a contestação e os documentos apresentados. 61. Átila, Vênus e Tábata foram empregados da empresa de Transportes Rápido & Feliz Ltda. e têm
                                <br/><br/>
								Sua resposta: <b class="text-info"><?=$lT['resposta_15']?></b>
							</div><br/>

							<div class="form-group">
								<label>
									16. O que lhe motivou a querer atuar como advogado(a) associado(a) ao Rocha, Marinho e Sales e no que você pode contribuir para dar um melhor atendimento aos clientes comuns?
								</label>
                                <br/>
                                <p>Sua resposta:</p>
								<textarea class="form-control" rows="6" style="border: 1px solid #999;" disabled><?=$lT['resposta_16']?></textarea>
							</div><br/>

                            <button type="button" class="btn btn-default" onclick="javascript: $('html, body').animate({scrollTop:0}, 'slow');">
                                <i class="fa fa-arrow-circle-up"></i> Topo
                            </button>

						</div>
						<!-- ============================= FIM TRABALHISTA ============================= -->
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
	            <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-file-alt color-blue"></i> Envio de Avalia&ccedil;&atilde;o T&eacute;cnica </h4>
	          </div>
	          <div class="modal-body">
	            <p>
		            As respostas n&atilde;o poder&atilde;o ser editadas ap&oacute;s envio.
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
	            <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-thumbs-down color-red"></i> A&ccedil;&atilde;o n&atilde;o permitida! </h4>
	          </div>
	          <div class="modal-body">
	            <p>
		            Identificamos a&ccedil;&atilde;o n&atilde;o permitida durante sua avalia&ccedil;&atilde;o.
	        	</p>
	            <p align="center" class="text-info" id="carregandoEnvio" style="display: none;"><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i></p>
	          </div>
	          <div class="modal-footer">
		          <button type="button" class="btn btn-danger" onclick="javascript:$('#carregandoEnvio').hide();" data-dismiss="modal">
		          Desculpe-me, n&atilde;o ir&aacute; se repetir</button>
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

</body>
</html>
