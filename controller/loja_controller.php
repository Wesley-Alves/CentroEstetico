<?php
    include_once("../model/loja.php");
    include_once("../database/loja_dao.php");

    // Classe controladora para itens da página "Nossas Lojas".
    class LojaController {
        // Método para montar a linha na tabela de itens na página de administração do CMS.
        // Recebe um objeto "loja" e retorna o html em si.
        public function montarHtml($loja) {
            ?>
            <div class="linha" data-id="<?= $loja->id ?>">
                <div class="coluna imagem">
                    <img src="../imagens/lojas/<?= $loja->fotos[0] ?>" alt="<?= $loja->cidade ?>">
                </div>
                <div class="coluna endereco vcenter">
                    <span>
                        <p><?= $loja->logradouro ?> Nº <?= $loja->numero ?></p>
                        <p>CEP <?= $loja->cep ?> - <?= $loja->bairro ?></p>
                        <p><?= $loja->cidade ?> - <?= $loja->estado ?></p>
                    </span>
                </div>
                <div class="coluna telefone vcenter">
                    <p><?= $loja->telefone ?></p>
                </div>
                <div class="coluna ativo">
                    <a href="../controller/router.php?tipo=loja&modo=ativar&id=<?= $loja->id ?>" class="ativar">
                        <?php if ($loja->ativo) { ?>
                            <img src="imagens/icones/habilitado.png" alt="Habilitado" title="Habilitado" data-ativo="1">
                        <?php } else { ?>
                            <img src="imagens/icones/desabilitado.png" alt="Desabilitado" title="Desabilitado" data-ativo="0">
                        <?php } ?>
                    </a>
                </div>
                <div class="coluna acoes">
                    <a href="../controller/router.php?tipo=loja&modo=editar&id=<?= $loja->id ?>" class="editar">
                        <img src="imagens/icones/editar.png" alt="Editar" title="Editar">
                    </a>
                    <a href="../controller/router.php?tipo=loja&modo=excluir&id=<?= $loja->id ?>" class="excluir" data-titulo="<?= $loja->cidade ?>">
                        <img src="imagens/icones/excluir.png" alt="Excluir" title="Excluir">
                    </a>
                </div>
            </div>
            <?php
        }
        
        // Método para construir uma modal com o formulário de adicionar uma novo loja.
        // Retorna o html da modal pronto para abrir com o JQuery.
        public function getModalAdicionar() {
            $lojaDao = new LojaDAO();
            $estados = $lojaDao->selecionarEstados();
            ?>
            <div class="modal_form">
                <div class="body">
                    <div class="header clearfix">
                        <a href="#" class="fechar">×</a>
                        <h1 class="titulo">Adicionar Loja</h1>
                    </div>
                    <div class="content">
                        <form action="../controller/router.php?tipo=loja&modo=gravar" id="form_adicionar">
                            <div class="grupo _60">
                                <label for="txt_logradouro">Logradouro:</label>
                                <input type="text" name="txt_logradouro" id="txt_logradouro" maxlength="100" required>
                            </div>
                            <div class="grupo _15">
                                <label for="txt_numero">Número:</label>
                                <input type="text" name="txt_numero" id="txt_numero" maxlength="5" required>
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
                                <label for="slt_estado">Estado:</label>
                                <select name="slt_estado" id="slt_estado" required>
                                    <option label=" " hidden></option>
                                    <?php
                                        foreach ($estados as $estado) {
                                            echo "<option value=\"{$estado["id"]}\">{$estado["nome"]}</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="grupo _50">
                                <label for="slt_cidade">Cidade:</label>
                                <select name="slt_cidade" id="slt_cidade" required>
                                    <option value="">...</option>
                                </select>
                            </div>
                            <div class="grupo _50">
                                <p class="label">Imagens:</p>
                                <div class="upload_imagem_multiplo">
                                    <ul></ul>
                                    <label for="img_foto">Adicionar arquivo</label>
                                    <input type="file" name="img_foto" id="img_foto" accept="image/*">
                                </div>
                            </div>
                            <div class="grupo _50 nopadding">
                                <div class="grupo">
                                    <label for="txt_bairro">Bairro:</label>
                                    <input type="text" name="txt_bairro" id="txt_bairro" maxlength="100" required>
                                </div>
                                <div class="grupo">
                                    <label for="txt_cep">CEP:</label>
                                    <input type="text" name="txt_cep" id="txt_cep" maxlength="9" placeholder="XXXXX-XXX" pattern="[0-9]{5}-[0-9]{3}" title="XXXXX-XXX" required>
                                </div>
                                <div class="grupo">
                                    <label for="txt_telefone">Telefone:</label>
                                    <input type="text" name="txt_telefone" id="txt_telefone" maxlength="15" placeholder="(XX) XXXX-XXXX" pattern="\([0-9]{2}\) [0-9]{4,5}-[0-9]{4}" title="(XX) XXXX-XXXX" required>
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
        
        // Método para construir uma modal com o formulário de atualizar uma loja já existente.
        // Recebe o id de uma loja pelo método GET e retorna o html da modal pronto para abrir com o JQuery.
        public function getModalEditar() {
            $id = $_GET["id"];
            $lojaDao = new LojaDAO();
            $loja = $lojaDao->selecionar($id);
            $estados = $lojaDao->selecionarEstados();
            $cidades = $lojaDao->selecionarCidades($loja->idEstado);
            ?>
            <div class="modal_form">
                <div class="body">
                    <div class="header clearfix">
                        <a href="#" class="fechar">×</a>
                        <h1 class="titulo"><?= $loja->cidade ?> - <?= $loja->uf ?></h1>
                    </div>
                    <div class="content">
                        <form action="../controller/router.php?tipo=loja&modo=atualizar" id="form_atualizar">
                            <input type="hidden" name="id" value="<?= $loja->id ?>">
                            <input type="hidden" name="id_endereco" value="<?= $loja->idEndereco ?>">
                            <div class="grupo _60">
                                <label for="txt_logradouro">Logradouro:</label>
                                <input type="text" name="txt_logradouro" id="txt_logradouro" maxlength="100" required value="<?= $loja->logradouro ?>">
                            </div>
                            <div class="grupo _15">
                                <label for="txt_numero">Número:</label>
                                <input type="text" name="txt_numero" id="txt_numero" maxlength="5" required value="<?= $loja->numero ?>">
                            </div>
                            <div class="grupo _25">
                                <p class="label">Exibição:</p>
                                <label class="switch">
                                    <input type="hidden" name="chk_ativo" value="0" <?= $loja->ativo == "0" ? "checked" : "" ?>>
                                    <input type="checkbox" name="chk_ativo" value="1" <?= $loja->ativo == "1" ? "checked" : "" ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="grupo _50">
                                <label for="slt_estado">Estado:</label>
                                <select name="slt_estado" id="slt_estado" required>
                                    <option label=" " hidden></option>
                                    <?php
                                        foreach ($estados as $estado) {
                                            echo "<option value=\"{$estado["id"]}\"" . ($estado["id"] == $loja->idEstado ? "selected" : "") .">{$estado["nome"]}</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="grupo _50">
                                <label for="slt_cidade">Cidade:</label>
                                <select name="slt_cidade" id="slt_cidade" required>
                                    <?php
                                        foreach ($cidades as $cidade) {
                                            echo "<option value=\"{$cidade["id"]}\"" . ($cidade["id"] == $loja->idCidade ? "selected" : "") .">{$cidade["nome"]}</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="grupo _50">
                                <p class="label">Imagens:</p>
                                <div class="upload_imagem_multiplo">
                                    <ul>
                                        <?php for ($i = 0; $i < count($loja->fotos); $i++) { ?>
                                            <li>
                                                <a href="#" class="remover">
                                                    <img src="imagens/icones/excluir.png" alt="Remover" title="Remover">
                                                </a>
                                                <a href="#" class="editar">
                                                    <img src="imagens/icones/editar.png" alt="Editar" title="Editar">
                                                </a>
                                                <input type="hidden" name="imagem_<?= $i ?>" value="<?= $loja->fotos[$i] ?>">
                                                <input type="file" name="img_foto_<?= $i ?>" accept="image/*">
                                                <img src="../imagens/lojas/<?= $loja->fotos[$i] ?>" title="Imagem <?= $i + 1 ?>">
                                                <span>Imagem <?= $i + 1 ?></span>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <label for="img_foto">Adicionar arquivo</label>
                                    <input type="file" name="img_foto" id="img_foto" accept="image/*">
                                </div>
                            </div>
                            <div class="grupo _50 nopadding">
                                <div class="grupo">
                                    <label for="txt_bairro">Bairro:</label>
                                    <input type="text" name="txt_bairro" id="txt_bairro" maxlength="100" required value="<?= $loja->bairro ?>">>
                                </div>
                                <div class="grupo">
                                    <label for="txt_cep">CEP:</label>
                                    <input type="text" name="txt_cep" id="txt_cep" maxlength="9" placeholder="XXXXX-XXX" pattern="[0-9]{5}-[0-9]{3}" title="XXXXX-XXX" required value="<?= $loja->cep ?>">>
                                </div>
                                <div class="grupo">
                                    <label for="txt_telefone">Telefone:</label>
                                    <input type="text" name="txt_telefone" id="txt_telefone" maxlength="15" placeholder="(XX) XXXX-XXXX" pattern="\([0-9]{2}\) [0-9]{4,5}-[0-9]{4}" title="(XX) XXXX-XXXX" required value="<?= $loja->telefone ?>">>
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
        
        // Método usado para listar todas as cidades de um estado específico ao usuário selecionar um estado.
        // Recebe o id de um estado via GET.
        // Retorna lista de options para ser colocado dentro de um objeto select do html.
        public function listarCidades() {
            $id = $_GET["id"];
            $lojaDao = new LojaDAO();
            $cidades = $lojaDao->selecionarCidades($id);
            foreach ($cidades as $cidade) {
                echo "<option value=\"{$cidade["id"]}\">{$cidade["nome"]}</option>";
            }
        }
        
        // Método para tratar a submissão do formulário de adicionar uma nova loja.
        // Recebe os dados do formulário via POST e tenta fazer o upload de todas as imagens selecionadas, permitindo um número ilimitado de imagens.
        // Caso o upload seja bem sucedido monta um objeto loja e manda para a classe DAO, e ao final retorna o html de uma nova linha na tabela de admnistração.
        // Caso aconteça algum erro com o upload de alguma das imagens retorna uma mensagem iniciada pelo prefixo ERRO: que é tratada em um algoritmo em JavaScript.
        public function gravar() {
            $logradouro = $_POST["txt_logradouro"];
            $numero = $_POST["txt_numero"];
            $ativo = $_POST["chk_ativo"];
            $idCidade = $_POST["slt_cidade"];
            $bairro = $_POST["txt_bairro"];
            $cep = $_POST["txt_cep"];
            $telefone = $_POST["txt_telefone"];
            $fotos = array();
            
            $i = 0;
            while (isset($_FILES["img_foto_$i"])) {
                $arquivo = basename($_FILES["img_foto_$i"]["name"]);
                $nomeArquivo = pathinfo($arquivo, PATHINFO_FILENAME);
                $extensao = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
                $nomeCriptografado = md5($nomeArquivo . uniqid()) . "." . $extensao;
                $caminhoArquivo = "../imagens/lojas/" . $nomeCriptografado;
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
                
                $i += 1;
                $fotos[] = $nomeCriptografado;
            }
            
            $loja = new Loja();
            $loja->logradouro = $logradouro;
            $loja->numero = $numero;
            $loja->ativo = $ativo;
            $loja->idCidade = $idCidade;
            $loja->bairro = $bairro;
            $loja->cep = $cep;
            $loja->telefone = $telefone;
            $loja->fotos = $fotos;
            
            $lojaDao = new LojaDAO();
            $loja->idEndereco = $lojaDao->inserirEndereco($loja);
            $id = $lojaDao->inserir($loja);
            $loja->id = $id;
            $lojaDao->inserirFotos($loja);
            $this->montarHtml($lojaDao->selecionar($id));
        }
        
        // Método para tratar a submissão do formulário de atualizar uma loja já existente.
        // Recebe os dados do formulário via POST e tenta fazer o upload caso alguma nova imagem tenha sido selecionada, ou apenas mantém a imagem atual.
        // Caso o upload seja bem sucedido monta um objeto loja e manda para a classe DAO, e ao final retorna o html de uma nova linha na tabela de admnistração.
        // Caso aconteça algum erro com o upload de alguma das imagens retorna uma mensagem iniciada pelo prefixo ERRO: que é tratada em um algoritmo em JavaScript.
        public function atualizar() {
            $id = $_POST["id"];
            $idEndereco = $_POST["id_endereco"];
            $logradouro = $_POST["txt_logradouro"];
            $numero = $_POST["txt_numero"];
            $ativo = $_POST["chk_ativo"];
            $idCidade = $_POST["slt_cidade"];
            $bairro = $_POST["txt_bairro"];
            $cep = $_POST["txt_cep"];
            $telefone = $_POST["txt_telefone"];
            $fotos = array();
            
            $i = 0;
            while (isset($_FILES["img_foto_$i"])) {
                if (!empty($_FILES["img_foto_$i"]["name"])) {
                    $arquivo = basename($_FILES["img_foto_$i"]["name"]);
                    $nomeArquivo = pathinfo($arquivo, PATHINFO_FILENAME);
                    $extensao = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
                    $nomeCriptografado = md5($nomeArquivo . uniqid()) . "." . $extensao;
                    $caminhoArquivo = "../imagens/lojas/" . $nomeCriptografado;
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
            
            $loja = new Loja();
            $loja->id = $id;
            $loja->idEndereco = $idEndereco;
            $loja->logradouro = $logradouro;
            $loja->numero = $numero;
            $loja->ativo = $ativo;
            $loja->idCidade = $idCidade;
            $loja->bairro = $bairro;
            $loja->cep = $cep;
            $loja->telefone = $telefone;
            $loja->fotos = $fotos;
            
            $lojaDao = new LojaDAO();
            $lojaDao->atualizarEndereco($loja);
            $lojaDao->atualizar($loja);
            $lojaDao->excluirFotos($id);
            $lojaDao->inserirFotos($loja);
            $this->montarHtml($lojaDao->selecionar($id));
        }
        
        // Método usado para tratar a requisição de uma exclusão de uma loja.
        // Recebe o id de uma loja via GET.
        // Retorna o mesmo id como resposta para que a linha da loja excluida seja removida do html, usando como referência este id.
        public function excluir() {
            $id = $_GET["id"];
            $lojaDao = new LojaDAO();
            $lojaDao->excluirFotos($id);
            $lojaDao->excluir($id);
            echo json_encode(array("id" => $id));
        }
        
        // Método usado para tratar a requisição de ativar e desativar uma loja.
        // Recebe o id de uma loja e um byte que diz se ela já está ativa ou não via GET.
        // Retorna uma mensagem para ser exibida em um modal na tela do CMS.
        public function ativar() {
            $id = $_GET["id"];
            $ativo = $_GET["ativo"];
            $lojaDao = new LojaDAO();
            $lojaDao->ativar($id, $ativo == "1" ? 0 : 1);
            if ($ativo == "1") {
                echo "Esta loja foi desabilitada e não será mais exibida.";
            } else {
                echo "Esta loja foi habilitada e agora será exibida.";
            }
        }
    }
?>