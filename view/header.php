<header>
    <div id="cabecalho">
        <div id="botao_menu"></div>
        <p id="logo_mobile">Centro Estético</p>
        <img src="imagens/geral/logo.png" alt="Logo" id="logo">
        <nav id="menu">
            <a href="index.php" class="<?= $pagina == "home" ? "active" : "" ?>">Home</a>
            <a href="destaques.php" class="<?= $pagina == "destaques" ? "active" : "" ?>">Destaque</a>
            <a href="sobre_nos.php" class="<?= $pagina == "sobre" ? "active" : "" ?>">Sobre Nós</a>
            <a href="promocoes.php" class="<?= $pagina == "promocoes" ? "active" : "" ?>">Promoções</a>
            <a href="lojas.php" class="<?= $pagina == "lojas" ? "active" : "" ?>">Lojas</a>
            <a href="produto_mes.php" class="<?= $pagina == "produto_mes" ? "active" : "" ?>">Produto do Mês</a>
            <a href="fale_conosco.php" class="<?= $pagina == "fale_conosco" ? "active" : "" ?>">Fale Conosco</a>
        </nav>
        <div id="login">
            <form id="form_login" action="#" method="POST">
                <div id="campos">
                    <div class="grupo">
                        <label for="nome_usuario">Usuário:</label>
                        <input type="text" id="nome_usuario" name="nome_usuario" required>
                    </div>
                    <div class="grupo">
                        <label for="senha">Senha:</label>
                        <input type="password" id="senha" name="senha" required>
                    </div>
                </div>
                <input type="submit" value="Entrar">
            </form>
        </div>
    </div>
</header>