<?php
    include_once("database.php");
    // Classe responsável por acessar o banco de dados de um objeto "categoria".
    class CategoriaDAO {
        // Seleciona todas as categorias do banco de dados.
        // Recebe um boolean ativo para filtrar se deve trazer somente as categorias ativas ou não.
        // Retorna um array de categorias.
        public function selecionarTodos($ativo) {
            $categorias = array();
            $conexao = getDatabaseConnection();
            $result = $conexao->query("SELECT * FROM tbl_categoria" . ($ativo ? "  WHERE ativo = 1" : ""));
            while ($data = $result->fetch_array()) {
                $categoria = new Categoria();
                $categoria->id = $data["id"];
                $categoria->nome = $data["nome"];
                $categoria->ativo = $data["ativo"];
                $categorias[] = $categoria;
            }
            
            $conexao->close();
            return $categorias;
        }
        
        // Seleciona uma única categoria do banco de dados.
        // Recebe o id da categoria e retorna um objeto categoria.
        public function selecionar($id) {
            $categoria = null;
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("SELECT * FROM tbl_categoria WHERE id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $result = get_result($statement);
            if ($data = array_shift($result)) {
                $categoria = new Categoria();
                $categoria->id = $data["id"];
                $categoria->nome = $data["nome"];
                $categoria->ativo = $data["ativo"];
            }
            
            $statement->close();
            $conexao->close();
            return $categoria;
        }
        
        // Insere uma nova categoria no banco de dados.
        // Recebe um objeto categoria e retorna o id inserido no banco.
        public function gravar($categoria) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("INSERT INTO tbl_categoria (nome, ativo) VALUES (?, ?)");
            $statement->bind_param("si", $categoria->nome, $categoria->ativo);
            $statement->execute();
            $id = $statement->insert_id;
            $statement->close();
            $conexao->close();
            return $id;
        }
        
        // Atualiza as informações de uma categoria no banco de dados.
        // Recebe um objeto categoria.
        public function atualizar($categoria) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_categoria SET nome = ?, ativo = ? WHERE id = ?");
            $statement->bind_param("sii", $categoria->nome, $categoria->ativo, $categoria->id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Ativa ou destiva uma categoria.
        // Recebe o id da categoria e um boolean que indica se ela será ativada ou desativada.
        public function ativar($id, $ativo) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_categoria SET ativo = ? WHERE id = ?");
            $statement->bind_param("ii", $ativo, $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Remove uma categoria do banco de dados.
        // Recebe o id da categoria.
        public function excluir($id) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("DELETE FROM tbl_subcategoria WHERE idCategoria = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
            
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("DELETE FROM tbl_categoria WHERE id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
    }
?>