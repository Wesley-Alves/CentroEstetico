<?php
    include_once("../model/item_fale_conosco.php");
    include_once("../database/fale_conosco_dao.php");

    // Classe controladora para o formulário da página "Fale Conosco" e a visualização dos dados via CMS.
    class FaleConoscoController {
        // Método usado para tratar a submissão do formulário de contato.
        // Recebe todos os dados do formulário via POST e monta um objeto para mandar para a classe DAO.
        public function gravar() {
            $item = new ItemFaleConosco();
            $item->nome = $_POST["txt_nome"];
            $item->email = $_POST["txt_email"];
            $item->telefone = $_POST["txt_telefone"];
            $item->celular = $_POST["txt_celular"];
            $item->profissao = $_POST["txt_profissao"];
            $item->sexo = $_POST["slt_sexo"];
            $item->homePage = $_POST["txt_home_page"];
            $item->facebook = $_POST["txt_facebook"];
            $item->produtos = $_POST["txt_produtos"];
            $item->comentarios = $_POST["txt_comentarios"];
            
            $faleConoscoDao = new FaleConoscoDAO();
            $faleConoscoDao->inserir($item);
        }
        
        // Método para montar a linha na tabela de itens na página de administração do CMS.
        // Recebe um objeto "fale conosco" e retorna o html em si.
        public function montarHtml($item) {
            ?>
            <div class="linha medio" data-id="<?= $item->id ?>">
                <div class="coluna nome vcenter">
                    <span><?= $item->nome ?></span>
                </div>
                <div class="coluna email pequeno vcenter">
                    <span><?= $item->email ?></span>
                </div>
                <div class="coluna celular vcenter">
                    <p><?= $item->celular ?></p>
                </div>
                <div class="coluna sugestao">
                    <p><?= $item->comentarios ?></p>
                </div>
                <div class="coluna acoes">
                    <a href="../controller/router.php?tipo=fale_conosco&modo=visualizar&id=<?= $item->id ?>" class="visualizar">
                        <img src="imagens/icones/visualizar.png" alt="Visualizar" title="Visualizar">
                    </a>
                    <a href="../controller/router.php?tipo=fale_conosco&modo=excluir&id=<?= $item->id ?>" class="excluir">
                        <img src="imagens/icones/excluir.png" alt="Excluir" title="Excluir">
                    </a>
                </div>
            </div>
            <?php
        }
        
        // Método para construir uma modal com o formulário de visualizar um envio da página Fale Conosco.
        // Recebe o id de um contato pelo método GET e retorna o html da modal pronto para abrir com o JQuery.
        // Foi usado uma form sem ação com inputs desabilitados para melhorar a visualização dos campos caso algum ultrapasse o limite da área pré-estabelecida.
        public function getModalVisualizar() {
            $id = $_GET["id"];
            $faleConoscoDao = new FaleConoscoDAO();
            $item = $faleConoscoDao->selecionar($id);
            ?>
            <div class="modal_form">
                <div class="body">
                    <div class="header clearfix">
                        <a href="#" class="fechar">×</a>
                        <h1 class="titulo">Dados do formulário</h1>
                    </div>
                    <div class="content">
                        <form action="#" id="form_fale_conosco">
                            <div class="grupo _50">
                                <label for="txt_nome">Nome:</label>
                                <input type="text" id="txt_nome" value="<?= $item->nome ?>" disabled>
                            </div>
                            <div class="grupo _50">
                                <label for="txt_email">Email:</label>
                                <input type="text" id="txt_email" value="<?= $item->email ?>" disabled>
                            </div>
                            <div class="grupo _33">
                                <label for="txt_telefone">Telefone:</label>
                                <input type="text" id="txt_telefone" value="<?= $item->telefone ?>" disabled>
                            </div>
                            <div class="grupo _34">
                                <label for="txt_celular">Celular:</label>
                                <input type="text" id="txt_celular" value="<?= $item->celular ?>" disabled>
                            </div>
                            <div class="grupo _33">
                                <label for="txt_sexo">Sexo:</label>
                                <input type="text" id="txt_sexo" value="<?= $item->sexo == "M" ? "Masculino" : "Feminino" ?>" disabled>
                            </div>
                            <div class="grupo _33">
                                <label for="txt_profissao">Profissão:</label>
                                <input type="text" id="txt_profissao" value="<?= $item->profissao ?>" disabled>
                            </div>
                            <div class="grupo _34">
                                <label for="txt_home_page">Home Page:</label>
                                <input type="text" id="txt_home_page" value="<?= $item->homePage ?>" disabled>
                            </div>
                            <div class="grupo _33">
                                <label for="txt_facebook">Facebook:</label>
                                <input type="text" id="txt_facebook" value="<?= $item->facebook ?>" disabled>
                            </div>
                            <div class="grupo _50">
                                <label for="txt_produtos">Produtos:</label>
                                <input type="text" id="txt_produtos" value="<?= $item->produtos ?>" disabled>
                            </div>
                            <div class="grupo _50">
                                <label for="txt_comentarios">Sugestões/Críticas:</label>
                                <input type="text" id="txt_comentarios" value="<?= $item->comentarios ?>" disabled>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php
        }
        
        // Método usado para tratar a requisição de uma exclusão de um item da página fale conosco.
        // Recebe o id de um contato via GET.
        // Retorna o mesmo id como resposta para que a linha do contato excluido seja removida do html, usando como referência este id.
        public function excluir() {
            $id = $_GET["id"];
            $faleConoscoDao = new FaleConoscoDAO();
            $faleConoscoDao->excluir($id);
            echo json_encode(array("id" => $id));
        }
    }
?>