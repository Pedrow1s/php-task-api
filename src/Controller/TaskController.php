<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Task;
use App\Service\TaskService;

class TaskController
{
    public function __construct(
        private TaskService $service
    ) {}

        public function inicio(): void
    {
        $this->responder([
            'nome' => 'PHP Task API',
            'versao' => '1.0.0',
            'status' => 'online'
        ]);
    }

    public function listar(): void
    {
        $tarefas = $this->service->listar();

        $this->responder($tarefas);
    }

    public function buscarPorId(int $id): void
    {
        $tarefa = $this->service->buscarPorId($id);

        if ($tarefa === null) {
            $this->responder(
                ['erro' => 'Tarefa não encontrada'],
                404
            );

            return;
        }

        $this->responder($tarefa);
    }

    public function criar(): void
    {
        $dados = $this->obterDados();

        if ($dados === null) {
            return;
        }

        $erro = $this->validarDados($dados);

        if ($erro !== null) {
            $this->responder(
                ['erro' => $erro],
                400
            );

            return;
        }

        $tarefa = new Task(
            null,
            $dados['titulo'],
            $dados['descricao'],
            $dados['concluida'] ?? false,
            $dados['prioridade']
        );

        $tarefa = $this->service->adicionar($tarefa);

        $this->responder($tarefa, 201);
    }

    public function atualizar(int $id): void
    {
        $dados = $this->obterDados();

        if ($dados === null) {
            return;
        }

        $erro = $this->validarDados($dados);

        if ($erro !== null) {
            $this->responder(
                ['erro' => $erro],
                400
            );

            return;
        }

        $tarefa = new Task(
            $id,
            $dados['titulo'],
            $dados['descricao'],
            $dados['concluida'],
            $dados['prioridade']
        );

        $tarefaAtualizada = $this->service->atualizar(
            $id,
            $tarefa
        );

        if ($tarefaAtualizada === null) {
            $this->responder(
                ['erro' => 'Tarefa não encontrada'],
                404
            );

            return;
        }

        $this->responder($tarefaAtualizada);
    }

    public function excluir(int $id): void
    {
        $excluiu = $this->service->excluir($id);

        if (!$excluiu) {
            $this->responder(
                ['erro' => 'Tarefa não encontrada'],
                404
            );

            return;
        }

        $this->responder([
            'mensagem' => 'Tarefa excluída com sucesso'
        ]);
    }

    private function obterDados(): ?array
    {
        $conteudo = file_get_contents('php://input');

        $dados = json_decode($conteudo, true);

        if (!is_array($dados)) {
            $this->responder(
                ['erro' => 'JSON inválido'],
                400
            );

            return null;
        }

        return $dados;
    }

    private function validarDados(array $dados): ?string
    {
        if (
            !isset($dados['titulo']) ||
            !is_string($dados['titulo']) ||
            trim($dados['titulo']) === ''
        ) {
            return 'O campo titulo é obrigatório.';
        }

        if (
            !isset($dados['descricao']) ||
            !is_string($dados['descricao']) ||
            trim($dados['descricao']) === ''
        ) {
            return 'O campo descricao é obrigatório.';
        }

        if (
            !isset($dados['prioridade']) ||
            !is_int($dados['prioridade'])
        ) {
            return 'O campo prioridade deve ser um inteiro.';
        }

        if (
            isset($dados['concluida']) &&
            !is_bool($dados['concluida'])
        ) {
            return 'O campo concluida deve ser booleano.';
        }

        return null;
    }

    private function responder(
        mixed $dados,
        int $status = 200
    ): void {
        http_response_code($status);

        header('Content-Type: application/json');

        echo json_encode(
            $dados,
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
        );
    }
}