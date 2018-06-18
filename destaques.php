<?php
    require_once("model/destaque.php");
    require_once("database/destaque_dao.php");
    $destaqueDao = new DestaqueDAO();
    $destaques = $destaqueDao->selecionarTodos(true);
    $pagina = "destaques";
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
    <body class="loadfix">
        <?php include("view/header.php"); ?>
        <div id="main">
            <div id="redes_sociais">
                <a href="http://www.facebook.com" target="_blank" class="rede_social facebook" title="Facebook"></a>
                <a href="http://www.instagram.com" target="_blank" class="rede_social instagram" title="Instagram"></a>
                <a href="http://www.twitter.com" target="_blank" class="rede_social twitter" title="Twitter"></a>
            </div>
            <?php 
                $alternate = false; 
                foreach ($destaques as $destaque) { ?>
                    <section class="destaque <?= $alternate ? "alternate" : "" ?>" data-parallax="scroll" data-image-src="imagens/destaques/<?= $destaque->imagem ?>" data-z-index="1" data-mirror-container="#main">
                        <div class="destaque_fundo">
                            <div class="texto">
                                <h2><?= $destaque->titulo ?></h2>
                                <p class="descricao"><?= $destaque->texto ?></p>
                            </div>
                        </div>
                    </section>
            <?php
                    $alternate = !$alternate;
                } 
            ?>
        </div>
        <?php include("view/footer.php") ?>
    </body>
</html>