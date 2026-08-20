<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controller\TaskController;
use App\Model\Task;
use App\Router\Router;
use App\Service\TaskService;

$service = new TaskService();

$service->adicionar(
    new Task(
        null,
        'Estudar PHP',
        'Aprender PHP moderno',
        false,
        3
    )
);

$service->adicionar(
    new Task(
        null,
        'Estudar Composer',
        'Aprender autoload e PSR-4',
        false,
        2
    )
);

$controller = new TaskController($service);

$router = new Router();

$router->get(
    '/',
    fn() => $controller->inicio()
);

$router->get(
    '/tasks',
    fn() => $controller->listar()
);

$router->get(
    '/tasks/{id}',
    fn(string $id) => $controller->buscarPorId((int) $id)
);

$router->post(
    '/tasks',
    fn() => $controller->criar()
);

$router->put(
    '/tasks/{id}',
    fn(string $id) => $controller->atualizar((int) $id)
);

$router->delete(
    '/tasks/{id}',
    fn(string $id) => $controller->excluir((int) $id)
);

$router->dispatch();