<?php
    include_once("../model/usuario.php");
    include_once("../controller/sobre_nos_controller.php");
    session_start();
    if (!isset($_SESSION["usuario"])) {
        header("location:../");
        exit();
    }

    $usuario = $_SESSION["usuario"];
    if (!$usuario->nivel->permConteudo) {
        header("location:index.php");
        exit();
    }

    $sobreNosDao = new SobreNosDAO();
    $itens = $sobreNosDao->selecionarTodos(false);
    $sobreNosController = new SobreNosController();
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
                <a href="../controller/router.php?tipo=sobre_nos&modo=adicionar" id="adicionar" class="botao">Adicionar</a>
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
                            foreach ($itens as $item) {
                                $sobreNosController->montarHtml($item);
                            }
                        ?>
                    </div>
                </div>
            </div>
            <?php include("view/footer.php") ?>
        </div>
    </body>
</html>