<?php
    include_once("../model/produto.php");
    include_once("../model/categoria.php");
    include_once("../model/subcategoria.php");
    include_once("../database/produto_dao.php");
    include_once("../database/categoria_dao.php");
    include_once("../database/subcategoria_dao.php");
    
    // Classe controladora para os produtos da página "Home".
    class ProdutoController {
        // Método para montar a linha na tabela de itens na página de administração do CMS.
        // Recebe um objeto "produto" e retorna o html em si.
        public function montarHtml($produto) {
            ?>
            <div class="linha" data-id="<?= $produto->id ?>">
                <div class="coluna imagem">
                    <img src="../imagens/produtos/<?= $produto->imagem ?>" alt="<?= $produto->titulo ?>">
                </div>
                <div class="coluna titulo vcenter">
                    <span><?= $produto->titulo ?></span>
                </div>
                <div class="coluna texto">
                    <p><?= $produto->descricao ?></p>
                </div>
                <div class="coluna ativo">
                    <a href="../controller/router.php?tipo=produto&modo=ativar&id=<?= $produto->id ?>" class="ativar">
                        <?php if ($produto->ativo) { ?>
                            <img src="imagens/icones/habilitado.png" alt="Habilitado" title="Habilitado" data-ativo="1">
                        <?php } else { ?>
                            <img src="imagens/icones/desabilitado.png" alt="Desabilitado" title="Desabilitado" data-ativo="0">
                        <?php } ?>
                    </a>
                </div>
                <div class="coluna acoes">
                    <a href="../controller/router.php?tipo=produto&modo=editar&id=<?= $produto->id ?>" class="editar">
                        <img src="imagens/icones/editar.png" alt="Editar" title="Editar">
                    </a>
                    <a href="../controller/router.php?tipo=produto&modo=excluir&id=<?= $produto->id ?>" class="excluir" data-titulo="<?= $produto->titulo ?>">
                        <img src="imagens/icones/excluir.png" alt="Excluir" title="Excluir">
                    </a>
                </div>
            </div>
            <?php
        }
        
        // Método para construir uma modal com o formulário de adicionar um novo produto.
        // Retorna o html da modal pronto para abrir com o JQuery.
        public function getModalAdicionar() {
            $categoriaDao = new CategoriaDAO();
            $categorias = $categoriaDao->selecionarTodos(false);
            $subcategoriaDao = new SubcategoriaDAO();
            ?>
            <div class="modal_form">
                <div class="body">
                    <div class="header clearfix">
                        <a href="#" class="fechar">×</a>
                        <h1 class="titulo">Adicionar Produto</h1>
                    </div>
                    <div class="content">
                        <form action="../controller/router.php?tipo=produto&modo=gravar" id="form_adicionar">
                            <div class="grupo _50">
                                <label for="txt_titulo">Título:</label>
                                <input type="text" name="txt_titulo" id="txt_titulo" maxlength="100" required>
                            </div>
                            <div class="grupo _25">
                                <label for="txt_preco">Preço:</label>
                                <input type="text" name="txt_preco" id="txt_preco" required>
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
                                <div class="upload_imagem">
                                    <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs%3D" alt="Imagem">
                                    <label for="img_foto">Selecione um arquivo</label>
                                    <input type="file" name="img_foto" id="img_foto" accept="image/*" required>
                                </div>
                            </div>
                            <div class="grupo _50 nopadding">
                                <div class="grupo">
                                    <label for="slt_subcategoria">Subcategoria:</label>
                                    <select name="slt_subcategoria" required>
                                        <option label=" " hidden></option>
                                        <?php
                                            foreach ($categorias as $categoria) {
                                                $subcategorias = $subcategoriaDao->selecionarTodos($categoria->id, true);
                                        ?>
                                                <optgroup label="<?= $categoria->nome ?>">
                                                    <?php foreach ($subcategorias as $subcategoria) { ?>
                                                        <option value="<?= $subcategoria->id ?>"><?= $subcategoria->nome ?></option>
                                                    <?php } ?>
                                                </optgroup>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="grupo">
                                    <label for="txt_descricao">Descrição:</label>
                                    <textarea name="txt_descricao" id="txt_descricao" class="pequeno" required></textarea>
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
        
        // Método para construir uma modal com o formulário de atualizar um produto já existente.
        // Recebe o id de um produto pelo método GET e retorna o html da modal pronto para abrir com o JQuery.
        public function getModalEditar() {
            $id = $_GET["id"];
            $produtoDao = new ProdutoDAO();
            $produto = $produtoDao->selecionar($id);
            $categoriaDao = new CategoriaDAO();
            $categorias = $categoriaDao->selecionarTodos(false);
            $subcategoriaDao = new SubcategoriaDAO();
            ?>
            <div class="modal_form">
                <div class="body">
                    <div class="header clearfix">
                        <a href="#" class="fechar">×</a>
                        <h1 class="titulo"><?= $produto->titulo ?></h1>
                    </div>
                    <div class="content">
                        <form action="../controller/router.php?tipo=produto&modo=atualizar" id="form_atualizar">
                            <input type="hidden" name="id" value="<?= $produto->id ?>">
                            <input type="hidden" name="imagem_atual" value="<?= $produto->imagem ?>">
                            <div class="grupo _50">
                                <label for="txt_titulo">Título:</label>
                                <input type="text" name="txt_titulo" id="txt_titulo" maxlength="100" required value="<?= $produto->titulo ?>">
                            </div>
                            <div class="grupo _25">
                                <label for="txt_preco">Preço:</label>
                                <input type="text" name="txt_preco" id="txt_preco" value="<?= $produto->preco ?>" required>
                            </div>
                            <div class="grupo _25">
                                <p class="label">Exibição:</p>
                                <label class="switch">
                                    <input type="hidden" name="chk_ativo" value="0" <?= $produto->ativo == "0" ? "checked" : "" ?>>
                                    <input type="checkbox" name="chk_ativo" value="1" <?= $produto->ativo == "1" ? "checked" : "" ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="grupo _50">
                                <p class="label">Imagem:</p>
                                <div class="upload_imagem">
                                    <img src="../imagens/produtos/<?= $produto->imagem ?>" alt="Imagem">
                                    <label for="img_foto">Selecione um arquivo</label>
                                    <input type="file" name="img_foto" id="img_foto" accept="image/*">
                                </div>
                            </div>
                            <div class="grupo _50 nopadding">
                                <div class="grupo">
                                    <label for="slt_subcategoria">Subcategoria:</label>
                                    <select name="slt_subcategoria" id="slt_subcategoria" required>
                                        <option label=" " hidden></option>
                                        <?php
                                            foreach ($categorias as $categoria) {
                                                $subcategorias = $subcategoriaDao->selecionarTodos($categoria->id, true);
                                        ?>
                                                <optgroup label="<?= $categoria->nome ?>">
                                                    <?php foreach ($subcategorias as $subcategoria) { ?>
                                                        <option value="<?= $subcategoria->id ?>" <?= $subcategoria->id == $produto->idSubcategoria ? "selected" : ""; ?>><?= $subcategoria->nome ?></option>
                                                    <?php } ?>
                                                </optgroup>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="grupo">
                                    <label for="txt_descricao">Descrição:</label>
                                    <textarea name="txt_descricao" id="txt_descricao" class="pequeno" required><?= $produto->descricao ?></textarea>
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
        
        // Método para tratar a submissão do formulário de adicionar um novo produto.
        // Recebe os dados do formulário via POST e tenta fazer o upload da imagem selecionada.
        // Caso o upload seja bem sucedido monta um objeto produto e manda para a classe DAO, e ao final retorna o html de uma nova linha na tabela de admnistração.
        // Caso aconteça algum erro com o upload retorna uma mensagem iniciada pelo prefixo ERRO: que é tratada em um algoritmo em JavaScript.
        public function gravar() {
            $titulo = $_POST["txt_titulo"];
            $preco = str_replace(",", ".", str_replace(".", "", $_POST["txt_preco"]));
            $ativo = $_POST["chk_ativo"];
            $descricao = $_POST["txt_descricao"];
            $idSubcategoria = $_POST["slt_subcategoria"];
            
            $arquivo = basename($_FILES["img_foto"]["name"]);
            $nomeArquivo = pathinfo($arquivo, PATHINFO_FILENAME);
            $extensao = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
            $nomeCriptografado = md5($nomeArquivo . uniqid()) . "." . $extensao;
            $caminhoArquivo = "../imagens/produtos/" . $nomeCriptografado;
            
            if ($_FILES["img_foto"]["error"]) {
                echo "ERRO:Erro ao enviar o arquivo. Código: " . $_FILES["img_foto"]["error"];
            } elseif (!getimagesize($_FILES["img_foto"]["tmp_name"])) {
                echo "ERRO:Este arquivo não é uma imagem.";
            } elseif ($extensao != "jpg" && $extensao != "png" && $extensao != "jpeg") {
                echo "ERRO:Este tipo de imagem não é suportado.";
            } elseif (!move_uploaded_file($_FILES["img_foto"]["tmp_name"], $caminhoArquivo)) {
                echo "ERRO:Erro ao enviar a imagem.";
            } else {
                $produto = new Produto();
                $produto->titulo = $titulo;
                $produto->preco = $preco;
                $produto->ativo = $ativo;
                $produto->descricao = $descricao;
                $produto->imagem = $nomeCriptografado;
                $produto->idSubcategoria = $idSubcategoria;
                
                $produtoDao = new ProdutoDAO();
                $id = $produtoDao->inserir($produto);
                $produto->id = $id;
                $this->montarHtml($produto);
            }
        }
        
        // Método para tratar a submissão do formulário de atualizar um produto já existente.
        // Recebe os dados do formulário via POST e tenta fazer o upload caso uma nova imagem tenha sido selecionada, ou apenas mantém a imagem atual.
        // Caso o upload seja bem sucedido monta um objeto produto e manda para a classe DAO, e ao final retorna o html de uma nova linha na tabela de admnistração.
        // Caso aconteça algum erro com o upload retorna uma mensagem iniciada pelo prefixo ERRO: que é tratada em um algoritmo em JavaScript.
        public function atualizar() {
            $id = $_POST["id"];
            $titulo = $_POST["txt_titulo"];
            $preco = str_replace(",", ".", str_replace(".", "", $_POST["txt_preco"]));
            $ativo = $_POST["chk_ativo"];
            $descricao = $_POST["txt_descricao"];
            $idSubcategoria = $_POST["slt_subcategoria"];
            $imagemAtual = $_POST["imagem_atual"];
            
            $produtoDao = new ProdutoDAO();
            $produto = $produtoDao->selecionar($id);
            if ($produto->produtoDoMes && $ativo != "1") {
                echo "ERRO:Você não pode desativar o atual produto do mês.";
            } else {
                if (!empty($_FILES["img_foto"]["name"])) {
                    $arquivo = basename($_FILES["img_foto"]["name"]);
                    $nomeArquivo = pathinfo($arquivo, PATHINFO_FILENAME);
                    $extensao = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
                    $nomeCriptografado = md5($nomeArquivo . uniqid()) . "." . $extensao;
                    $caminhoArquivo = "../imagens/produtos/" . $nomeCriptografado;
                    $erro = "";

                    if ($_FILES["img_foto"]["error"]) {
                        $erro = "ERRO:Erro ao enviar o arquivo. Código: " . $_FILES["img_foto"]["error"];
                    } elseif (!getimagesize($_FILES["img_foto"]["tmp_name"])) {
                        $erro = "ERRO:Este arquivo não é uma imagem.";
                    } elseif ($extensao != "jpg" && $extensao != "png" && $extensao != "jpeg") {
                        $erro = "ERRO:Este tipo de imagem não é suportado.";
                    } elseif (!move_uploaded_file($_FILES["img_foto"]["tmp_name"], $caminhoArquivo)) {
                        $erro = "ERRO:Erro ao enviar a imagem.";
                    }

                    if ($erro) {
                        echo $erro;
                        return;
                    } else {
                        $imagemAtual = $nomeCriptografado;
                    }
                }

                $produto = new Produto();
                $produto->id = $id;
                $produto->titulo = $titulo;
                $produto->preco = $preco;
                $produto->ativo = $ativo;
                $produto->descricao = $descricao;
                $produto->imagem = $imagemAtual;
                $produto->idSubcategoria = $idSubcategoria;
                $produtoDao->atualizar($produto);
                $this->montarHtml($produto);
            }
        }
        
        // Método usado para tratar a requisição de uma exclusão de produto.
        // Recebe o id de um produto via GET.
        // Retorna o mesmo id como resposta para que a linha do produto excluido seja removida do html, usando como referência este id.
        public function excluir() {
            $id = $_GET["id"];
            $produtoDao = new ProdutoDAO();
            $produtoDao->excluir($id);
            echo json_encode(array("id" => $id));
        }
        
        // Método usado para tratar a requisição de ativar e desativar um produto.
        // Recebe o id de um produto e um byte que diz se ele já está ativo ou não via GET.
        // Retorna uma mensagem para ser exibida em um modal na tela do CMS.
        public function ativar() {
            $id = $_GET["id"];
            $ativo = $_GET["ativo"];
            $produtoDao = new ProdutoDAO();
            $produto = $produtoDao->selecionar($id);
            if ($produto->produtoDoMes) {
                echo "ERRO:Você não pode desativar este produto, pois ele é o atual produto do mês.";
            } else {
                $produtoDao->ativar($id, $ativo == "1" ? 0 : 1);
                if ($ativo == "1") {
                    echo "Este produto foi desabilitado e não será mais exibido.";
                } else {
                    echo "Este produto foi habilitado e agora será exibido.";
                }
            }
        }
        
        // Método para listar os produtos de acordo com algum filtro.
        // Recebe o id de uma subcategoria ou algum filtro de busca via POST.
        // Retorna o html dos produtos já montado.
        public function listar() {
            $produtoDao = new ProdutoDAO();
            if (isset($_POST["idSubcategoria"])) {
                $produtos = $produtoDao->listarProdutos($_POST["idSubcategoria"], null);
            } else if (isset($_POST["txt_busca"])) {
                $produtos = $produtoDao->listarProdutos(null, $_POST["txt_busca"]);
            } else {
                $produtos = $produtoDao->listarProdutos(null, null);
            }
            
            if (sizeof($produtos) == 0) {
                echo "<p class=\"nenhum_produto\">Nenhum produto encontrado.</p>";
            } else {
                foreach ($produtos as $produto) { 
            ?>
                    <div class="produto">
                        <p class="titulo"><?= $produto->titulo ?></p>
                        <img src="imagens/produtos/<?= $produto->imagem ?>" alt="<?= $produto->titulo ?>">
                        <p class="descricao"><?= $produto->descricao ?></p>
                        <div class="rodape">
                            <p class="preco">R$ <?= number_format($produto->preco, 2, ",", ""); ?></p>
                            <a href="#" class="detalhes" data-id="<?= $produto->id ?>">Detalhes</a>
                        </div>
                    </div>
            <?php 
                }
            }
        }
        
        // Método para exibir os detalhes de um produto.
        // Recebe o id do produto via POST.
        // Retorna o html da modal com os detalhes do produto.
        public function mostrarDetalhes() {
            $id = $_POST["id"];
            $produtoDao = new ProdutoDAO();
            $produto = $produtoDao->mostrarDetalhes($id);
            $produtoDao->adicionarClique($id);
            ?>
            <div class="modal modal_detalhes">
                <div class="body">
                    <div class="header clearfix">
                        <a href="#" class="fechar">×</a>
                        <h1 class="titulo"><?= $produto->titulo ?></h1>
                    </div>
                    <div class="content">
                        <img src="./imagens/produtos/<?= $produto->imagem ?>">
                        <div class="informacoes">
                            <p><?= $produto->categoria ?></p>
                            <p><?= $produto->subcategoria ?></p>
                            <p>R$ <?= number_format($produto->preco, 2, ",", "") ?></p>
                        </div>
                        <p><span>Descrição</span><?= $produto->descricao ?></p>
                    </div>
                </div>
            </div>
            <?php
        }
    }
?>