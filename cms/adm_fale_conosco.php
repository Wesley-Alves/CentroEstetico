<?php
    include_once("../model/usuario.php");
    include_once("../controller/fale_conosco_controller.php");
    session_start();
    if (!isset($_SESSION["usuario"])) {
        // Verifica se o usuário está logado, caso não esteja redireciona para a index do site.
        header("location:../");
        exit();
    }

    $usuario = $_SESSION["usuario"];
    if (!$usuario->nivel->permFaleConosco) {
        // Verifica se o nível do usuário possui permissão de fale conosco, caso não possua redireciona para o index do CMS.
        header("location:index.php");
        exit();
    }

    $faleConoscoDao = new FaleConoscoDAO();
    $itens = $faleConoscoDao->selecionarTodos();
    $faleConoscoController = new FaleConoscoController();
?>

<!DOCTYPE html>
<html lang="br">
    <head>
        <title>CMS | Centro Estético</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <link rel="shortcut icon" type="image/png" href="../imagens/icones/favicon.png">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="../js/jquery-3.2.1.min.js"></script>
        <script src="js/script.js"></script>
    </head>
    <body>
        <div id="main">
            <?php include("view/header.php") ?>
            <?php include("view/menu.php") ?>
            <div id="content">
                <div id="tabela">
                    <div id="tabela_header">
                        <p class="coluna nome">NOME</p>
                        <p class="coluna email pequeno">EMAIL</p>
                        <p class="coluna celular">CELULAR</p>
                        <p class="coluna sugestao">SUGESTÃO/CRITICA</p>
                        <p class="coluna acoes">#</p>
                    </div>
                    <div id="tabela_body">
                        <?php
                            foreach ($itens as $item) {
                                $faleConoscoController->montarHtml($item);
                            }
                        ?>
                    </div>
                </div>
            </div>
            <?php include("view/footer.php") ?>
        </div>
    </body>
</html>