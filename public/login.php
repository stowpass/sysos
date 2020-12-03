<?php
require_once __DIR__ . '/includes/funcoes.php';

session_start();

header('Content-Type: application/json');
error_reporting(E_ERROR | E_PARSE);

// JSON referente ao login ao sistema
$link = abreConn();
$arr = array();

$log = $_GET["email"];
$pass = $_GET["senha"];

$q = mysqli_query($link, "SELECT * FROM tb_candidato where email = '". $log ."' and senha = '" . $pass . "' and status = 'A' ");
$l = mysqli_fetch_assoc($q);
mysqli_free_result($q);

// Setando as variáveis de sessão (afim de evitar consultas repetitivas)
$_SESSION["id"] = $l["id"];
$_SESSION["nome"] = $l["nome"];
$_SESSION["email"] = $l["email"];
$_SESSION["telefone"] = $l["telefone"];
$_SESSION["endereco"] = $l["endereco"];
$_SESSION["nascimento"] = $l["nascimento"];
$_SESSION["primeiraTentativa"] = $l["primeira_tentativa"];

// Caso tenha Foto (url)
if ($l["foto"] != ''){
	$_SESSION["foto"] = $l["foto"];
} else {
	$_SESSION['foto'] = "https://lh3.googleusercontent.com/uFp_tsTJboUY7kue5XAsGA=s120"; //Padrao Google
}

// Evitar que respostas sejam reenviadas
if ($l["finalizado"] == 'S'){
	$_SESSION["finalizado"] = $l["finalizado"];
}

// Evitar que respostas sejam reenviadas
if ($l["primeira_tentativa"] != '' && $l["finalizado"] != 'S'){
	$_SESSION["tempo_inicio"] = $l["primeira_tentativa"];
}

// Verifica se encontrou usuario no banco
if ($_SESSION["id"] == "") {
	$arr2 = array(
		"loginOk" => "N",
	);
} else {

	// Recupera o cargo pleiteado
	$qC = mysqli_query($link, "SELECT * FROM tb_cargo where id = " . $l['cargo'] . " ");
	$lC = mysqli_fetch_assoc($qC);
	mysqli_free_result($qC);

	$_SESSION["cargo"] = $lC["cargo"];

	$arr2 = array(
		"loginOk" => "S",
	);
}

mysqli_close($link);

array_push($arr,$arr2);

print json_encode($arr);

?>
