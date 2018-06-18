<?php
    include_once("../model/usuario.php");
    include_once("../controller/categoria_controller.php");
    include_once("../controller/subcategoria_controller.php");
    session_start();
    if (!isset($_SESSION["usuario"])) {
        // Verifica se o usuário está logado, caso não esteja redireciona para a index do site.
        header("location:../");
        exit();
    }

    $usuario = $_SESSION["usuario"];
    if (!$usuario->nivel->permProdutos) {
        // Verifica se o nível do usuário possui permissão de produtos, caso não possua redireciona para o index do CMS.
        header("location:index.php");
        exit();
    }

    $categoriaDao = new CategoriaDAO();
    $categorias = $categoriaDao->selecionarTodos(false);
    $categoriaController = new CategoriaController();
    $subcategoriaController = new SubcategoriaController();
?>

<!DOCTYPE html>
<html lang="pt">
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
            <div id="content" class="clearfix">
                <div id="categorias">
                    <div class="header">
                        <p>Categorias</p>
                        <a href="../controller/router.php?tipo=categoria&modo=adicionar" id="adicionar_categoria" class="botao">Adicionar</a>
                    </div>
                    <div class="body">
                        <?php 
                            foreach ($categorias as $categoria) {
                                $categoriaController->montarHtml($categoria);
                            }
                        ?>
                    </div>
                </div>
                <div id="subcategorias">
                    <?php $subcategoriaController->selecionar(); ?>
                </div>
            </div>
            <?php include("view/footer.php") ?>
        </div>
    </body>
</html>