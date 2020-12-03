<?php

require_once __DIR__ . '/../../bootstrap.php';

//local
function abreConn(){
	$bd = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_DATABASE']) or die("Não conseguiu conectar ao servidor! " . mysqli_error($bd));
	return $bd;
}

/**
 * Conecta ao banco de dados usando PDO
 *
 * @return PDO
 */
function conectarBanco(): \PDO {
    try {
        $db = new \PDO(
            "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_DATABASE']}",
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD']
        );
        $db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);

        return $db;
    }
    catch (\PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'erro' => $e->getMessage()
        ]);
        die();
    }
}

// Redirecionamento de p�gina
function redirect($url){
?>
	<script language="javascript">
		document.location.href = "<?=$url?>";
	</script>
<?php
	die();
}

// Retira acentos
function tirarAcentos($string){
  return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/", "/(ç)/", "/(Ç)/"),explode(" ","a A e E i I o O u U n N c C"),$string);
}

// Encurtar textos
function limitarTexto($texto, $limite){
  $contador = strlen($texto);
  if ( $contador >= $limite ) {
  	$texto = substr($texto, 0, strrpos(substr($texto, 0, $limite), ' ')) . ' ...';
  	return $texto;
  } else {
    return $texto;
  }
}

/**
 * Verifica se há uma sessão ativa e válida.
 *
 * @param $redirect Redireciona imediatamente para o recurso.
 * @param $admin    Se o módulo a ser acessado é de nível administrativo.
 */
function isSessaoValida(string $redirect = null, bool $admin = true)
{
    ativarSession();

    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
        if ($admin)
            $tabela = 'tb_admin';
        else
            $tabela = 'tb_candidato';

        $db = conectarBanco();
        $stmt = $db->prepare("SELECT COUNT(*) FROM $tabela WHERE id = ? AND email = ? AND status = 'A' LIMIT 1");
        $stmt->bindParam(1, $_SESSION['id'], PDO::PARAM_INT);
        $stmt->bindParam(2, $_SESSION['email'], PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->fetchColumn() > 0)
            return true;
    }

    desconectar($redirect, 'Você precisa estar logado para acessar esta função.');

    return false;
}

/**
 * Verifica se a sessão não está ativa, então ative-a.
 */
function ativarSession()
{
    if (session_status() !== PHP_SESSION_ACTIVE)
        session_start();
}

/**
 * Apaga a sessão ativa.
 *
 * @param $redirect
 * @param $mensagem
 */
function desconectar(string $redirect = null, string $mensagem = null)
{
    ativarSession();
    session_destroy();
    ativarSession();
    if ($redirect !== null)
        redirecionar($redirect, $mensagem);
}

/**
 * Redireciona o cliente para um resource do sistema.
 *
 * @param $endpoint
 * @param $mensagem
 */
function redirecionar(string $endpoint, string $mensagem = null)
{
    if ($mensagem)
        $erro = "?erro=$mensagem";
    else
        $erro = '';

    header("Location: {$_ENV['BASE_URL']}/$endpoint$erro", true);
    exit(0);
}
