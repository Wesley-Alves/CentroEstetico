<nav id="menu">
    <a href="index.php">
        <img src="imagens/paginas/adm_conteudo.png" alt="Adm. Conteúdo">
        <p>Adm. Conteúdo</p>
    </a>
    <a href="adm_fale_conosco.php">
        <img src="imagens/paginas/adm_fale_conosco.png" alt="Adm. Fale Conosco">
        <p>Adm. Fale Conosco</p>
    </a>
    <a href="index_produtos.php">
        <img src="imagens/paginas/adm_produtos.png" alt="Adm. Produtos">
        <p>Adm. Produtos</p>
    </a>
    <a href="index_usuarios.php">
        <img src="imagens/paginas/adm_usuarios.png" alt="Adm. Usuários">
        <p>Adm. Usuários</p>
    </a>
    <?php 
        // Exibe somente os dois primeiros nomes do usuário no menu, para que o nome não fique muito grande.
        $nome = explode(" ", $usuario->nome); 
    ?>
    <div id="painel_usuario">
        <p>
            <span>Bem-vindo(a)</span>
            <span id="nome"><?php echo count($nome) <= 2 ? $usuario->nome : ($nome[0] . " " . $nome[1]); ?></span>
            <a href="logout.php" id="sair">Logout</a>
        </p>
    </div>
</nav>