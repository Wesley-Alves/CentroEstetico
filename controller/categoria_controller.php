<?php
    include_once("../model/categoria.php");
    include_once("../database/categoria_dao.php");
    include_once("../database/produto_dao.php");
    
    // Classe controladora para as categorias dos produtos.
    class CategoriaController {
        // Método para montar a linha na tabela de itens na página de administração do CMS.
        // Recebe um objeto "categoria" e retorna o html em si.
        public function montarHtml($categoria) {
            ?>
            <div class="linha" data-id="<?= $categoria->id ?>">
                <div class="coluna titulo">
                    <p>
                        <a href="../controller/router.php?tipo=subcategoria&modo=selecionar&id=<?= $categoria->id ?>" class="selecionar">
                            <?= $categoria->nome ?>
                        </a>
                    </p>
                </div>
                <div class="coluna ativo">
                    <a href="../controller/router.php?tipo=categoria&modo=ativar&id=<?= $categoria->id ?>" class="ativar">
                        <?php if ($categoria->ativo) { ?>
                            <img src="imagens/icones/habilitado.png" alt="Habilitado" title="Habilitado" data-ativo="1">
                        <?php } else { ?>
                            <img src="imagens/icones/desabilitado.png" alt="Desabilitado" title="Desabilitado" data-ativo="0">
                        <?php } ?>
                    </a>
                </div>
                <div class="coluna acoes">
                    <a href="../controller/router.php?tipo=categoria&modo=editar&id=<?= $categoria->id ?>" class="editar">
                        <img src="imagens/icones/editar.png" alt="Editar" title="Editar">
                    </a>
                    <a href="../controller/router.php?tipo=categoria&modo=excluir&id=<?= $categoria->id ?>" class="excluir" data-titulo="<?= $categoria->nome ?>">
                        <img src="imagens/icones/excluir.png" alt="Excluir" title="Excluir">
                    </a>
                </div>
            </div>
            <?php
        }
        
        // Método para montar a linha na tabela de itens a partir de um id.
        // Recebe o id da categoria e retorna o html em si.
        public function montarHtmlId($id) {
            $id = $_GET["id"];
            $categoriaDao = new CategoriaDAO();
            $categoria = $categoriaDao->selecionar($id);
            $this->montarHtml($categoria);
        }
        
        // Método para construir formulário de adicionar uma nova categoria.
        // Retorna o html do formulário.
        public function montarFormAdicionar() {
            ?>
            <div class="linha adicionar">
                <div class="coluna form">
                    <form action="../controller/router.php?tipo=categoria&modo=gravar">
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
        
        // Método para construir formulário de editar uma categoria já existente.
        // Retorna o html do formulário.
        public function montarFormEditar() {
            $id = $_GET["id"];
            $categoriaDao = new CategoriaDAO();
            $categoria = $categoriaDao->selecionar($id);
            ?>
            <div class="linha atualizar">
                <div class="coluna form">
                    <form action="../controller/router.php?tipo=categoria&modo=atualizar">
                        <input type="hidden" name="id" value="<?= $categoria->id ?>">
                        <input type="text" name="txt_nome" maxlength="50" value="<?= $categoria->nome ?>" required>
                        <input type="hidden" name="ativo" value="<?= $categoria->ativo ?>">
                        <input type="submit">
                    </form>
                </div>
                <div class="coluna ativo">
                    <a href="#" class="ativar">
                        <?php if ($categoria->ativo) { ?>
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
                    <a href="../controller/router.php?tipo=categoria&modo=html&id=<?= $categoria->id ?>" class="cancelar">
                        <img src="imagens/icones/excluir.png" alt="Cancelar" title="Cancelar">
                    </a>
                </div>
            </div>
            <?php
        }
        
        // Método para tratar a submissão do formulário de adicionar uma nova categoria.
        // Recebe os dados do formulário via POST e monta um objeto para ser tratado pelo DAO.
        public function gravar() {
            $categoria = new Categoria();
            $categoria->nome = $_POST["txt_nome"];
            $categoria->ativo = $_POST["ativo"];
            
            $categoriaDao = new CategoriaDAO();
            $categoria->id = $categoriaDao->gravar($categoria);
            $this->montarHtml($categoria);
        }
        
        // Método para tratar a submissão do formulário de atualizar uma categoria já existente.
        // Recebe os dados do formulário via POST e monta um objeto para ser tratado pelo DAO.
        public function atualizar() {
            $categoria = new Categoria();
            $categoria->id = $_POST["id"];
            $categoria->nome = $_POST["txt_nome"];
            $categoria->ativo = $_POST["ativo"];
            
            $categoriaDao = new CategoriaDAO();
            $categoriaDao->atualizar($categoria);
            $this->montarHtml($categoria);
        }
        
        // Método usado para tratar a requisição de ativar e desativar uma categoria.
        // Recebe o id de uma categoria e um byte que diz se ela já está ativa ou não via GET.
        // Retorna uma mensagem para ser exibida em um modal na tela do CMS.
        public function ativar() {
            $id = $_GET["id"];
            $ativo = $_GET["ativo"];
            
            $categoriaDao = new CategoriaDAO();
            $categoriaDao->ativar($id, $ativo == "1" ? 0 : 1);
            if ($ativo == "1") {
                echo "Esta categoria foi desabilitada e agora suas subcategorias e seus produtos não serão mais exibidos.";
            } else {
                echo "Esta categoria foi habilitada e agora suas subcategorias e seus produtos serão exibidos.";
            }
        }
        
        // Método usado para tratar a requisição de uma exclusão de categoria.
        // Recebe o id de uma promoção via GET.
        // Retorna o mesmo id como resposta para que a linha da categoria excluida seja removida do html, usando como referência este id.
        public function excluir() {
            $id = $_GET["id"];
            $produtoDao = new ProdutoDAO();
            if ($produtoDao->checarProdutosPorCategoria($id)) {
                echo json_encode(array("error" => "Esta categoria contém algum produto cadastrado, portando não é possível exclui-la."));
            } else {
                $categoriaDao = new CategoriaDAO();
                $categoriaDao->excluir($id);
                echo json_encode(array("id" => $id, "type" => "categoria"));
            }
        }
    }
?>