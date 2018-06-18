<?php
    include_once("../model/imagem_slider.php");
    include_once("../database/slider_dao.php");

    // Classe controladora para as imagens do slider na página "Home".
    class SliderController {
        // Método para montar a linha na tabela de itens na página de administração do CMS.
        // Recebe um objeto "imagem" e retorna o html em si.
        public function montarHtml($imagem) {
            ?>
            <div class="linha" data-id="<?= $imagem->id ?>">
                <div class="coluna imagem">
                    <img src="../imagens/slider/<?= $imagem->imagem ?>" alt="<?= $imagem->legenda ?>">
                </div>
                <div class="coluna legenda vcenter">
                    <span><?= $imagem->legenda ?></span>
                </div>
                <div class="coluna ativo">
                    <a href="../controller/router.php?tipo=slider&modo=ativar&id=<?= $imagem->id ?>" class="ativar">
                        <?php if ($imagem->ativo) { ?>
                            <img src="imagens/icones/habilitado.png" alt="Habilitado" title="Habilitado" data-ativo="1">
                        <?php } else { ?>
                            <img src="imagens/icones/desabilitado.png" alt="Desabilitado" title="Desabilitado" data-ativo="0">
                        <?php } ?>
                    </a>
                </div>
                <div class="coluna acoes">
                    <a href="../controller/router.php?tipo=slider&modo=editar&id=<?= $imagem->id ?>" class="editar">
                        <img src="imagens/icones/editar.png" alt="Editar" title="Editar">
                    </a>
                    <a href="../controller/router.php?tipo=slider&modo=excluir&id=<?= $imagem->id ?>" class="excluir">
                        <img src="imagens/icones/excluir.png" alt="Excluir" title="Excluir">
                    </a>
                </div>
            </div>
            <?php
        }
        
        // Método para construir uma modal com o formulário de adicionar uma nova imagem.
        // Retorna o html da modal pronto para abrir com o JQuery.
        public function getModalAdicionar() {
            ?>
            <div class="modal_form">
                <div class="body">
                    <div class="header clearfix">
                        <a href="#" class="fechar">×</a>
                        <h1 class="titulo">Adicionar Slider</h1>
                    </div>
                    <div class="content">
                        <form action="../controller/router.php?tipo=slider&modo=gravar" id="form_adicionar">
                            <div class="grupo _75">
                                <label for="txt_legenda">Legenda:</label>
                                <input type="text" name="txt_legenda" id="txt_legenda" maxlength="100" required>
                            </div>
                            <div class="grupo _25">
                                <p class="label">Exibição:</p>
                                <label class="switch">
                                    <input type="hidden" name="chk_ativo" value="0">
                                    <input type="checkbox" name="chk_ativo" value="1" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="grupo _50 margin_center">
                                <p class="label">Imagem:</p>
                                <div class="upload_imagem">
                                    <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs%3D" alt="Imagem">
                                    <label for="img_foto">Selecione um arquivo</label>
                                    <input type="file" name="img_foto" id="img_foto" accept="image/*" required>
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
        
        // Método para construir uma modal com o formulário de atualizar uma imagem já existente.
        // Recebe o id de um destaque pelo método GET e retorna o html da modal pronto para abrir com o JQuery.
        public function getModalEditar() {
            $id = $_GET["id"];
            $sliderDao = new SliderDAO();
            $imagem = $sliderDao->selecionar($id);
            ?>
            <div class="modal_form">
                <div class="body">
                    <div class="header clearfix">
                        <a href="#" class="fechar">×</a>
                        <h1 class="titulo"><?= $imagem->legenda ?></h1>
                    </div>
                    <div class="content">
                        <form action="../controller/router.php?tipo=slider&modo=atualizar" id="form_atualizar">
                            <input type="hidden" name="id" value="<?= $imagem->id ?>">
                            <input type="hidden" name="imagem_atual" value="<?= $imagem->imagem ?>">
                            <div class="grupo _75">
                                <label for="txt_legenda">Legenda:</label>
                                <input type="text" name="txt_legenda" id="txt_legenda" maxlength="100" required value="<?= $imagem->legenda ?>">
                            </div>
                            <div class="grupo _25">
                                <p class="label">Exibição:</p>
                                <label class="switch">
                                    <input type="hidden" name="chk_ativo" value="0" <?= $imagem->ativo == "0" ? "checked" : "" ?>>
                                    <input type="checkbox" name="chk_ativo" value="1" <?= $imagem->ativo == "1" ? "checked" : "" ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="grupo _50 margin_center">
                                <p class="label">Imagem:</p>
                                <div class="upload_imagem">
                                    <img src="../imagens/slider/<?= $imagem->imagem ?>" alt="Imagem">
                                    <label for="img_foto">Selecione um arquivo</label>
                                    <input type="file" name="img_foto" id="img_foto" accept="image/*">
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
        
        // Método para tratar a submissão do formulário de adicionar uma nova imagem.
        // Recebe os dados do formulário via POST e tenta fazer o upload da imagem selecionada.
        // Caso o upload seja bem sucedido monta um objeto destaque e manda para a classe DAO, e ao final retorna o html de uma nova linha na tabela de admnistração.
        // Caso aconteça algum erro com o upload retorna uma mensagem iniciada pelo prefixo ERRO: que é tratada em um algoritmo em JavaScript.
        public function gravar() {
            $legenda = $_POST["txt_legenda"];
            $ativo = $_POST["chk_ativo"];
            
            $arquivo = basename($_FILES["img_foto"]["name"]);
            $nomeArquivo = pathinfo($arquivo, PATHINFO_FILENAME);
            $extensao = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
            $nomeCriptografado = md5($nomeArquivo . uniqid()) . "." . $extensao;
            $caminhoArquivo = "../imagens/slider/" . $nomeCriptografado;
            
            if ($_FILES["img_foto"]["error"]) {
                echo "ERRO:Erro ao enviar o arquivo. Código: " . $_FILES["img_foto"]["error"];
            } elseif (!getimagesize($_FILES["img_foto"]["tmp_name"])) {
                echo "ERRO:Este arquivo não é uma imagem.";
            } elseif ($extensao != "jpg" && $extensao != "png" && $extensao != "jpeg") {
                echo "ERRO:Este tipo de imagem não é suportado.";
            } elseif (!move_uploaded_file($_FILES["img_foto"]["tmp_name"], $caminhoArquivo)) {
                echo "ERRO:Erro ao enviar a imagem.";
            } else {
                $imagem = new ImagemSlider();
                $imagem->legenda = $legenda;
                $imagem->ativo = $ativo;
                $imagem->imagem = $nomeCriptografado;
                
                $sliderDao = new SliderDAO();
                $id = $sliderDao->inserir($imagem);
                $imagem->id = $id;
                $this->montarHtml($imagem);
            }
        }
        
        // Método para tratar a submissão do formulário de atualizar uma imagem já existente.
        // Recebe os dados do formulário via POST e tenta fazer o upload caso uma nova imagem tenha sido selecionada, ou apenas mantém a imagem atual.
        // Caso o upload seja bem sucedido monta um objeto destaque e manda para a classe DAO, e ao final retorna o html de uma nova linha na tabela de admnistração.
        // Caso aconteça algum erro com o upload retorna uma mensagem iniciada pelo prefixo ERRO: que é tratada em um algoritmo em JavaScript.
        public function atualizar() {
            $id = $_POST["id"];
            $legenda = $_POST["txt_legenda"];
            $ativo = $_POST["chk_ativo"];
            $imagemAtual = $_POST["imagem_atual"];
            
            if (!empty($_FILES["img_foto"]["name"])) {
                $arquivo = basename($_FILES["img_foto"]["name"]);
                $nomeArquivo = pathinfo($arquivo, PATHINFO_FILENAME);
                $extensao = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
                $nomeCriptografado = md5($nomeArquivo . uniqid()) . "." . $extensao;
                $caminhoArquivo = "../imagens/slider/" . $nomeCriptografado;
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
                
            $imagem = new ImagemSlider();
            $imagem->id = $id;
            $imagem->legenda = $legenda;
            $imagem->ativo = $ativo;
            $imagem->imagem = $imagemAtual;
            $sliderDao = new SliderDAO();
            $sliderDao->atualizar($imagem);
            $this->montarHtml($imagem);
        }
        
        // Método usado para tratar a requisição de uma exclusão de imagem.
        // Recebe o id de uma imagem via GET.
        // Retorna o mesmo id como resposta para que a linha da imagem excluida seja removida do html, usando como referência este id.
        public function excluir() {
            $id = $_GET["id"];
            $sliderDao = new SliderDAO();
            $sliderDao->excluir($id);
            echo json_encode(array("id" => $id));
        }
        
        // Método usado para tratar a requisição de ativar e desativar uma imagem.
        // Recebe o id de uma imagem e um byte que diz se ela já está ativa ou não via GET.
        // Retorna uma mensagem para ser exibida em um modal na tela do CMS.
        public function ativar() {
            $id = $_GET["id"];
            $ativo = $_GET["ativo"];
            $sliderDao = new SliderDAO();
            $sliderDao->ativar($id, $ativo == "1" ? 0 : 1);
            if ($ativo == "1") {
                echo "Esta imagem foi desabilitada e não será mais exibida.";
            } else {
                echo "Esta imagem foi habilitada e agora será exibida.";
            }
        }
    }
?>