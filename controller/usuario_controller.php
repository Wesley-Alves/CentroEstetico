<?php
    // Arquivo controlador para usuários e autenticação.
    include_once("../model/usuario.php");
    include_once("../database/usuario_dao.php");
    include_once("../database/nivel_dao.php");
    session_start();

    class UsuarioController {
        // Método para autenticação de usuário.
        // Recebe os dados de login via POST e tenta fazer a autenticação.
        // Caso seja bem sucedida salva um usuário na sessão.
        // Caso contrário retorna uma mensagem de erro.
        public function autenticar() {
            $usuario = $_POST["nome_usuario"];
            $senha = $_POST["senha"];
            
            $usuarioDao = new UsuarioDAO();
            $usuario = $usuarioDao->autenticar($usuario, md5($senha));
            if (is_null($usuario)) {
                echo "Usuário ou senha incorretos.";
            } else {
                $_SESSION["usuario"] = $usuario;
            }
        }
        
        // Método para montar a linha na tabela de itens na página de administração do CMS.
        // Recebe um objeto "usuário" e retorna o html em si.
        public function montarHtml($usuario) {
            ?>
            <div class="linha" data-id="<?php echo $usuario->id ?>">
                <div class="coluna imagem medio">
                    <img src="../imagens/usuarios/<?php echo $usuario->imagem ?>">
                </div>
                <div class="coluna nome vcenter">
                    <span><?php echo $usuario->nome ?></span>
                </div>
                <div class="coluna email vcenter">
                    <span><?php echo $usuario->email ?></span>
                </div>
                <div class="coluna nivel vcenter">
                    <p><?php echo $usuario->nivel->titulo ?></p>
                </div>
                
                <div class="coluna ativo">
                    <a href="../controller/router.php?tipo=usuario&modo=ativar&id=<?php echo $usuario->id ?>" class="ativar">
                        <?php if ($usuario->ativo) { ?>
                            <img src="imagens/icones/habilitado.png" alt="Habilitado" title="Habilitado" data-ativo="1">
                        <?php } else { ?>
                            <img src="imagens/icones/desabilitado.png" alt="Desabilitado" title="Desabilitado" data-ativo="0">
                        <?php } ?>
                    </a>
                </div>
                <div class="coluna acoes">
                    <a href="../controller/router.php?tipo=usuario&modo=editar&id=<?php echo $usuario->id ?>" class="editar">
                        <img src="imagens/icones/editar.png" alt="Editar" title="Editar">
                    </a>
                    <a href="../controller/router.php?tipo=usuario&modo=excluir&id=<?php echo $usuario->id ?>" class="excluir" data-titulo="<?php echo $usuario->nome ?>">
                        <img src="imagens/icones/excluir.png" alt="Excluir" title="Excluir">
                    </a>
                </div>
            </div>
            <?php
        }
        
        // Método para construir uma modal com o formulário de adicionar um novo usuário.
        // Retorna o html da modal pronto para abrir com o JQuery.
        public function getModalAdicionar() {
            $nivelDao = new NivelDAO();
            $niveis = $nivelDao->selecionarTodos(true);
            ?>
            <div class="modal_form">
                <div class="body">
                    <div class="header clearfix">
                        <a href="#" class="fechar">×</a>
                        <h1 class="titulo">Adicionar Usuário</h1>
                    </div>
                    <div class="content">
                        <form action="../controller/router.php?tipo=usuario&modo=gravar" id="form_adicionar">
                            <div class="grupo _25">
                                <p class="label">Imagem:</p>
                                <div class="upload_imagem medio">
                                    <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs%3D" alt="Imagem">
                                    <label for="img_foto">Selecione</label>
                                    <input type="file" name="img_foto" id="img_foto" accept="image/*" required>
                                </div>
                            </div>
                            <div class="grupo _75 nopadding">
                                <div class="grupo _66">
                                    <label for="txt_nome">Nome:</label>
                                    <input type="text" name="txt_nome" id="txt_nome" maxlength="100" required>
                                </div>
                                <div class="grupo _34">
                                    <p class="label">Ativar:</p>
                                    <label class="switch">
                                        <input type="hidden" name="chk_ativo" value="0">
                                        <input type="checkbox" name="chk_ativo" value="1" checked>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                                <div class="grupo _50">
                                    <label for="txt_email">Email:</label>
                                    <input type="email" name="txt_email" id="txt_email" maxlength="100" required>
                                </div>
                                <div class="grupo _50">
                                    <label for="slt_nivel">Nível:</label>
                                    <select name="slt_nivel" required>
                                        <?php foreach ($niveis as $nivel) { ?>
                                            <option value="<?= $nivel->id ?>"><?= $nivel->titulo ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="grupo _50">
                                    <label for="txt_usuario">Nome de usuário:</label>
                                    <input type="text" name="txt_usuario" id="txt_usuario" maxlength="30" required>
                                </div>
                                <div class="grupo _50">
                                    <label for="txt_senha">Senha:</label>
                                    <input type="password" name="txt_senha" id="txt_senha" maxlength="30" required>
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
        
        // Método para construir uma modal com o formulário de atualizar um usuário já existente.
        // Recebe o id de um usuário pelo método GET e retorna o html da modal pronto para abrir com o JQuery.
        public function getModalEditar() {
            $id = $_GET["id"];
            $usuarioDao = new UsuarioDAO();
            $usuario = $usuarioDao->selecionar($id);
            $nivelDao = new NivelDAO();
            $niveis = $nivelDao->selecionarTodos(true);
            ?>
            <div class="modal_form">
                <div class="body">
                    <div class="header clearfix">
                        <a href="#" class="fechar">×</a>
                        <h1 class="titulo"><?= $usuario->nome ?></h1>
                    </div>
                    <div class="content">
                        <form action="../controller/router.php?tipo=usuario&modo=atualizar" id="form_atualizar">
                            <input type="hidden" name="id" value="<?= $usuario->id ?>">
                            <input type="hidden" name="imagem_atual" value="<?= $usuario->imagem ?>">
                            <div class="grupo _25">
                                <p class="label">Imagem:</p>
                                <div class="upload_imagem medio">
                                    <img src="../imagens/usuarios/<?= $usuario->imagem ?>" alt="Imagem">
                                    <label for="img_foto">Selecione</label>
                                    <input type="file" name="img_foto" id="img_foto" accept="image/*">
                                </div>
                            </div>
                            <div class="grupo _75 nopadding">
                                <div class="grupo _66">
                                    <label for="txt_nome">Nome:</label>
                                    <input type="text" name="txt_nome" id="txt_nome" maxlength="100" required value="<?= $usuario->nome ?>">
                                </div>
                                <div class="grupo _34">
                                    <p class="label">Ativar:</p>
                                    <label class="switch">
                                        <input type="hidden" name="chk_ativo" value="0" <?= $usuario->ativo == "0" ? "checked" : "" ?>>
                                        <input type="checkbox" name="chk_ativo" value="1" <?= $usuario->ativo == "1" ? "checked" : "" ?>>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                                <div class="grupo _50">
                                    <label for="txt_email">Email:</label>
                                    <input type="email" name="txt_email" id="txt_email" maxlength="100" required value="<?= $usuario->email ?>">
                                </div>
                                <div class="grupo _50">
                                    <label for="slt_nivel">Nível:</label>
                                    <select name="slt_nivel" required>
                                        <?php foreach ($niveis as $nivel) { ?>
                                            <option value="<?= $nivel->id ?>" <?= $nivel->id == $usuario->nivel->id ? "selected" : ""; ?>><?= $nivel->titulo ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="grupo _50">
                                    <label for="txt_usuario">Nome de usuário:</label>
                                    <input type="text" name="txt_usuario" id="txt_usuario" maxlength="30" required value="<?= $usuario->usuario ?>">
                                </div>
                                <div class="grupo _50">
                                    <label for="txt_senha">Senha:</label>
                                    <input type="password" name="txt_senha" id="txt_senha" maxlength="30" placeholder="(não alterado)">
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
        
        // Método para tratar a submissão do formulário de adicionar um novo usuário.
        // Recebe os dados do formulário via POST e tenta fazer o upload da imagem selecionada.
        // Caso o upload seja bem sucedido monta um objeto destaque e manda para a classe DAO, e ao final retorna o html de uma nova linha na tabela de admnistração.
        // Caso aconteça algum erro com o upload retorna uma mensagem iniciada pelo prefixo ERRO: que é tratada em um algoritmo em JavaScript.
        public function gravar() {
            $nome = $_POST["txt_nome"];
            $ativo = $_POST["chk_ativo"];
            $email = $_POST["txt_email"];
            $nomeUsuario = $_POST["txt_usuario"];
            $senha = $_POST["txt_senha"];
            $idNivel = $_POST["slt_nivel"];
            $nivelDao = new NivelDAO();
            $nivel = $nivelDao->selecionar($idNivel);
            
            $arquivo = basename($_FILES["img_foto"]["name"]);
            $nomeArquivo = pathinfo($arquivo, PATHINFO_FILENAME);
            $extensao = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
            $nomeCriptografado = md5($nomeArquivo . uniqid()) . "." . $extensao;
            $caminhoArquivo = "../imagens/usuarios/" . $nomeCriptografado;
            
            if ($_FILES["img_foto"]["error"]) {
                echo "ERRO:Erro ao enviar o arquivo. Código: " . $_FILES["img_foto"]["error"];
            } elseif (!getimagesize($_FILES["img_foto"]["tmp_name"])) {
                echo "ERRO:Este arquivo não é uma imagem.";
            } elseif ($extensao != "jpg" && $extensao != "png" && $extensao != "jpeg") {
                echo "ERRO:Este tipo de imagem não é suportado.";
            } elseif (!move_uploaded_file($_FILES["img_foto"]["tmp_name"], $caminhoArquivo)) {
                echo "ERRO:Erro ao enviar a imagem.";
            } else {
                $usuario = new Usuario();
                $usuario->nome = $nome;
                $usuario->ativo = $ativo;
                $usuario->email = $email;
                $usuario->imagem = $nomeCriptografado;
                $usuario->usuario = $nomeUsuario;
                $usuario->senha = md5($senha);
                $usuario->nivel = $nivel;
                
                $usuarioDao = new UsuarioDAO();
                $id = $usuarioDao->inserir($usuario);
                $usuario->id = $id;
                $this->montarHtml($usuario);
            }
        }
        
        // Método para tratar a submissão do formulário de atualizar um usuário já existente.
        // Recebe os dados do formulário via POST e tenta fazer o upload caso uma nova imagem tenha sido selecionada, ou apenas mantém a imagem atual.
        // Caso o upload seja bem sucedido monta um objeto destaque e manda para a classe DAO, e ao final retorna o html de uma nova linha na tabela de admnistração.
        // Caso aconteça algum erro com o upload retorna uma mensagem iniciada pelo prefixo ERRO: que é tratada em um algoritmo em JavaScript.
        // Retorna um erro caso o usuário tente desativar a própria conta a partir do formulário.
        // Salva os novos dados do usuário na sessão caso o usuário esteja atualizando ele mesmo.
        public function atualizar() {
            $id = $_POST["id"];
            $nome = $_POST["txt_nome"];
            $ativo = $_POST["chk_ativo"];
            $email = $_POST["txt_email"];
            $nomeUsuario = $_POST["txt_usuario"];
            $senha = $_POST["txt_senha"];
            $idNivel = $_POST["slt_nivel"];
            $imagemAtual = $_POST["imagem_atual"];
            $nivelDao = new NivelDAO();
            $nivel = $nivelDao->selecionar($idNivel);
            
            if ($_SESSION["usuario"]->id == $id && $ativo != "1") {
                echo "ERRO:Você não pode se auto desativar.";
            } else {
                if (!empty($_FILES["img_foto"]["name"])) {
                    $arquivo = basename($_FILES["img_foto"]["name"]);
                    $nomeArquivo = pathinfo($arquivo, PATHINFO_FILENAME);
                    $extensao = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
                    $nomeCriptografado = md5($nomeArquivo . uniqid()) . "." . $extensao;
                    $caminhoArquivo = "../imagens/usuarios/" . $nomeCriptografado;
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

                $usuario = new Usuario();
                $usuario->id = $id;
                $usuario->nome = $nome;
                $usuario->ativo = $ativo;
                $usuario->email = $email;
                $usuario->imagem = $imagemAtual;
                $usuario->usuario = $nomeUsuario;
                $usuario->nivel = $nivel;
                $usuarioDao = new UsuarioDAO();
                $usuarioDao->atualizar($usuario);
                if (!empty($senha)) {
                    $usuarioDao->atualizarSenha($id, md5($senha));
                }
                
                if ($_SESSION["usuario"]->id == $id) {
                    $_SESSION["usuario"] = $usuario;
                }
                
                $this->montarHtml($usuario);
            }
        }
        
        // Método usado para tratar a requisição de uma exclusão de usúario.
        // Recebe o id de um usuário via GET.
        // Retorna o mesmo id como resposta para que a linha do usuário excluido seja removida do html, usando como referência este id.
        // Retorna um erro caso o usuário esteja tentando excluir ele mesmo.
        public function excluir() {
            $id = $_GET["id"];
            if ($_SESSION["usuario"]->id == $id) {
                echo json_encode(array("erro" => "Você não pode se auto excluir."));
            } else {
                $usuarioDao = new UsuarioDAO();
                $usuarioDao->excluir($id);
                echo json_encode(array("id" => $id));
            }
        }
        
        // Método usado para tratar a requisição de ativar e desativar um usuário.
        // Recebe o id de um usuário e um byte que diz se ele já está ativo ou não via GET.
        // Retorna uma mensagem para ser exibida em um modal na tela do CMS.
        // Retorna um erro caso o usuário esteja tentando desativar ele mesmo.
        public function ativar() {
            $id = $_GET["id"];
            $ativo = $_GET["ativo"];
            if ($_SESSION["usuario"]->id == $id) {
                echo "ERRO:Você não pode se auto desativar.";
            } else {
                $usuarioDao = new UsuarioDAO();
                $usuarioDao->ativar($id, $ativo == "1" ? 0 : 1);
                if ($ativo == "1") {
                    echo "Este usuário foi desabilitado e não poderá mais se logar.";
                } else {
                    echo "Este usuário foi habilitado e agora se logar.";
                }
            }
        }
    }
?>