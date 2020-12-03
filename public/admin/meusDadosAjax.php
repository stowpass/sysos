<?php

require_once __DIR__ . '/../includes/funcoes.php';

$metodo = $_SERVER['REQUEST_METHOD'];

if (isSessaoValida()) {
    switch ($metodo) {
        case 'POST':
            if (
                (isset($_POST['senha']) && !empty($_POST['senha'])) &&
                (isset($_POST['senha_conf']) && !empty($_POST['senha_conf']))
            ) {
                $senha = filter_var($_POST['senha'], FILTER_SANITIZE_STRING);
                $senha_conf = filter_var($_POST['senha_conf'], FILTER_SANITIZE_STRING);

                if ($senha === $senha_conf) {
                    $id = $_SESSION['id'];
                    $db = conectarBanco();
                    $hash = password_hash($senha, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("UPDATE tb_admin SET senha = ? WHERE id = $id");
                    $stmt->bindParam(1, $hash, PDO::PARAM_STR);

                    try {
                        $stmt->execute();

                        if ($stmt->rowCount()) {
                            $response = [
                                'mensagem' => 'As informações foram atualizadas com êxito.'
                            ];
                        }
                    } catch (\PDOException $e) {
                        http_response_code(500);
                        $response = [
                            'erro' => $e->getMessage()
                        ];
                    }
                } else {
                    http_response_code(400);
                    $response = [
                        'mensagem' => 'As senhas informadas não coincidem.'
                    ];
                }
            } else {
                http_response_code(400);
                $response = [
                    'mensagem' => 'Alguns campos não foram preenchidos.'
                ];
            }
        break;
    }
} else {
    http_response_code(401);
    $response = [
        'mensagem' => 'Você não está autorizado à realizar esta ação.'
    ];
}

if (!$response) {
    http_response_code(400);
    $response = [
        'erro' => 'Ocorreu um problema ao processar a solicitação.'
    ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
