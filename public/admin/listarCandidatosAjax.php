<?php

require_once __DIR__ . '/../includes/funcoes.php';

session_start();

if (isSessaoValida()) {
    $db = conectarBanco();

    $registros = $db->prepare(
        "SELECT COUNT(*) FROM tb_candidato WHERE status != 'E' AND trabalhe_conosco != 'S'"
    );

    $registros->execute();
    $total = $registros->fetchColumn();

    $pagina = (int) $_GET['pagina'] ?? 1;
    $limite = (int) $_GET['max'] ?? 10;
    $tabela = (int) $_GET['tabela'] ?? 1;

    $offset = ($pagina - 1) * $limite;
    $start = $offset + 1;

    $selects = 'c.id, c.nome, c.celular, c.finalizado, c.aprovado, ps.nome processo_seletivo, co.cargo';
    $joins = 'LEFT JOIN tb_processo_seletivo ps ON ps.id = c.processo_atual_id LEFT JOIN tb_cargo co ON co.id = c.cargo_id';

    if (isset($_GET['nome']) && !empty($_GET['nome'])) {
        $q = "SELECT $selects FROM tb_candidato c $joins WHERE MATCH(c.nome) AGAINST(? IN BOOLEAN MODE) AND c.status != 'E' AND c.trabalhe_conosco != 'S' ORDER BY MATCH(c.nome) AGAINST(? IN BOOLEAN MODE) DESC, 1 DESC LIMIT $offset, $limite";

        $filtro = $db->prepare(
            "SELECT COUNT(*) FROM tb_candidato WHERE MATCH(nome) AGAINST(? IN BOOLEAN MODE) AND status != 'E' AND trabalhe_conosco != 'S'"
        );
    }
    else {
        $q = "SELECT $selects FROM tb_candidato c $joins WHERE c.status != 'E' AND c.trabalhe_conosco != 'S' ORDER BY 1 DESC LIMIT $offset, $limite";
    }

    $candidatos = $db->prepare($q);

    if (isset($_GET['nome']) && !empty($_GET['nome'])) {
        $candidatos->bindParam(1, $_GET['nome'], PDO::PARAM_STR);
        $candidatos->bindParam(2, $_GET['nome'], PDO::PARAM_STR);
        $filtro->bindParam(1, $_GET['nome'], PDO::PARAM_STR);
    }

    try {
        $candidatos->execute();

        if (isset($_GET['nome']) && !empty($_GET['nome'])) {
            $filtro->execute();
            $filtrado = $filtro->fetchColumn();
        } else {
            $filtrado = $total;
        }

        $ultima = ceil($total / $limite);
        $anterior = $pagina > 1 ? $pagina - 1 : null;
        $proxima = $pagina < $ultima ? $pagina + 1 : null;
        $resultados = $candidatos->fetchAll();
        $response = compact('tabela', 'total', 'filtrado', 'anterior', 'proxima', 'ultima', 'limite', 'resultados');
    } catch (\PDOException $e) {
        http_response_code(500);
        $response = [
            'erro' => $e->getMessage()
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
