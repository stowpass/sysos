<?php
require_once __DIR__ . '/includes/funcoes.php';

session_start();

header('Content-Type: application/json');
error_reporting(E_ERROR | E_PARSE);

// JSON referente ao login ao sistema
$link = abreConn();
$arr = array();

if (isSessaoValida(null, false)) {
    // Quantas vezes colou
    $q = mysqli_query($link, "SELECT * FROM tb_candidato WHERE id = '".$_SESSION["id"]."' ");
    $l = mysqli_fetch_assoc($q);
    mysqli_free_result($q);

    // Verifica se encontrou usuario no banco
    if ($l["id"] == "") {
        $arr2 = array(
            "achou" => "N",
            "causa" => "nao_achou",
        );
    } else {

        $soma = $l['texto_copiado_qntd'] + 1;

        $sql2 = "UPDATE tb_candidato SET texto_copiado_qntd = '".$soma."' WHERE id='".$_SESSION["id"]."' ";
        mysqli_query($link, $sql2);

        $arr2 = array(
            "achou" => "S",
            "id" => $l["id"],
        );
    }

    mysqli_close($link);
} else {
    $arr2 = array(
        "achou" => "N",
        "causa" => "nao_autorizado",
    );
}

array_push($arr,$arr2);

print json_encode($arr);
