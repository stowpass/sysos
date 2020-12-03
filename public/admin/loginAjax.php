<?php
require_once __DIR__ . '/../includes/funcoes.php';

if (!isSessaoValida()) {
    if (isset($_POST) && !empty($_POST)) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $senha = filter_var($_POST['senha'], FILTER_SANITIZE_STRING);

        $db = conectarBanco();
        $stmt = $db->prepare('SELECT a.id, a.nome, a.email, a.senha, b.area FROM tb_admin a LEFT JOIN tb_area b ON b.id = a.id_setor WHERE a.email = ? AND a.status = \'A\'');
        $stmt->bindParam(1, $email);
        $stmt->execute();

        $usuario = $stmt->fetch();

        if ($usuario) {
            if (password_verify($senha, $usuario->senha)) {
                $_SESSION['id'] = $usuario->id;
                $_SESSION['nome'] = $usuario->nome;
                $_SESSION['email'] = $usuario->email;
                $_SESSION['setor'] = $usuario->area;
                $_SESSION['is_admin'] = true;

                $response = [
                    'mensagem' => 'Sucesso: aguarde um momento...'
                ];
            } else {
                http_response_code(401);
                $response = [
                    'mensagem' => 'A senha informada não é válida.'
                ];
            }
        } else {
            http_response_code(404);
            $response = [
                'mensagem' => 'Este usuário não foi encontrado.'
            ];
        }
    } else {
        http_response_code(400);
        $response = [
            'erro' => 'Alguns campos não foram preenchidos.'
        ];
    }
} else {
    http_response_code(400);
    $response = [
        'erro' => 'Já existe uma sessão ativa.'
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
