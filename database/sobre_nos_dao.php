<?php
    include_once("database.php");
    // Classe responsável por acessar o banco de dados de um objeto da página "sobre nós".
    class SobreNosDAO {
        // Insere um novo item no banco de dados.
        // Recebe um objeto item e retorna o id inserido no banco.
        public function inserir($item) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("INSERT INTO tbl_sobre_nos (titulo, imagem, texto, ativo) VALUES (?, ?, ?, ?)");
            $statement->bind_param("sssi", $item->titulo, $item->imagem, $item->texto, $item->ativo);
            $statement->execute();
            $id = $statement->insert_id;
            $statement->close();
            $conexao->close();
            return $id;
        }
        
        // Seleciona todos os itens do banco de dados.
        // Recebe um boolean ativo para filtrar se deve trazer somente os itens ativos ou não.
        // Retorna um array de itens.
        public function selecionarTodos($ativo) {
            $itens = array();
            $conexao = getDatabaseConnection();
            $result = $conexao->query("SELECT * FROM tbl_sobre_nos" . ($ativo ? " WHERE ativo = 1 " : ""));
            while ($data = $result->fetch_array()) {
                $item = new ItemSobreNos();
                $item->id = $data["id"];
                $item->titulo = $data["titulo"];
                $item->imagem = $data["imagem"];
                $item->texto = $data["texto"];
                $item->ativo = $data["ativo"];
                $itens[] = $item;
            }
            
            $conexao->close();
            return $itens;
        }
        
        // Seleciona um único item do banco de dados.
        // Recebe o id do item e retorna um objeto item.
        public function selecionar($id) {
            $item = null;
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("SELECT * FROM tbl_sobre_nos WHERE id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $result = get_result($statement);
            if ($data = array_shift($result)) {
                $item = new ItemSobreNos();
                $item->id = $data["id"];
                $item->titulo = $data["titulo"];
                $item->imagem = $data["imagem"];
                $item->texto = $data["texto"];
                $item->ativo = $data["ativo"];
            }
            
            $statement->close();
            $conexao->close();
            return $item;
        }
        
        // Atualiza as informações de um item no banco de dados.
        // Recebe um objeto item.
        public function atualizar($item) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_sobre_nos SET titulo = ?, imagem = ?, texto = ?, ativo = ? WHERE id = ?");
            $statement->bind_param("sssii", $item->titulo, $item->imagem, $item->texto, $item->ativo, $item->id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Remove um item do banco de dados.
        // Recebe o id do item.
        public function excluir($id) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("DELETE FROM tbl_sobre_nos WHERE id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Ativa ou destiva um item.
        // Recebe o id do item e um boolean que indica se ele será ativado ou desativado.
        public function ativar($id, $ativo) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_sobre_nos SET ativo = ? WHERE id = ?");
            $statement->bind_param("ii", $ativo, $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
    }
?>