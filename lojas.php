<?php
    require_once("model/loja.php");
    require_once("database/loja_dao.php");
    $lojaDao = new LojaDAO();
    $lojas = $lojaDao->selecionarTodos(true);
    $pagina = "lojas";
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
        <script src="js/responsiveslides.min.js"></script>
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
                foreach ($lojas as $loja) { ?>
                    <section class="container_loja <?= $alternate ? "alternate" : "" ?>">
                        <div class="loja clearfix">
                            <div class="informacoes">
                                <div class="texto">
                                    <h2><?= $loja->cidade ?> - <?= $loja->uf ?></h2>
                                    <span><?= $loja->logradouro ?> Nº <?= $loja->numero ?></span>
                                    <span><?= $loja->bairro ?></span>
                                    <span>CEP <?= $loja->cep ?></span>
                                    <span class="telefone"><?= $loja->telefone ?></span>
                                </div>
                            </div>
                            <div class="container_galeria">
                                <div class="galeria">
                                    <ul class="imagens">
                                        <?php foreach ($loja->fotos as $foto) { ?>
                                            <li><img src="imagens/lojas/<?= $foto ?>" alt="Imagem"></li>
                                        <?php } ?>
                                    </ul>
                                    <div class="controles"></div>
                                </div>
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