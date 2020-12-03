<?php
require_once __DIR__ . '/../includes/funcoes.php';

session_start();

header('Content-Type: application/json');
error_reporting(E_ERROR | E_PARSE);

// JSON referente ao login ao sistema
$link = abreConn();
$arr = array();

// Encontra Cidade
$q = mysqli_query($link, "SELECT * FROM cidade where nome LIKE '%". $_GET['param'] ."%' ");


while ($rs = mysqli_fetch_array($q)){
	// Verifica se encontrou usuario no banco
	if ($rs["id"] == "") {
		$arr2 = array(
			"achou" => "N",
		);
	} else {

		// Encontra Estado
		$qE = mysqli_query($link, "SELECT * FROM estado where id = ".$rs['estado']." ");
		$lE = mysqli_fetch_assoc($qE);
		mysqli_free_result($qE);

		$arr2 = array(
			"achou" => "S",
			"cidade" => tirarAcentos($rs["nome"]),
			"uf" => $lE["uf"],
			"id" => $rs["id"],
		);

		array_push($arr,$arr2);
	}
}

mysqli_close($link);

print json_encode($arr);

?>
