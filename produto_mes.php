<?php
    require_once("model/produto_do_mes.php");
    require_once("database/produto_do_mes_dao.php");
    $produtoDoMesDao = new ProdutoDoMesDAO();
    $produto = $produtoDoMesDao->selecionarAtual();
    $pagina = "produto_mes";
?>

<!DOCTYPE html>
<html lang="pt">
    <head>
        <title>Centro Estético</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="theme-color" content="#132329">
        <meta name="msapplication-navbutton-color" content="#132329">
        <meta name="apple-mobile-web-app-status-bar-style" content="#132329">
        <link rel="shortcut icon" type="image/png" href="imagens/icones/favicon.png">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/parallax.js"></script>
        <script src="js/script.js"></script>
    </head>
    <body>
        <?php include("view/header.php"); ?>
        <div id="main">
            <div id="redes_sociais">
                <a href="http://www.facebook.com" target="_blank" class="rede_social facebook" title="Facebook"></a>
                <a href="http://www.instagram.com" target="_blank" class="rede_social instagram" title="Instagram"></a>
                <a href="http://www.twitter.com" target="_blank" class="rede_social twitter" title="Twitter"></a>
            </div>
            <div id="produto_mes_fundo" data-parallax="scroll" data-image-src="imagens/geral/fundo2.jpg" data-z-index="1" data-mirror-container="#main" data-bleed="-1" data-ie-fix="1">
                <?php if ($produto) {?>
                    <section class="produto_mes">
                        <h2><?= $produto->titulo ?></h2>
                        <div class="galeria_thumbnail">
                            <img src="imagens/produtos/<?= $produto->imagemPrincipal ?>" alt="<?= $produto->titulo ?>" class="imagem">
                            <div class="container_thumbails">
                                <div class="thumbnails clearfix">
                                    <a href="#" class="active"><img src="imagens/produtos/<?= $produto->imagemPrincipal ?>" alt="Thumbnail"></a>
                                    <a href="#"><img src="imagens/produto_mes/<?= $produto->fotos[0]; ?>" alt="Thumbnail"></a>
                                    <a href="#"><img src="imagens/produto_mes/<?= $produto->fotos[1]; ?>" alt="Thumbnail"></a>
                                    <a href="#"><img src="imagens/produto_mes/<?= $produto->fotos[2]; ?>" alt="Thumbnail"></a>
                                </div>
                            </div>
                            <p><?= $produto->descricao ?></p>
                            <p class="preco">R$ <?= number_format($produto->preco, 2, ",", ""); ?></p>
                        </div>
                    </section>
                <?php } ?>
            </div>
        </div>
        <?php include("view/footer.php") ?>
    </body>
</html>