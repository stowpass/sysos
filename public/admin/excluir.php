<?php
require_once __DIR__ . '/../includes/funcoes.php';

session_start();

header('Content-Type: application/json');
error_reporting(E_ERROR | E_PARSE);

// JSON referente ao login ao sistema
$link = abreConn();
$arr = array();

// GERAL
if (isset($_GET['area']) && isset($_GET['id'])){

	// ================== Candidato ====================
	if ($_GET['area'] == "candidato"){

		$sqlUp = "UPDATE tb_candidato SET status='E' WHERE id='".$_GET["id"]."' ";
		mysqli_query($link, $sqlUp);
		$arr2 = array(
			"excluido" => "S",
			"id" => $_GET["id"],
		);

	}
	// ================== Candidato ====================


	// ================== Contratacao ====================
	if ($_GET['area'] == "contratacao"){

		$sqlUp = "UPDATE tb_solicitar_contratacao SET status='E' WHERE id='".$_GET["id"]."' ";
		mysqli_query($link, $sqlUp);
		$arr2 = array(
			"excluido" => "S",
			"id" => $_GET["id"],
		);

	}
	// ================== Contratcao ====================


	// ================== Processo Seletivo ====================
	if ($_GET['area'] == "processoSeletivo"){

		$sqlUp = "UPDATE tb_processo_seletivo SET status='E' WHERE id='".$_GET["id"]."' ";
		mysqli_query($link, $sqlUp);
		$arr2 = array(
			"excluido" => "S",
			"id" => $_GET["id"],
		);

	}
	// ================== Processo Seletivo ====================



	// ================== Processo Seletivo ====================
	if ($_GET['area'] == "novaProva"){

		$sqlUp = "UPDATE tb_candidato SET primeira_tentativa = '', texto_copiado_qntd = '0', finalizado = '', aprovado = ''  WHERE id='".$_GET["id"]."' ";
		mysqli_query($link, $sqlUp);

		$sqlUp = "UPDATE tb_respostas SET status = 'E' WHERE id_candidato='".$_GET["id"]."' ";
		mysqli_query($link, $sqlUp);

		$arr2 = array(
			"novaProva" => "S",
			"id" => $_GET["id"],
		);

	}
	// ================== Processo Seletivo ====================


} else { // GERAL
	$arr2 = array(
		"excluido" => "N",
	);
}

mysqli_close($link);

array_push($arr,$arr2);

print json_encode($arr);

?>
