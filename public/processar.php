<?php
require_once __DIR__ . '/includes/funcoes.php';

session_start();

isSessaoValida('', false);

// Nao permite reenviar respostas
if (isset($_SESSION['finalizado'])){
	redirect("home.php");
}

date_default_timezone_set('America/Fortaleza');
$diaCorrente = date('d');
$mesCorrente = date('m');
$anoCorrente = date('Y');
$horaCorrente = date('H');
$minutoCorrente = date('i');
$segundoCorrente = date('s');
$_SESSION['tempo_envio'] = $diaCorrente."/".$mesCorrente."/".$anoCorrente." - ".$horaCorrente.":".$minutoCorrente.":".$segundoCorrente;

error_reporting(E_ERROR | E_PARSE);

// JSON referente ao cadastro de Atendimento
$link = abreConn();

// Verifica se não vem em branco
if ($_POST['resposta1'] != "" || $_POST['resposta2'] != "" || $_POST['resposta3'] != "" || $_POST['resposta4'] != "" || $_POST['resposta5'] != ""){

	$sql = "insert into tb_respostas (id_candidato,
									   resposta_1,
									   resposta_2,
									   resposta_3,
									   resposta_4,
									   resposta_5,
									   tempo_inicio,
									   tempo_envio,
									   status) values('".$_POST['idCandidato']."',
									   '".$_POST['resposta1']."',
									   '".$_POST['resposta2']."',
									   '".$_POST['resposta3']."',
									   '".$_POST["resposta4"]."',
									   '".$_POST["resposta5"]."',
									   '".$_POST["primeiraTentativa"]."',
									   '".$_SESSION['tempo_envio']."',
									   'A') " ;
	mysqli_query($link, $sql);

	$sql2 = "UPDATE tb_candidato SET finalizado = 'S' WHERE id='".$_POST["idCandidato"]."' ";
	mysqli_query($link, $sql2);

	$_SESSION['finalizado'] = 'S';
	unset($_SESSION['tempo_inicio']);

} else {
	$_SESSION['finalizado'] = 'N';
}

redirect("finalizado.php");

mysqli_close($link);

?>
