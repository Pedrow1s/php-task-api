<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Task;

class TaskService
{
    private array $tarefas = [];

    private int $proximoId = 1;

    public function adicionar(Task $tarefa): Task
    {
        $tarefa->id = $this->proximoId++;

        $this->tarefas[] = $tarefa;

        return $tarefa;
    }

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

    public function atualizar(int $id, Task $tarefaAtualizada): ?Task
    {
        foreach ($this->tarefas as $indice => $tarefa) {
            if ($tarefa->id === $id) {
                $tarefaAtualizada->id = $id;

                $this->tarefas[$indice] = $tarefaAtualizada;

                return $tarefaAtualizada;
            }
        }

        return null;
    }

    public function excluir(int $id): bool
    {
        foreach ($this->tarefas as $indice => $tarefa) {
            if ($tarefa->id === $id) {
                unset($this->tarefas[$indice]);

                $this->tarefas = array_values($this->tarefas);

                return true;
            }
        }

        return false;
    }
}