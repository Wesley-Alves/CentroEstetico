<?php
    include_once("database.php");
    // Classe responsável por acessar o banco de dados de um objeto "subcategoria".
    class SubcategoriaDAO {
        // Seleciona todas as subcategorias do banco de dados.
        // Recebe um boolean ativo para filtrar se deve trazer somente as subcategorias ativas ou não.
        // Retorna um array de subcategorias.
        public function selecionarTodos($idCategoria, $ativo) {
            $subcategorias = array();
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("SELECT * FROM tbl_subcategoria WHERE idCategoria = ?" . ($ativo ? " AND ativo = 1" : ""));
            $statement->bind_param("i", $idCategoria);
            $statement->execute();
            $result = get_result($statement);
            while ($data = array_shift($result)) {
                $subcategoria = new Subcategoria();
                $subcategoria->id = $data["id"];
                $subcategoria->nome = $data["nome"];
                $subcategoria->ativo = $data["ativo"];
                $subcategoria->idCategoria = $data["idCategoria"];
                $subcategorias[] = $subcategoria;
            }
            
            $statement->close();
            $conexao->close();
            return $subcategorias;
        }
        
        // Seleciona uma única subcategoria do banco de dados.
        // Recebe o id da subcategoria e retorna um objeto subcategoria.
        public function selecionar($id) {
            $subcategoria = null;
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("SELECT * FROM tbl_subcategoria WHERE id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $result = get_result($statement);
            if ($data = array_shift($result)) {
                $subcategoria = new Subcategoria();
                $subcategoria->id = $data["id"];
                $subcategoria->nome = $data["nome"];
                $subcategoria->ativo = $data["ativo"];
                $subcategoria->idCategoria = $data["idCategoria"];
            }
            
            $statement->close();
            $conexao->close();
            return $subcategoria;
        }
        
        // Insere uma nova subcategoria no banco de dados.
        // Recebe um objeto subcategoria e retorna o id inserido no banco.
        public function gravar($subcategoria) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("INSERT INTO tbl_subcategoria (nome, ativo, idCategoria) VALUES (?, ?, ?)");
            $statement->bind_param("sii", $subcategoria->nome, $subcategoria->ativo, $subcategoria->idCategoria);
            $statement->execute();
            $id = $statement->insert_id;
            $statement->close();
            $conexao->close();
            return $id;
        }
        
        // Atualiza as informações de uma subcategoria no banco de dados.
        // Recebe um objeto subcategoria.
        public function atualizar($subcategoria) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_subcategoria SET nome = ?, ativo = ? WHERE id = ?");
            $statement->bind_param("sii", $subcategoria->nome, $subcategoria->ativo, $subcategoria->id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Ativa ou destiva uma subcategoria.
        // Recebe o id da subcategoria e um boolean que indica se ela será ativada ou desativada.
        public function ativar($id, $ativo) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_subcategoria SET ativo = ? WHERE id = ?");
            $statement->bind_param("ii", $ativo, $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Remove uma subcategoria do banco de dados.
        // Recebe o id da subcategoria.
        public function excluir($id) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("DELETE FROM tbl_subcategoria WHERE id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
    }
?>