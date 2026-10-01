<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

class Redirect {
    public function __construct()
    {

    }

    public function headerRedirect($pagina = null){
        
        if (isset($_SERVER['HTTP_REFERER'])) {
            $referer_url = $_SERVER['HTTP_REFERER']."?redirect=true";

            // Parse o referer para obter o host
            $referer_host = parse_url($referer_url, PHP_URL_HOST);
            $current_host = $_SERVER['HTTP_HOST'];

            // Verifique se o referer é do mesmo domínio
            if ($referer_host === $current_host) {
                header("Location: $referer_url");
                exit(); // Importante para parar a execução do script
            }
        }else{
            // Caso o referer não seja válido ou não esteja definido, redirecionar para uma página padrão
            header("Location: login.php?redirect=true");
            exit; // Importante para parar a execução do script
        }
    }

    public function headerRedirectSystem($referer_url = null){
        if (isset($_SERVER['HTTP_REFERER'])) {
            // Verifique se o referer é do mesmo domínio
            if ($referer_url !== null) {
                header("Location: ".$referer_url."?redirect=true");
                exit(); // Importante para parar a execução do script
            }else{
                $previous = "javascript:history.go(-1)";
                if(isset($_SERVER['HTTP_REFERER'])) {
                    $previous = $_SERVER['HTTP_REFERER'];
                }
                header("Location: ".$previous."?redirect=true");
                exit(); // Importante para parar a execução do script
            }
        }else{
            // Caso o referer não seja válido ou não esteja definido, redirecionar para uma página padrão
            header("Location: login.php?redirect=true");
            exit(); // Importante para parar a execução do script
        }
    }
}
?>
