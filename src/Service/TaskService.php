<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Task;

class TaskService
{
    /**
     * @var Task[]
     */
    private array $tarefas = [];

    public function adicionar(Task $tarefa): void
    {
        $this->tarefas[] = $tarefa;
    }

    /**
     * @return Task[]
     */
    public function listar(): array
    {
        return $this->tarefas;
    }

    public function buscarPorId(int $id): ?Task
    {
        foreach ($this->tarefas as $tarefa) {
            if ($tarefa->id === $id) {
                return $tarefa;
            }
        }

        return null;
    }

    public function atualizar(int $id, Task $tarefaAtualizada): bool
    {
        foreach ($this->tarefas as $indice => $tarefa) {
            if ($tarefa->id === $id) {
                $this->tarefas[$indice] = $tarefaAtualizada;

                return true;
            }
        }

        return false;
    }

    public function excluir(int $id): bool
    {
        foreach ($this->tarefas as $indice => $tarefa) {
            if ($tarefa->id === $id) {
                unset($this->tarefas[$indice]);

                return true;
            }
        }

        return false;
    }
}