<?php
require_once __DIR__ . '/../includes/funcoes.php';

session_start();

header('Content-Type: application/json');
error_reporting(E_ERROR | E_PARSE);

// JSON referente ao login ao sistema
$link = abreConn();
$arr = array();

// Encontra Cidade
$q = mysqli_query($link, "SELECT * FROM cidade where estado = '".$_GET['estado']."' ");


while ($rs = mysqli_fetch_array($q)){

	$arr2 = array(
		"cidade" => $rs["nome"],
		"id" => $rs["id"],
	);

	array_push($arr,$arr2);
}

mysqli_close($link);

print json_encode($arr);

?>
