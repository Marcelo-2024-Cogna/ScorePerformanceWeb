?php

declare(strict_types=1);

include_once 'modelRegras.php';

/**
 * ControllerRegras — orquestra a exibição das regras de pontuação.
 *
 * Retorna arrays de dados; não gera HTML.
 *
 * @version 2.0.0
 */
final class controllerRegras
{
    private readonly modelRegras $model;

    public function __construct()
    {
        $this->model = new modelRegras();
    }

    /**
     * Retorna os dados das regras para exibição na view.
     *
     * @return array<int, array<string, mixed>>
     */
    public function dadosRegras(): array
    {
        return $this->model->buscaDadosRegras();
    }

    /**
     * @deprecated Use dadosRegras() (camelCase)
     */
    public function DadosRegras(): array
    {
        return $this->dadosRegras();
    }
}
