<?php
    include_once("../model/item_sobre_nos.php");
    include_once("../database/sobre_nos_dao.php");

    // Classe controladora para itens da página "Sobre Nós".
    class SobreNosController {
        // Método para montar a linha na tabela de itens na página de administração do CMS.
        // Recebe um objeto "item" e retorna o html em si.
        public function montarHtml($item) {
            ?>
            <div class="linha" data-id="<?= $item->id ?>">
                <div class="coluna imagem">
                    <img src="../imagens/sobre_nos/<?= $item->imagem ?>" alt="<?= $item->titulo ?>">
                </div>
                <div class="coluna titulo vcenter">
                    <span><?= $item->titulo ?></span>
                </div>
                <div class="coluna texto">
                    <p><?= $item->texto ?></p>
                </div>
                <div class="coluna ativo">
                    <a href="../controller/router.php?tipo=sobre_nos&modo=ativar&id=<?= $item->id ?>" class="ativar">
                        <?php if ($item->ativo) { ?>
                            <img src="imagens/icones/habilitado.png" alt="Habilitado" title="Habilitado" data-ativo="1">
                        <?php } else { ?>
                            <img src="imagens/icones/desabilitado.png" alt="Desabilitado" title="Desabilitado" data-ativo="0">
                        <?php } ?>
                    </a>
                </div>
                <div class="coluna acoes">
                    <a href="../controller/router.php?tipo=sobre_nos&modo=editar&id=<?= $item->id ?>" class="editar">
                        <img src="imagens/icones/editar.png" alt="Editar" title="Editar">
                    </a>
                    <a href="../controller/router.php?tipo=sobre_nos&modo=excluir&id=<?= $item->id ?>" class="excluir" data-titulo="<?= $item->titulo ?>">
                        <img src="imagens/icones/excluir.png" alt="Excluir" title="Excluir">
                    </a>
                </div>
            </div>
            <?php
        }
        
        // Método para construir uma modal com o formulário de adicionar um novo item.
        // Retorna o html da modal pronto para abrir com o JQuery.
        public function getModalAdicionar() {
            ?>
            <div class="modal_form">
                <div class="body">
                    <div class="header clearfix">
                        <a href="#" class="fechar">×</a>
                        <h1 class="titulo">Adicionar Sobre Nós</h1>
                    </div>
                    <div class="content">
                        <form action="../controller/router.php?tipo=sobre_nos&modo=gravar" id="form_adicionar">
                            <div class="grupo _75">
                                <label for="txt_titulo">Título:</label>
                                <input type="text" name="txt_titulo" id="txt_titulo" maxlength="100" required>
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
                            <div class="grupo _50">
                                <label for="txt_texto">Texto:</label>
                                <textarea name="txt_texto" id="txt_texto" required></textarea>
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
        
        // Método para construir uma modal com o formulário de atualizar um item já existente.
        // Recebe o id de um item pelo método GET e retorna o html da modal pronto para abrir com o JQuery.
        public function getModalEditar() {
            $id = $_GET["id"];
            $sobreNosDao = new SobreNosDAO();
            $item = $sobreNosDao->selecionar($id);
            ?>
            <div class="modal_form">
                <div class="body">
                    <div class="header clearfix">
                        <a href="#" class="fechar">×</a>
                        <h1 class="titulo"><?= $item->titulo ?></h1>
                    </div>
                    <div class="content">
                        <form action="../controller/router.php?tipo=sobre_nos&modo=atualizar" id="form_atualizar">
                            <input type="hidden" name="id" value="<?= $item->id ?>">
                            <input type="hidden" name="imagem_atual" value="<?= $item->imagem ?>">
                            <div class="grupo _75">
                                <label for="txt_titulo">Título:</label>
                                <input type="text" name="txt_titulo" id="txt_titulo" maxlength="100" required value="<?= $item->titulo ?>">
                            </div>
                            <div class="grupo _25">
                                <p class="label">Exibição:</p>
                                <label class="switch">
                                    <input type="hidden" name="chk_ativo" value="0" <?= $item->ativo == "0" ? "checked" : "" ?>>
                                    <input type="checkbox" name="chk_ativo" value="1" <?= $item->ativo == "1" ? "checked" : "" ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="grupo _50">
                                <p class="label">Imagem:</p>
                                <div class="upload_imagem">
                                    <img src="../imagens/sobre_nos/<?= $item->imagem ?>" alt="Imagem">
                                    <label for="img_foto">Selecione um arquivo</label>
                                    <input type="file" name="img_foto" id="img_foto" accept="image/*">
                                </div>
                            </div>
                            <div class="grupo _50">
                                <label for="txt_texto">Texto:</label>
                                <textarea name="txt_texto" id="txt_texto" required><?= $item->texto ?></textarea>
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
        
        // Método para tratar a submissão do formulário de adicionar um novo item.
        // Recebe os dados do formulário via POST e tenta fazer o upload da imagem selecionada.
        // Caso o upload seja bem sucedido monta um objeto destaque e manda para a classe DAO, e ao final retorna o html de uma nova linha na tabela de admnistração.
        // Caso aconteça algum erro com o upload retorna uma mensagem iniciada pelo prefixo ERRO: que é tratada em um algoritmo em JavaScript.
        public function gravar() {
            $titulo = $_POST["txt_titulo"];
            $ativo = $_POST["chk_ativo"];
            $texto = $_POST["txt_texto"];
            
            $arquivo = basename($_FILES["img_foto"]["name"]);
            $nomeArquivo = pathinfo($arquivo, PATHINFO_FILENAME);
            $extensao = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
            $nomeCriptografado = md5($nomeArquivo . uniqid()) . "." . $extensao;
            $caminhoArquivo = "../imagens/sobre_nos/" . $nomeCriptografado;
            
            if ($_FILES["img_foto"]["error"]) {
                echo "ERRO:Erro ao enviar o arquivo. Código: " . $_FILES["img_foto"]["error"];
            } elseif (!getimagesize($_FILES["img_foto"]["tmp_name"])) {
                echo "ERRO:Este arquivo não é uma imagem.";
            } elseif ($extensao != "jpg" && $extensao != "png" && $extensao != "jpeg") {
                echo "ERRO:Este tipo de imagem não é suportado.";
            } elseif (!move_uploaded_file($_FILES["img_foto"]["tmp_name"], $caminhoArquivo)) {
                echo "ERRO:Erro ao enviar a imagem.";
            } else {
                $item = new ItemSobreNos();
                $item->titulo = $titulo;
                $item->ativo = $ativo;
                $item->texto = $texto;
                $item->imagem = $nomeCriptografado;
                
                $sobreNosDao = new SobreNosDAO();
                $id = $sobreNosDao->inserir($item);
                $item->id = $id;
                $this->montarHtml($item);
            }
        }
        
        // Método para tratar a submissão do formulário de atualizar um item já existente.
        // Recebe os dados do formulário via POST e tenta fazer o upload caso uma nova imagem tenha sido selecionada, ou apenas mantém a imagem atual.
        // Caso o upload seja bem sucedido monta um objeto destaque e manda para a classe DAO, e ao final retorna o html de uma nova linha na tabela de admnistração.
        // Caso aconteça algum erro com o upload retorna uma mensagem iniciada pelo prefixo ERRO: que é tratada em um algoritmo em JavaScript.
        public function atualizar() {
            $id = $_POST["id"];
            $titulo = $_POST["txt_titulo"];
            $ativo = $_POST["chk_ativo"];
            $texto = $_POST["txt_texto"];
            $imagemAtual = $_POST["imagem_atual"];
            
            if (!empty($_FILES["img_foto"]["name"])) {
                $arquivo = basename($_FILES["img_foto"]["name"]);
                $nomeArquivo = pathinfo($arquivo, PATHINFO_FILENAME);
                $extensao = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
                $nomeCriptografado = md5($nomeArquivo . uniqid()) . "." . $extensao;
                $caminhoArquivo = "../imagens/sobre_nos/" . $nomeCriptografado;
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
                
            $item = new ItemSobreNos();
            $item->id = $id;
            $item->titulo = $titulo;
            $item->ativo = $ativo;
            $item->texto = $texto;
            $item->imagem = $imagemAtual;
            $sobreNosDao = new SobreNosDAO();
            $sobreNosDao->atualizar($item);
            $this->montarHtml($item);
        }
        
        // Método usado para tratar a requisição de uma exclusão de um item.
        // Recebe o id de um item via GET.
        // Retorna o mesmo id como resposta para que a linha do item excluido seja removida do html, usando como referência este id.
        public function excluir() {
            $id = $_GET["id"];
            $sobreNosDao = new SobreNosDAO();
            $sobreNosDao->excluir($id);
            echo json_encode(array("id" => $id));
        }
        
        // Método usado para tratar a requisição de ativar e desativar um item.
        // Recebe o id de um item e um byte que diz se ele já está ativo ou não via GET.
        // Retorna uma mensagem para ser exibida em um modal na tela do CMS.
        public function ativar() {
            $id = $_GET["id"];
            $ativo = $_GET["ativo"];
            $sobreNosDao = new SobreNosDAO();
            $sobreNosDao->ativar($id, $ativo == "1" ? 0 : 1);
            if ($ativo == "1") {
                echo "Este item foi desabilitado e não será mais exibido.";
            } else {
                echo "Este item foi habilitado e agora será exibido.";
            }
        }
    }
?>