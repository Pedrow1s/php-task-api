<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\TaskService;

class TaskController
{
    public function __construct(
        private TaskService $service
    ) {}

    public function listar(): void
    {
        $tarefas = $this->service->listar();

        header('Content-Type: application/json');

        echo json_encode($tarefas);
    }
}