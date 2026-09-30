# Requirements Document

## Introduction

O projeto **ScorePerformanceWeb** é um painel de maturidade para times de Engenharia, desenvolvido em PHP 8.2.12 com MySQL (XAMPP). Ele registra e exibe métricas de desempenho por jornada, time, período e sprint, gerando relatórios e PDFs.

A reestruturação proposta visa modernizar o código para aproveitar os recursos do **PHP 8.2**, introduzindo namespaces, autoloading PSR-4, tipagem forte, separação real de responsabilidades (MVC limpo), injeção de dependência, configuração via variáveis de ambiente e práticas de segurança contra injeção SQL. O comportamento funcional existente deve ser preservado integralmente.

---

## Glossary

- **Application**: O sistema ScorePerformanceWeb como um todo.
- **Autoloader**: Mecanismo PSR-4 de carregamento automático de classes sem `include`/`require` manuais.
- **Controller**: Classe responsável por orquestrar a lógica de uma rota, delegando ao Model e passando dados à View.
- **DatabaseConnection**: Classe responsável por fornecer a conexão PDO com o banco de dados MySQL.
- **DotenvLoader**: Componente responsável por ler variáveis de ambiente do arquivo `.env`.
- **Model**: Classe responsável exclusivamente pelas consultas e operações no banco de dados.
- **PDO**: PHP Data Objects — interface de acesso a banco de dados utilizada pelo projeto.
- **Repository**: Classe que encapsula as queries de um domínio específico (Regras, Faixas, ScorePerformance, etc.).
- **Router**: Componente responsável por mapear requisições HTTP a Controllers.
- **Singleton**: Padrão de projeto que garante uma única instância de uma classe durante a execução.
- **Template / View**: Arquivo PHP responsável exclusivamente pela renderização HTML, sem lógica de negócio.
- **TemplateEngine**: Componente responsável por renderizar templates PHP com dados passados por variáveis.
- **Validator**: Classe responsável por validar dados de entrada (formulários, arquivos CSV).

---

## Requirements

### Requirement 1: Autoloading e Namespaces PSR-4

**User Story:** Como desenvolvedor, quero que as classes sejam carregadas automaticamente via PSR-4, para que eu não precise gerenciar manualmente arquivos `include`/`require` em cada arquivo.

#### Acceptance Criteria

1. THE **Application** SHALL organizar todas as classes PHP sob o namespace raiz `ScorePerformance`, com sub-namespaces `Controllers`, `Models`, `Config`, `Database` e `Views`.
2. THE **Autoloader** SHALL carregar automaticamente qualquer classe do namespace `ScorePerformance` a partir do diretório `src/` sem necessidade de `include` ou `require` explícito.
3. WHEN um arquivo de classe é criado, THE **Autoloader** SHALL resolver o caminho do arquivo a partir do namespace e do nome da classe seguindo a convenção PSR-4.
4. IF uma classe solicitada não for encontrada no diretório mapeado, THEN THE **Autoloader** SHALL lançar uma exceção `ClassNotFoundException` com mensagem descritiva.

---

### Requirement 2: Conexão com Banco de Dados via Singleton e Variáveis de Ambiente

**User Story:** Como desenvolvedor, quero que a conexão PDO seja instanciada uma única vez por requisição e que as credenciais sejam lidas de variáveis de ambiente, para que não haja desperdício de conexões e nenhuma credencial fique exposta no código-fonte.

#### Acceptance Criteria

1. THE **DatabaseConnection** SHALL implementar o padrão Singleton, retornando sempre a mesma instância PDO durante o ciclo de vida de uma requisição.
2. THE **DotenvLoader** SHALL ler as variáveis `DB_HOST`, `DB_NAME`, `DB_USER` e `DB_PASS` do arquivo `.env` localizado na raiz do projeto.
3. WHEN o arquivo `.env` não for encontrado ou uma variável obrigatória estiver ausente, THE **DotenvLoader** SHALL lançar uma exceção `ConfigurationException` com a lista das variáveis ausentes.
4. THE **DatabaseConnection** SHALL configurar a conexão PDO com `ERRMODE_EXCEPTION`, `FETCH_ASSOC` e `EMULATE_PREPARES => false`.
5. IF a conexão com o banco de dados falhar, THEN THE **DatabaseConnection** SHALL registrar o erro em log e lançar uma exceção `DatabaseConnectionException` em vez de imprimir a mensagem diretamente na tela.
6. THE **Application** SHALL incluir um arquivo `.env.example` com os nomes das variáveis necessárias e sem valores reais, para servir de referência.

---

### Requirement 3: Separação de Responsabilidades (MVC Limpo)

**User Story:** Como desenvolvedor, quero que Controllers não gerem HTML e que Views não contenham lógica de negócio, para que cada camada tenha uma única responsabilidade e o código seja mais testável e manutenível.

#### Acceptance Criteria

