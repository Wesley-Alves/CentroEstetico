<?php
    $pagina = "fale_conosco";
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
        <script src="js/jquery.mask.min.js"></script>
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
            <section id="fale_conosco_fundo" data-parallax="scroll" data-image-src="imagens/geral/fundo3.jpg" data-z-index="1" data-mirror-container="#main" data-ie-fix="true">
                <div class="texto">
                    <h2>Como podemos lhe ajudar ?</h2>
                    <p>Deixe sua pergunta, sugestão ou crítica...</p>
                    <p>Preencha seus dados no formulário abaixo e responderemos o mais breve possível.</p>
                </div>
            </section>
            <div id="fale_conosco">
                <form action="#" id="form_fale_conosco" class="clearfix">
                    <div class="grupo duas_colunas">
                        <label for="txt_nome">Nome<span class="required">*</span></label>
                        <input type="text" name="txt_nome" maxlength="100" required id="txt_nome">
                    </div>
                    <div class="grupo duas_colunas">
                        <label for="txt_email">Email<span class="required">*</span></label>
                        <input type="email" name="txt_email" maxlength="100" required id="txt_email">
                    </div>
                    <div class="grupo quatro_colunas">
                        <label for="txt_telefone">Telefone</label>
                        <input type="text" name="txt_telefone" maxlength="14" placeholder="(XX) XXXX-XXXX" pattern="\([0-9]{2}\) [0-9]{4}-[0-9]{4}" title="(XX) XXXX-XXXX" id="txt_telefone">
                    </div>
                    <div class="grupo quatro_colunas">
                        <label for="txt_celular">Celular<span class="required">*</span></label>
                        <input type="text" name="txt_celular" maxlength="15" placeholder="(XX) XXXXX-XXXX" required pattern="\([0-9]{2}\) [0-9]{5}-[0-9]{4}" title="(XX) XXXXX-XXXX" id="txt_celular">
                    </div>
                    <div class="grupo quatro_colunas">
                        <label for="txt_profissao">Profissão<span class="required">*</span></label>
                        <input type="text" name="txt_profissao" maxlength="80" required id="txt_profissao">
                    </div>
                    <div class="grupo quatro_colunas">
                        <label for="slt_sexo">Sexo<span class="required">*</span></label>
                        <select name="slt_sexo" id="slt_sexo">
                            <option value="M">Masculino</option>
                            <option value="F">Feminino</option>
                        </select>
                    </div>
                    <div class="grupo duas_colunas">
                        <label for="txt_home_page">Home Page</label>
                        <input type="url" name="txt_home_page" maxlength="200" id="txt_home_page">
                    </div>
                    <div class="grupo duas_colunas">
                        <label for="txt_facebook">Facebook</label>
                        <input type="url" name="txt_facebook" maxlength="200" id="txt_facebook">
                    </div>
                    <div class="grupo duas_colunas">
                        <label for="txt_produtos">Informações de Produtos</label>
                        <textarea name="txt_produtos" id="txt_produtos"></textarea>
                    </div>
                    <div class="grupo duas_colunas">
                        <label for="txt_comentarios">Sugestões / Críticas</label>
                        <textarea name="txt_comentarios" id="txt_comentarios"></textarea>
                    </div>
                    <div id="aviso"><span class="required">*</span> Campo obrigatório</div>
                    <input type="submit" value="Enviar">
                </form>
            </div>
        </div>
        <?php include("view/footer.php") ?>
    </body>
</html>