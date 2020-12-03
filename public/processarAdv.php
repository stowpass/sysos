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

if ($_POST['areaAtuacaoHidden'] == "Civil"){
	// Verifica se não vem em branco
	if ($_POST['respostaCivil1'] != "" || $_POST['respostaCivil2'] != "" || $_POST['respostaCivil3'] != "" || $_POST['respostaCivil4'] != "" || $_POST['respostaCivil5'] != ""
		|| $_POST['respostaCivil6'] != "" || $_POST['respostaCivil7'] != "" || $_POST['respostaCivil8'] != "" || $_POST['respostaCivil9'] != ""){

		$sql = "insert into tb_respostas (id_candidato,
										   area_atuacao,
										   resposta_1,
										   resposta_2,
										   resposta_3,
										   resposta_4,
										   resposta_5,
										   resposta_6,
										   resposta_7,
										   resposta_8,
										   resposta_9,
										   tempo_inicio,
										   tempo_envio,
										   status) values('".$_POST['idCandidato']."',
										   'Civil',
										   '".$_POST['respostaCivil1']."',
										   '".$_POST['respostaCivil2']."',
										   '".$_POST['respostaCivil3']."',
										   '".$_POST["respostaCivil4"]."',
										   '".$_POST["respostaCivil5"]."',
										   '".$_POST["respostaCivil6"]."',
										   '".$_POST["respostaCivil7"]."',
										   '".$_POST["respostaCivil8"]."',
										   '".$_POST["respostaCivil9"]."',
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
}


if ($_POST['areaAtuacaoHidden'] == "Trabalhista"){
	// Verifica se não vem em branco
	if (
        $_POST['respostaTrabalhista1'] != ""
        || $_POST['respostaTrabalhista2'] != ""
        || $_POST['respostaTrabalhista3'] != ""
        || $_POST['respostaTrabalhista4'] != ""
        || $_POST['respostaTrabalhista5'] != ""
        || $_POST['respostaTrabalhista6'] != ""
        || $_POST['respostaTrabalhista7'] != ""
        || $_POST['respostaTrabalhista8'] != ""
        || $_POST['respostaTrabalhista9'] != ""
        || $_POST['respostaTrabalhista10'] != ""
        || $_POST['respostaTrabalhista11'] != ""
        || $_POST['respostaTrabalhista12'] != ""
        || $_POST['respostaTrabalhista13'] != ""
        || $_POST['respostaTrabalhista14'] != ""
        || $_POST['respostaTrabalhista15'] != ""
        || $_POST['respostaTrabalhista16'] != ""
    ){

		$sql = "insert into tb_respostas (id_candidato,
										   area_atuacao,
										   resposta_1,
										   resposta_2,
										   resposta_3,
										   resposta_4,
										   resposta_5,
										   resposta_6,
										   resposta_7,
										   resposta_8,
										   resposta_9,
										   resposta_10,
										   resposta_11,
										   resposta_12,
										   resposta_13,
										   resposta_14,
										   resposta_15,
										   resposta_16,
										   tempo_inicio,
										   tempo_envio,
										   status) values('".$_POST['idCandidato']."',
										   'Trabalhista',
										   '".$_POST['respostaTrabalhista1']."',
										   '".$_POST['respostaTrabalhista2']."',
										   '".$_POST['respostaTrabalhista3']."',
										   '".$_POST["respostaTrabalhista4"]."',
										   '".$_POST["respostaTrabalhista5"]."',
										   '".$_POST["respostaTrabalhista6"]."',
										   '".$_POST["respostaTrabalhista7"]."',
										   '".$_POST["respostaTrabalhista8"]."',
										   '".$_POST["respostaTrabalhista9"]."',
										   '".$_POST["respostaTrabalhista10"]."',
										   '".$_POST["respostaTrabalhista11"]."',
										   '".$_POST["respostaTrabalhista12"]."',
										   '".$_POST["respostaTrabalhista13"]."',
										   '".$_POST["respostaTrabalhista14"]."',
										   '".$_POST["respostaTrabalhista15"]."',
										   '".$_POST["respostaTrabalhista16"]."',
										   '".$_POST["primeiraTentativa"]."',
										   '".$_SESSION['tempo_envio']."',
										   'A') " ;
		mysqli_query($link, $sql);

		$sql2 = "UPDATE tb_candidato SET finalizado = 'S' WHERE id='".$_POST['idCandidato']."' ";
		mysqli_query($link, $sql2);

		$_SESSION['finalizado'] = 'S';
		unset($_SESSION['tempo_inicio']);

	} else {
		$_SESSION['finalizado'] = 'N';
	}
}


