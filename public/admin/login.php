<?php
require_once __DIR__ . '/../includes/funcoes.php';

session_start();

header('Content-Type: application/json');
error_reporting(E_ERROR | E_PARSE);

$arr = array();

if (!isSessaoValida()) {
    // JSON referente ao login ao sistema
    $link = abreConn();

    $log = $_GET["email"];
    $pass = $_GET["senha"];

    $q = mysqli_query($link, "SELECT * FROM tb_admin where email = '". $log ."' and senha = '" . $pass . "' and status = 'A' ");
    $l = mysqli_fetch_assoc($q);
    mysqli_free_result($q);

    // Setando as variáveis de sessão (afim de evitar consultas repetitivas)
    $_SESSION["id"] = (int) $l["id"];
    $_SESSION["nome"] = $l["nome"];
    $_SESSION["email"] = $l["email"];
    $_SESSION['is_admin'] = true;


    // Verifica se encontrou usuario no banco
    if ($_SESSION["id"] == "") {
        $arr2 = array(
            "loginOk" => "N"
        );
    } else {
        // Recupera o setor
        $qC = mysqli_query($link, "SELECT * FROM tb_area where id = " . $l['id_setor'] . " ");
        $lC = mysqli_fetch_assoc($qC);
        mysqli_free_result($qC);

        $_SESSION["setor"] = $lC["area"];

        $arr2 = array(
            "loginOk" => "S",
        );
    }
} else {
    $arr2 = array(
		"loginOk" => "N",
        "causa" => "Já existe uma sessão ativa."
	);
}

mysqli_close($link);

array_push($arr,$arr2);

print json_encode($arr);

?>
