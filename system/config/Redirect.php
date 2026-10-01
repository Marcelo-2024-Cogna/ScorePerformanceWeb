?php

declare(strict_types=1);

/**
 * Redirect — utilitário de redirecionamento HTTP seguro.
 *
 * Valida o referer antes de redirecionar para evitar open-redirect.
 *
 * @version 2.0.0
 */
final class Redirect
{
    /**
     * Redireciona para o referer do mesmo domínio ou para a página padrão.
     */
    public static function toReferer(string $fallback = 'login.php'): never
    {
        $target = self::resolveReferer($fallback);
        header('Location: ' . $target);
        exit;
    }

    /**
     * Redireciona para uma URL específica dentro do mesmo domínio.
     */
    public static function to(string $url): never
    {
        $target = !empty($url) ? $url . '?redirect=true' : self::resolveReferer('login.php');
        header('Location: ' . $target);
        exit;
    }

    // -------------------------------------------------------------------------
    // Privado
    // -------------------------------------------------------------------------

    private static function resolveReferer(string $fallback): string
    {
        if (!isset($_SERVER['HTTP_REFERER'])) {
            return $fallback . '?redirect=true';
        }

        $refererUrl  = $_SERVER['HTTP_REFERER'] . '?redirect=true';
        $refererHost = parse_url($refererUrl, PHP_URL_HOST);
        $currentHost = $_SERVER['HTTP_HOST'] ?? '';

        return ($refererHost === $currentHost) ? $refererUrl : $fallback . '?redirect=true';
    }
}