1. THE **Controller** SHALL receber a requisição HTTP, chamar o **Repository** correspondente e repassar os dados como array para o **TemplateEngine**.
2. THE **TemplateEngine** SHALL sempre usar `extract()` com escopo isolado para expor variáveis ao arquivo de template, independentemente de qualquer outra condição.
3. WHEN um **Controller** precisar exibir uma lista de dados, THE **Controller** SHALL passar os dados como array ao template, sem construir strings HTML.
4. IF um **Controller** receber parâmetros inválidos, THEN THE **Controller** SHALL passar uma mensagem de erro ao template sem lançar exceção não capturada.
5. THE **Template** SHALL conter apenas lógica de apresentação (loops `foreach`, condicionais de exibição e chamadas a funções de escape). THE **Template** SHALL NOT realizar consultas ao banco de dados nem efetuar chamadas a Controllers, sem exceção.
6. WHERE um **Controller** precisar retornar HTML de `<select>`, THE **Controller** SHALL passar o array de opções ao template, que será responsável pela renderização do elemento.

---

### Requirement 4: Tipagem Forte e Recursos do PHP 8.2

**User Story:** Como desenvolvedor, quero que o código utilize os recursos modernos do PHP 8.2 (tipagem forte, propriedades readonly, enums, named arguments), para que os erros de tipo sejam detectados em tempo de execução e o código seja mais legível.

#### Acceptance Criteria

1. THE **Application** SHALL declarar `declare(strict_types=1)` em todos os arquivos PHP de classe.
2. THE **Model** e **Repository** SHALL declarar tipos de retorno explícitos em todos os métodos públicos (`array`, `string`, `int`, `bool`, `?array`, etc.).
3. THE **Model** e **Repository** SHALL declarar tipos de parâmetro explícitos em todos os métodos públicos.
4. WHERE uma propriedade de classe não deve ser modificada após a construção, THE **Application** SHALL declará-la como `readonly`.
5. WHEN um método puder retornar nulo em caso de falha, THE **Application** SHALL usar tipo de retorno nullable (`?tipo`) em vez de retornar string de erro.
6. THE **Application** SHALL usar `match` em vez de `switch` nos locais onde o valor retornado é diretamente atribuído.

---

### Requirement 5: Repositórios e Consultas com Prepared Statements

**User Story:** Como desenvolvedor, quero que todas as consultas ao banco de dados usem prepared statements parametrizados, para que o sistema esteja protegido contra injeção SQL.

#### Acceptance Criteria

1. THE **Repository** SHALL usar `$pdo->prepare()` seguido de `execute()` com parâmetros ligados para todas as queries que recebem entrada do usuário.
2. IF uma query de stored procedure receber parâmetros do usuário, THEN THE **Repository** SHALL ligar os parâmetros via `bindParam()` ou `bindValue()` em vez de interpolação de string.
3. THE **Repository** SHALL encapsular cada tipo de consulta em um método dedicado com nome descritivo em vez de receber uma string de tipo como parâmetro.
4. WHEN uma query não retornar resultados, THE **Repository** SHALL retornar um array vazio `[]` em vez de lançar exceção.
5. IF uma exceção PDO for capturada, THEN THE **Repository** SHALL registrar o erro em log e lançar uma exceção de domínio (`RepositoryException`) em vez de retornar string de erro.

---

### Requirement 6: Validação e Sanitização de Entradas

**User Story:** Como desenvolvedor, quero que todas as entradas de formulário e de arquivo CSV sejam validadas e sanitizadas por uma classe dedicada, para que dados inválidos não cheguem ao banco de dados.

#### Acceptance Criteria

1. THE **Validator** SHALL validar cada campo de entrada com regras explícitas (tipo esperado, obrigatoriedade, formato) antes do processamento.
2. WHEN o arquivo CSV enviado pelo usuário não corresponder ao cabeçalho esperado, THE **Validator** SHALL retornar um array de erros com número de linha e descrição do problema.
3. THE **Validator** SHALL usar `filter_var()` com filtros apropriados (`FILTER_VALIDATE_INT`, `FILTER_VALIDATE_FLOAT`) para validar campos numéricos do CSV.
4. IF o campo obrigatório estiver vazio, THEN THE **Validator** SHALL incluir uma mensagem de erro descritiva para aquele campo no array de retorno.
5. THE **Application** SHALL usar `htmlspecialchars()` com `ENT_QUOTES | ENT_HTML5` ao renderizar qualquer dado proveniente do banco de dados ou de entrada do usuário nos templates.

---

### Requirement 7: Estrutura de Diretórios e Organização do Projeto

**User Story:** Como desenvolvedor, quero que o projeto siga uma estrutura de diretórios padronizada, para que seja fácil localizar qualquer arquivo e o projeto possa crescer de forma organizada.

#### Acceptance Criteria

