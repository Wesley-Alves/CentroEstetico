<?php
    include_once("../model/usuario.php");
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
?>

<!DOCTYPE html>
<html lang="pt">
    <head>
        <title>CMS | Centro Estético</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <link rel="shortcut icon" type="image/png" href="../imagens/icones/favicon.png">
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <div id="main">
            <?php include("view/header.php") ?>
            <?php include("view/menu.php") ?>
            <div id="content">
                <div id="adm_conteudo">
                    <ul>
                        <li>
                            <a href="adm_produtos.php">
                                <img src="imagens/paginas/adm_produtos.png" alt="Produtos">
                                <p>Produtos</p>
                            </a>
                        </li>
                        <li>
                            <a href="adm_categorias.php">
                                <img src="imagens/paginas/adm_categorias.png" alt="Categorias e Subcategorias">
                                <p>Categorias e Subcategorias</p>
                            </a>
                        </li>
                        <li>
                            <a href="adm_estatisticas.php">
                                <img src="imagens/paginas/adm_estatisticas.png" alt="Estatistícas de Acesso">
                                <p>Estatistícas de Acesso</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <?php include("view/footer.php") ?>
        </div>
    </body>
</html>