<?php
require_once __DIR__ . '/../includes/funcoes.php';

session_start();

header('Content-Type: application/json');

date_default_timezone_set('America/Sao_Paulo');
$dia = date('d');
$mes = date('m');
$ano = date('Y');
$dataAgora = $dia."/".$mes."/".$ano;
error_reporting(E_ERROR | E_PARSE);

// JSON referente ao cadastro de Atendimento
$link = abreConn();
$arr = array();

// Verifica se não vem em branco
if ($_GET['idEquipe'] != "" && isset($_SESSION['id'])){

	if ($_GET['idSolicitacao'] == "undefined" || $_GET['idSolicitacao'] == ""){ // Se for NOVO CADASTRO

		// Insero a nova solicitação
		$sql = "insert into tb_solicitar_contratacao (cadastrador_id,
										   data_cadastro,
										   equipe_id,
										   ponto_focal,
										   motivo_contratacao,
										   colaboador_substituido,
										   modalidade_contratacao,
										   cargo_id,
										   horario_trabalho,
										   grupo_emails,
										   pastas_redes,
										   nome_novo_colaborador,
										   sugestao_email,
										   base_alocacao,
										   data_inicio_atividades,
										   obs,
										   status) values('".$_SESSION['id']."',
										   '".$dataAgora."',
										   '".$_GET['idEquipe']."',
										   '".$_GET['pontoFocal']."',
										   '".$_GET['motivoContratacao']."',
										   '".$_GET['colaboradorSubstituido']."',
										   '".$_GET['modalidadeContratacao']."',
										   '".$_GET['idCargo']."',
										   '".$_GET['horarioTrabalho']."',
										   '".$_GET['grupoEmails']."',
										   '".$_GET['pastaRede']."',
										   '".$_GET['nomeNovoColaborador']."',
										   '".$_GET['emailSugerido']."',
										   '".$_GET['baseAlocacaoSelecionada']."',
										   '".$_GET['dataInicio']."',
										   '".$_GET["obs"]."',
										   'A') " ;
		mysqli_query($link, $sql);

	}

	if ($_GET['idSolicitacao'] != ""){ // Se for EDICAO

		$sql2 = "UPDATE tb_solicitar_contratacao SET equipe_id = '".$_GET['idEquipe']."',
											ponto_focal = '".$_GET['pontoFocal']."',
											motivo_contratacao = '".$_GET['motivoContratacao']."',
											colaboador_substituido = '".$_GET['colaboradorSubstituido']."',
											modalidade_contratacao = '".$_GET['modalidadeContratacao']."',
											cargo_id = '".$_GET['idCargo']."',
											horario_trabalho = '".$_GET['horarioTrabalho']."',
											grupo_emails = '".$_GET['grupoEmails']."',
											pastas_redes = '".$_GET['pastaRede']."',
											nome_novo_colaborador = '".$_GET['nomeNovoColaborador']."',
											sugestao_email = '".$_GET['emailSugerido']."',
											base_alocacao = '".$_GET['baseAlocacaoSelecionada']."',
											data_inicio_atividades = '".$_GET['dataInicio']."',
											obs = '".$_GET['obs']."'
											WHERE id ='".$_GET["idSolicitacao"]."' ";
		mysqli_query($link, $sql2);
	}

	// Informa via objeto JSON que o cadastro foi realizado com sucesso
	$arr2 = array(
		"cadastrou" => "S",
	);

} else {
	$arr2 = array(
		"cadastrou" => "N",
	);
}

mysqli_close($link);

array_push($arr,$arr2);

print json_encode($arr);

?>
