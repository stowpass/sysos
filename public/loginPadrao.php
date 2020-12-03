<?php
require_once __DIR__ . '/includes/funcoes.php';

session_start();

// JSON referente ao login ao sistema
$link = abreConn();

$log = $_POST["cpf"];

$q = mysqli_query($link, "SELECT * FROM tb_candidato where cpf = '". $log ."' and cpf != '' and status = 'A' ");
$l = mysqli_fetch_assoc($q);
mysqli_free_result($q);

// Setando as variáveis de sessão (afim de evitar consultas repetitivas)
$_SESSION["id"] = (int) $l["id"];
$_SESSION["nome"] = $l["nome"];
$_SESSION["cpf"] = $l["cpf"];
$_SESSION["email"] = $l["email"];
$_SESSION["telefone"] = $l["telefone"];
$_SESSION["celular"] = $l["celular"];
$_SESSION["endereco"] = $l["endereco"];
$_SESSION["nascimento"] = $l["nascimento"];
$_SESSION["primeiraTentativa"] = $l["primeira_tentativa"];
$_SESSION["cargoId"] = (int) $l['cargo_id'];
$_SESSION['is_candidato'] = true;

// Advogados
$_SESSION["areaAtuacao"] = $l["area_atuacao"];
$_SESSION["formacao"] = $l["formacao"];
$_SESSION["numOab"] = $l["num_oab"];

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

// Verifica se resultado ja saiu
if ($l["aprovado"] == 'S' || $l["aprovado"] == 'N' || $l["aprovado"] == 'B'){
	$_SESSION["aprovado"] = $l["aprovado"];
}

// Verifica se encontrou usuario no banco
if ($_SESSION["id"] == "") {
	redirect("index.php");
} else {

	// Recupera o cargo pleiteado
	$qC = mysqli_query($link, "SELECT * FROM tb_cargo where id = " . $l['cargo_id'] . " ");
	$lC = mysqli_fetch_assoc($qC);
	mysqli_free_result($qC);

	$_SESSION["cargo"] = $lC["cargo"];

	redirect("home.php");
}

mysqli_close($link);

?>
