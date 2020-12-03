<?php
require_once __DIR__ . '/../includes/funcoes.php';

session_start();

header('Content-Type: application/json');
error_reporting(E_ERROR | E_PARSE);

// JSON referente ao login ao sistema
$link = abreConn();
$arr = array();

// GERAL
if (isset($_GET['area']) && isset($_GET['param'])){

	// ================== Processo Seletivo ====================
	if ($_GET['area'] == "processo"){

		$q = mysqli_query($link, "SELECT * FROM tb_processo_seletivo where nome = '".$_GET['param']."' order by id desc limit 0,1 ");
		$num_rows = mysqli_num_rows($q);

		$l = mysqli_fetch_assoc($q);
		mysqli_free_result($q);

		if ($num_rows > 0){
			$arr2 = array(
				"duplicado" => "S",
				"id" => $_GET["id"],
			);
		} else {
			$arr2 = array(
				"duplicado" => "N",
				"id" => $_GET["id"],
			);
		}

	}
	// ================== Processo Seletivo ====================


	// ================== Candidato Externo ====================
	if ($_GET['area'] == "candidatoExt"){

		$q = mysqli_query($link, "SELECT * FROM tb_candidato where cpf = '".$_GET['param']."' and status = 'A' ");
		$num_rows = mysqli_num_rows($q);

		$l = mysqli_fetch_assoc($q);
		mysqli_free_result($q);

		if ($num_rows > 0){
			$arr2 = array(
				"duplicado" => "S"
			);
		} else {
			$arr2 = array(
				"duplicado" => "N"
			);
		}

	}
	// ================== Candidato Externo ====================


} else { // GERAL
	$arr2 = array(
		"duplicado" => "N"
	);
}

mysqli_close($link);

array_push($arr,$arr2);

print json_encode($arr);

?>
