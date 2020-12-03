<?php

require_once __DIR__ . '/../includes/funcoes.php';

session_start();

$notFound = [
    'mensagem' => 'O candidato informado não existe.'
];

$metodo = $_SERVER['REQUEST_METHOD'];

if (isSessaoValida()) {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = (int) $_GET['id'];
        $db = conectarBanco();

        if ($metodo === 'GET') {
            $candidato = $db->prepare(
                'SELECT c.id,
                    c.nome,
                    c.nascimento,
                    c.email,
                    c.celular,
                    c.telefone,
                    c.endereco,
                    c.finalizado,
                    c.aprovado,
                    c.primeira_tentativa,
                    c.texto_copiado_qntd infracoes,
                    co.cargo,
                    ps.nome processo_atual
                FROM tb_candidato c
                LEFT JOIN tb_processo_seletivo ps ON ps.id = c.processo_atual_id
                LEFT JOIN tb_cargo co ON co.id = c.cargo_id
                WHERE c.id = ? AND c.status = ?'
            );

            $candidato->bindParam(1, $id, \PDO::PARAM_INT);
            $candidato->bindValue(2, 'A', \PDO::PARAM_STR);

            try {
                $candidato->execute();
                $c = $candidato->fetch();

                if ($c) {
                    $respostas = $db->prepare(
                        'SELECT r.tempo_inicio, r.tempo_envio
                        FROM tb_respostas r
                        WHERE r.id_candidato = ? AND r.status = ?
                        ORDER BY r.id DESC
                        LIMIT 1'
                    );

                    $respostas->bindParam(1, $id, \PDO::PARAM_INT);
                    $respostas->bindValue(2, 'A', \PDO::PARAM_STR);
                    $respostas->execute();
                    $r = $respostas->fetch();

                    $response = $r ? array_merge((array) $c, (array) $r) : $c;
                } else {
                    http_response_code(404);
                    $response = $notFound;
                }
            } catch (\PDOException $e) {
                http_response_code(500);

                $response = [
                    'erro' => $e->getMessage()
                ];
            }
        }
        else if ($metodo === 'POST') {
            if (isset($_GET['acao']) && !empty($_GET['acao'])) {
                if ($_GET['acao'] === 'aprovar') {
                    $aprovar = 'S';
                } else if ($_GET['acao'] === 'desaprovar') {
                    $aprovar = 'N';
                } else if ($_GET['acao'] === 'banco') {
                    $aprovar = 'B';
                }

                if ($_GET['acao'] === 'novaprova') {
                    $stmt = $db->prepare(
                        'UPDATE tb_candidato SET primeira_tentativa = ?, texto_copiado_qntd = ?, finalizado = ?, aprovado = ? WHERE id = ?'
                    );

                    $stmt->bindValue(1, '', \PDO::PARAM_STR);
                    $stmt->bindValue(2, 0, \PDO::PARAM_INT);
                    $stmt->bindValue(3, '', \PDO::PARAM_STR);
                    $stmt->bindValue(4, '', \PDO::PARAM_STR);
                    $stmt->bindParam(5, $id, \PDO::PARAM_INT);

                    try {
                        $stmt->execute();
                        if ($stmt->rowCount()) {
                            $stmt = $db->prepare(
                                'UPDATE tb_respostas SET status = ? WHERE id_candidato = ? AND status = ?'
                            );

                            $stmt->bindValue(1, 'E', \PDO::PARAM_STR);
                            $stmt->bindParam(2, $id, \PDO::PARAM_INT);
                            $stmt->bindValue(3, 'A', \PDO::PARAM_STR);

                            $stmt->execute();

                            $response = [
                                'mensagem' => 'ok',
                                'finalizado' => 'N'
                            ];
                        } else {
                            http_response_code(404);
                            $response = $notFound;
                        }
                    } catch(\PDOException $e) {
                        http_response_code(500);

                        $response = [
                            'erro' => $e->getMessage()
                        ];
                    }
                }

                if ($aprovar) {
                    $stmt = $db->prepare(
                        'UPDATE tb_candidato SET aprovado = ? WHERE id = ?'
                    );

                    $stmt->bindParam(1, $aprovar, \PDO::PARAM_STR);
                    $stmt->bindParam(2, $id, \PDO::PARAM_INT);

                    try {
                        $stmt->execute();
                        if ($stmt->rowCount()) {
                            $response = [
                                'mensagem' => 'ok',
                                'aprovado' => $aprovar
                            ];
                        } else {
                            http_response_code(404);
                            $response = $notFound;
                        }
                    } catch(\PDOException $e) {
                        http_response_code(500);

                        $response = [
                            'erro' => $e->getMessage()
                        ];
                    }
                }
            }
        }
        else if ($metodo === 'DELETE') {
            $stmt = $db->prepare(
                'UPDATE tb_candidato SET status = ? WHERE id = ?'
            );

            $stmt->bindValue(1, 'E', \PDO::PARAM_STR);
            $stmt->bindParam(2, $id, \PDO::PARAM_INT);

            try {
                $stmt->execute();
                if ($stmt->rowCount()) {
                    http_response_code(204);

                    // dummy
                    $response = [
                        'status' => 'ok'
                    ];
                } else {
                    http_response_code(404);
                    $response = $notFound;
                }
            } catch(\PDOException $e) {
                http_response_code(500);

                $response = [
                    'erro' => $e->getMessage()
                ];
            }
        }
    }
    else {
        http_response_code(400);
        $response = [
            'mensagem' => 'Informe o ID do candidato à consultar.'
        ];
    }
}
else {
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
