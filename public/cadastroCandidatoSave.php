<?php
require_once __DIR__ . '/includes/funcoes.php';

date_default_timezone_set('America/Sao_Paulo');
$dia = date('d');
$mes = date('m');
$ano = date('Y');
$dataAgora = $dia."/".$mes."/".$ano;
error_reporting(E_ERROR | E_PARSE);

$link = abreConn();

// Verifica se não vem em branco
if ($_POST['nome'] != ""){

	$processoSeletivo = "";
	if ($_POST['cargo'] == "1"){
		$processoSeletivo = 1; // Outros
	}

	if ($_POST['cargo'] == "3"){
		$processoSeletivo = 2; // Advogado
	}

	if ($_POST['cargo'] == "5"){
		$processoSeletivo = 4; // Estagiario
    }

    $semestreEstagio = (int) $_POST['semestreEstagio'] ?: 0;
    $cidadeSelecionada = (int) $_POST['cidadeSelecionada'];
    $cargo = (int) $_POST['cargo'];

	// Insero a nova solicitação
	$sql = "insert into tb_candidato (data_cadastro,
									   trabalhe_conosco,
									   nome,
									   cpf,
									   num_oab,
									   area_atuacao,
									   formacao,
									   estagio_semestre,
									   estagio_matricula,
									   estagio_horario,
									   estagio_transp_pro,
									   estagio_part_aud,
									   cidade,
									   estado_civil,
									   conjugue,
									   filiacao,
									   dependentes,
									   rg,
									   ssp_rg,
									   titulo_eleitor,
									   reservista,
									   agencia_bradesco,
									   conta_bradesco,
									   email,
									   telefone,
									   celular,
									   endereco,
									   coord1,
									   coord2,
									   complemento,
									   nascimento,
									   cargo_id,
									   processo_atual_id,
									   linkedin,
									   cvLattes,
									   obs,
									   status) values('".$dataAgora."',
									   'N',
									   '".$_POST['nome']."',
									   '".$_POST['cpf']."',
									   '".$_POST['numOab']."',
									   '".$_POST['areaAtuacao']."',
									   '".$_POST['formacao']."',
									   '".$semestreEstagio."',
									   '".$_POST['matriculaEstagio']."',
									   '".$_POST['dipoHorarioEstagio']."',
									   '".$_POST['possuiTransporteEstagio']."',
									   '".$_POST['partAudienciaEstagio']."',
									   '".$cidadeSelecionada."',
									   '".$_POST['estadoCivil']."',
									   '".$_POST['conjugue']."',
									   '".$_POST['filiacao']."',
									   '".$_POST['dependentes']."',
									   '".$_POST['rg']."',
									   '".$_POST['expRg']."',
									   '".$_POST['tituloEleitor']."/".$_POST['tituloEleitorZona']."/".$_POST['tituloEleitorSecao']."',
									   '".$_POST['reservista']."',
									   '".$_POST['agenciaBradesco']."-".$_POST['agenciaBradescoDigito']."',
									   '".$_POST['contaBradesco']."-".$_POST['contaBradescoDigito']."',
									   '".$_POST['email']."',
									   '".$_POST['telefone']."',
									   '".$_POST['celular']."',
									   '".$_POST['endereco']."',
									   '".$_POST['coord1']."',
									   '".$_POST['coord2']."',
									   '".$_POST['complemento']."',
									   '".$_POST['nascimento']."',
									   '".$cargo."',
									   '".$processoSeletivo."',
									   '".$_POST['linkedIn']."',
									   '".$_POST['cvLattes']."',
									   '".$_POST['obs']."',
									   'A') " ;
	if (!mysqli_query($link, $sql)) {
        redirect("cadastroCandidato.php?sucesso=0");
    }

	redirect("cadastroCandidato.php?sucesso=1");

} else {
	redirect("cadastroCandidato.php?sucesso=0");
}

mysqli_close($link);
?>
