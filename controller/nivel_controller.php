<?php
    include_once("../model/nivel.php");
    include_once("../database/nivel_dao.php");
    include_once("../database/usuario_dao.php");
    
    // Classe controladora para os níveis de usuário.
    class NivelController {
        // Método para montar a linha na tabela de itens na página de administração do CMS.
        // Recebe um objeto "nível" e retorna o html em si.
        public function montarHtml($nivel) {
            ?>
            <div class="linha" data-id="<?= $nivel->id ?>">
                <div class="coluna titulo vcenter">
                    <span><?= $nivel->titulo ?></span>
                </div>
                <div class="coluna perm vcenter">
                    <p><?= $nivel->permFaleConosco ? "Sim" : "Não" ?></p>
                </div>
                <div class="coluna perm vcenter">
                    <p><?= $nivel->permConteudo ? "Sim" : "Não" ?></p>
                </div>
                <div class="coluna perm vcenter">
                    <p><?= $nivel->permProdutos ? "Sim" : "Não" ?></p>
                </div>
                <div class="coluna perm vcenter">
                    <p><?= $nivel->permUsuarios ? "Sim" : "Não" ?></p>
                </div>
                <div class="coluna ativo">
                    <a href="../controller/router.php?tipo=nivel&modo=ativar&id=<?= $nivel->id ?>" class="ativar">
                        <?php if ($nivel->ativo) { ?>
                            <img src="imagens/icones/habilitado.png" alt="Habilitado" title="Habilitado" data-ativo="1">
                        <?php } else { ?>
                            <img src="imagens/icones/desabilitado.png" alt="Desabilitado" title="Desabilitado" data-ativo="0">
                        <?php } ?>
                    </a>
                </div>
                <div class="coluna acoes">
                    <a href="../controller/router.php?tipo=nivel&modo=editar&id=<?= $nivel->id ?>" class="editar">
                        <img src="imagens/icones/editar.png" alt="Editar" title="Editar">
                    </a>
                    <a href="../controller/router.php?tipo=nivel&modo=excluir&id=<?= $nivel->id ?>" class="excluir" data-titulo="<?= $nivel->titulo ?>">
                        <img src="imagens/icones/excluir.png" alt="Excluir" title="Excluir">
                    </a>
                </div>
            </div>
            <?php
        }
        
        // Método para construir uma modal com o formulário de adicionar um novo nível.
        // Retorna o html da modal pronto para abrir com o JQuery.
        public function getModalAdicionar() {
            ?>
            <div class="modal_form">
                <div class="body">
                    <div class="header clearfix">
                        <a href="#" class="fechar">×</a>
                        <h1 class="titulo">Adicionar Nível de Usuário</h1>
                    </div>
                    <div class="content">
                        <form action="../controller/router.php?tipo=nivel&modo=gravar" id="form_adicionar">
                            <div class="grupo _75">
                                <label for="txt_titulo">Título:</label>
                                <input type="text" name="txt_titulo" id="txt_titulo" maxlength="100" required>
                            </div>
                            <div class="grupo _25">
                                <p class="label">Ativar:</p>
                                <label class="switch">
                                    <input type="hidden" name="chk_ativo" value="0">
                                    <input type="checkbox" name="chk_ativo" value="1" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="grupo center">
                                <input type="hidden" name="chk_perm_fale_conosco" value="0" checked>
                                <input type="checkbox" name="chk_perm_fale_conosco" id="chk_perm_fale_conosco" value="1">
                                <label for="chk_perm_fale_conosco" class="lbl_checkbox">Permissão para administrar Fale Conosco</label>
                            </div>
                            <div class="grupo center">
                                <input type="hidden" name="chk_perm_conteudo" value="0" checked>
                                <input type="checkbox" name="chk_perm_conteudo" id="chk_perm_conteudo" value="1">
                                <label for="chk_perm_conteudo" class="lbl_checkbox">Permissão para administrar Conteúdo</label>
                            </div>
                            <div class="grupo center">
                                <input type="hidden" name="chk_perm_produtos" value="0" checked>
                                <input type="checkbox" name="chk_perm_produtos" id="chk_perm_produtos" value="1">
                                <label for="chk_perm_produtos" class="lbl_checkbox">Permissão para administrar Produtos</label>
                            </div>
                            <div class="grupo center">
                                <input type="hidden" name="chk_perm_usuarios" value="0" checked>
                                <input type="checkbox" name="chk_perm_usuarios" id="chk_perm_usuarios" value="1">
                                <label for="chk_perm_usuarios" class="lbl_checkbox">Permissão para administrar Usuários</label>
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
        
        // Método para construir uma modal com o formulário de atualizar um nível já existente.
        // Recebe o id de um nível pelo método GET e retorna o html da modal pronto para abrir com o JQuery.
        public function getModalEditar() {
            $id = $_GET["id"];
            $nivelDao = new NivelDAO();
            $nivel = $nivelDao->selecionar($id);
            ?>
            <div class="modal_form">
                <div class="body">
                    <div class="header clearfix">
                        <a href="#" class="fechar">×</a>
                        <h1 class="titulo"><?= $nivel->titulo ?></h1>
                    </div>
                    <div class="content">
                        <form action="../controller/router.php?tipo=nivel&modo=atualizar" id="form_atualizar">
                            <input type="hidden" name="id" value="<?= $nivel->id ?>">
                            <div class="grupo _75">
                                <label for="txt_titulo">Título:</label>
                                <input type="text" name="txt_titulo" id="txt_titulo" maxlength="100" required value="<?= $nivel->titulo ?>">
                            </div>
                            <div class="grupo _25">
                                <p class="label">Ativar:</p>
                                <label class="switch">
                                    <input type="hidden" name="chk_ativo" value="0" <?= $nivel->ativo == "0" ? "checked" : "" ?>>
                                    <input type="checkbox" name="chk_ativo" value="1" <?= $nivel->ativo == "1" ? "checked" : "" ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="grupo center">
                                <input type="hidden" name="chk_perm_fale_conosco" value="0" <?= $nivel->permFaleConosco == "0" ? "checked" : "" ?>>
                                <input type="checkbox" name="chk_perm_fale_conosco" id="chk_perm_fale_conosco" value="1" <?= $nivel->permFaleConosco == "1" ? "checked" : "" ?>>
                                <label for="chk_perm_fale_conosco" class="lbl_checkbox">Permissão para administrar Fale Conosco</label>
                            </div>
                            <div class="grupo center">
                                <input type="hidden" name="chk_perm_conteudo" value="0" <?= $nivel->permConteudo == "0" ? "checked" : "" ?>>
                                <input type="checkbox" name="chk_perm_conteudo" id="chk_perm_conteudo" value="1" <?= $nivel->permConteudo == "1" ? "checked" : "" ?>>
                                <label for="chk_perm_conteudo" class="lbl_checkbox">Permissão para administrar Conteúdo</label>
                            </div>
                            <div class="grupo center">
                                <input type="hidden" name="chk_perm_produtos" value="0" <?= $nivel->permProdutos == "0" ? "checked" : "" ?>>
                                <input type="checkbox" name="chk_perm_produtos" id="chk_perm_produtos" value="1" <?= $nivel->permProdutos == "1" ? "checked" : "" ?>>
                                <label for="chk_perm_produtos" class="lbl_checkbox">Permissão para administrar Produtos</label>
                            </div>
                            <div class="grupo center">
                                <input type="hidden" name="chk_perm_usuarios" value="0" <?= $nivel->permUsuarios == "0" ? "checked" : "" ?>>
                                <input type="checkbox" name="chk_perm_usuarios" id="chk_perm_usuarios" value="1" <?= $nivel->permUsuarios == "1" ? "checked" : "" ?>>
                                <label for="chk_perm_usuarios" class="lbl_checkbox">Permissão para administrar Usuários</label>
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
        
        // Método para tratar a submissão do formulário de adicionar um novo nível.
        // Recebe os dados do formulário via POST e monta um objeto para ser tratado pelo DAO.
        public function gravar() {
            $nivel = new Nivel();
            $nivel->titulo = $_POST["txt_titulo"];
            $nivel->ativo = $_POST["chk_ativo"];
            $nivel->permFaleConosco = $_POST["chk_perm_fale_conosco"];
            $nivel->permConteudo = $_POST["chk_perm_conteudo"];
            $nivel->permProdutos = $_POST["chk_perm_produtos"];
            $nivel->permUsuarios = $_POST["chk_perm_usuarios"];
            
            $nivelDao = new NivelDAO();
            $id = $nivelDao->inserir($nivel);
            $nivel->id = $id;
            $this->montarHtml($nivel);
        }
        
        // Método para tratar a submissão do formulário de atualizar um nível já existente.
        // Recebe os dados do formulário via POST e monta um objeto para ser tratado pelo DAO.
        public function atualizar() {
            $nivel = new Nivel();
            $nivel->id = $_POST["id"];
            $nivel->titulo = $_POST["txt_titulo"];
            $nivel->ativo = $_POST["chk_ativo"];
            $nivel->permFaleConosco = $_POST["chk_perm_fale_conosco"];
            $nivel->permConteudo = $_POST["chk_perm_conteudo"];
            $nivel->permProdutos = $_POST["chk_perm_produtos"];
            $nivel->permUsuarios = $_POST["chk_perm_usuarios"];
            
            $nivelDao = new NivelDAO();
            $nivelDao->atualizar($nivel);
            $this->montarHtml($nivel);
        }
        
        // Método usado para tratar a requisição de uma exclusão de nível.
        // Recebe o id de um nível via GET.
        // Retorna o mesmo id como resposta para que a linha do nível excluido seja removida do html, usando como referência este id.
        // Caso exista algum usuário já cadastrado no nível, retorna uma mensagem de erro proibindo a exclusão.
        public function excluir() {
            $id = $_GET["id"];
            $usuarioDao = new UsuarioDAO();
            if ($usuarioDao->checarUsuariosPorNivel($id)) {
                echo json_encode(array("error" => "Não é possível deletar um nível enquanto houver um usuário cadastrado nele."));
            } else {
                $nivelDao = new NivelDAO();
                $nivelDao->excluir($id);
                echo json_encode(array("id" => $id));
            }
        }
        
        // Método usado para tratar a requisição de ativar e desativar um nível.
        // Recebe o id de um nível e um byte que diz se ele já está ativo ou não via GET.
        // Retorna uma mensagem para ser exibida em um modal na tela do CMS.
        public function ativar() {
            $id = $_GET["id"];
            $ativo = $_GET["ativo"];
            $nivelDao = new NivelDAO();
            $nivelDao->ativar($id, $ativo == "1" ? 0 : 1);
            if ($ativo == "1") {
                echo "Este nível foi desabilitado e não poderá mais ser associado à usuários.";
            } else {
                echo "Este nível foi habilitado e agora poderá ser associado à usuários.";
            }
        }
    }
?>