<?php
    require_once("model/promocao.php");
    require_once("database/promocao_dao.php");
    $promocaoDao = new PromocaoDAO();
    $promocoes = $promocaoDao->selecionarTodos(true);
    $pagina = "promocoes";
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
                foreach ($promocoes as $promocao) { ?>
                    <section class="promocao clearfix <?= $alternate ? "alternate" : "" ?>">
                        <div class="imagem">
                            <img src="imagens/produtos/<?= $promocao->imagem ?>" alt="<?= $promocao->titulo ?>">
                            <div class="preco">
                                <span class="borda"></span>
                                <span class="valor_original">R$ <?= number_format($promocao->preco, 2, ",", ""); ?></span>
                                <span class="valor_novo">R$ <?= number_format($promocao->novoPreco, 2, ",", ""); ?></span>
                            </div>
                        </div>
                        <div class="texto">
                            <h2><?= $promocao->titulo ?></h2>
                            <p><?= $promocao->descricao ?></p>
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