<?php
    include_once("../model/usuario.php");
    session_start();
    if (!isset($_SESSION["usuario"])) {
        // Verifica se o usuário está logado, caso não esteja redireciona para a index do site.
        header("location:../");
        exit();
    }

    $usuario = $_SESSION["usuario"];
    if (!$usuario->nivel->permConteudo) {
        // Caso o usuário não possua permissão de conteúdo, redireciona para outra página.
        if ($usuario->nivel->permFaleConosco) {
            // Caso o usuário possua permissão de fale conosco.
            header("location:adm_fale_conosco.php");
            exit();
        } else if ($usuario->nivel->permProdutos) {
            // Caso o usuário possua permissão de produtos.
            header("location:index_produtos.php");
            exit();
        } else if ($usuario->nivel->permUsuarios) {
            // Caso o usuário possua permissão de usuários.
            header("location:index_usuarios.php");
            exit();
        } else {
            // O usuário não possui nenhuma permissão
            header("location:../");
            exit();
        }
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
                            <a href="adm_destaques.php">
                                <img src="imagens/paginas/destaques.png" alt="Destaques">
                                <p>Destaques</p>
                            </a>
                        </li>
                        <li>
                            <a href="adm_sobre_nos.php">
                                <img src="imagens/paginas/sobre_nos.png" alt="Sobre Nós">
                                <p>Sobre Nós</p>
                            </a>
                        </li>
                        <li>
                            <a href="adm_promocoes.php">
                                <img src="imagens/paginas/promocoes.png" alt="Promoções">
                                <p>Promoções</p>
                            </a>
                        </li>
                        <li>
                            <a href="adm_lojas.php">
                                <img src="imagens/paginas/lojas.png" alt="Lojas">
                                <p>Lojas</p>
                            </a>
                        </li>
                        <li>
                            <a href="adm_produto_do_mes.php">
                                <img src="imagens/paginas/produto_do_mes.png" alt="Produto do Mês">
                                <p>Produto do Mês</p>
                            </a>
                        </li>
                        <li>
                            <a href="adm_slider.php">
                                <img src="imagens/paginas/inicio.png" alt="Slider">
                                <p>Slider</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <?php include("view/footer.php") ?>
        </div>
    </body>
</html>