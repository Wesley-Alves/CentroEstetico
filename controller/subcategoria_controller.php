<?php
    include_once("../model/categoria.php");
    include_once("../model/subcategoria.php");
    include_once("../database/categoria_dao.php");
    include_once("../database/subcategoria_dao.php");
    include_once("../database/produto_dao.php");
    
    // Classe controladora para as subcategorias dos produtos.
    class SubcategoriaController {
        // Método para selecionar todas as subcategorias de uma categoria e montar uma tabela.
        // Recebe o id da categoria via GET.
        // Retorna o html com todas as suas subcategorias em formato de tabela.
        public function selecionar() {
            if (isset($_GET["id"])) {
                $categoriaId = $_GET["id"];
                $categoriaDao = new CategoriaDAO();
                $subcategoriaDao = new SubcategoriaDAO();
                $nome = $categoriaDao->selecionar($categoriaId)->nome;
                $subcategorias = $subcategoriaDao->selecionarTodos($categoriaId, false);
                ?>
                <div class="header" data-id="<?= $categoriaId ?>">
                    <p title="Subcategorias - <?= $nome ?>"><?= $nome ?></p>
                    <a href="../controller/router.php?tipo=subcategoria&modo=adicionar&categoria=<?= $categoriaId ?>" id="adicionar_subcategoria" class="botao">Adicionar</a>
                </div>
                <div class="body">
                    <?php 
                        foreach ($subcategorias as $subcategoria) {
                            $this->montarHtml($subcategoria);
                        }
                    ?>
                </div>
                <?php
            } else {
                ?>
                <div class="header">
                    <p>Subcategorias</p>
                    <a href="#" id="adicionar_subcategoria" class="botao disabled_no_load">Adicionar</a>
                </div>
                <div class="body">
                    <p class="selecione">Clique no título de uma categoria ao lado para visualizar suas subcategorias.</p>
                </div>
                <?php
            }
        }
        
        // Método para montar a linha na tabela de itens na página de administração do CMS.
        // Recebe um objeto "subcategoria" e retorna o html em si.
        public function montarHtml($subcategoria) {
            ?>
            <div class="linha" data-id="<?= $subcategoria->id ?>">
                <div class="coluna titulo">
                    <p><?= $subcategoria->nome ?></p>
                </div>
                <div class="coluna ativo">
                    <a href="../controller/router.php?tipo=subcategoria&modo=ativar&id=<?= $subcategoria->id ?>" class="ativar">
                        <?php if ($subcategoria->ativo) { ?>
                            <img src="imagens/icones/habilitado.png" alt="Habilitado" title="Habilitado" data-ativo="1">
                        <?php } else { ?>
                            <img src="imagens/icones/desabilitado.png" alt="Desabilitado" title="Desabilitado" data-ativo="0">
                        <?php } ?>
                    </a>
                </div>
                <div class="coluna acoes">
                    <a href="../controller/router.php?tipo=subcategoria&modo=editar&id=<?= $subcategoria->id ?>" class="editar">
                        <img src="imagens/icones/editar.png" alt="Editar" title="Editar">
                    </a>
                    <a href="../controller/router.php?tipo=subcategoria&modo=excluir&id=<?= $subcategoria->id ?>" class="excluir" data-titulo="<?= $subcategoria->nome ?>">
                        <img src="imagens/icones/excluir.png" alt="Excluir" title="Excluir">
                    </a>
                </div>
            </div>
            <?php
        }
        
        // Método para montar a linha na tabela de itens a partir de um id.
        // Recebe o id da subcategoria e retorna o html em si.
        public function montarHtmlId() {
            $id = $_GET["id"];
            $subcategoriaDao = new SubcategoriaDAO();
            $subcategoria = $subcategoriaDao->selecionar($id);
            $this->montarHtml($subcategoria);
        }
        
        // Método para construir formulário de adicionar uma nova subcategoria.
        // Retorna o html do formulário.
        public function montarFormAdicionar() {
            ?>
            <div class="linha adicionar">
                <div class="coluna form">
                    <form action="../controller/router.php?tipo=subcategoria&modo=gravar">
                        <input type="hidden" name="id_categoria" value="<?= $_GET["categoria"]; ?>">
                        <input type="text" name="txt_nome" maxlength="50" required>
                        <input type="hidden" name="ativo" value="1">
                        <input type="submit">
                    </form>
                </div>
                <div class="coluna ativo">
                    <a href="#" class="ativar">
                        <img src="imagens/icones/habilitado.png" alt="Habilitado" title="Habilitado" data-ativo="1">
                    </a>
                </div>
                <div class="coluna acoes">
                    <a href="#" class="salvar">
                        <img src="imagens/icones/salvar.png" alt="Salvar" title="Salvar">
                    </a>
                    <a href="#" class="cancelar">
                        <img src="imagens/icones/excluir.png" alt="Cancelar" title="Cancelar">
                    </a>
                </div>
            </div>
            <?php
        }
        
        // Método para construir formulário de editar uma subcategoria já existente.
        // Retorna o html do formulário.
        public function montarFormEditar() {
            $id = $_GET["id"];
            $subcategoriaDao = new SubcategoriaDAO();
            $subcategoria = $subcategoriaDao->selecionar($id);
            ?>
            <div class="linha atualizar">
                <div class="coluna form">
                    <form action="../controller/router.php?tipo=subcategoria&modo=atualizar">
                        <input type="hidden" name="id" value="<?= $subcategoria->id ?>">
                        <input type="text" name="txt_nome" maxlength="50" value="<?= $subcategoria->nome ?>" required>
                        <input type="hidden" name="ativo" value="<?= $subcategoria->ativo ?>">
                        <input type="submit">
                    </form>
                </div>
                <div class="coluna ativo">
                    <a href="#" class="ativar">
                        <?php if ($subcategoria->ativo) { ?>
                            <img src="imagens/icones/habilitado.png" alt="Habilitado" title="Habilitado" data-ativo="1">
                        <?php } else { ?>
                            <img src="imagens/icones/desabilitado.png" alt="Desabilitado" title="Desabilitado" data-ativo="0">
                        <?php } ?>
                    </a>
                </div>
                <div class="coluna acoes">
                    <a href="#" class="salvar">
                        <img src="imagens/icones/salvar.png" alt="Salvar" title="Salvar">
                    </a>
                    <a href="../controller/router.php?tipo=subcategoria&modo=html&id=<?= $subcategoria->id ?>" class="cancelar">
                        <img src="imagens/icones/excluir.png" alt="Cancelar" title="Cancelar">
                    </a>
                </div>
            </div>
            <?php
        }
        
        // Método para tratar a submissão do formulário de adicionar uma nova subcategoria.
        // Recebe os dados do formulário via POST e monta um objeto para ser tratado pelo DAO.
        public function gravar() {
            $subcategoria = new Subcategoria();
            $subcategoria->nome = $_POST["txt_nome"];
            $subcategoria->ativo = $_POST["ativo"];
            $subcategoria->idCategoria = $_POST["id_categoria"];
            $subcategoriaDao = new SubcategoriaDAO();
            $subcategoria->id = $subcategoriaDao->gravar($subcategoria);
            $this->montarHtml($subcategoria);
        }
        
        // Método para tratar a submissão do formulário de atualizar uma subcategoria já existente.
        // Recebe os dados do formulário via POST e monta um objeto para ser tratado pelo DAO.
        public function atualizar() {
            $subcategoria = new Subcategoria();
            $subcategoria->id = $_POST["id"];
            $subcategoria->nome = $_POST["txt_nome"];
            $subcategoria->ativo = $_POST["ativo"];
            $subcategoriaDao = new SubcategoriaDAO();
            $subcategoriaDao->atualizar($subcategoria);
            $this->montarHtml($subcategoria);
        }
        
        // Método usado para tratar a requisição de ativar e desativar uma subcategoria.
        // Recebe o id de uma subcategoria e um byte que diz se ela já está ativa ou não via GET.
        // Retorna uma mensagem para ser exibida em um modal na tela do CMS.
        public function ativar() {
            $id = $_GET["id"];
            $ativo = $_GET["ativo"];
            
            $subcategoriaDao = new SubcategoriaDAO();
            $subcategoriaDao->ativar($id, $ativo == "1" ? 0 : 1);
            if ($ativo == "1") {
                echo "Esta subcategoria foi desabilitada e agora seus produtos não serão mais exibidos.";
            } else {
                echo "Esta subcategoria foi habilitada e agora seus produtos serão exibidos.";
            }
        }
        
        // Método usado para tratar a requisição de uma exclusão de subcategoria.
        // Recebe o id de uma subcategoria via GET.
        // Retorna o mesmo id como resposta para que a linha da subcategoria excluida seja removida, usando como referência este id.
        public function excluir() {
            $id = $_GET["id"];
            $produtoDao = new ProdutoDAO();
            if ($produtoDao->checarProdutosPorSubcategoria($id)) {
                echo json_encode(array("error" => "Esta subcategoria contém algum produto cadastrado, portando não é possível exclui-la."));
            } else {
                $subcategoriaDao = new SubcategoriaDAO();
                $subcategoriaDao->excluir($id);
                echo json_encode(array("id" => $id, "type" => "subcategoria"));
            }
        }
    }
?>