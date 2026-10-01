?php

declare(strict_types=1);

/**
 * SanitizeTransaction — utilitários de limpeza de sessão e memória.
 *
 * Métodos estáticos sem estado; não inicia sessão no construtor.
 * Chame session_start() no ponto de entrada da aplicação.
 *
 * @version 2.0.0
 */
final class SanitizeTransaction
{
    /**
     * Limpa todas as superglobais mutáveis da requisição atual.
     * Útil para forçar reprocessamento limpo após operação sensível.
     */
    public static function clearRequestVars(): void
    {
        $_SESSION = [];
        $_REQUEST = [];
        $_COOKIE  = [];
        $_FILES   = [];
        $_POST    = [];
        $_GET     = [];
    }

    /**
     * Destroi a sessão PHP atual.
     * Requer que session_start() tenha sido chamado antes.
     */
    public static function destroySession(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }

    /**
     * Força uma passagem do garbage collector e o desabilita.
     * Útil antes de processamentos de lote de longa duração.
     */
    public static function disableGC(): void
    {
        if (gc_enabled()) {
            gc_collect_cycles();
            gc_disable();
        }
    }

    /**
     * Reabilita o garbage collector.
     */
    public static function enableGC(): void
    {
        gc_enable();
    }
}
