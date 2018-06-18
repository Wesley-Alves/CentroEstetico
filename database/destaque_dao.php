<?php
    include_once("database.php");
    // Classe responsável por acessar o banco de dados de um objeto "destaque".
    class DestaqueDAO {
        // Insere um novo destaque no banco de dados.
        // Recebe um objeto destaque e retorna o id inserido no banco.
        public function inserir($destaque) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("INSERT INTO tbl_destaque (titulo, imagem, texto, ativo) VALUES (?, ?, ?, ?)");
            $statement->bind_param("sssi", $destaque->titulo, $destaque->imagem, $destaque->texto, $destaque->ativo);
            $statement->execute();
            $id = $statement->insert_id;
            $statement->close();
            $conexao->close();
            return $id;
        }
        
        // Seleciona todos os destaques do banco de dados.
        // Recebe um boolean ativo para filtrar se deve trazer somente os destaques ativos ou não.
        // Retorna um array de destaques.
        public function selecionarTodos($ativo) {
            $destaques = array();
            $conexao = getDatabaseConnection();
            $result = $conexao->query("SELECT * FROM tbl_destaque" . ($ativo ? " WHERE ativo = 1 " : ""));
            while ($data = $result->fetch_array()) {
                $destaque = new Destaque();
                $destaque->id = $data["id"];
                $destaque->titulo = $data["titulo"];
                $destaque->imagem = $data["imagem"];
                $destaque->texto = $data["texto"];
                $destaque->ativo = $data["ativo"];
                $destaques[] = $destaque;
            }
            
            $conexao->close();
            return $destaques;
        }
        
        // Seleciona um único destaque do banco de dados.
        // Recebe o id do destaque e retorna um objeto destaque.
        public function selecionar($id) {
            $destaque = null;
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("SELECT * FROM tbl_destaque WHERE id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $result = get_result($statement);
            if ($data = array_shift($result)) {
                $destaque = new Destaque();
                $destaque->id = $data["id"];
                $destaque->titulo = $data["titulo"];
                $destaque->imagem = $data["imagem"];
                $destaque->texto = $data["texto"];
                $destaque->ativo = $data["ativo"];
            }
            
            $statement->close();
            $conexao->close();
            return $destaque;
        }
        
        // Atualiza as informações de um destaque no banco de dados.
        // Recebe um objeto destaque.
        public function atualizar($destaque) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_destaque SET titulo = ?, imagem = ?, texto = ?, ativo = ? WHERE id = ?");
            $statement->bind_param("sssii", $destaque->titulo, $destaque->imagem, $destaque->texto, $destaque->ativo, $destaque->id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Remove um destaque do banco de dados.
        // Recebe o id do destaque.
        public function excluir($id) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("DELETE FROM tbl_destaque WHERE id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Ativa ou destiva um destaque.
        // Recebe o id do destaque e um boolean que indica se ele será ativado ou desativado.
        public function ativar($id, $ativo) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_destaque SET ativo = ? WHERE id = ?");
            $statement->bind_param("ii", $ativo, $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
    }
?>