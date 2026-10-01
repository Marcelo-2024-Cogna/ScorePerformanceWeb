?php

declare(strict_types=1);

include_once 'modelFaixasOrganizadas.php';

/**
 * ControllerFaixasOrganizadas — orquestra a exibição das faixas organizadas.
 *
 * Retorna arrays de dados; não gera HTML.
 *
 * @version 2.0.0
 */
final class controllerFaixasOrganizadas
{
    private readonly modelFaixasOrganizadas $model;

    public function __construct()
    {
        $this->model = new modelFaixasOrganizadas();
    }

    /**
     * Retorna todas as faixas organizadas.
     *
     * @return array<int, array<string, mixed>>
     */
    public function dadosFaixasOrganizadas(): array
    {
        return $this->model->buscaDadosFaixasOrg();
    }

    /**
     * @deprecated Use dadosFaixasOrganizadas() (camelCase)
     */
    public function DadosFaixasOrganizadas(): array
    {
        return $this->dadosFaixasOrganizadas();
    }
}
