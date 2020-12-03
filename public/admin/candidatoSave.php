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
if ($_GET['idCargo'] != "" && isset($_SESSION['id'])){

	if ($_GET['idCandidato'] == "undefined" || $_GET['idCandidato'] == ""){ // Se for NOVO CADASTRO

		// Insero a nova solicitação
		$sql = "insert into tb_candidato (cadastrador_id,
										   data_cadastro,
										   nome,
										   senha,
										   email,
										   telefone,
										   endereco,
										   nascimento,
										   foto,
										   cargo_id,
										   processo_atual_id,
										   obs,
										   status) values('".$_SESSION['id']."',
										   '".$dataAgora."',
										   '".$_GET['nome']."',
										   '".$_GET['senha']."',
										   '".$_GET['email']."',
										   '".$_GET['telefone']."',
										   '".$_GET['endereco']."',
										   '".$_GET['nascimento']."',
										   '".$_GET['urlFoto']."',
										   '".$_GET['idCargo']."',
										   '".$_GET['idProcessoSeletivo']."',
										   '".$_GET['obs']."',
										   'A') " ;
		mysqli_query($link, $sql);

	}

	if ($_GET['idCandidato'] != ""){ // Se for EDICAO

		$sql2 = "UPDATE tb_candidato SET nome = '".$_GET['nome']."',
											senha = '".$_GET['senha']."',
											email = '".$_GET['email']."',
											telefone = '".$_GET['telefone']."',
											endereco = '".$_GET['endereco']."',
											nascimento = '".$_GET['nascimento']."',
											foto = '".$_GET['urlFoto']."',
											cargo_id = '".$_GET['idCargo']."',
											processo_atual_id = '".$_GET['idProcessoSeletivo']."',
											obs = '".$_GET['obs']."',
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
