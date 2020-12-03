<?php

require_once __DIR__ . '/../includes/funcoes.php';

$metodo = $_SERVER['REQUEST_METHOD'];

$permitido = [
    'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
];

if ($metodo === 'POST') {
    if (
        (isset($_POST['nome']) && !empty($_POST['nome']))
        && (isset($_POST['email']) && !empty($_POST['email']))
        && (isset($_POST['celular']) && !empty($_POST['celular']))
        && (isset($_POST['cargo']) && !empty($_POST['cargo']))
        && (isset($_POST['formacao']) && !empty($_POST['formacao']))
        && (isset($_FILES['cv']) && !empty($_FILES['cv']))
    ) {
        switch ($_POST['cargo']) {
            case 1:
                $processoSeletivo = 1; // Analista Sistemas / Requisitos
                break;

            case 3:
                $processoSeletivo = 2; // Advogado
                break;

            case 4:
                $processoSeletivo = 5; // Outros
                break;

            case 5:
                $processoSeletivo = 4; // Estagiario
                break;
        }

        $email = mb_strtolower($_POST['email']);

        $date = new DateTime;
        $pastaCvs = __DIR__ . '/../cvs';
        $arquivo = $_FILES['cv']['name'];
        $type = $_FILES['cv']['type'];
        $size = $_FILES['cv']['size'];
        $tmpDir = $_FILES['cv']['tmp_name'];
        $ext = mb_strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
        $nome = "{$date->format('YmdHis')}.$ext";
        $local = "$pastaCvs/$nome";

        if (in_array($type, $permitido)) {
            if (round($size / 1024 / 1024 <= 2)) { // 2MB
                $i = 0;

                while (file_exists($local)) {
                    ++$i;
                    $nome = "{$date->format('YmdHis')}-{$i}.$ext";
                    $local = "$pastaCvs/$nome";
                }

                if (move_uploaded_file($tmpDir, $local)) {
                    $emailRegx = '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
                    $email = mb_strtolower($_POST['email']);

                    if (preg_match($emailRegx, $email)) {
                        $db = conectarBanco();
                        $stmt = $db->prepare('
                            INSERT INTO tb_candidato (
                                data_cadastro,
                                trabalhe_conosco,
                                arquivo_cv,
                                nome,
                                num_oab,
                                area_atuacao,
                                formacao,
                                estagio_semestre,
                                estagio_matricula,
                                estagio_horario,
                                cidade,
                                email,
                                celular,
                                cargo_id,
                                processo_atual_id
                            ) VALUES (
                                ?,
                                ?,
                                ?,
                                ?,
                                ?,
                                ?,
                                ?,
                                ?,
                                ?,
                                ?,
                                ?,
                                ?,
                                ?,
                                ?,
                                ?
                            )
                        ');

                        try {
                            $stmt->bindParam(1, $date->format('d/m/Y'), \PDO::PARAM_STR);
                            $stmt->bindValue(2, 'S', \PDO::PARAM_STR);
                            $stmt->bindParam(3, $nome, \PDO::PARAM_STR);
                            $stmt->bindParam(4, $_POST['nome'], \PDO::PARAM_STR);
                            $stmt->bindParam(5, $_POST['numOab'], \PDO::PARAM_STR);
                            $stmt->bindParam(6, $_POST['areaAtuacao'], \PDO::PARAM_STR);
                            $stmt->bindParam(7, $_POST['formacao'], \PDO::PARAM_STR);
                            $stmt->bindValue(8, $_POST['semestreEstagio'] ?: 0, \PDO::PARAM_STR);
                            $stmt->bindParam(9, $_POST['matriculaEstagio'], \PDO::PARAM_STR);
                            $stmt->bindParam(10, $_POST['dipoHorarioEstagio'], \PDO::PARAM_STR);
                            $stmt->bindValue(11, $_POST['cidadeSelecionada'] ?: 0, \PDO::PARAM_STR);
                            $stmt->bindParam(12, $email, \PDO::PARAM_STR);
                            $stmt->bindParam(13, $_POST['celular'], \PDO::PARAM_STR);
                            $stmt->bindValue(14, (int) $_POST['cargo'], \PDO::PARAM_INT);
                            $stmt->bindParam(15, $processoSeletivo, \PDO::PARAM_INT);
                            $stmt->execute();

                            $response = [
                                'mensagem' => 'Seu currículo foi enviado.'
                            ];
                        } catch (\PDOException $e) {
                            unlink($local);
                            http_response_code(500);
                            $response = [
                                'erro' => 'Ocorreu um erro ao tentar armazenar as informações.',
                                'diagnostico' => $e->getMessage()
                            ];
                        }
                    } else {
                        unlink($local);
                        http_response_code(400);
                        $response = [
                            'mensagem' => 'O email informado não é valido.'
                        ];
                    }
                } else {
                    http_response_code(500);
                    $response = [
                        'erro' => 'Ocorreu um problema ao tentar enviar o arquivo.'
                    ];
                }
            }
            else {
                http_response_code(400);
                $response = [
                    'mensagem' => 'O arquivo excede o limite permitido.'
                ];
            }
        }
        else {
            http_response_code(400);
            $response = [
                'mensagem' => 'O arquivo enviado não é permitido.'
            ];
        }
    }
    else {
        http_response_code(400);
        $response = [
            'mensagem' => 'Verifique se todos os campos foram preenchidos.'
        ];
    }
}

if (!$response) {
    http_response_code(400);
    $response = [
        'erro' => 'Ocorreu um problema ao processar a solicitação.'
    ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
