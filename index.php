<?php
    require_once("model/imagem_slider.php");
    require_once("model/categoria.php");
    require_once("model/subcategoria.php");
    require_once("model/produto.php");
    require_once("database/slider_dao.php");
    require_once("database/categoria_dao.php");
    require_once("database/subcategoria_dao.php");
    require_once("database/produto_dao.php");
    $sliderDao = new SliderDAO();
    $imagens = $sliderDao->selecionarTodos(true);
    $pagina = "home";

    $categoriaDao = new CategoriaDAO();
    $categorias = $categoriaDao->selecionarTodos(true);
    $subcategoriaDao = new SubcategoriaDAO();
    $produtoDao = new ProdutoDAO();
    $produtos = $produtoDao->listarProdutos(null, null);
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
        <section id="main">
            <div id="redes_sociais">
                <a href="http://www.facebook.com" target="_blank" class="rede_social facebook" title="Facebook"></a>
                <a href="http://www.instagram.com" target="_blank" class="rede_social instagram" title="Instagram"></a>
                <a href="http://www.twitter.com" target="_blank" class="rede_social twitter" title="Twitter"></a>
            </div>
            <?php if (count($imagens) > 0) { ?>
                <div id="area_slider">
                    <div id="container_slider">
                        <ul id="slider">
                            <?php foreach ($imagens as $imagem) { ?>
                                <li>
                                    <img src="imagens/slider/<?= $imagem->imagem ?>" alt="<?= $imagem->legenda ?>">
                                    <p class="legenda"><?= $imagem->legenda ?></p>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            <?php } ?>
            <div id="area_principal" class="clearfix">
                <div id="titulo_mobile">
                    <h1>Produtos</h1>
                    <div id="botao_categorias"></div>
                </div>
                <ul id="menu_categorias">
                    <?php
                        foreach ($categorias as $categoria) {
                            $subcategorias = $subcategoriaDao->selecionarTodos($categoria->id, true);
                    ?>
                            <li>
                                <p><a href="#"><?= $categoria->nome ?></a></p>
                                <?php if (!empty($subcategorias)) { ?>
                                    <ul class="submenu_categorias">
                                        <?php foreach ($subcategorias as $subcategoria) { ?>
                                            <li><a href="#" data-id="<?= $subcategoria->id ?>"><?= $subcategoria->nome ?></a></li>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </li>
                    <?php } ?>
                </ul>
                <div id="produtos">
                    <div id="caixa_pesquisa">
                        <form action="#" id="form_pesquisa">
                            <input type="text" name="txt_busca" placeholder="Buscar por um produto">
                            <input type="submit" value="">
                        </form>
                    </div>
                    <div id="area_produtos" class="clearfix">
                        <?php foreach ($produtos as $produto) { ?>
                            <div class="produto">
                                <p class="titulo"><?= $produto->titulo ?></p>
                                <img src="imagens/produtos/<?= $produto->imagem ?>" alt="<?= $produto->titulo ?>">
                                <p class="descricao"><?= $produto->descricao ?></p>
                                <div class="rodape">
                                    <p class="preco">R$ <?= number_format($produto->preco, 2, ",", ""); ?></p>
                                    <a href="#" class="detalhes" data-id="<?= $produto->id ?>">Detalhes</a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
        <?php include("view/footer.php") ?>
    </body>
</html>