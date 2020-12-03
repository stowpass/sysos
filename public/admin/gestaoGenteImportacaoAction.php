<?php
require_once __DIR__ . '/../includes/funcoes.php';

session_start();

isSessaoValida('admin/');

date_default_timezone_set('America/Sao_Paulo');
$dia = date('d');
$mes = date('m');
$ano = date('Y');
$dataAgora = $dia."/".$mes."/".$ano;
error_reporting(E_ERROR | E_PARSE);

$link = abreConn();

// Verifica se não vem em branco
if ($_FILES["arquivo"]["name"] != ""){

	// UPLOAD =============================

	$ext = strtolower(strrchr($_FILES["arquivo"]["name"],"."));
	$target_dir = "../arquivos_gente_gestao/";
	$new_file_name = $dia."-".$mes."-".$ano."-".$_FILES["arquivo"]["name"];
	$target_file = $target_dir . $new_file_name;
	$uploadOk = 1;
	$ArqFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

	// Check if file already exists
	/*if (file_exists($target_file)) {
	    //echo "Arquivo ja existente. Procure renomear seu CV. ";
	    $uploadOk = 0;
	}*/

	// Check file size
	if (round($_FILES["arquivo"]["size"] / 1024) > 2048) {
	    //echo "Tamanho maior que o permitido (2 MB). ";
	    $uploadOk = 0;
	}

	// Allow certain file formats
	if(/*$ArqFileType != "xls" && $ArqFileType != "xlsx" &&*/ $ArqFileType != "csv") {
	    //echo "Formato indevido. ";
	    $uploadOk = 0;
	}

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	    //echo "Erro ao subir arquivo. ";
	    redirect("gestaoGenteDashboard.php?sucesso=0");
	// if everything is ok, try to upload file
	} else {
	    if (move_uploaded_file($_FILES["arquivo"]["tmp_name"], $target_file)) {

	        $lendo = @fopen("../arquivos_gente_gestao/".$dia."-".$mes."-".$ano."-".$_FILES["arquivo"]["name"],"r");
			if (!$lendo) {
				echo "Erro ao abrir a URL.<br>";
				echo "Arquivo selecionado: ".$_FILES["arquivo"]["name"];
			exit;
			} else {
				$qCl = mysqli_query($link, "UPDATE tb_dashboard_gestao_gente SET status = 'E' where status = 'A' ");
				$lCl = mysqli_fetch_assoc($qCl);
			}

			$posicao = 0;
				while (!feof($lendo)) {
				$linha = fgets($lendo,256);
				$posicao++;

				$insere = "linha".$posicao."";

				/* quebramos as linhas */
				$linha = explode(";", $linha);

				$base = tirarAcentos($linha[0]);
				$equipe = tirarAcentos($linha[1]);
				$justificativa = tirarAcentos($linha[8]);

				if ($linha[0] != '' && $linha[0] != 'TOTAL' && $linha[0] != 'Base'){
					$insere="INSERT into tb_dashboard_gestao_gente (status, base, equipe, qntd_advogados, qntd_estagiarios, qntd_clt, vagas_abertas_adv, vagas_abertas_estagio, vagas_abertas_clt, justificativas, id_quem_enviou, arquivo_enviado, data_envio)
												values
											  ('A', '".$base."', '".$equipe."', '$linha[2]', '$linha[3]', '$linha[4]', '$linha[5]', '$linha[6]', '$linha[7]', '".$justificativa."', '".$_SESSION['id']."', '".$dia."-".$mes."-".$ano."-".$_FILES["arquivo"]["name"]."', '".$ano."-".$mes."-".$dia."')";
					mysqli_query($link, $insere) or die(mysql_error());
				}
			}

			/* fechamos o txt */
			fclose($lendo);

	        redirect("gestaoGenteDashboard.php?sucesso=1");

	    } else {
	        //echo "Erro ao enviar arquivo.";
	        redirect("gestaoGenteDashboard.php?sucesso=2");
	    }
	}

	// FIM UPLOAD =========================

} else {
	redirect("gestaoGenteDashboard.php?sucesso=3");
}

mysqli_close($link);
?>
