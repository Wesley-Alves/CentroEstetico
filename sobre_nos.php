<?php
    require_once("model/item_sobre_nos.php");
    require_once("database/sobre_nos_dao.php");
    $sobreNosDao = new SobreNosDAO();
    $itens = $sobreNosDao->selecionarTodos(true);
    $pagina = "sobre";
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
        <script src="js/script.js"></script>
    </head>
    <body>
        <?php include("view/header.php"); ?>
        <div id="main" class="no_overflow">
            <div id="redes_sociais">
                <a href="http://www.facebook.com" target="_blank" class="rede_social facebook" title="Facebook"></a>
                <a href="http://www.instagram.com" target="_blank" class="rede_social instagram" title="Instagram"></a>
                <a href="http://www.twitter.com" target="_blank" class="rede_social twitter" title="Twitter"></a>
            </div>
            <?php 
                $alternate = false; 
                foreach ($itens as $item) { ?>
                    <section class="sobre_nos clearfix <?= $alternate ? "alternate" : "" ?>">
                        <div class="imagem">
                            <img src="imagens/sobre_nos/<?= $item->imagem ?>" alt="<?= $item->titulo ?>">
                        </div>
                        <div class="texto">
                            <h2><?= $item->titulo ?></h2>
                            <p><?= $item->texto ?></p>
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