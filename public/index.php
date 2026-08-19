<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controller\TaskController;
use App\Model\Task;
use App\Service\TaskService;
use App\Router\Router;

$service = new TaskService();

$service->adicionar(
    new Task(
        1,
        'Estudar PHP',
        'Aprender PHP moderno',
        false,
        3
    )
);

$service->adicionar(
    new Task(
        2,
        'Estudar Composer',
        'Aprender autoload e PSR-4',
        false,
        2
    )
);

$controller = new TaskController($service);
$router = new Router();

$router->get('/tasks', fn() => $controller->listar());



$controller->listar();