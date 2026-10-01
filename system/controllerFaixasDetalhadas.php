?php

declare(strict_types=1);

include_once 'modelFaixasDetalhadas.php';

/**
 * ControllerFaixasDetalhadas — orquestra a exibição das faixas detalhadas.
 *
 * Retorna arrays de dados; não gera HTML.
 *
 * @version 2.0.0
 */
final class controllerFaixasDetalhadas
{
    private readonly modelFaixasDetalhadas $model;

    public function __construct()
    {
        $this->model = new modelFaixasDetalhadas();
    }

    /**
     * Retorna todas as faixas detalhadas.
     *
     * @return array<int, array<string, mixed>>
     */
    public function dadosFaixasDetalhadas(): array
    {
        return $this->model->buscaDadosFaixasDet();
    }

    /**
     * Retorna apenas as faixas com classificação "Atendeu Totalmente".
     *
     * @return array<int, array<string, mixed>>
     */
    public function dadosFaixasDetalhadasIdeais(): array
    {
        return $this->model->buscaDadosFaixasIdeal();
    }

    /**
     * @deprecated Use dadosFaixasDetalhadas() (camelCase)
     */
    public function DadosFaixasDetalhadas(): array
    {
        return $this->dadosFaixasDetalhadas();
    }

    /**
     * @deprecated Use dadosFaixasDetalhadasIdeais() (camelCase)
     */
    public function DadosFaixasDetalhadasIdeais(): array
    {
        return $this->dadosFaixasDetalhadasIdeais();
    }
}
