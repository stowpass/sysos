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


	// ================== Aprovar/Reprovar Candidato ====================
	if ($_GET['area'] == "aprovarReprovar"){

		$sqlUp = "UPDATE tb_candidato SET aprovado='".$_GET['valorAprovacao']."' WHERE id='".$_GET["id"]."' ";
		mysqli_query($link, $sqlUp);
		$arr2 = array(
			"encontrado" => "S",
			"id" => $_GET["id"],
		);

	}
	// ================== Aprovar/Reprovar ====================


	// ================== Autorizar/Negar Trabalhe conosco para Candidato ====================
	if ($_GET['area'] == "moveToCandidate"){

		$sqlUp = "UPDATE tb_candidato SET trabalhe_conosco='".$_GET['valor']."' WHERE id='".$_GET["id"]."' ";
		mysqli_query($link, $sqlUp);
		$arr2 = array(
			"encontrado" => "S",
			"id" => $_GET["id"],
		);

	}
	// ================== Autorizar/Negar ====================


} else { // GERAL
	$arr2 = array(
		"encontrado" => "N",
	);
}

mysqli_close($link);

array_push($arr,$arr2);

print json_encode($arr);

?>
