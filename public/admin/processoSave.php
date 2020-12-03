<?php session_start(); ?>
<?php require_once __DIR__ . '/../includes/funcoes.php';

header('Content-Type: application/json');

date_default_timezone_set('America/Sao_Paulo');
$dia = date('d');
$mes = date('m');
$ano = date('Y');
$dataAgora = $dia."/".$mes."/".$ano;
error_reporting(E_ERROR | E_PARSE);

// JSON referente ao cadastro de Processo Seletivo
$link = abreConn();
$arr = array();

// Verifica se não vem em branco
if ($_GET['nomeProcesso'] != "" && isset($_SESSION['id'])){

	if ($_GET['idProcesso'] == "undefined" || $_GET['idProcesso'] == ""){ // Se for NOVO CADASTRO

		// Insero o novo processo
		$sql = "insert into tb_processo_seletivo (cadastrador_id,
										   cargo_id,
										   nome,
										   data_criacao,
										   data_inicio,
										   andamento,
										   obs,
										   status) values('".$_SESSION['id']."',
										   '".$_GET['idCargo']."',
										   '".$_GET['nomeProcesso']."',
										   '".$dataAgora."',
										   '".$_GET["dataInicio"]."',
										   'A',
										   '".$_GET["obs"]."',
										   'A') " ;
		mysqli_query($link, $sql);

		// Recupera ultimo id
		$qUp = mysqli_query($link, "SELECT * FROM tb_processo_seletivo where cadastrador_id = " . $_SESSION['id'] . " order by id desc limit 0,1 ");
		$lUp = mysqli_fetch_assoc($qUp);
		mysqli_free_result($qUp);

		// Atrela as perguntas ao processo recem criado
		$perguntaValor = "";
		for ($i = 1; $i < 6; $i++) {

			if ($i == 1){
				$perguntaValor = $_GET['pergunta1'];
			}
			if ($i == 2){
				$perguntaValor = $_GET['pergunta2'];
			}
			if ($i == 3){
				$perguntaValor = $_GET['pergunta3'];
			}
			if ($i == 4){
				$perguntaValor = $_GET['pergunta4'];
			}
			if ($i == 5){
				$perguntaValor = $_GET['pergunta5'];
			}

			if ($perguntaValor != ""){
				$sql1 = "insert into tb_processo_perguntas (processo_seletivo_id,
												   pergunta_id,
												   status) values('".$lUp['id']."',
												   '".$perguntaValor."',
												   'A') " ;
				mysqli_query($link, $sql1);
			}
		}


	}

	if ($_GET['idProcesso'] != ""){ // Se for EDICAO

		$sql2 = "UPDATE tb_processo_seletivo SET nome_completo = '".$_GET['nomeCompleto']."',
											apelido = '".$_GET['apelido']."',
											data_nascimento = '".$_GET['dataNascimento']."',
											cpf = '".$_GET['cpf']."',
											email = '".$_GET['email']."',
											telefone = '".$_GET['telefone']."',
											sexo = '".$_GET['sexo']."',
											endereco = '".$_GET['endereco']."',
											coord1 = '".$_GET['coord1']."',
											coord2 = '".$_GET['coord2']."',
											complemento = '".$_GET['complemento']."',
											obs = '".$_GET['obs']."'
											WHERE id ='".$_GET["idProcesso"]."' ";
		mysqli_query($link, $sql2);
	}

	// Informa via objeto JSON que o cadastro foi realizado com sucesso
	$arr2 = array(
		"cadastrou" => "S",
	);

} else {
	$arr2 = array(
		"cadastrou" => "N",
	);
}

mysqli_close($link);

array_push($arr,$arr2);

print json_encode($arr);

?>
