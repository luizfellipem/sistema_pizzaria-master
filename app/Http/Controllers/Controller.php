<?php
require 'vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

// Simulando um banco de dados com um array
$usuarios = [
    1 => ["nome" => "João", "idade" => 30],
    2 => ["nome" => "Maria", "idade" => 25]
];

// Rota PUT para atualizar um usuário
$app->put('/usuario/{id}', function (Request $request, Response $response, array $args) use (&$usuarios) {
    $id = (int)$args['id'];
    $dados = $request->getParsedBody();

    if (isset($usuarios[$id])) {
        $usuarios[$id] = array_merge($usuarios[$id], $dados);
        $response->getBody()->write(json_encode(["mensagem" => "Usuário atualizado com sucesso!", "usuario" => $usuarios[$id]]));
    } else {
        $response = $response->withStatus(404);
        $response->getBody()->write(json_encode(["erro" => "Usuário não encontrado!"]));
    }

    return $response->withHeader('Content-Type', 'application/json');
});

// Rota DELETE para deletar um usuário
$app->delete('/usuario/{id}', function (Request $request, Response $response, array $args) use (&$usuarios) {
    $id = (int)$args['id'];

    if (isset($usuarios[$id])) {
        $deletado = $usuarios[$id];
        unset($usuarios[$id]);
        $response->getBody()->write(json_encode(["mensagem" => "Usuário deletado com sucesso!", "usuario" => $deletado]));
    } else {
        $response = $response->withStatus(404);
        $response->getBody()->write(json_encode(["erro" => "Usuário não encontrado!"]));
    }

    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();

$app->delete('/usuario/{id}', function ($request, $response, $args) use (&$usuarios) {
    $id = (int)$args['id'];

    unset($usuarios[$id]);

    return $response->withJson(["mensagem" => "Usuário deletado com sucesso!"]);
});