if ($_POST['areaAtuacaoHidden'] == "Ambas"){
	// Verifica se não vem em branco
	if (
        $_POST['respostaCivil1'] != ""
        || $_POST['respostaCivil2'] != ""
        || $_POST['respostaCivil3'] != ""
        || $_POST['respostaCivil4'] != ""
        || $_POST['respostaCivil5'] != ""
        || $_POST['respostaCivil6'] != ""
        || $_POST['respostaCivil7'] != ""
        || $_POST['respostaCivil8'] != ""
        || $_POST['respostaCivil9'] != ""
        || $_POST['respostaTrabalhista1'] != ""
        || $_POST['respostaTrabalhista2'] != ""
        || $_POST['respostaTrabalhista3'] != ""
        || $_POST['respostaTrabalhista4'] != ""
        || $_POST['respostaTrabalhista5'] != ""
        || $_POST['respostaTrabalhista6'] != ""
        || $_POST['respostaTrabalhista7'] != ""
        || $_POST['respostaTrabalhista8'] != ""
        || $_POST['respostaTrabalhista9'] != ""
        || $_POST['respostaTrabalhista10'] != ""
        || $_POST['respostaTrabalhista11'] != ""
        || $_POST['respostaTrabalhista12'] != ""
        || $_POST['respostaTrabalhista13'] != ""
        || $_POST['respostaTrabalhista14'] != ""
        || $_POST['respostaTrabalhista15'] != ""
        || $_POST['respostaTrabalhista16'] != ""
    ){

		$sql = "insert into tb_respostas (id_candidato,
										   area_atuacao,
										   resposta_1,
										   resposta_2,
										   resposta_3,
										   resposta_4,
										   resposta_5,
										   resposta_6,
										   resposta_7,
										   resposta_8,
										   resposta_9,
										   tempo_inicio,
										   tempo_envio,
										   status) values('".$_POST['idCandidato']."',
										   'Civil',
										   '".$_POST['respostaCivil1']."',
										   '".$_POST['respostaCivil2']."',
										   '".$_POST['respostaCivil3']."',
										   '".$_POST["respostaCivil4"]."',
										   '".$_POST["respostaCivil5"]."',
										   '".$_POST["respostaCivil6"]."',
										   '".$_POST["respostaCivil7"]."',
										   '".$_POST["respostaCivil8"]."',
										   '".$_POST["respostaCivil9"]."',
										   '".$_POST["primeiraTentativa"]."',
										   '".$_SESSION['tempo_envio']."',
										   'A') " ;
		mysqli_query($link, $sql);

		$sqlT = "insert into tb_respostas (id_candidato,
										   area_atuacao,
										   resposta_1,
										   resposta_2,
										   resposta_3,
										   resposta_4,
										   resposta_5,
										   resposta_6,
										   resposta_7,
										   resposta_8,
										   resposta_9,
										   resposta_10,
										   resposta_11,
										   resposta_12,
										   resposta_13,
										   resposta_14,
										   resposta_15,
										   resposta_16,
										   tempo_inicio,
										   tempo_envio,
										   status) values('".$_POST['idCandidato']."',
										   'Trabalhista',
										   '".$_POST['respostaTrabalhista1']."',
										   '".$_POST['respostaTrabalhista2']."',
										   '".$_POST['respostaTrabalhista3']."',
										   '".$_POST["respostaTrabalhista4"]."',
										   '".$_POST["respostaTrabalhista5"]."',
										   '".$_POST["respostaTrabalhista6"]."',
										   '".$_POST["respostaTrabalhista7"]."',
										   '".$_POST["respostaTrabalhista8"]."',
										   '".$_POST["respostaTrabalhista9"]."',
										   '".$_POST["respostaTrabalhista10"]."',
										   '".$_POST["respostaTrabalhista11"]."',
										   '".$_POST["respostaTrabalhista12"]."',
										   '".$_POST["respostaTrabalhista13"]."',
										   '".$_POST["respostaTrabalhista14"]."',
										   '".$_POST["respostaTrabalhista15"]."',
										   '".$_POST["respostaTrabalhista16"]."',
										   '".$_POST["primeiraTentativa"]."',
										   '".$_SESSION['tempo_envio']."',
										   'A') " ;
		mysqli_query($link, $sqlT);

		$sql2 = "UPDATE tb_candidato SET finalizado = 'S' WHERE id='".$_POST['idCandidato']."' ";
		mysqli_query($link, $sql2);

		$_SESSION['finalizado'] = 'S';
		unset($_SESSION['tempo_inicio']);

	} else {
		$_SESSION['finalizado'] = 'N';
	}
}

redirect("finalizado.php");

mysqli_close($link);

?>
