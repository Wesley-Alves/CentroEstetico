<?php
    include_once("../model/usuario.php");
    include_once("../controller/destaque_controller.php");
    session_start();
    if (!isset($_SESSION["usuario"])) {
        // Verifica se o usuário está logado, caso não esteja redireciona para a index do site.
        header("location:../");
        exit();
    }

    $usuario = $_SESSION["usuario"];
    if (!$usuario->nivel->permConteudo) {
        // Verifica se o nível do usuário possui permissão de conteúdo, caso não possua redireciona para o index do CMS.
        header("location:index.php");
        exit();
    }

    $destaqueDao = new DestaqueDAO();
    $destaques = $destaqueDao->selecionarTodos(false);
    $destaqueController = new DestaqueController();
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
                <a href="../controller/router.php?tipo=destaque&modo=adicionar" id="adicionar" class="botao">Adicionar</a>
                <div id="tabela">
                    <div id="tabela_header">
                        <p class="coluna imagem">IMAGEM</p>
                        <p class="coluna titulo">TÍTULO</p>
                        <p class="coluna texto">TEXTO</p>
                        <p class="coluna ativo">ATIVO</p>
                        <p class="coluna acoes">#</p>
                    </div>
                    <div id="tabela_body">
                        <?php
                            foreach ($destaques as $destaque) {
                                $destaqueController->montarHtml($destaque);
                            }
                        ?>
                    </div>
                </div>
            </div>
            <?php include("view/footer.php") ?>
        </div>
    </body>
</html>