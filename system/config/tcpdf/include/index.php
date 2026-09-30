<?php
// Registrar o acesso no log
$file = __DIR__ . '/acessos_bloqueados.log';
$data = date('Y-m-d H:i:s') . " - IP: " . $_SERVER['REMOTE_ADDR'] . " tentou acessar " . $_SERVER['REQUEST_URI'] . PHP_EOL;
file_put_contents($file, $data, FILE_APPEND);

// Exibir mensagem ao usuário
http_response_code(403);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Bloqueado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        h1 {
            color: #ff6f61;
        }
        p {
            color: #555;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Acesso Bloqueado</h1>
    <p>Infelizmente, você não tem permissão para acessar esta área.</p>
    <p>Data e hora: <strong><?php echo date('d/m/Y H:i:s'); ?></strong></p>
    <p>Se acredita que isso é um erro, <a href="mailto:TeamDeliverys@kroton.onmicrosoft.com>">entre em contato com o time responsável</a>.</p>
</body>
</html>
