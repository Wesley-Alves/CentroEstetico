<?php
    include_once("../model/produto_do_mes.php");
    include_once("../database/produto_do_mes_dao.php");

    // Classe controladora para os produtos para serem ativados como produto do mês.
    class ProdutoDoMesController {
        // Método para montar a linha na tabela de itens na página de administração do CMS.
        // Recebe um objeto "produto" e retorna o html em si.
        public function montarHtml($produto) {
            ?>
            <div class="linha" data-id="<?= $produto->idProduto ?>">
                <div class="coluna imagem">
                    <img src="../imagens/produtos/<?= $produto->imagemPrincipal ?>" alt="<?= $produto->titulo ?>">
                </div>
                <div class="coluna titulo vcenter">
                    <span><?= $produto->titulo ?></span>
                </div>
                <div class="coluna texto">
                    <p><?= $produto->descricao ?></p>
                </div>
                <div class="coluna produto_do_mes">
                    <?php if ($produto->produtoDoMes) { ?>
                        <a href="#" class="desativar">
                            <img src="imagens/icones/habilitado.png" alt="Habilitado" title="Habilitado">
                        </a>
                    <?php } else { ?>
                        <a href="../controller/router.php?tipo=produto_do_mes&modo=ativar&id=<?= $produto->idProduto ?>" class="ativar_produto_do_mes">
                            <img src="imagens/icones/desabilitado.png" alt="Desabilitado" title="Desabilitado">
                        </a>
                    <?php } ?>
                </div>
            </div>
            <?php
        }
        
        // Método para construir uma modal com o formulário de ativar um produto do mês.
        // O sistema exige que mais três imagens sejam associadas a um produto além de sua imagem padrão para que ele seja ativado como produto do mês.
        // Caso o produto já tenha sido ativado como produto do mês antes, mantém as imagens já selecionadas anteriormente, mas também permite sua alteração.
        // Recebe o id do produto que está sendo ativo via GET.
        // Retorna o html da modal pronto para abrir com o JQuery.
        public function getModalAtivar() {
            $id = $_GET["id"];
            $produtoDoMesDao = new ProdutoDoMesDAO();
            $produto = $produtoDoMesDao->selecionar($id);
            ?>
            <div class="modal_form">
                <div class="body">
                    <div class="header clearfix">
                        <a href="#" class="fechar">×</a>
                        <h1 class="titulo">Ativar Produto do Mês</h1>
                    </div>
                    <div class="content">
                        <form action="../controller/router.php?tipo=produto_do_mes&modo=salvar" id="form_ativar">
                            <input type="hidden" name="id" value="<?= $produto->idProduto ?>">
                            <div class="grupo _75">
                                <label for="txt_produto">Produto:</label>
                                <input type="text" name="txt_produto" id="txt_produto" disabled value="<?= $produto->titulo ?>">
                            </div>
                            <div class="grupo _25">
                                <label for="txt_preco">Preço:</label>
                                <input type="text" name="txt_preco" id="txt_preco" disabled value="<?= $produto->preco ?>">
                            </div>
                            <div class="grupo _50">
                                <p class="label">Imagem Principal:</p>
                                <div class="upload_imagem disabled">
                                    <img src="../imagens/produtos/<?= $produto->imagemPrincipal ?>" alt="Imagem">
                                </div>
                            </div>
                            <div class="grupo _50">
                                <label for="txt_descricao">Descrição:</label>
                                <textarea name="txt_descricao" id="txt_descricao" disabled><?= $produto->descricao ?></textarea>
                            </div>
                            <div class="grupo _33">
                                <p class="label">2ª Imagem:</p>
                                <div class="upload_imagem pequeno">
                                    <?php if(isset($produto->fotos[0])) { ?>
                                        <img src="../imagens/produto_mes/<?= $produto->fotos[0] ?>" alt="Imagem">
                                        <input type="hidden" name="imagem_0" value="<?= $produto->fotos[0] ?>">
                                        <label for="img_foto_0">Selecione um arquivo</label>
                                        <input type="file" name="img_foto_0" id="img_foto_0" accept="image/*">
                                    <?php } else { ?>
                                        <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs%3D" alt="Imagem">
                                        <label for="img_foto_0">Selecione um arquivo</label>
                                        <input type="file" name="img_foto_0" id="img_foto_0" accept="image/*" required>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="grupo _34">
                                <p class="label">3ª Imagem:</p>
                                <div class="upload_imagem pequeno">
                                    <?php if(isset($produto->fotos[1])) { ?>
                                        <img src="../imagens/produto_mes/<?= $produto->fotos[1] ?>" alt="Imagem">
                                        <input type="hidden" name="imagem_1" value="<?= $produto->fotos[1] ?>">
                                        <label for="img_foto_1">Selecione um arquivo</label>
                                        <input type="file" name="img_foto_1" id="img_foto_1" accept="image/*">
                                    <?php } else { ?>
                                        <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs%3D" alt="Imagem">
                                        <label for="img_foto_1">Selecione um arquivo</label>
                                        <input type="file" name="img_foto_1" id="img_foto_1" accept="image/*" required>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="grupo _33">
                                <p class="label">4ª Imagem:</p>
                                <div class="upload_imagem pequeno">
                                    <?php if(isset($produto->fotos[2])) { ?>
                                        <img src="../imagens/produto_mes/<?= $produto->fotos[2] ?>" alt="Imagem">
                                        <input type="hidden" name="imagem_2" value="<?= $produto->fotos[2] ?>">
                                        <label for="img_foto_2">Selecione um arquivo</label>
                                        <input type="file" name="img_foto_2" id="img_foto_2" accept="image/*">
                                    <?php } else { ?>
                                        <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs%3D" alt="Imagem">
                                        <label for="img_foto_2">Selecione um arquivo</label>
                                        <input type="file" name="img_foto_2" id="img_foto_2" accept="image/*" required>
                                    <?php } ?>
                                </div>
                            </div>
                            <input type="submit">
                        </form>
                    </div>
                    <div class="footer clearfix">
                        <p class="erro"></p>
                        <a href="#" class="form_submit" data-form="#form_ativar">Ativar</a>
                        <a href="#" class="fechar">Cancelar</a>
                    </div>
                </div>
            </div>
            <?php
        }
        
        // Método usado para tratar a requisição de uma ativação de um produto como produto do mês.
        // Recebe o id do produto e suas imagens via POST.
        // Caso o upload das três imagens seja bem sucedido, desativa todos os outros produtos como produto do mês e ativa o produto atual.
        public function salvar() {
            $id = $_POST["id"];
            $fotos = array();
            $i = 0;
            while (isset($_FILES["img_foto_$i"])) {
                if (!empty($_FILES["img_foto_$i"]["name"])) {
                    $arquivo = basename($_FILES["img_foto_$i"]["name"]);
                    $nomeArquivo = pathinfo($arquivo, PATHINFO_FILENAME);
                    $extensao = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
                    $nomeCriptografado = md5($nomeArquivo . uniqid()) . "." . $extensao;
                    $caminhoArquivo = "../imagens/produto_mes/" . $nomeCriptografado;
                    $erro = "";
                    $indice = $i + 1;

                    if ($_FILES["img_foto_$i"]["error"]) {
                        $erro = "ERRO:Erro ao enviar o arquivo $indice. Código: " . $_FILES["img_foto_$i"]["error"];
                    } elseif (!getimagesize($_FILES["img_foto_$i"]["tmp_name"])) {
                        $erro = "ERRO:O arquivo $indice não é uma imagem.";
                    } elseif ($extensao != "jpg" && $extensao != "png" && $extensao != "jpeg") {
                        $erro = "ERRO:O tipo de imagem do arquivo $indice não é suportado.";
                    } elseif (!move_uploaded_file($_FILES["img_foto_$i"]["tmp_name"], $caminhoArquivo)) {
                        $erro = "ERRO:Erro ao enviar a imagem $indice.";
                    }

                    if ($erro) {
                        return $erro;
                    }
                    
                } else {
                    $nomeCriptografado = $_POST["imagem_$i"];
                }
                
                $i += 1;
                $fotos[] = $nomeCriptografado;
            }
            
            $produtoDoMesDao = new ProdutoDoMesDAO();
            $produtoDoMesDao->excluirFotos($id);
            $produtoDoMesDao->inserirFotos($id, $fotos);
            $produtoDoMesDao->desativarTodos();
            $produtoDoMesDao->ativar($id);
        }
    }
?>