1. THE **Application** SHALL organizar os arquivos de código-fonte sob o diretório `src/` com subdiretórios `Controllers/`, `Models/`, `Database/`, `Config/` e `Views/`.
2. THE **Application** SHALL manter os templates HTML/PHP no diretório `src/Views/templates/`.
3. THE **Application** SHALL manter os arquivos públicos (CSS, JS, imagens, PDFs) no diretório `public/assets/`.
4. THE **Application** SHALL ter um único ponto de entrada em `public/index.php` que inicializa o autoloader e o router.
5. WHEN um novo módulo funcional for adicionado, THE **Application** SHALL seguir o padrão Controller/Model/View já existente no diretório `src/`.
6. THE **Application** SHALL manter o arquivo `.env` na raiz do projeto e incluir `.env` no `.gitignore`.

---

### Requirement 8: Herança, Composição e Reutilização

**User Story:** Como desenvolvedor, quero que a hierarquia de classes use herança apenas quando existe uma relação "é um" clara, e composição nos demais casos, para que o código seja mais flexível e menos acoplado.

#### Acceptance Criteria

1. THE **Application** SHALL remover a herança de `controllerLoadDataTemplate` sobre `controllerScorePerformance`, de modo que `controllerLoadDataTemplate` não estenda mais `controllerScorePerformance`. THE **Application** SHALL injetar uma instância de `controllerScorePerformance` (ou equivalente refatorado) no construtor de `controllerLoadDataTemplate` via injeção de dependência. Estas duas obrigações são mandatórias e independentes entre si.
2. WHERE dois ou mais Controllers compartilham comportamento comum (ex.: tratamento de erro, acesso ao template engine), THE **Application** SHALL extrair esse comportamento para uma classe abstrata `BaseController`.
3. THE **BaseController** SHALL prover métodos protegidos reutilizáveis para renderização de templates e tratamento de erros.
4. WHEN um Controller for instanciado, THE **Application** SHALL injetar suas dependências (Repository, TemplateEngine) via construtor em vez de instanciá-las internamente.

---

### Requirement 9: Geração de PDF com Separação de Responsabilidades

**User Story:** Como desenvolvedor, quero que a geração de PDF seja encapsulada em uma classe de serviço dedicada, para que a lógica de impressão não fique misturada com a lógica de controle.

#### Acceptance Criteria

1. THE **Application** SHALL criar uma classe `PdfService` responsável exclusivamente pela configuração e geração de documentos PDF via TCPDF.
2. THE **PdfService** SHALL expor um método `generate(string $view, array $data): void` que recebe o nome da view e os dados, e produz o output PDF.
3. WHEN o Controller receber uma requisição de impressão, THE **Controller** SHALL delegar a geração ao **PdfService** sem conhecer detalhes da biblioteca TCPDF.
4. IF a biblioteca TCPDF não estiver disponível, THEN THE **PdfService** SHALL lançar uma exceção `PdfServiceException` com mensagem descritiva.

---

### Requirement 10: Carregamento em Lote via CSV (Batch Load)

**User Story:** Como operador, quero que o carregamento em lote via CSV seja tratado como uma operação atômica com relatório de resultado, para que eu saiba exatamente quais linhas foram processadas com sucesso e quais falharam.

#### Acceptance Criteria

1. THE **Application** SHALL processar cada linha do CSV como uma transação independente, registrando sucesso ou falha individualmente sem interromper o lote completo.
2. WHEN o processamento do lote finalizar, THE **Application** SHALL retornar um relatório com o total de linhas processadas, linhas com sucesso e linhas com falha.
3. IF uma linha do CSV falhar no banco de dados, THEN THE **Application** SHALL registrar o número da linha e a mensagem de erro no relatório, sem fazer rollback das linhas já gravadas.
4. THE **Application** SHALL limitar o tempo de execução (`set_time_limit`) apenas durante o processamento do lote, restaurando o limite padrão ao término.

---

### Requirement 11: Tratamento de Erros e Logging

**User Story:** Como desenvolvedor, quero que os erros sejam tratados de forma centralizada e registrados em log, para que problemas em produção possam ser diagnosticados sem expor mensagens de erro ao usuário final.

#### Acceptance Criteria

1. THE **Application** SHALL registrar um handler global de exceções não capturadas usando `set_exception_handler()`.
2. WHEN uma exceção não capturada ocorrer, THE **Application** SHALL registrar o stack trace em arquivo de log e exibir ao usuário apenas uma mensagem genérica de erro.
3. THE **Application** SHALL usar a função nativa `error_log()` ou uma classe de logging dedicada para registrar erros em vez de `print` ou `echo`.
4. IF o ambiente for de desenvolvimento (variável `APP_ENV=development`), THEN THE **Application** SHALL exibir mensagens de erro detalhadas. WHILE o ambiente for de produção, THE **Application** SHALL suprimir detalhes técnicos na resposta HTTP.
5. THE **Application** SHALL remover as chamadas a `ini_set('display_errors', 1)` e `error_reporting(E_ALL)` dos arquivos de classe, centralizando essa configuração no ponto de entrada.
