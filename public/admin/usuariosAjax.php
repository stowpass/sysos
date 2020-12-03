<?php

require_once __DIR__ . '/../includes/funcoes.php';

$metodo = $_SERVER['REQUEST_METHOD'];

if (isSessaoValida()) {
    switch ($metodo) {
        case 'GET':
            $db = conectarBanco();
            $registros = $db->prepare('SELECT COUNT(*) FROM tb_admin WHERE (is_admin = 0 AND status = \'A\')');
            $registros->execute();
            $total = $registros->fetchColumn();

            $pagina = (int) $_GET['pagina'] ?: 1;
            $limite = (int) $_GET['max'] ?: 10;
            $tabela = (int) $_GET['tabela'] ?: 1;

            $offset = ($pagina - 1) * $limite;
            $start = $offset + 1;

            $selects = 'a.id, a.nome, a.email';
            $table = 'tb_admin a';
            $criteria = '';

            if (isset($_GET['nome']) && !empty($_GET['nome'])) {
                $criteria = 'WHERE MATCH(a.nome) AGAINST(? IN BOOLEAN MODE) ORDER BY MATCH(a.nome) AGAINST(? IN BOOLEAN MODE) DESC, 1 ASC AND (a.is_admin = 0 AND a.status = \'A\')';
            } else {
                $criteria = 'WHERE (a.is_admin = 0 AND a.status = \'A\')';
            }

            $stmt = $db->prepare("SELECT $selects FROM $table $criteria LIMIT $offset, $limite");

            if (isset($_GET['nome']) && !empty($_GET['nome'])) {
                $stmt->bindParam(1, $_GET['nome'], PDO::PARAM_STR);
                $stmt->bindParam(2, $_GET['nome'], PDO::PARAM_STR);
            }

            try {
                $stmt->execute();

                if (isset($_GET['nome']) && !empty($_GET['nome'])) {
                    $filtrado = $stmt->rowCount();
                } else {
                    $filtrado = $total;
                }

                $ultima = ceil($filtrado / $limite);
                $anterior = $pagina > 1 ? $pagina - 1 : null;
                $proxima = $pagina < $ultima ? $pagina + 1 : null;
                $resultados = $stmt->fetchAll();

                $response = compact(
                    'tabela',
                    'total',
                    'filtrado',
                    'anterior',
                    'proxima',
                    'ultima',
                    'limite',
                    'resultados'
                );
            } catch (\PDOException $e) {
                http_response_code(500);
                $response = [
                    'erro' => $e->getMessage()
                ];
            }
        break;

        case 'POST':
            if (
                (isset($_POST['nome']) && !empty($_POST['nome'])) &&
                (isset($_POST['email']) && !empty($_POST['email']))
            ) {
                $nome = filter_var($_POST['nome'], FILTER_SANITIZE_STRING);
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

                $db = conectarBanco();
                $stmt = $db->prepare('SELECT COUNT(*) FROM tb_admin WHERE email = ? AND status = \'A\'');
                $stmt->bindParam(1, $email);
                $stmt->execute();

                $existe = $stmt->fetchColumn();

                if (!$existe) {
                    $senha = password_hash('rms@2020', PASSWORD_DEFAULT);
                    $stmt = $db->prepare('INSERT INTO tb_admin (id_setor, nome, email, senha) VALUES (2, ?, ?, ?)');
                    $stmt->bindParam(1, $nome, PDO::PARAM_STR);
                    $stmt->bindParam(2, $email, PDO::PARAM_STR);
                    $stmt->bindParam(3, $senha, PDO::PARAM_STR);

                    try {
                        $stmt->execute();

                        if ($stmt->rowCount()) {
                            http_response_code(201);
                            $response = [
                                'mensagem' => "O cadastro de $nome foi realizado com êxito."
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
                        'mensagem' => 'Já existe um usuário associado à este e-mail.'
                    ];
                }
            } else {
                http_response_code(400);
                $response = [
                    'mensagem' => 'Alguns campos não foram preenchidos.'
                ];
            }
        break;

        case 'DELETE':
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $db = conectarBanco();

                $id = (int) filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

                if ($id !== $_SESSION['id']) {
                    $stmt = $db->prepare('UPDATE tb_admin SET status = \'E\' WHERE id = ?');
                    $stmt->bindParam(1, $id, PDO::PARAM_INT);

                    try {
                        $stmt->execute();

                        if ($stmt->rowCount()) {
                            http_response_code(204);

                            // dummy
                            $response = [
                                'status' => 'ok'
                            ];
                        } else {
                            http_response_code(400);
                            $response = [
                                'erro' => 'Erro desconhecido: Não foi possível completar a operação.'
                            ];
                        }
                    } catch (\PDOException $e) {
                        http_response_code(500);

                        $response = [
                            'erro' => $e->getMessage()
                        ];
                    }
                } else {
                    http_response_code(403);
                    $response = [
                        'mensagem' => 'Você não pode desativar seu próprio acesso.'
                    ];
                }
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
