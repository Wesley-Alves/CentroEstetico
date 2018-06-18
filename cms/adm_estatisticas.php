<?php
    include_once("../model/usuario.php");
    include_once("../database/produto_dao.php");
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

    $produtoDao = new ProdutoDAO();
    $estatisticas = $produtoDao->getEstatisticas();
    $produtos = $produtoDao->selecionarProdutosGrafico();
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
            <div id="content">
                <div id="tabs">
                    <a href="#" data-id="1" class="active">Gráfico de barras</a>
                    <a href="#" data-id="2">Gráfico de pizza</a>
                    <div id="barra"></div>
                </div>
                <div id="tab_content">
                    <div class="tab active" id="tab1">
                        <div id="grafico_barras">
                            <div id="legenda_esquerda">
                                <p id="total" title="Total de cliques"><?= $estatisticas["total"] ?></p>
                                <p id="inicio">0</p>
                            </div>
                            <ul class="colunas">
                                <?php
                                    for ($i = 0; $i < count($produtos); $i++) {
                                        $produto = $produtos[$i];
                                ?>
                                        <li style="height: <?= $produto["porcentagem"] ?>%; animation-delay: <?= 100 + (100 * $i) ?>ms; left: <?= 20 + (140 * $i) ?>px;" title="<?= $produto["titulo"] ?> - <?= $produto["cliques"] ?> cliques - <?= $produto["porcentagem"] ?>%">
                                            <?= $produto["porcentagem"] ?>%
                                        </li>
                                <?php
                                    }
                                ?>
                            </ul>
                            <div class="legenda">
                                <?php foreach ($produtos as $produto) { ?>
                                    <span><?= $produto["titulo"] ?></span>
                                <?php } ?>
                                <p id="media" title="Média de cliques por produto">Média: <?= $estatisticas["media"] ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="tab" id="tab2">
                        <div id="grafico_pizza_container">
                            <div id="grafico_pizza">
                                <div class="base"></div>
                                <?php 
                                    $totalCliques = 0;
                                    foreach ($produtos as $index => $produto) {
                                        $totalCliques += $produto["cliques"];
                                        $produtos[$index]["cor"] = str_pad(dechex(rand(0x111111, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
                                    }

                                    $anguloTotal = 0;
                                    $duracaoTotal = 0;
                                    foreach ($produtos as $produto) {
                                        $anguloProduto = 360 * ($produto["cliques"] / $totalCliques);
                                        while ($anguloProduto > 0) {
                                            $angulo = min($anguloProduto, 180);
                                            $duracao = $angulo * 3.14;
                                    ?>
                                            <div class="fatia" style="transform: rotate(<?= $anguloTotal ?>deg);">
                                                <div class="item" style="background-color: #<?= $produto["cor"] ?>; transform: rotate(<?= $angulo ?>deg); animation-duration: <?= $duracao ?>ms; animation-delay: <?= $duracaoTotal ?>ms;"></div>
                                            </div>
                                    <?php
                                            $anguloTotal += $angulo; 
                                            $duracaoTotal += $duracao;
                                            $anguloProduto -= $angulo;
                                        }
                                    } 
                                ?>
                             </div>
                            <div class="legenda">
                                <?php foreach ($produtos as $produto) { ?>
                                    <div><span style="background-color: #<?= $produto["cor"] ?>;"></span><?= $produto["titulo"] ?> - <?= $produto["cliques"] ?> cliques - <?= $produto["porcentagem"] ?>%</div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include("view/footer.php") ?>
        </div>
    </body>
</html>