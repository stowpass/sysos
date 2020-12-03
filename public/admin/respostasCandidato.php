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

// CANDIDATO
$q = mysqli_query($bd, "SELECT * FROM tb_candidato where id = '". $_GET['id'] ."'");
$l = mysqli_fetch_assoc($q);
mysqli_free_result($q);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Candidato: <?=$l['nome']?></title>
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/styles.css" rel="stylesheet">
	<!--Custom Font-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
	<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<script src="js/respond.min.js"></script>
	<![endif]-->
</head>

<body style="padding: 10px;" onload="self.print();">

	<?php

	// CARGO
	$qCg = mysqli_query($bd, "SELECT * FROM tb_cargo where id = '". $l['cargo_id'] ."' ");
	$lCg = mysqli_fetch_assoc($qCg);
	mysqli_free_result($qCg);

	// AREA
	$qAr = mysqli_query($bd, "SELECT * FROM tb_area where id = '". $lCg['id_area'] ."' ");
	$lAr = mysqli_fetch_assoc($qAr);
	mysqli_free_result($qAr);


	// TEMPO PROVA
	$qTp = mysqli_query($bd, "SELECT * FROM tb_respostas where id_candidato = '" . $_GET['id'] . "' and status = 'A' order by id desc limit 0,1");
	$lTp = mysqli_fetch_assoc($qTp);
	mysqli_free_result($qTp);

	// Para cargo de ADVOGADO
	if ($l['area_atuacao'] == "Civil"){
		$l['area_atuacao'] = "C&iacute;vil";
	}
	?>

	<!-- DADOS CADASTRAIS -->
	<h2><strong>Identifica&ccedil;&atilde;o</strong></h2>
	<hr/>

	<h3><i class="fa fa-user-circle"></i> <?=$l['nome']?></h3>
	<br/>
	<strong>Vaga Pleiteada</strong>: <?=$lCg['cargo']?> <strong>Setor</strong>: <?=$lAr['area']?>
	<br/>
	<?php if ($l['cargo_id'] == 3){ ?>
	<strong>OAB e Data Insc.</strong>: <?=$l['num_oab']?>
	<br/>
	<strong>&Aacute;rea de Atua&ccedil;ao</strong>: <?=$l['area_atuacao']?>
	<br/>
	<?php } ?>
	<?php if ($l['cargo_id'] == 5){ ?>
	<strong>&Aacute;rea de Atua&ccedil;ao</strong>: <?=$l['area_atuacao']?>
	<br/>
	<strong>Semestre Oficial</strong>: <?=$l['estagio_semestre']?>
	<br/>
	<strong>Matr&iacute;cula</strong>: <?=$l['estagio_matricula']?>
	<br/>
	<?php } ?>
	<strong>In&iacute;cio da Avalia&ccedil;&atilde;o</strong>: <?=$lTp['tempo_inicio']?>
	<br/>
	<strong>T&eacute;rmino da Avalia&ccedil;&atilde;o</strong>: <?=$lTp['tempo_envio']?>
	<br/>
	<br/>

	<!-- DADOS PROFISSIONAIS -->

	<?php if ($l["area_atuacao"] == NULL){ // ================== OUTROS CARGOS ?>
		<div class="alert alert-info"><h2><strong>Respostas &agrave;s quest&otilde;es</strong></h2>
			<div id="minhasRespostas">
				<hr/>
				<?php
					$qR = mysqli_query($bd, "SELECT * FROM tb_respostas where id_candidato = '" . $_GET['id'] . "' and status = 'A' order by id desc limit 0,1 ");
					$lR = mysqli_fetch_assoc($qR);
					mysqli_free_result($qR);
				?>
				<strong>1) Em poucas palavras, disserte sobre a linguagem <em>UML</em></strong>
				<br/>
				<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_1']?> "</em>
				<br/><br/>
				<strong>2) Sobre metodologia &aacute;gil, explique o porqu&ecirc; de sua import&acirc;ncia na gest&atilde;o de projetos</strong>
				<br/>
				<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_2']?> "</em>
				<br/><br/>
				<strong>3) As principais atividades de um Analista de Requisitos/Neg&oacute;cios s&atilde;o: <em>Elicitar</em> e <em>Analisar</em> Requisitos. Explique a(s) diferen&ccedil;a(s) entre estas duas atividades</strong>
				<br/>
				<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_3']?> "</em>
				<br/><br/>
				<strong>4) Explique em poucas palavras, o fluxo que voc&ecirc; costuma utilizar para elaborar um Documento de Requisitos</strong>
				<br/>
				<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_4']?> "</em>
				<br/><br/>
				<strong>5) "<em>Programa&ccedil;&atilde;o Orientada a Objetos (POO) &eacute; um conceito que pode ser aplicado &agrave; linguagem JAVA, por&eacute;m não &agrave; linguagem PHP</em>". Explique o porquê desta afirma&ccedil;&atilde;o ser verdadeira ou falsa, deixando claro o significado de POO</strong>
				<br/>
				<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_5']?> "</em>
				<br/><br/>
				<small>*As respostas do candidato n&atilde;o podem ser editadas.</small>
			</div>
		</div>
	<?php } ?>

	<?php if ($l["area_atuacao"] == "C&iacute;vil" && $l['cargo_id'] == 3){ // ================== Adv Civil ?>
		<div class="alert alert-info"><h2><strong>Respostas &agrave;s quest&otilde;es - C&iacute;vil</strong></h2>
			<div id="minhasRespostas">
				<hr/>
				<?php
					$qR = mysqli_query($bd, "SELECT * FROM tb_respostas where id_candidato = '" . $_GET['id'] . "' and status = 'A' and area_atuacao = 'Civil' order by id desc limit 0,1 ");
					$lR = mysqli_fetch_assoc($qR);
					mysqli_free_result($qR);
				?>

				<label>1. Acerca da revelia, &eacute; correto afirmar que:<br/></label>
						<br/>
						A) A revelia se dá com a não apresentação de exceção ou de reconvenção no prazo da resposta.<br/>
						B) Ainda que o litígio verse sobre direitos indisponíveis, a revelia produz seus efeitos normalmente.<br/>
						C) O revel pode intervir no processo em qualquer fase, recebendo-o no estado em que se encontrar.<br/>
						D) Contra o revel, ainda que tenha patrono constituído nos autos, correrão os prazos independentemente de intimação.<br/>
						<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_1']?> "</em>
				<br/><br/>

				<label>2. Sobre a suspens&atilde;o e interrup&ccedil;&atilde;o de prazo pode se dizer que:<br/></label>
						<br/>
						A) S&atilde;o institutos de direito processual com a mesma consequ&ecirc;ncia.<br/>
						B) Na suspens&atilde;o o prazo &eacute; integralmente devolvido &agrave; parte.<br/>
						C) Na interrup&ccedil;&atilde;o o prazo retoma a contagem a partir do dia que foi interrompido.<br/>
						D) Na interrup&ccedil;&atilde;o o prazo &eacute; integralmente devolvido &agrave; parte.<br/>
						<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_2']?> "</em>
				<br/><br/>

				<label>3. Sobre prescri&ccedil;&atilde;o e decad&ecirc;ncia, &eacute; correto afirmar que:<br/></label>
						<br/>
						A) O juiz n&atilde;o pode reconhecer de of&iacute;cio a prescri&ccedil;&atilde;o.<br/>
						B) O C&oacute;digo Civil permite a alega&ccedil;&atilde;o de prescri&ccedil;&atilde;o em qualquer grau de Jurisdi&ccedil;&atilde;o.<br/>
						C) A prescri&ccedil;&atilde;o da pretens&atilde;o do autor n&atilde;o pode ser alegada somente em apela&ccedil;&atilde;o.<br/>
						D) Prescri&ccedil;&atilde;o e decad&ecirc;ncia s&atilde;o institutos sem qualquer distin&ccedil;&atilde;o.<br/>
						<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_3']?> "</em>
				<br/><br/>

				<label>4. Quanto ao cumprimento da senten&ccedil;a, &eacute; correto dizer que:<br/></label>
						<br/>
						A) A impugna&ccedil;&atilde;o ao cumprimento da senten&ccedil;a ser&aacute; recebida como regra geral nos efeitos devolutivo e suspensivo, podendo o juiz atribuir somente efeito devolutivo se do duplo efeito advier preju&iacute;zo irrepar&aacute;vel ou de dif&iacute;cil repara&ccedil;&atilde;o ao credor.<br/>
						B) &Eacute; definitiva a execu&ccedil;&atilde;o da senten&ccedil;a transitada em julgada e provis&oacute;ria quando se tratar de senten&ccedil;a impugnada mediante recurso recebido nos efeitos devolutivo e suspensivo.<br/>
						C) Na impugna&ccedil;&atilde;o ao cumprimento da senten&ccedil;a, se o executado alegar que o exequente, em excesso de execu&ccedil;&atilde;o, pleiteia  quantia superior &agrave; condena&ccedil;&atilde;o, dever&aacute; declarar de imediato o valor que entende correto, sob pena de rejei&ccedil;&atilde;o liminar dessa impugna&ccedil;&atilde;o.<br/>
						D) A decis&atilde;o que resolver a impugna&ccedil;&atilde;o ao cumprimento de senten&ccedil;a &eacute; recorr&acute;vel sempre por meio de agravo de  instrumento.<br/>
						E) A execu&ccedil;&atilde;o provis&oacute;ria ocorre por conta e risco do credor, devendo os atos que importem levantamento de dep&oacute;sito em dinheiro ou aliena&ccedil;&atilde;o de propriedade serem precedidos necessariamente de cau&ccedil;&atilde;o id&ocirc;nea, sem exce&ccedil;&atilde;o.<br/>
						<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_4']?> "</em>
				<br/><br/>

				<label>5. Sobre pagamento em consigna&ccedil;&atilde;o, assinale a alternativa incorreta:<br/></label>
						<br/>
						A) Considera-se pagamento, e extingue a obriga&ccedil;&atilde;o, o dep&oacute;sito judicial ou em estabelecimento banc&aacute;rio da coisa devida, nos casos e forma legais.<br/>
						B) Julgado procedente o dep&oacute;sito, o devedor j&aacute; n&atilde;o poder&aacute; levant&aacute;-lo, embora o credor consinta, sen&atilde;o de acordo com os outros devedores e fiadores.<br/>
						C) Se a coisa devida for im&oacute;vel, n&atilde;o poder&aacute; ser objeto de pagamento em consigna&ccedil;&atilde;o, pois n&atilde;o poder&aacute; ser depositada em ju&iacute;zo.<br/>
						D) Para que a consigna&ccedil;&atilde;o tenha for&ccedil;a de pagamento, ser&aacute; mister concorram, em rela&ccedil;&atilde;o &agrave;s pessoas, ao objeto, modo e tempo, todos os requisitos sem os quais n&aacute;o &eacute; v&aacute;lido o pagamento.<br/>
						<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_5']?> "</em>
				<br/><br/>

				<label>6. Estipulando o doador que os bens doados reverterão ao seu patrimônio se o donat&aacute;rio vier a falecer antes dele, ter-se-&aacute; doa&ccedil;&atilde;o:<br/></label>
						<br/>
						A) Com cl&aacute;usula de revers&atilde;o.<br/>
						B) Conjuntiva inoficiosa.<br/>
						C) Sob condi&ccedil;&atilde;o suspensiva expressa.<br/>
						D) Nenhuma das alternativas.<br/>
						<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_6']?> "</em>
				<br/><br/>

				<label>7. Discorra sobre os Princ&iacute;pios da Impugna&ccedil;&atilde;o Espec&iacute;fica e da Eventualidade, mencionando: Quando se aplicam; A quem se destina e Quais os efeitos decorrentes da sua inobserv&acirc;ncia<br/>
				</label><u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_7']?> "</em>
				<br/><br/>

				<label>8. Hipoteticamente, voc&ecirc; &eacute; o(a) &uacute;nico(a) advogado(a) associado(a) do escrit&oacute;rio presente no momento e todos os demais est&atilde;o temporariamente impossibilitados de serem contratados. Eis que um dos advogados contratados como correspondente de uma comarca interiorana, atuando em favor de um dos clientes do escrit&oacute;rio, em um processo f&iacute;sico tramitando sob o rito da Lei n&uacute;mero 9.099/95, entra em contato informando que a contesta&ccedil;&atilde;o e os documentos para sua habilita&ccedil;&atilde;o n&atilde;o est&atilde;o nos autos. Ocorre que a Audi&ecirc;ncia de Instru&ccedil;&atilde;o ocorrer&aacute; em 20 minutos. Quais s&atilde;o suas provid&ecirc;ncias?<br/>
				</label><u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_8']?> "</em>
				<br/><br/>


				<small>*As respostas do candidato n&atilde;o podem ser editadas.</small>
			</div>
		</div>
	<?php } ?>

	<?php if ($l["area_atuacao"] == "Trabalhista" && $l['cargo_id'] == 3){ // ================== Adv Trabalhista ?>
		<div class="alert alert-info"><h2><strong>Respostas &agrave;s quest&otilde;es - Trabalhista</strong></h2>
			<div id="minhasRespostas">
				<hr/>
				<?php
					$qR = mysqli_query($bd, "SELECT * FROM tb_respostas where id_candidato = '" . $_GET['id'] . "' and status = 'A' and area_atuacao = 'Trabalhista' order by id desc limit 0,1 ");
					$lR = mysqli_fetch_assoc($qR);
					mysqli_free_result($qR);
				?>

				<label>
                    1. Em uma audiência trabalhista, cada parte conduz suas testemunhas, que, inicialmente, são ouvidas pelo juiz, começando pelas do autor. Após o magistrado fazer as perguntas que deseja, abre oportunidade para que os advogados façam suas indagações, começando pelo patrono do autor, que faz suas perguntas diretamente à testemunha, contra o que se opõe o juiz, afirmando que as perguntas deveriam ser feitas a ele, que, em seguida, perguntaria à testemunha. Diante do incidente instalado e de acordo com o regramento da CLT, assinale a afirmativa correta:
                </label>
                <br/>
                <p>
                    A) Correto o advogado, pois, de acordo com o CPC, o advogado fará perguntas diretamente à testemunha.<br />

                    B) A CLT não tem dispositivo próprio, daí porque poderia ser admitido tanto o sistema direto quanto o indireto.<br />

                    <b class="text-success">
                        C) Correto o magistrado, pois a CLT determina que o sistema seja indireto ou presidencial.<br />
                    </b>

                    D) A CLT determina que o sistema seja híbrido, intercalando perguntas feitas diretamente pelo advogado, com indagações realizadas pelo juiz.
                </p>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_1']?> "</em>
				<br/><br/>

				<label>
                    2. É <u>correto</u> afirmar que:
                </label>
                <br/>
                <p>
                    A) O juiz não pode reconhecer de ofício a prescrição.<br />

                    <b class="text-success">
                        B) O não comparecimento do reclamante à audiência importa no arquivamento da reclamação, e o não comparecimento do reclamado importa revelia, além de confissão quanto à matéria de fato.<br />
                    </b>

                    C) O aviso prévio sempre prorroga o contrato de trabalho por mais 30 dias.<br />

                    D) É obrigatório que o preposto da empresa reclamada seja seu empregado.
                </p>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_2']?> "</em>
                <br/><br/>

				<label>
                    3. Assinale as assertivas como <u><span class="text-success">VERDADEIRAS</span> ou <span class="text-danger">FALSAS</span></u>, corrigindo estas últimas:
                </label>
                <br/>
                <p>
                    <span class="text-danger">A) Todos os prazos recursais trabalhistas são de 8 dias.</span>&nbsp;<b>F</b><br />

                    <span class="text-success">B) A oposição de Embargos Declaratórios suspende o prazo recursal.</span>&nbsp;<b>V</b><br />

                    <span class="text-success">C) A sociedade de advogados pode associar-se com advogados, sem vínculo de emprego, para participação nos resultados.</span>&nbsp;<b>V</b><br />

                    <span class="text-danger">D) O marco inicial para contagem do prazo de embargos à execução é o recebimento da notificação para pagar ou para garantir a execução.</span>&nbsp;<b>F</b>
                </p>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_3']?> "</em>
				<br/><br/>

				<label>
                    4. A empregada X ingressou com reclamação trabalhista, requerendo indenização em danos morais, pois alega que adquiriu doença acidentária na empresa Y, dentre outros pedidos. No momento da audiência, o Juiz, ao deferir a realização de perícia, arbitrou valor à título de honorários periciais em quantia flagrantemente excessiva, bem como condicionou a realização de citada perícia à antecipação integral do referido valor por parte da reclamada. Ciente dos ditames que versam sobre a sucumbência do objeto da perícia, comente a atitude do Juiz, bem como indique se há alguma manifestação a ser suscitada pelo patrono da parte reclamada.
                </label>
                <br /><br />
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_4']?> "</em>
				<br/><br/>

				<label>
                    5. Z, ex-empregado da empresa W, há mais de 10 anos foi demitido sem justa causa e mediante o cumprimento de aviso prévio indenizado. Por conta da sua demissão, a empresa quitou as suas verbas rescisórias no prazo de 5 dias após a sua demissão, por meio de depósito na sua conta bancária, embora a homologação sindical da sua rescisão tenha ocorrido apenas após 30 dias da sua demissão. Diante dessa situação, questiona-se: o pagamento da rescisão obreira ocorreu de forma regular e tempestiva? A homologação da rescisão do contrato de trabalho obreiro por parte do sindicato é imprescindível para validar a extinção do contrato de trabalho em questão? Quais as consequências que essa rescisão pode trazer ao empregado?
				</label>
                <br/>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_5']?> "</em>
				<br/><br/>

				<label>
                    6. A empresa M costuma terceirizar alguns serviços não relacionados à sua atividade fim e contratou empregados da empresa N para prestarem serviços na sede da empresa contratante. Ocorre que a empresa contratada, inesperadamente, encerrou suas atividades, sem qualquer comunicação prévia aos seus empregados ou mesmo às empresas que a contrataram, a exemplo da empresa M. Com intuito de receber as verbas trabalhistas a que faziam jus, os empregados da empresa N ingressaram com ação trabalhista em face de ambas as empresas. <u>Nesse contexto, discorra acerca da possibilidade de terceirização de serviços entre empresas, bem como acerca do tipo de responsabilidade da empresa tomadora de serviços</u>.
                </label>
                <br/>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_6']?> "</em>
				<br/><br/>

				<label>
                    7. Quais os requisitos para que seja deferida a equiparação salarial entre empregados, após a Reforma Trabalhista (Lei número 13.467/2017)?
                </label>
                <br/>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_7']?> "</em>
				<br/><br/>

				<label>
                    8. Você foi contratado pelo dono da empresa F para resolver a seguinte situação: a empregada T foi demitida hoje por abando de emprego, haja vista que esta está há mais de 30 dias faltando ao trabalho e sem dar qualquer justificativa, não obstante as inúmeras e comprovadas tentativas da empresa em contatá-la. Diante dessa situação, o dono da empresa disse que não tinha como pagar as verbas rescisórias da ex-empregada, uma vez que esta não tinha qualquer conta bancária. Ante à tal situação, apresente uma solução para contornar o problema relatado pelo seu cliente.
				</label>
                <br/>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_8']?> "</em>
				<br/><br/>

				<label>
                    9. Sobre a competência da Justiça do Trabalho, a Consolidação das Leis do Trabalho e as Súmulas do Tribunal Superior do Trabalho estabelecem:
                </label>
                <br/>
                <p>
                    A) A competência territorial das Varas do Trabalho é determinada pela localidade onde o empregado prestar serviços ao empregador, ainda que tenha sido contratado noutro local ou no estrangeiro, desde que seja o autor da ação.<br />

                    <b class="text-success">
                        B) Quando for parte de dissídio agente ou viajante comercial, a competência será da Vara da localidade em que a empresa tenha agência ou filial e a esta o empregado esteja subordinado e, na falta, será competente a Vara do domicílio do empregado ou a da localidade mais próxima.<br />
                    </b>

                    C) Se o empregado for brasileiro, a Justiça do Trabalho brasileira tem competência para processar e julgar os dissídios ocorridos em agência ou filial no estrangeiro, ainda que haja convenção internacional dispondo em contrário.<br />

                    D) A Justiça do Trabalho é competente para determinar o recolhimento das contribuições previdenciárias, em relação às sentenças condenatórias em pecúnia que proferir e aos valores, objeto de acordo homologado, que integram o salário de contribuição, inclusive, no caso de reconhecimento de vínculo empregatício, quanto aos salários pagos durante a contratualidade.<br />

                    E) A Justiça do Trabalho é competente para processar e julgar as ações de indenização por danos morais e materiais decorrentes da relação de trabalho, inclusive as oriundas de acidente de trabalho e doenças a ele equiparados, ainda que propostas pelos dependentes, desde que habilitados no Instituto Nacional do Seguro Social ou sucessores do trabalhador falecido.
                </p>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_9']?> "</em>
				<br/><br/>

				<label>
                    10. Sobre a execução na Justiça do Trabalho, é correto afirmar:
                </label>
                <br/>
                <p>
                    A) O cheque emitido em reconhecimento de saldo de salários, férias e gratificação de natal não pode ser executado diretamente na Justiça do Trabalho.<br />

                    B) O agravo de petição só será recebido quando o agravante delimitar, justificadamente, as matérias e os valores impugnados, permitida a execução provisória da parte remanescente, nos próprios autos ou por carta de sentença.<br />

                    <b class="text-success">
                        C) Elaborada a conta e tornada líquida, o juiz poderá abrir às partes prazo sucessivo de dez dias para impugnação fundamentada com a indicação dos itens e valores objeto da discordância, e procederá à intimação da União para manifestação, no mesmo prazo, sob pena de preclusão.<br />
                    </b>

                    D) O exequente tem preferência em relação à arrematação para pedir adjudicação, devendo depositar de imediato a diferença, quando o valor do crédito for inferior ao valor dos bens, cujo preço não pode ser inferior ao do melhor lance de arrematação.<br />

                    E) O arrematante deverá garantir o lance com o sinal correspondente a 20% do seu valor, podendo levantá-lo se não complementar o valor remanescente da arrematação, no prazo de vinte e quatro horas, caso em que os bens executados voltarão à praça.
                </p>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_10']?> "</em>
				<br/><br/>

                <label>
                    11. A chamada Reforma Trabalhista, estabelecida pela Lei nº 13.467/2017, trouxe algumas mudanças importantes, entre elas, assuntos que já se encontravam na Consolidação das Leis do Trabalho e outros assuntos que foram incorporados na CLT com a reforma. Considerando as novidades que foram incorporadas pela Lei nº 13.467/2017 na CLT quanto aos dissídios individuais, assinale a alternativa correta.
                </label>
                <br/>
                <p>
                    A) Previu que as nulidades não serão declaradas senão mediante provocação das partes, na primeira oportunidade processual.<br />

                    B) No processo de execução, são devidas custas, de responsabilidade do executado, e pagas ao final do processo.<br />

                    <b class="text-success">
                        C) As testemunhas comparecerão à audiência de instrução independentemente de notificação ou intimação.<br />
                    </b>

                    D) Nas ações com procedimento sumaríssimo, o pedido deverá ser certo ou determinado, com indicação do valor correspondente.<br />

                    E) Será responsável por perdas e danos aquele que litigar de má-fé, seja reclamante, reclamado ou interveniente.
                </p>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_11']?> "</em>
				<br/><br/>

                <label>
                    12. Seu escritório foi contratado pela empresa Alumínio Brilhante Ltda. para assisti-la juridicamente em uma audiência. Você foi designado(a) para a audiência. Forneceram-lhe cópia da defesa e dos documentos, e afirmaram que tudo já havia sido juntado aos autos do processo eletrônico. Na hora da audiência, tendo sido aberta esta, bem como os autos eletrônicos do processo, o juiz constatou que a defesa não estava nos autos, mas apenas os documentos. Diante disso, o juiz facultou-lhe a opção de apresentar defesa.
                </label>
                <br/>
                <p>
                    A) Nos exatos termos previstos na CLT, você deverá entregar a cópia da defesa escrita que está em sua posse, e requerer juntada dos documentos posteriormente.<br />

                    <b class="text-success">
                        B) Aduzir defesa oral em 20 minutos e requerer prazo para juntada posterior dos documentos.<br />
                    </b>

                    C) Requerer o adiamento da audiência para posterior entrega da defesa e documentos.<br />

                    D) Requerer a digitalização da sua defesa para juntada imediata aos autos e requerer prazo para a juntada dos documentos no processo.
                </p>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_12']?> "</em>
				<br/><br/>

                <label>
                    13. Carla Lopes ajuizou reclamação trabalhista contra sua ex-empregadora, Supermercados Onofre, que, há seis meses, demitiu três de seus dezoito empregados, entre eles, Carla. Em sua petição inicial, ela requereu valores devidos em razão de verbas rescisórias pagas a menor, adicional de insalubridade nunca pago ao longo do contrato de trabalho e danos morais decorrentes de assédio moral. Nessa reclamatória, foi atribuído como valor da causa o importe de cinquenta mil reais. Acerca dessa situação hipotética, julgue o item que segue.
                </label>
                <br/>
                <p>
                    Carla poderá indicar como testemunhas ex-empregados da empresa. No entanto, a testemunha que tiver ajuizado ação contra a mesma reclamada poderá ser contraditada pela parte contrária e seu depoimento poderá ser tomado apenas na condição de informante do juízo.
                </p>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_13']?> "</em>
				<br/><br/>

                <label>
                    14. Analise as assertivas abaixo:
                </label>
                <br/>
                <p>
                    I - O depoimento da parte é uma das provas mais importantes, razão pela qual a parte jamais deverá renunciar à oportunidade de expor oralmente em audiência a sua versão para o juiz.<br />

                    II - Aduzir defesa oral em 20 minutos e requerer prazo para juntada posterior dos documentos.<br />

                    III - Requerer o adiamento da audiência para posterior entrega da defesa e documentos.
                </p>
                <p>
                    Assinale a alternativa CORRETA:
                </p>
                <p>
                    A) Apenas a assertiva I está correta.<br />

                    B) Apenas as assertivas II e III estão corretas.<br />

                    <b class="text-success">
                        C) Todas as assertivas estão incorretas.
                    </b>
                </p>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_14']?> "</em>
				<br/><br/>

                <label>
                    15. Seu escritório foi contratado pela empresa Alumínio Brilhante Ltda. para assisti-la juridicamente em uma audiência. Você foi designado(a) para a audiência. Forneceram-lhe cópia da defesa e dos documentos, e afirmaram que tudo já havia sido juntado aos autos do processo eletrônico. Na hora da audiência, tendo sido aberta esta, bem como os autos eletrônicos do processo, o juiz constatou que a defesa não estava nos autos, mas apenas os documentos. Diante disso, o juiz facultou-lhe a opção de apresentar defesa.
                </label>
                <br/>
                <p>
                    <b class="text-success">
                        A) não existe previsão legal tolerando atraso no horário de comparecimento da parte na audiência, sendo aplicados os efeitos da revelia e confissão à Reclamada, entretanto, presente a advogada, serão aceitos a contestação e os documentos apresentados.<br />
                    </b>

                    B) assiste razão à Reclamada, tendo em vista que o preposto esteve presente à audiência antes de seu término, razão pela qual não serão aplicados os efeitos da revelia e confissão à empresa.<br />

                    C) apesar de não existir previsão legal tolerando atrasos no horário de comparecimento da parte na audiência, tendo o preposto comparecido e apresentado justificativa para o seu atraso, deverá o juiz afastar os efeitos da revelia e confissão à Reclamada.<br />

                    D) assiste razão à Reclamada, mas não porque o preposto chegou atrasado antes do término da audiência, mas, sim, porque a advogada esteve presente pontualmente.<br />

                    E) não existe previsão legal tolerando atraso no horário de comparecimento da parte na audiência, sendo aplicados os efeitos da revelia e confissão à Reclamada, ainda, que presente a advogada, não serão aceitos a contestação e os documentos apresentados. 61. Átila, Vênus e Tábata foram empregados da empresa de Transportes Rápido & Feliz Ltda. e têm
                </p>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_15']?> "</em>
				<br/><br/>

                <label>
                    16. O que lhe motivou a querer atuar como advogado(a) associado(a) ao Rocha, Marinho e Sales e no que você pode contribuir para dar um melhor atendimento aos clientes comuns?
                </label>
                <br/>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_16']?> "</em>
				<br/><br/>

				<small>*As respostas do candidato n&atilde;o podem ser editadas.</small>
			</div>
		</div>
	<?php } ?>

	<?php if ($l["area_atuacao"] == "Ambas" && $l['cargo_id'] == 3){ // ================== Adv Ambos ?>

		<div class="alert alert-info"><h2><strong>Respostas &agrave;s quest&otilde;es - C&iacute;vil</strong></h2>
			<div id="minhasRespostas">
				<hr/>
				<?php
					$qR = mysqli_query($bd, "SELECT * FROM tb_respostas where id_candidato = '" . $_GET['id'] . "' and status = 'A' and area_atuacao = 'Civil' order by id desc limit 0,1 ");
					$lR = mysqli_fetch_assoc($qR);
					mysqli_free_result($qR);
				?>

				<label>1. Acerca da revelia, &eacute; correto afirmar que:<br/></label>
						<br/>
						A) A revelia se dá com a não apresentação de exceção ou de reconvenção no prazo da resposta.<br/>
						B) Ainda que o litígio verse sobre direitos indisponíveis, a revelia produz seus efeitos normalmente.<br/>
						C) O revel pode intervir no processo em qualquer fase, recebendo-o no estado em que se encontrar.<br/>
						D) Contra o revel, ainda que tenha patrono constituído nos autos, correrão os prazos independentemente de intimação.<br/>
						<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_1']?> "</em>
				<br/><br/>

				<label>2. Sobre a suspens&atilde;o e interrup&ccedil;&atilde;o de prazo pode se dizer que:<br/></label>
						<br/>
						A) S&atilde;o institutos de direito processual com a mesma consequ&ecirc;ncia.<br/>
						B) Na suspens&atilde;o o prazo &eacute; integralmente devolvido &agrave; parte.<br/>
						C) Na interrup&ccedil;&atilde;o o prazo retoma a contagem a partir do dia que foi interrompido.<br/>
						D) Na interrup&ccedil;&atilde;o o prazo &eacute; integralmente devolvido &agrave; parte.<br/>
						<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_2']?> "</em>
				<br/><br/>

				<label>3. Sobre prescri&ccedil;&atilde;o e decad&ecirc;ncia, &eacute; correto afirmar que:<br/></label>
						<br/>
						A) O juiz n&atilde;o pode reconhecer de of&iacute;cio a prescri&ccedil;&atilde;o.<br/>
						B) O C&oacute;digo Civil permite a alega&ccedil;&atilde;o de prescri&ccedil;&atilde;o em qualquer grau de Jurisdi&ccedil;&atilde;o.<br/>
						C) A prescri&ccedil;&atilde;o da pretens&atilde;o do autor n&atilde;o pode ser alegada somente em apela&ccedil;&atilde;o.<br/>
						D) Prescri&ccedil;&atilde;o e decad&ecirc;ncia s&atilde;o institutos sem qualquer distin&ccedil;&atilde;o.<br/>
						<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_3']?> "</em>
				<br/><br/>

				<label>4. Quanto ao cumprimento da senten&ccedil;a, &eacute; correto dizer que:<br/></label>
						<br/>
						A) A impugna&ccedil;&atilde;o ao cumprimento da senten&ccedil;a ser&aacute; recebida como regra geral nos efeitos devolutivo e suspensivo, podendo o juiz atribuir somente efeito devolutivo se do duplo efeito advier preju&iacute;zo irrepar&aacute;vel ou de dif&iacute;cil repara&ccedil;&atilde;o ao credor.<br/>
						B) &Eacute; definitiva a execu&ccedil;&atilde;o da senten&ccedil;a transitada em julgada e provis&oacute;ria quando se tratar de senten&ccedil;a impugnada mediante recurso recebido nos efeitos devolutivo e suspensivo.<br/>
						C) Na impugna&ccedil;&atilde;o ao cumprimento da senten&ccedil;a, se o executado alegar que o exequente, em excesso de execu&ccedil;&atilde;o, pleiteia  quantia superior &agrave; condena&ccedil;&atilde;o, dever&aacute; declarar de imediato o valor que entende correto, sob pena de rejei&ccedil;&atilde;o liminar dessa impugna&ccedil;&atilde;o.<br/>
						D) A decis&atilde;o que resolver a impugna&ccedil;&atilde;o ao cumprimento de senten&ccedil;a &eacute; recorr&acute;vel sempre por meio de agravo de  instrumento.<br/>
						E) A execu&ccedil;&atilde;o provis&oacute;ria ocorre por conta e risco do credor, devendo os atos que importem levantamento de dep&oacute;sito em dinheiro ou aliena&ccedil;&atilde;o de propriedade serem precedidos necessariamente de cau&ccedil;&atilde;o id&ocirc;nea, sem exce&ccedil;&atilde;o.<br/>
						<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_4']?> "</em>
				<br/><br/>

				<label>5. Sobre pagamento em consigna&ccedil;&atilde;o, assinale a alternativa incorreta:<br/></label>
						<br/>
						A) Considera-se pagamento, e extingue a obriga&ccedil;&atilde;o, o dep&oacute;sito judicial ou em estabelecimento banc&aacute;rio da coisa devida, nos casos e forma legais.<br/>
						B) Julgado procedente o dep&oacute;sito, o devedor j&aacute; n&atilde;o poder&aacute; levant&aacute;-lo, embora o credor consinta, sen&atilde;o de acordo com os outros devedores e fiadores.<br/>
						C) Se a coisa devida for im&oacute;vel, n&atilde;o poder&aacute; ser objeto de pagamento em consigna&ccedil;&atilde;o, pois n&atilde;o poder&aacute; ser depositada em ju&iacute;zo.<br/>
						D) Para que a consigna&ccedil;&atilde;o tenha for&ccedil;a de pagamento, ser&aacute; mister concorram, em rela&ccedil;&atilde;o &agrave;s pessoas, ao objeto, modo e tempo, todos os requisitos sem os quais n&aacute;o &eacute; v&aacute;lido o pagamento.<br/>
						<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_5']?> "</em>
				<br/><br/>

				<label>6. Estipulando o doador que os bens doados reverterão ao seu patrimônio se o donat&aacute;rio vier a falecer antes dele, ter-se-&aacute; doa&ccedil;&atilde;o:<br/></label>
						<br/>
						A) Com cl&aacute;usula de revers&atilde;o.<br/>
						B) Conjuntiva inoficiosa.<br/>
						C) Sob condi&ccedil;&atilde;o suspensiva expressa.<br/>
						D) Nenhuma das alternativas.<br/>
						<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_6']?> "</em>
				<br/><br/>

				<label>7. Discorra sobre os Princ&iacute;pios da Impugna&ccedil;&atilde;o Espec&iacute;fica e da Eventualidade, mencionando: Quando se aplicam; A quem se destina e Quais os efeitos decorrentes da sua inobserv&acirc;ncia<br/>
				</label>Resposta do Candidato: <em>" <?=$lR['resposta_7']?> "</em>
				<br/><br/>

				<label>8. Hipoteticamente, voc&ecirc; &eacute; o(a) &uacute;nico(a) advogado(a) associado(a) do escrit&oacute;rio presente no momento e todos os demais est&atilde;o temporariamente impossibilitados de serem contratados. Eis que um dos advogados contratados como correspondente de uma comarca interiorana, atuando em favor de um dos clientes do escrit&oacute;rio, em um processo f&iacute;sico tramitando sob o rito da Lei n&uacute;mero 9.099/95, entra em contato informando que a contesta&ccedil;&atilde;o e os documentos para sua habilita&ccedil;&atilde;o n&atilde;o est&atilde;o nos autos. Ocorre que a Audi&ecirc;ncia de Instru&ccedil;&atilde;o ocorrer&aacute; em 20 minutos. Quais s&atilde;o suas provid&ecirc;ncias?<br/>
				</label><u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_8']?> "</em>
				<br/><br/>


				<small>*As respostas do candidato n&atilde;o podem ser editadas.</small>
			</div>
		</div>

		<hr/>

		<div class="alert alert-info"><h2><strong>Respostas &agrave;s quest&otilde;es - Trabalhista</strong></h2>
			<div id="minhasRespostas">
				<hr/>
				<?php
					$qR = mysqli_query($bd, "SELECT * FROM tb_respostas where id_candidato = '" . $_GET['id'] . "' and status = 'A' and area_atuacao = 'Trabalhista' order by id desc limit 0,1 ");
					$lR = mysqli_fetch_assoc($qR);
					mysqli_free_result($qR);
				?>

                <label>
                    1. Em uma audiência trabalhista, cada parte conduz suas testemunhas, que, inicialmente, são ouvidas pelo juiz, começando pelas do autor. Após o magistrado fazer as perguntas que deseja, abre oportunidade para que os advogados façam suas indagações, começando pelo patrono do autor, que faz suas perguntas diretamente à testemunha, contra o que se opõe o juiz, afirmando que as perguntas deveriam ser feitas a ele, que, em seguida, perguntaria à testemunha. Diante do incidente instalado e de acordo com o regramento da CLT, assinale a afirmativa correta:
                </label>
                <br/>
                <p>
                    A) Correto o advogado, pois, de acordo com o CPC, o advogado fará perguntas diretamente à testemunha.<br />

                    B) A CLT não tem dispositivo próprio, daí porque poderia ser admitido tanto o sistema direto quanto o indireto.<br />

                    <b class="text-success">
                        C) Correto o magistrado, pois a CLT determina que o sistema seja indireto ou presidencial.<br />
                    </b>

                    D) A CLT determina que o sistema seja híbrido, intercalando perguntas feitas diretamente pelo advogado, com indagações realizadas pelo juiz.
                </p>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_1']?> "</em>
				<br/><br/>

				<label>
                    2. É <u>correto</u> afirmar que:
                </label>
                <br/>
                <p>
                    A) O juiz não pode reconhecer de ofício a prescrição.<br />

                    <b class="text-success">
                        B) O não comparecimento do reclamante à audiência importa no arquivamento da reclamação, e o não comparecimento do reclamado importa revelia, além de confissão quanto à matéria de fato.<br />
                    </b>

                    C) O aviso prévio sempre prorroga o contrato de trabalho por mais 30 dias.<br />

                    D) É obrigatório que o preposto da empresa reclamada seja seu empregado.
                </p>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_2']?> "</em>
                <br/><br/>

				<label>
                    3. Assinale as assertivas como <u><span class="text-success">VERDADEIRAS</span> ou <span class="text-danger">FALSAS</span></u>, corrigindo estas últimas:
                </label>
                <br/>
                <p>
                    <span class="text-danger">A) Todos os prazos recursais trabalhistas são de 8 dias.</span>&nbsp;<b>F</b><br />

                    <span class="text-success">B) A oposição de Embargos Declaratórios suspende o prazo recursal.</span>&nbsp;<b>V</b><br />

                    <span class="text-success">C) A sociedade de advogados pode associar-se com advogados, sem vínculo de emprego, para participação nos resultados.</span>&nbsp;<b>V</b><br />

                    <span class="text-danger">D) O marco inicial para contagem do prazo de embargos à execução é o recebimento da notificação para pagar ou para garantir a execução.</span>&nbsp;<b>F</b>
                </p>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_3']?> "</em>
				<br/><br/>

				<label>
                    4. A empregada X ingressou com reclamação trabalhista, requerendo indenização em danos morais, pois alega que adquiriu doença acidentária na empresa Y, dentre outros pedidos. No momento da audiência, o Juiz, ao deferir a realização de perícia, arbitrou valor à título de honorários periciais em quantia flagrantemente excessiva, bem como condicionou a realização de citada perícia à antecipação integral do referido valor por parte da reclamada. Ciente dos ditames que versam sobre a sucumbência do objeto da perícia, comente a atitude do Juiz, bem como indique se há alguma manifestação a ser suscitada pelo patrono da parte reclamada.
                </label>
                <br /><br />
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_4']?> "</em>
				<br/><br/>

				<label>
                    5. Z, ex-empregado da empresa W, há mais de 10 anos foi demitido sem justa causa e mediante o cumprimento de aviso prévio indenizado. Por conta da sua demissão, a empresa quitou as suas verbas rescisórias no prazo de 5 dias após a sua demissão, por meio de depósito na sua conta bancária, embora a homologação sindical da sua rescisão tenha ocorrido apenas após 30 dias da sua demissão. Diante dessa situação, questiona-se: o pagamento da rescisão obreira ocorreu de forma regular e tempestiva? A homologação da rescisão do contrato de trabalho obreiro por parte do sindicato é imprescindível para validar a extinção do contrato de trabalho em questão? Quais as consequências que essa rescisão pode trazer ao empregado?
				</label>
                <br/>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_5']?> "</em>
				<br/><br/>

				<label>
                    6. A empresa M costuma terceirizar alguns serviços não relacionados à sua atividade fim e contratou empregados da empresa N para prestarem serviços na sede da empresa contratante. Ocorre que a empresa contratada, inesperadamente, encerrou suas atividades, sem qualquer comunicação prévia aos seus empregados ou mesmo às empresas que a contrataram, a exemplo da empresa M. Com intuito de receber as verbas trabalhistas a que faziam jus, os empregados da empresa N ingressaram com ação trabalhista em face de ambas as empresas. <u>Nesse contexto, discorra acerca da possibilidade de terceirização de serviços entre empresas, bem como acerca do tipo de responsabilidade da empresa tomadora de serviços</u>.
                </label>
                <br/>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_6']?> "</em>
				<br/><br/>

				<label>
                    7. Quais os requisitos para que seja deferida a equiparação salarial entre empregados, após a Reforma Trabalhista (Lei número 13.467/2017)?
                </label>
                <br/>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_7']?> "</em>
				<br/><br/>

				<label>
                    8. Você foi contratado pelo dono da empresa F para resolver a seguinte situação: a empregada T foi demitida hoje por abando de emprego, haja vista que esta está há mais de 30 dias faltando ao trabalho e sem dar qualquer justificativa, não obstante as inúmeras e comprovadas tentativas da empresa em contatá-la. Diante dessa situação, o dono da empresa disse que não tinha como pagar as verbas rescisórias da ex-empregada, uma vez que esta não tinha qualquer conta bancária. Ante à tal situação, apresente uma solução para contornar o problema relatado pelo seu cliente.
				</label>
                <br/>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_8']?> "</em>
				<br/><br/>

				<label>
                    9. Sobre a competência da Justiça do Trabalho, a Consolidação das Leis do Trabalho e as Súmulas do Tribunal Superior do Trabalho estabelecem:
                </label>
                <br/>
                <p>
                    A) A competência territorial das Varas do Trabalho é determinada pela localidade onde o empregado prestar serviços ao empregador, ainda que tenha sido contratado noutro local ou no estrangeiro, desde que seja o autor da ação.<br />

                    <b class="text-success">
                        B) Quando for parte de dissídio agente ou viajante comercial, a competência será da Vara da localidade em que a empresa tenha agência ou filial e a esta o empregado esteja subordinado e, na falta, será competente a Vara do domicílio do empregado ou a da localidade mais próxima.<br />
                    </b>

                    C) Se o empregado for brasileiro, a Justiça do Trabalho brasileira tem competência para processar e julgar os dissídios ocorridos em agência ou filial no estrangeiro, ainda que haja convenção internacional dispondo em contrário.<br />

                    D) A Justiça do Trabalho é competente para determinar o recolhimento das contribuições previdenciárias, em relação às sentenças condenatórias em pecúnia que proferir e aos valores, objeto de acordo homologado, que integram o salário de contribuição, inclusive, no caso de reconhecimento de vínculo empregatício, quanto aos salários pagos durante a contratualidade.<br />

                    E) A Justiça do Trabalho é competente para processar e julgar as ações de indenização por danos morais e materiais decorrentes da relação de trabalho, inclusive as oriundas de acidente de trabalho e doenças a ele equiparados, ainda que propostas pelos dependentes, desde que habilitados no Instituto Nacional do Seguro Social ou sucessores do trabalhador falecido.
                </p>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_9']?> "</em>
				<br/><br/>

				<label>
                    10. Sobre a execução na Justiça do Trabalho, é correto afirmar:
                </label>
                <br/>
                <p>
                    A) O cheque emitido em reconhecimento de saldo de salários, férias e gratificação de natal não pode ser executado diretamente na Justiça do Trabalho.<br />

                    B) O agravo de petição só será recebido quando o agravante delimitar, justificadamente, as matérias e os valores impugnados, permitida a execução provisória da parte remanescente, nos próprios autos ou por carta de sentença.<br />

                    <b class="text-success">
                        C) Elaborada a conta e tornada líquida, o juiz poderá abrir às partes prazo sucessivo de dez dias para impugnação fundamentada com a indicação dos itens e valores objeto da discordância, e procederá à intimação da União para manifestação, no mesmo prazo, sob pena de preclusão.<br />
                    </b>

                    D) O exequente tem preferência em relação à arrematação para pedir adjudicação, devendo depositar de imediato a diferença, quando o valor do crédito for inferior ao valor dos bens, cujo preço não pode ser inferior ao do melhor lance de arrematação.<br />

                    E) O arrematante deverá garantir o lance com o sinal correspondente a 20% do seu valor, podendo levantá-lo se não complementar o valor remanescente da arrematação, no prazo de vinte e quatro horas, caso em que os bens executados voltarão à praça.
                </p>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_10']?> "</em>
				<br/><br/>

                <label>
                    11. A chamada Reforma Trabalhista, estabelecida pela Lei nº 13.467/2017, trouxe algumas mudanças importantes, entre elas, assuntos que já se encontravam na Consolidação das Leis do Trabalho e outros assuntos que foram incorporados na CLT com a reforma. Considerando as novidades que foram incorporadas pela Lei nº 13.467/2017 na CLT quanto aos dissídios individuais, assinale a alternativa correta.
                </label>
                <br/>
                <p>
                    A) Previu que as nulidades não serão declaradas senão mediante provocação das partes, na primeira oportunidade processual.<br />

                    B) No processo de execução, são devidas custas, de responsabilidade do executado, e pagas ao final do processo.<br />

                    <b class="text-success">
                        C) As testemunhas comparecerão à audiência de instrução independentemente de notificação ou intimação.<br />
                    </b>

                    D) Nas ações com procedimento sumaríssimo, o pedido deverá ser certo ou determinado, com indicação do valor correspondente.<br />

                    E) Será responsável por perdas e danos aquele que litigar de má-fé, seja reclamante, reclamado ou interveniente.
                </p>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_11']?> "</em>
				<br/><br/>

                <label>
                    12. Seu escritório foi contratado pela empresa Alumínio Brilhante Ltda. para assisti-la juridicamente em uma audiência. Você foi designado(a) para a audiência. Forneceram-lhe cópia da defesa e dos documentos, e afirmaram que tudo já havia sido juntado aos autos do processo eletrônico. Na hora da audiência, tendo sido aberta esta, bem como os autos eletrônicos do processo, o juiz constatou que a defesa não estava nos autos, mas apenas os documentos. Diante disso, o juiz facultou-lhe a opção de apresentar defesa.
                </label>
                <br/>
                <p>
                    A) Nos exatos termos previstos na CLT, você deverá entregar a cópia da defesa escrita que está em sua posse, e requerer juntada dos documentos posteriormente.<br />

                    <b class="text-success">
                        B) Aduzir defesa oral em 20 minutos e requerer prazo para juntada posterior dos documentos.<br />
                    </b>

                    C) Requerer o adiamento da audiência para posterior entrega da defesa e documentos.<br />

                    D) Requerer a digitalização da sua defesa para juntada imediata aos autos e requerer prazo para a juntada dos documentos no processo.
                </p>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_12']?> "</em>
				<br/><br/>

                <label>
                    13. Carla Lopes ajuizou reclamação trabalhista contra sua ex-empregadora, Supermercados Onofre, que, há seis meses, demitiu três de seus dezoito empregados, entre eles, Carla. Em sua petição inicial, ela requereu valores devidos em razão de verbas rescisórias pagas a menor, adicional de insalubridade nunca pago ao longo do contrato de trabalho e danos morais decorrentes de assédio moral. Nessa reclamatória, foi atribuído como valor da causa o importe de cinquenta mil reais. Acerca dessa situação hipotética, julgue o item que segue.
                </label>
                <br/>
                <p>
                    Carla poderá indicar como testemunhas ex-empregados da empresa. No entanto, a testemunha que tiver ajuizado ação contra a mesma reclamada poderá ser contraditada pela parte contrária e seu depoimento poderá ser tomado apenas na condição de informante do juízo.
                </p>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_13']?> "</em>
				<br/><br/>

                <label>
                    14. Analise as assertivas abaixo:
                </label>
                <br/>
                <p>
                    I - O depoimento da parte é uma das provas mais importantes, razão pela qual a parte jamais deverá renunciar à oportunidade de expor oralmente em audiência a sua versão para o juiz.<br />

                    II - Aduzir defesa oral em 20 minutos e requerer prazo para juntada posterior dos documentos.<br />

                    III - Requerer o adiamento da audiência para posterior entrega da defesa e documentos.
                </p>
                <p>
                    Assinale a alternativa CORRETA:
                </p>
                <p>
                    A) Apenas a assertiva I está correta.<br />

                    B) Apenas as assertivas II e III estão corretas.<br />

                    <b class="text-success">
                        C) Todas as assertivas estão incorretas.
                    </b>
                </p>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_14']?> "</em>
				<br/><br/>

                <label>
                    15. Seu escritório foi contratado pela empresa Alumínio Brilhante Ltda. para assisti-la juridicamente em uma audiência. Você foi designado(a) para a audiência. Forneceram-lhe cópia da defesa e dos documentos, e afirmaram que tudo já havia sido juntado aos autos do processo eletrônico. Na hora da audiência, tendo sido aberta esta, bem como os autos eletrônicos do processo, o juiz constatou que a defesa não estava nos autos, mas apenas os documentos. Diante disso, o juiz facultou-lhe a opção de apresentar defesa.
                </label>
                <br/>
                <p>
                    <b class="text-success">
                        A) não existe previsão legal tolerando atraso no horário de comparecimento da parte na audiência, sendo aplicados os efeitos da revelia e confissão à Reclamada, entretanto, presente a advogada, serão aceitos a contestação e os documentos apresentados.<br />
                    </b>

                    B) assiste razão à Reclamada, tendo em vista que o preposto esteve presente à audiência antes de seu término, razão pela qual não serão aplicados os efeitos da revelia e confissão à empresa.<br />

                    C) apesar de não existir previsão legal tolerando atrasos no horário de comparecimento da parte na audiência, tendo o preposto comparecido e apresentado justificativa para o seu atraso, deverá o juiz afastar os efeitos da revelia e confissão à Reclamada.<br />

                    D) assiste razão à Reclamada, mas não porque o preposto chegou atrasado antes do término da audiência, mas, sim, porque a advogada esteve presente pontualmente.<br />

                    E) não existe previsão legal tolerando atraso no horário de comparecimento da parte na audiência, sendo aplicados os efeitos da revelia e confissão à Reclamada, ainda, que presente a advogada, não serão aceitos a contestação e os documentos apresentados. 61. Átila, Vênus e Tábata foram empregados da empresa de Transportes Rápido & Feliz Ltda. e têm
                </p>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_15']?> "</em>
				<br/><br/>

                <label>
                    16. O que lhe motivou a querer atuar como advogado(a) associado(a) ao Rocha, Marinho e Sales e no que você pode contribuir para dar um melhor atendimento aos clientes comuns?
                </label>
                <br/>
                <u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_16']?> "</em>
				<br/><br/>

				<small>*As respostas do candidato n&atilde;o podem ser editadas.</small>
			</div>
		</div>
	<?php } ?>

	<?php if ($l["area_atuacao"] == "C&iacute;vil" && $l['cargo_id'] == 5){ // ================== Estagio Civil ?>
		<div class="alert alert-info"><h2><strong>Respostas &agrave;s quest&otilde;es - C&iacute;vil</strong></h2>
			<div id="minhasRespostas">
				<hr/>
				<?php
					$qR = mysqli_query($bd, "SELECT * FROM tb_respostas where id_candidato = '" . $_GET['id'] . "' and status = 'A' and area_atuacao = 'Civil' order by id desc limit 0,1 ");
					$lR = mysqli_fetch_assoc($qR);
					mysqli_free_result($qR);
				?>

				<label>1. "<i>Eis, <u class="text-danger">por&eacute;m</u>, que surgem da esquina duas mulheres desavisadas e tranquilas.</i>” Marque a alternativa que substitui o termo destacado sem altera&ccedil;&atilde;o de sentido:<br/></label>
						<br/>
						A) Ent&atilde;o<br/>
						B) Pois<br/>
						C) Entretanto<br/>
						D) Portanto<br/>
						<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_1']?> "</em>
				</br></br>

				<label>2. Nas frases a seguir, observe o uso da crase e marque a alternativa <u>CORRETA</u>:<br/><br/>
						I.	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Toda noite, &agrave; esta hora, fica-se sobressaltado.<br/>
						II. &nbsp;&nbsp;&nbsp;&nbsp;Sinto que quero bem &agrave; minha parceira de infort&uacute;nio.<br/>
						III. &nbsp;&nbsp;&nbsp;&Agrave; sombra dos edif&iacute;cios, os homens se recolhem.<br/>
						IV. &nbsp;&nbsp;&nbsp;Olhos terr&aacute;veis da pol&iacute;cia est&atilde;o &agrave; espreitar aqui e ali.</label>
						<br/>
						A) As frases I e II est&atilde;o certas.<br/>
						B) O uso da crase na frase II &eacute; facultativo.<br/>
						C) As frases II e III est&atilde;o incorretas.<br/>
						D) As frases III e IV est&atilde;o certas.<br/>
						<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_2']?> "</em>
				<br/><br/>

				<label>3. Em rela&ccedil;&atilde;o ao ato il&iacute;cito, &eacute; <u>INCORRETO</u> afirmar que:<br/></label>
						<br/>
						A) Constitui fonte de obriga&ccedil;&atilde;o.<br/>
						B) O abuso do direito &eacute; considerado pelo C&oacute;digo Civil como ato il&iacute;cito.<br/>
						C) Distingue-se responsabilidade subjetiva da responsabilidade objetiva em face da exist&ecirc;ncia do elemento culpa ou dolo na primeira.<br/>
						D) Basta a viola&ccedil;&atilde;o do direito para gerar o dever de indenizar ou ressarcir o preju&iacute;zo.<br/>
						<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_3']?> "</em>
				<br/><br/>

				<label>4. Em uma a&ccedil;&atilde;o de conhecimento, ao autor &eacute; permitido:<br/></label>
						<br/>
						A) Alterar o pedido antes da cita&ccedil;&atilde;o do r&eacute;u, independentemente do seu consentimento.<br/>
						B) Indicar outro r&eacute;u ap&oacute;s a cita&ccedil;&atilde;o do indicado inicialmente, em qualquer circunst&acirc;ncia.<br/>
						C) Suspender unilateralmente a tramita&ccedil;&atilde;o do processo independentemente do consentimento do r&eacute;u, desde que por prazo inferior a 30 (trinta) dias.<br/>
						D) Alterar o pedido depois do saneamento do processo, desde que com a concord&acirc;ncia do r&eacute;u.<br/>
				<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_4']?> "</em>
				<br/><br/>

				<label>5. Em rela&ccedil;&atilde;o aos recursos, &eacute; <u>CORRETO</u> afirmar:<br/></label>
						<br/>
						A) S&atilde;o sempre recebidos no duplo efeito, devolutivo e suspensivo.<br/>
						B) Das decis&otilde;es interlocut&oacute;rias e dos despachos n&atilde;o cabem recursos.<br/>
						C) &Eacute; poss&iacute;vel desistir de sua interposi&ccedil;&atilde;o, a qualquer tempo, sem anu&ecirc;ncia do recorrido ou dos litisconsortes.<br/>
						D) Somente podem ser interpostos pela parte totalmente vencida.<br/>
				<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_5']?> "</em>
				<br/><br/>

				<label>6. Quais as diferen&ccedil;as entre cita&ccedil;&atilde;o e intima&ccedil;&atilde;o?<br/></label>
						<br/>
						A) Cita&ccedil;&atilde;o &eacute; o ato pelo qual se chama a ju&iacute;zo o r&eacute;u ou o interessado a fim de se defender.; Intima&ccedil;&atilde;o &eacute; o ato pelo qual se d&aacute; ci&ecirc;ncia a algu&eacute;m dos atos e termos do processo, para que fa&ccedil;a ou deixe de fazer alguma coisa.<br/>
						B) Cita&ccedil;&atilde;o &eacute; o ato pelo qual se d&aacute; ci&ecirc;ncia a algu&eacute;m dos atos e termos do processo, para que fa&ccedil;a ou deixe de fazer alguma coisa; Intima&ccedil;&atilde;o &eacute; ato pelo qual se chama a ju&iacute;zo o r&eacute;u ou o interessado a fim de se defender.<br/>
						C) Cita&ccedil;&atilde;o &eacute; o ato pelo qual o juiz cita o nome do r&eacute;u na audi&ecirc;ncia; Intima&ccedil;&atilde;o &eacute; o ato pelo qual se chama a ju&iacute;zo o r&eacute;u ou o interessado a fim de se defender.<br/>
						D) Cita&ccedil;&atilde;o &eacute; o ato pelo qual pode-se dar entrada em um processo; Intima&ccedil;&atilde;o &eacute; o ato pelo qual se chama para responder um processo.<br/>
				<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_6']?> "</em>
				<br/><br/>

				<label>7. Qual das hip&oacute;teses abaixo n&atilde;o caracteriza forma legal de adimplemento e extin&ccedil;&atilde;o das obriga&ccedil;&otilde;es?<br/></label>
						<br/>
						A) Confus&atilde;o.<br/>
						B) Remiss&atilde;o de d&iacute;vida.<br/>
						C) Indeniza&ccedil;&atilde;o.<br/>
						D) Nova&ccedil;&atilde;o.<br/>
				<br/>
				</label><u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_7']?> "</em>
				<br/><br/>

				<label>8. Sobre mora &eacute; correto afirmar que:<br/></label>
						<br/>
						A) H&aacute; apenas uma esp&eacute;cie de mora: a do devedor.<br/>
						B) A mora caracteriza-se apenas pelo inadimplemento do prazo do cumprimento da obriga&ccedil;&atilde;o.<br/>
						C) Mora n&atilde;o pode, de forma nenhuma, trazer qualquer benef&iacute;cio para o credor.<br/>
						D) Mora &eacute; o retardamento ou imperfeito cumprimento da obriga&ccedil;&atilde;o.<br/>
				<br/>
				</label><u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_8']?> "</em>
				<br/><br/>

				<label>9. Durante o curso processual em mat&eacute;ria civil poder&aacute; ser considerado litigante de m&aacute;-f&eacute; aquele que:<br/><br/>
						I.	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Proceder de modo temer&aacute;rio em qualquer incidente ou ato do processo.<br/>
						II. &nbsp;&nbsp;&nbsp;&nbsp;Alterar a verdade dos fatos.<br/>
						III. &nbsp;&nbsp;&nbsp;Deduzir pretens&atilde;o ou defesa contra texto n&atilde;o expresso de lei ou fato controverso.<br/>
						IV. &nbsp;&nbsp;&nbsp;Usar do processo para conseguir objetivo ilegal.</label>
						<br/>
						A) Apenas as assertivas I e III est&atilde;o corretas.<br/>
						B) As assertivas I, II e IV est&atilde;o corretas.<br/>
						C) Apenas a assertiva III est&aacute; correta.<br/>
						D) Apenas as assertivas I e IV est&atilde;o incorretas.<br/>
				<br/>
				</label><u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_9']?> "</em>
				<br/><br/>

				<label>10. Apresentamos dois temas, no qual voc&ecirc; deve escolher e dissertar, colocando seu ponto de vista e argumenta&ccedil;&atilde;o.</label><br/><br/>

						<strong>Suprema Corte dos EUA aprova casamento gay</strong><br/>
						A institucionaliza&ccedil;&atilde;o do casamento homoafetivo promovido pela Suprema Corte dos Estados Unidos reacendeu aqui no Brasil o debate para quest&otilde;es do direito civil de casais gays. Em 2013, o tema esteve quente quando uma resolu&ccedil;&atilde;o do Supremo Tribunal Federal (STF) autorizou a celebra&ccedil;&atilde;o de casamento civil entre pessoas do mesmo sexo nos cart&oacute;rios brasileiros. O tema aparece em novelas e propagandas.

						<br/><br/>
						<strong>Direitos trabalhistas para dom&eacute;sticos s&atilde;o consolidados</strong></br>
						Em vigor desde 2013, as novas regras que garantem melhores condi&ccedil;&otilde;es de trabalho para os profissionais que fazem trabalhos dom&eacute;sticos levantam debates que passam n&atilde;o s&oacute; por quest&otilde;es trabalhistas, mas tamb&eacute;m sociais. O tema virou uma pol&ecirc;mica porque patr&otilde;es alegam que n&atilde;o podem pagar os direitos e os dom&eacute;sticos consideram a PEC uma conquista hist&oacute;rica. Entre os direitos est&atilde;o hora extra e  adicional noturno.<br/><br/>
				</label><u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_10']?> "</em>
				<br/><br/>


				<small>*As respostas do candidato n&atilde;o podem ser editadas.</small>
			</div>
		</div>
	<?php } ?>

	<?php if ($l["area_atuacao"] == "Trabalhista" && $l['cargo_id'] == 5){ // ================== Estagio Civil ?>
		<div class="alert alert-info"><h2><strong>Respostas &agrave;s quest&otilde;es - Trabalhista</strong></h2>
			<div id="minhasRespostas">
				<hr/>
				<?php
					$qR = mysqli_query($bd, "SELECT * FROM tb_respostas where id_candidato = '" . $_GET['id'] . "' and status = 'A' and area_atuacao = 'Trabalhista' order by id desc limit 0,1 ");
					$lR = mysqli_fetch_assoc($qR);
					mysqli_free_result($qR);
				?>

				<label>1. O Princ&iacute;pio da Prote&ccedil;&atilde;o ao Trabalhador suplanta o princ&iacute;pio da Isonomia das Partes: Explique.<br/>
						</label><br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_1']?> "</em>
				</br></br>

				<label>2. Cite e discorra acerca dos requisitos para caracteriza&ccedil;&atilde;o do v&iacute;nculo empregat&iacute;cio.<br/>
						</label><br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_2']?> "</em>
				<br/><br/>

				<label>3. A empresa Fogo Dourado Ltda costuma terceirizar alguns servi&ccedil;os n&atilde;o relacionadas &agrave; atividade fim da empresa e contratou os empregados da empresa Irm&atilde;os S.A. para prestarem servi&ccedil;os na sede da empresa contratante. Ocorre que a empresa contratada, Irm&atilde;os S.A., inesperadamente encerrou suas atividades sem qualquer comunica&ccedil;&atilde;o previa a seus empregados e empresas contratantes. Com intuito de receber as verbas trabalhistas, os empregados desta empresa ingressaram com reclama&ccedil;&atilde;o trabalhista em face de ambas as empresas.
				<br/><br/>Assim, discorra acerca da possibilidade de terceiriza&ccedil;&atilde;o de servi&ccedil;os, bem como acerca do tipo de responsabilidade da empresa tomadora de servi&ccedil;os.<br/>
				</label><br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_3']?> "</em>
				<br/><br/>

				<small>*As respostas do candidato n&atilde;o podem ser editadas.</small>
			</div>
		</div>
	<?php } ?>

	<?php if ($l["area_atuacao"] == "Ambas" && $l['cargo_id'] == 5){ // ================== Estagio Civil ?>

		<div class="alert alert-info"><h2><strong>Respostas &agrave;s quest&otilde;es - C&iacute;vil</strong></h2>
			<div id="minhasRespostas">
				<hr/>
				<?php
					$qR = mysqli_query($bd, "SELECT * FROM tb_respostas where id_candidato = '" . $_GET['id'] . "' and status = 'A' and area_atuacao = 'Civil' order by id desc limit 0,1 ");
					$lR = mysqli_fetch_assoc($qR);
					mysqli_free_result($qR);
				?>

				<label>1. "<i>Eis, <u class="text-danger">por&eacute;m</u>, que surgem da esquina duas mulheres desavisadas e tranquilas.</i>” Marque a alternativa que substitui o termo destacado sem altera&ccedil;&atilde;o de sentido:<br/></label>
						<br/>
						A) Ent&atilde;o<br/>
						B) Pois<br/>
						C) Entretanto<br/>
						D) Portanto<br/>
						<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_1']?> "</em>
				</br></br>

				<label>2. Nas frases a seguir, observe o uso da crase e marque a alternativa <u>CORRETA</u>:<br/><br/>
						I.	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Toda noite, &agrave; esta hora, fica-se sobressaltado.<br/>
						II. &nbsp;&nbsp;&nbsp;&nbsp;Sinto que quero bem &agrave; minha parceira de infort&uacute;nio.<br/>
						III. &nbsp;&nbsp;&nbsp;&Agrave; sombra dos edif&iacute;cios, os homens se recolhem.<br/>
						IV. &nbsp;&nbsp;&nbsp;Olhos terr&aacute;veis da pol&iacute;cia est&atilde;o &agrave; espreitar aqui e ali.</label>
						<br/>
						A) As frases I e II est&atilde;o certas.<br/>
						B) O uso da crase na frase II &eacute; facultativo.<br/>
						C) As frases II e III est&atilde;o incorretas.<br/>
						D) As frases III e IV est&atilde;o certas.<br/>
						<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_2']?> "</em>
				<br/><br/>

				<label>3. Em rela&ccedil;&atilde;o ao ato il&iacute;cito, &eacute; <u>INCORRETO</u> afirmar que:<br/></label>
						<br/>
						A) Constitui fonte de obriga&ccedil;&atilde;o.<br/>
						B) O abuso do direito &eacute; considerado pelo C&oacute;digo Civil como ato il&iacute;cito.<br/>
						C) Distingue-se responsabilidade subjetiva da responsabilidade objetiva em face da exist&ecirc;ncia do elemento culpa ou dolo na primeira.<br/>
						D) Basta a viola&ccedil;&atilde;o do direito para gerar o dever de indenizar ou ressarcir o preju&iacute;zo.<br/>
						<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_3']?> "</em>
				<br/><br/>

				<label>4. Em uma a&ccedil;&atilde;o de conhecimento, ao autor &eacute; permitido:<br/></label>
						<br/>
						A) Alterar o pedido antes da cita&ccedil;&atilde;o do r&eacute;u, independentemente do seu consentimento.<br/>
						B) Indicar outro r&eacute;u ap&oacute;s a cita&ccedil;&atilde;o do indicado inicialmente, em qualquer circunst&acirc;ncia.<br/>
						C) Suspender unilateralmente a tramita&ccedil;&atilde;o do processo independentemente do consentimento do r&eacute;u, desde que por prazo inferior a 30 (trinta) dias.<br/>
						D) Alterar o pedido depois do saneamento do processo, desde que com a concord&acirc;ncia do r&eacute;u.<br/>
				<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_4']?> "</em>
				<br/><br/>

				<label>5. Em rela&ccedil;&atilde;o aos recursos, &eacute; <u>CORRETO</u> afirmar:<br/></label>
						<br/>
						A) S&atilde;o sempre recebidos no duplo efeito, devolutivo e suspensivo.<br/>
						B) Das decis&otilde;es interlocut&oacute;rias e dos despachos n&atilde;o cabem recursos.<br/>
						C) &Eacute; poss&iacute;vel desistir de sua interposi&ccedil;&atilde;o, a qualquer tempo, sem anu&ecirc;ncia do recorrido ou dos litisconsortes.<br/>
						D) Somente podem ser interpostos pela parte totalmente vencida.<br/>
				<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_5']?> "</em>
				<br/><br/>

				<label>6. Quais as diferen&ccedil;as entre cita&ccedil;&atilde;o e intima&ccedil;&atilde;o?<br/></label>
						<br/>
						A) Cita&ccedil;&atilde;o &eacute; o ato pelo qual se chama a ju&iacute;zo o r&eacute;u ou o interessado a fim de se defender.; Intima&ccedil;&atilde;o &eacute; o ato pelo qual se d&aacute; ci&ecirc;ncia a algu&eacute;m dos atos e termos do processo, para que fa&ccedil;a ou deixe de fazer alguma coisa.<br/>
						B) Cita&ccedil;&atilde;o &eacute; o ato pelo qual se d&aacute; ci&ecirc;ncia a algu&eacute;m dos atos e termos do processo, para que fa&ccedil;a ou deixe de fazer alguma coisa; Intima&ccedil;&atilde;o &eacute; ato pelo qual se chama a ju&iacute;zo o r&eacute;u ou o interessado a fim de se defender.<br/>
						C) Cita&ccedil;&atilde;o &eacute; o ato pelo qual o juiz cita o nome do r&eacute;u na audi&ecirc;ncia; Intima&ccedil;&atilde;o &eacute; o ato pelo qual se chama a ju&iacute;zo o r&eacute;u ou o interessado a fim de se defender.<br/>
						D) Cita&ccedil;&atilde;o &eacute; o ato pelo qual pode-se dar entrada em um processo; Intima&ccedil;&atilde;o &eacute; o ato pelo qual se chama para responder um processo.<br/>
				<br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_6']?> "</em>
				<br/><br/>

				<label>7. Qual das hip&oacute;teses abaixo n&atilde;o caracteriza forma legal de adimplemento e extin&ccedil;&atilde;o das obriga&ccedil;&otilde;es?<br/></label>
						<br/>
						A) Confus&atilde;o.<br/>
						B) Remiss&atilde;o de d&iacute;vida.<br/>
						C) Indeniza&ccedil;&atilde;o.<br/>
						D) Nova&ccedil;&atilde;o.<br/>
				<br/>
				</label><u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_7']?> "</em>
				<br/><br/>

				<label>8. Sobre mora &eacute; correto afirmar que:<br/></label>
						<br/>
						A) H&aacute; apenas uma esp&eacute;cie de mora: a do devedor.<br/>
						B) A mora caracteriza-se apenas pelo inadimplemento do prazo do cumprimento da obriga&ccedil;&atilde;o.<br/>
						C) Mora n&atilde;o pode, de forma nenhuma, trazer qualquer benef&iacute;cio para o credor.<br/>
						D) Mora &eacute; o retardamento ou imperfeito cumprimento da obriga&ccedil;&atilde;o.<br/>
				<br/>
				</label><u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_8']?> "</em>
				<br/><br/>

				<label>9. Durante o curso processual em mat&eacute;ria civil poder&aacute; ser considerado litigante de m&aacute;-f&eacute; aquele que:<br/><br/>
						I.	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Proceder de modo temer&aacute;rio em qualquer incidente ou ato do processo.<br/>
						II. &nbsp;&nbsp;&nbsp;&nbsp;Alterar a verdade dos fatos.<br/>
						III. &nbsp;&nbsp;&nbsp;Deduzir pretens&atilde;o ou defesa contra texto n&atilde;o expresso de lei ou fato controverso.<br/>
						IV. &nbsp;&nbsp;&nbsp;Usar do processo para conseguir objetivo ilegal.</label>
						<br/>
						A) Apenas as assertivas I e III est&atilde;o corretas.<br/>
						B) As assertivas I, II e IV est&atilde;o corretas.<br/>
						C) Apenas a assertiva III est&aacute; correta.<br/>
						D) Apenas as assertivas I e IV est&atilde;o incorretas.<br/>
				<br/>
				</label><u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_9']?> "</em>
				<br/><br/>

				<label>10. Apresentamos dois temas, no qual voc&ecirc; deve escolher e dissertar, colocando seu ponto de vista e argumenta&ccedil;&atilde;o.</label><br/><br/>

						<strong>Suprema Corte dos EUA aprova casamento gay</strong><br/>
						A institucionaliza&ccedil;&atilde;o do casamento homoafetivo promovido pela Suprema Corte dos Estados Unidos reacendeu aqui no Brasil o debate para quest&otilde;es do direito civil de casais gays. Em 2013, o tema esteve quente quando uma resolu&ccedil;&atilde;o do Supremo Tribunal Federal (STF) autorizou a celebra&ccedil;&atilde;o de casamento civil entre pessoas do mesmo sexo nos cart&oacute;rios brasileiros. O tema aparece em novelas e propagandas.

						<br/><br/>
						<strong>Direitos trabalhistas para dom&eacute;sticos s&atilde;o consolidados</strong></br>
						Em vigor desde 2013, as novas regras que garantem melhores condi&ccedil;&otilde;es de trabalho para os profissionais que fazem trabalhos dom&eacute;sticos levantam debates que passam n&atilde;o s&oacute; por quest&otilde;es trabalhistas, mas tamb&eacute;m sociais. O tema virou uma pol&ecirc;mica porque patr&otilde;es alegam que n&atilde;o podem pagar os direitos e os dom&eacute;sticos consideram a PEC uma conquista hist&oacute;rica. Entre os direitos est&atilde;o hora extra e  adicional noturno.<br/><br/>
				</label><u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_10']?> "</em>
				<br/><br/>


				<small>*As respostas do candidato n&atilde;o podem ser editadas.</small>
			</div>
		</div>

		<hr/>

		<div class="alert alert-info"><h2><strong>Respostas &agrave;s quest&otilde;es - Trabalhista</strong></h2>
			<div id="minhasRespostas">
				<hr/>
				<?php
					$qR = mysqli_query($bd, "SELECT * FROM tb_respostas where id_candidato = '" . $_GET['id'] . "' and status = 'A' and area_atuacao = 'Trabalhista' order by id desc limit 0,1 ");
					$lR = mysqli_fetch_assoc($qR);
					mysqli_free_result($qR);
				?>

				<label>1. O Princ&iacute;pio da Prote&ccedil;&atilde;o ao Trabalhador suplanta o princ&iacute;pio da Isonomia das Partes: Explique.<br/>
						</label><br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_1']?> "</em>
				</br></br>

				<label>2. Cite e discorra acerca dos requisitos para caracteriza&ccedil;&atilde;o do v&iacute;nculo empregat&iacute;cio.<br/>
						</label><br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_2']?> "</em>
				<br/><br/>

				<label>3. A empresa Fogo Dourado Ltda costuma terceirizar alguns servi&ccedil;os n&atilde;o relacionadas &agrave; atividade fim da empresa e contratou os empregados da empresa Irm&atilde;os S.A. para prestarem servi&ccedil;os na sede da empresa contratante. Ocorre que a empresa contratada, Irm&atilde;os S.A., inesperadamente encerrou suas atividades sem qualquer comunica&ccedil;&atilde;o previa a seus empregados e empresas contratantes. Com intuito de receber as verbas trabalhistas, os empregados desta empresa ingressaram com reclama&ccedil;&atilde;o trabalhista em face de ambas as empresas.
				<br/><br/>Assim, discorra acerca da possibilidade de terceiriza&ccedil;&atilde;o de servi&ccedil;os, bem como acerca do tipo de responsabilidade da empresa tomadora de servi&ccedil;os.<br/>
				</label><br/>
						<u>Resposta do Candidato</u>: <em>" <?=$lR['resposta_3']?> "</em>
				<br/><br/>

				<small>*As respostas do candidato n&atilde;o podem ser editadas.</small>
			</div>
		</div>
	<?php } ?>

	<?php mysqli_close($bd); ?>

	<script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="../js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../js/fontawesome-all.min.js"></script>
	<script type="text/javascript" src="../js/custom.js"></script>
	<script type="text/javascript" src="../js/admin.js"></script>

</body>

</html>
