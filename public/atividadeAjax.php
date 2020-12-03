<?php

require_once './includes/funcoes.php';

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo === 'GET') {
    if (isSessaoValida(null, false)) {
        $response = [
            'nome' => $_SESSION['nome'],
            'email' => $_SESSION['email'],
            'hora_atual' => (new DateTime)->format('Y-m-d H:i:s')
        ];
    } else {
        http_response_code(400);
        $response = [
            'mensagem' => 'ATENÇÃO: você precisa fazer login'
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
