<?php
    include_once("database.php");
    // Classe responsável por acessar o banco de dados de um objeto "nível".
    class NivelDAO {
        // Insere um novo nível no banco de dados.
        // Recebe um objeto nível e retorna o id inserido no banco.
        public function inserir($nivel) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("INSERT INTO tbl_nivel_usuario (titulo, permFaleConosco, permConteudo, permProdutos, permUsuarios, ativo) VALUES (?, ?, ?, ?, ?, ?)");
            $statement->bind_param("siiiii", $nivel->titulo, $nivel->permFaleConosco, $nivel->permConteudo, $nivel->permProdutos, $nivel->permUsuarios, $nivel->ativo);
            $statement->execute();
            $id = $statement->insert_id;
            $statement->close();
            $conexao->close();
            return $id;
        }
        
        // Seleciona todos os níveis do banco de dados.
        // Recebe um boolean ativo para filtrar se deve trazer somente os níveis ativos ou não.
        // Retorna um array de níveis.
        public function selecionarTodos($ativo) {
            $niveis = array();
            $conexao = getDatabaseConnection();
            $result = $conexao->query("SELECT * FROM tbl_nivel_usuario" . ($ativo ? " WHERE ativo = 1 " : ""));
            while ($data = $result->fetch_array()) {
                $nivel = new Nivel();
                $nivel->id = $data["id"];
                $nivel->titulo = $data["titulo"];
                $nivel->permFaleConosco = $data["permFaleConosco"];
                $nivel->permConteudo = $data["permConteudo"];
                $nivel->permProdutos = $data["permProdutos"];
                $nivel->permUsuarios = $data["permUsuarios"];
                $nivel->ativo = $data["ativo"];
                $niveis[] = $nivel;
            }
            
            $conexao->close();
            return $niveis;
        }
        
        // Seleciona um único nível do banco de dados.
        // Recebe o id do nível e retorna um objeto nível.
        public function selecionar($id) {
            $nivel = null;
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("SELECT * FROM tbl_nivel_usuario WHERE id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $result = get_result($statement);
            if ($data = array_shift($result)) {
                $nivel = new Nivel();
                $nivel->id = $data["id"];
                $nivel->titulo = $data["titulo"];
                $nivel->permFaleConosco = $data["permFaleConosco"];
                $nivel->permConteudo = $data["permConteudo"];
                $nivel->permProdutos = $data["permProdutos"];
                $nivel->permUsuarios = $data["permUsuarios"];
                $nivel->ativo = $data["ativo"];
            }
            
            $statement->close();
            $conexao->close();
            return $nivel;
        }
        
        // Atualiza as informações de um nível no banco de dados.
        // Recebe um objeto nível.
        public function atualizar($nivel) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_nivel_usuario SET titulo = ?, permFaleConosco = ?, permConteudo = ?, permProdutos = ?, permUsuarios = ?, ativo = ? WHERE id = ?");
            $statement->bind_param("siiiiii", $nivel->titulo, $nivel->permFaleConosco, $nivel->permConteudo, $nivel->permProdutos, $nivel->permUsuarios, $nivel->ativo, $nivel->id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Remove um nível do banco de dados.
        // Recebe o id do nível.
        public function excluir($id) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("DELETE FROM tbl_nivel_usuario WHERE id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Ativa ou destiva um nível.
        // Recebe o id do nível e um boolean que indica se ele será ativado ou desativado.
        public function ativar($id, $ativo) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_nivel_usuario SET ativo = ? WHERE id = ?");
            $statement->bind_param("ii", $ativo, $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
    }
?>