<?php
require_once __DIR__ . '/../includes/funcoes.php';
include_once __DIR__ . '/../variaveis.php';

session_start();

isSessaoValida('admin/');

date_default_timezone_set('America/Fortaleza');
$diaCorrente = date('d');
$mesCorrente = date('m');
$anoCorrente = date('Y');

$bd = abreConn();
?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'head.php' ?>
	<!--Custom Font-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i">
</head>

<body style="padding: 10px;" onload="self.print();">

	<?php

	$q = mysqli_query($bd, "SELECT * FROM tb_candidato where id = '". $_GET['id'] ."' and status = 'A' ");
	$l = mysqli_fetch_assoc($q);
	mysqli_free_result($q);

	// CIDADE
	$qCid = mysqli_query($bd, "SELECT * FROM cidade where id = '". $l['cidade'] ."' ");
	$lCid = mysqli_fetch_assoc($qCid);
	mysqli_free_result($qCid);

	// ESTADO
	$qUf = mysqli_query($bd, "SELECT * FROM estado where id = '". $lCid['estado'] ."' ");
	$lUf = mysqli_fetch_assoc($qUf);
	mysqli_free_result($qUf);

	// TITULO ELEITOR
	$tituloCompleto = explode("/", $l['titulo_eleitor']);
	$titulo = $tituloCompleto[0];
	$zona = $tituloCompleto[1];
	$secao = $tituloCompleto[2];

	// CARGO
	$qCg = mysqli_query($bd, "SELECT * FROM tb_cargo where id = '". $l['cargo_id'] ."' ");
	$lCg = mysqli_fetch_assoc($qCg);
	mysqli_free_result($qCg);

	// AREA
	$qAr = mysqli_query($bd, "SELECT * FROM tb_area where id = '". $lCg['id_area'] ."' ");
	$lAr = mysqli_fetch_assoc($qAr);
	mysqli_free_result($qAr);

	// Para cargo de ADVOGADO
	if ($l['area_atuacao'] == "Civil"){
		$l['area_atuacao'] = "C&iacute;vil";
	}


	?>

	<!-- DADOS CADASTRAIS -->
	<hr style="margin: 5px" />
	<h2><strong>Dados Cadastrais</strong></h2>
	<hr/>

	<h3><i class="fa fa-user-circle"></i> <?=$l['nome']?></h3>
	<br/>
	<strong>CPF</strong>: <?=$l['cpf']?> <strong>RG</strong>: <?=$l['rg']?> <?=$l['ssp_rg']?>
	<br/>
	<strong>Nascimento</strong>: <?=$l['nascimento']?>
	<br/>
	<strong>E-mail</strong>: <?=$l['email']?>
	<br/>
	<strong>Tel. Celular</strong>: <?=$l['celular']?> <strong>Tel. Residencial</strong>: <?=$l['telefone']?>
	<br/>
	<strong>Cidade</strong>: <?=$lCid['nome']?> - <?=$lUf['nome']?> (<?=$lUf['uf']?>)
	<br/>
	<strong>Endere&ccedil;o</strong>: <?=$l['endereco']?> <?=$l['complemento']?>
	<br/>
	<strong>Estado Civil</strong>: <?=$l['estado_civil']?> <strong>Conjugue</strong>: <?=$l['conjugue']?>
	<br/>
	<strong>Filia&ccedil;&atilde;o</strong>: <?=$l['filiacao']?>
	<br/>
	<strong>Dependente(s)</strong>: <?=$l['dependentes']?>
	<br/>
	<strong>T&iacute;tulo de Eleitor</strong>: <?=$titulo?> <strong>Zona</strong>: <?=$zona?> <strong>Se&ccedil;&atilde;o</strong>: <?=$secao?>
	<br/>

	<br/><br/>
	<hr style="margin: 5px" />

	<!-- DADOS PROFISSIONAIS -->
	<h2><strong>Dados Profissionais</strong></h2>
	<hr/>

	<strong>Vaga Pleiteada</strong>: <?=$lCg['cargo']?> <strong>Setor</strong>: <?=$lAr['area']?>
	<br/>

	<?php if ($l['cargo_id'] == 3){ ?>
	<strong>Forma&ccedil;&atilde;o</strong>: <?=$l['formacao']?>
	<br/>
	<strong>OAB e Data Insc.</strong>: <?=$l['num_oab']?>
	<br/>
	<strong>&Aacute;rea de Atua&ccedil;ao</strong>: <?=$l['area_atuacao']?>
	<br/>
	<?php } ?>
	<?php if ($l['cargo_id'] == 5){ ?>
	<strong>Forma&ccedil;&atilde;o</strong>: <?=$l['formacao']?> <strong>Semestre Oficial</strong>: <?=$l['estagio_semestre']?>
	<br/>
	<strong>Matr&iacute;cula</strong>: <?=$l['estagio_matricula']?>
	<br/>
	<strong>Hor&aacute;rio Pretendido</strong>: <?=$l['estagio_horario']?>
	<br/>
	<strong>Possui Transporte Particular</strong>: <?=$l['estagio_transp_pro']?>
	<br/>
	<strong>J&aacute; Participou de Audi&ecirc;ncia</strong>: <?=$l['estagio_part_aud']?>
	<br/>
	<?php } ?>
	<strong>Ag&ecirc;ncia BRADESCO</strong>: <?=$l['agencia_bradesco']?> <strong>Conta BRADESCO</strong>: <?=$l['conta_bradesco']?>
	<br/>

	<br/><br/>
	<hr style="margin: 5px" />

	<!-- DADOS INFORMACOES -->
	<h2><strong>Outras Informa&ccedil;&otilde;es</strong></h2>
	<hr/>

	<strong>Link do LINKEDIN</strong>: <a href="<?=$l['linkedin']?>" target="_Blank"><?=$l['linkedin']?></a>
	<br/><br/>
	<strong>CV Lattes</strong>: <a href="<?=$l['linkedin']?>" target="_Blank"><?=$l['cvLattes']?></a>
	<br/><br/>
	<strong>Outras Informa&ccedil;&otilde;es</strong>:
	<br/>
	 <?=$l['obs']?>

    <?php
    mysqli_close($bd);
    include 'scripts.php';
    ?>
    <script type="text/javascript" src="../js/custom.js"></script>
    <script type="text/javascript" src="../js/admin.js"></script>

</body>

</html>
