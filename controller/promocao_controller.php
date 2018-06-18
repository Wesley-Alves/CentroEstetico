<?php
    include_once("../model/promocao.php");
    include_once("../database/promocao_dao.php");
    include_once("../model/produto.php");
    include_once("../database/produto_dao.php");

    // Classe controladora para itens da página "Promoções".
    class PromocaoController {
        // Método para montar a linha na tabela de itens na página de administração do CMS.
        // Recebe um objeto "promoção" e retorna o html em si.
        public function montarHtml($promocao) {
            ?>
            <div class="linha" data-id="<?= $promocao->id ?>">
                <div class="coluna imagem">
                    <img src="../imagens/produtos/<?= $promocao->imagem ?>" alt="<?= $promocao->titulo ?>">
                </div>
                <div class="coluna titulo vcenter">
                    <span><?= $promocao->titulo ?></span>
                </div>
                <div class="coluna texto">
                    <p><?= $promocao->descricao ?></p>
                </div>
                <div class="coluna ativo">
                    <a href="../controller/router.php?tipo=promocao&modo=ativar&id=<?= $promocao->id ?>" class="ativar">
                        <?php if ($promocao->ativo) { ?>
                            <img src="imagens/icones/habilitado.png" alt="Habilitado" title="Habilitado" data-ativo="1">
                        <?php } else { ?>
                            <img src="imagens/icones/desabilitado.png" alt="Desabilitado" title="Desabilitado" data-ativo="0">
                        <?php } ?>
                    </a>
                </div>
                <div class="coluna acoes">
                    <a href="../controller/router.php?tipo=promocao&modo=editar&id=<?= $promocao->id ?>" class="editar">
                        <img src="imagens/icones/editar.png" alt="Editar" title="Editar">
                    </a>
                    <a href="../controller/router.php?tipo=promocao&modo=excluir&id=<?= $promocao->id ?>" class="excluir" data-titulo="<?= $promocao->titulo ?>">
                        <img src="imagens/icones/excluir.png" alt="Excluir" title="Excluir">
                    </a>
                </div>
            </div>
            <?php
        }
        
        // Método para construir uma modal com o formulário de adicionar um nova nova promoção a partir de produtos já existentes.
        // Retorna o html da modal pronto para abrir com o JQuery.
        public function getModalAdicionar() {
            $produtoDao = new ProdutoDAO();
            $produtos = $produtoDao->selecionarTodos();
            ?>
            <div class="modal_form">
                <div class="body">
                    <div class="header clearfix">
                        <a href="#" class="fechar">×</a>
                        <h1 class="titulo">Adicionar Promoção</h1>
                    </div>
                    <div class="content">
                        <form action="../controller/router.php?tipo=promocao&modo=gravar" id="form_adicionar">
                            <div class="grupo _75">
                                <label for="slt_produto">Produto:</label>
                                <select name="slt_produto" id="slt_produto" required>
                                    <option label=" " hidden></option>
                                    <?php
                                        foreach ($produtos as $produto) {
                                            echo "<option value=\"{$produto->id}\">{$produto->titulo}</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="grupo _25">
                                <p class="label">Exibição:</p>
                                <label class="switch">
                                    <input type="hidden" name="chk_ativo" value="0">
                                    <input type="checkbox" name="chk_ativo" value="1" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="grupo _50">
                                <p class="label">Imagem:</p>
                                <div class="upload_imagem disabled">
                                    <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs%3D" alt="Imagem">
                                </div>
                            </div>
                            <div class="grupo _50 nopadding">
                                <div class="grupo _50">
                                    <label for="txt_preco">Preço Antigo:</label>
                                    <input type="text" name="txt_preco" id="txt_preco" disabled>
                                </div>
                                <div class="grupo _50">
                                    <label for="txt_novo_preco">Novo Preço:</label>
                                    <input type="text" name="txt_novo_preco" id="txt_novo_preco" required>
                                </div>
                                <div class="grupo">
                                    <label for="txt_descricao">Descrição:</label>
                                    <textarea name="txt_descricao" id="txt_descricao" class="pequeno" disabled></textarea>
                                </div>
                            </div>
                            <input type="submit">
                        </form>
                    </div>
                    <div class="footer clearfix">
                        <p class="erro"></p>
                        <a href="#" class="form_submit" data-form="#form_adicionar">Salvar</a>
                        <a href="#" class="fechar">Cancelar</a>
                    </div>
                </div>
            </div>
            <?php
        }
        
        // Método para construir uma modal com o formulário de atualizar uma promoção já existente.
        // Recebe o id de uma promoção pelo método GET e retorna o html da modal pronto para abrir com o JQuery.
        public function getModalEditar() {
            $id = $_GET["id"];
            $promocaoDao = new PromocaoDAO();
            $promocao = $promocaoDao->selecionar($id);
            $produtoDao = new ProdutoDAO();
            $produtos = $produtoDao->selecionarTodos();
            ?>
            <div class="modal_form">
                <div class="body">
                    <div class="header clearfix">
                        <a href="#" class="fechar">×</a>
                        <h1 class="titulo"><?= $promocao->titulo ?></h1>
                    </div>
                    <div class="content">
                        <form action="../controller/router.php?tipo=promocao&modo=atualizar" id="form_atualizar">
                            <input type="hidden" name="id" value="<?= $promocao->id ?>">
                            <div class="grupo _75">
                                <label for="slt_produto">Produto:</label>
                                <select name="slt_produto" id="slt_produto" required>
                                    <?php
                                        foreach ($produtos as $produto) {
                                            echo "<option value=\"{$produto->id}\"" . ($produto->id == $promocao->idProduto ? "selected" : "") . ">{$produto->titulo}</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="grupo _25">
                                <p class="label">Exibição:</p>
                                <label class="switch">
                                    <input type="hidden" name="chk_ativo" value="0" <?= $promocao->ativo == "0" ? "checked" : "" ?>>
                                    <input type="checkbox" name="chk_ativo" value="1" <?= $promocao->ativo == "1" ? "checked" : "" ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="grupo _50">
                                <p class="label">Imagem:</p>
                                <div class="upload_imagem disabled">
                                    <img src="../imagens/produtos/<?= $promocao->imagem ?>" alt="Imagem">
                                </div>
                            </div>
                            <div class="grupo _50 nopadding">
                                <div class="grupo _50">
                                    <label for="txt_preco">Preço Antigo:</label>
                                    <input type="text" name="txt_preco" id="txt_preco" value="<?= number_format($promocao->preco, 2, ",", ""); ?>" disabled>
                                </div>
                                <div class="grupo _50">
                                    <label for="txt_novo_preco">Novo Preço:</label>
                                    <input type="text" name="txt_novo_preco" id="txt_novo_preco" value="<?= number_format($promocao->novoPreco, 2, ",", ""); ?>" required>
                                </div>
                                <div class="grupo">
                                    <label for="txt_descricao">Descrição:</label>
                                    <textarea name="txt_descricao" id="txt_descricao" class="pequeno" disabled><?= $promocao->descricao ?></textarea>
                                </div>
                            </div>
                            <input type="submit">
                        </form>
                    </div>
                    <div class="footer clearfix">
                        <p class="erro"></p>
                        <a href="#" class="form_submit" data-form="#form_atualizar">Salvar</a>
                        <a href="#" class="fechar">Cancelar</a>
                    </div>
                </div>
            </div>
            <?php
        }
        
        // Método utilizado para recuperar as informações de um produto ao ser selecionado a partir de um objeto select do HTML.
        // Recebe o id do produto selecionado.
        // Retorna um objeto em formato JSON com a imagem, descrição e preço do produto selecionado.
        public function getInfoProduto() {
            $id = $_GET["id"];
            $produtoDao = new ProdutoDAO();
            $produto = $produtoDao->selecionar($id);
            echo(json_encode(array("imagem" => $produto->imagem, "descricao" => $produto->descricao, "preco" => number_format($produto->preco, 2, ",", ""))));
        }
        
        // Método para tratar a submissão do formulário de adicionar uma nova promoção.
        // Recebe os dados do formulário via POST e monta um objeto para ser tratado pelo DAO.
        public function gravar() {
            $promocao = new Promocao();
            $promocao->idProduto = $_POST["slt_produto"];
            $promocao->preco = str_replace(",", ".", str_replace(".", "", $_POST["txt_novo_preco"]));
            $promocao->ativo = $_POST["chk_ativo"];
            
            $promocaoDao = new PromocaoDAO();
            $id = $promocaoDao->inserir($promocao);
            $this->montarHtml($promocaoDao->selecionar($id));
        }
        
        // Método para tratar a submissão do formulário de atualizar uma promoção já existente.
        // Recebe os dados do formulário via POST e monta um objeto para ser tratado pelo DAO.
        public function atualizar() {
            $promocao = new Promocao();
            $promocao->id = $_POST["id"];
            $promocao->idProduto = $_POST["slt_produto"];
            $promocao->preco = str_replace(",", ".", str_replace(".", "", $_POST["txt_novo_preco"]));
            $promocao->ativo = $_POST["chk_ativo"];
            
            $promocaoDao = new PromocaoDAO();
            $id = $promocaoDao->atualizar($promocao);
            $this->montarHtml($promocaoDao->selecionar($promocao->id));
        }
        
        // Método usado para tratar a requisição de uma exclusão de promoção.
        // Recebe o id de uma promoção via GET.
        // Retorna o mesmo id como resposta para que a linha da promoção excluida seja removida do html, usando como referência este id.
        public function excluir() {
            $id = $_GET["id"];
            $promocaoDao = new PromocaoDAO();
            $promocaoDao->excluir($id);
            echo json_encode(array("id" => $id));
        }
        
        // Método usado para tratar a requisição de ativar e desativar uma promoção.
        // Recebe o id de uma promoção e um byte que diz se ela já está ativa ou não via GET.
        // Retorna uma mensagem para ser exibida em um modal na tela do CMS.
        public function ativar() {
            $id = $_GET["id"];
            $ativo = $_GET["ativo"];
            $promocaoDao = new PromocaoDAO();
            $promocaoDao->ativar($id, $ativo == "1" ? 0 : 1);
            if ($ativo == "1") {
                echo "Esta promoção foi desabilitada e não será mais exibida.";
            } else {
                echo "Esta promoção foi habilitada e agora será exibida.";
            }
        }
    }
?>