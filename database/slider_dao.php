<?php
    // Arquivo responsável pelas funções do banco de dados das imagens do slider da página "Home".
    include_once("database.php");
    class SliderDAO {
        public function inserir($imagem) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("INSERT INTO tbl_slider (imagem, legenda, ativo) VALUES (?, ?, ?)");
            $statement->bind_param("ssi", $imagem->imagem, $imagem->legenda, $imagem->ativo);
            $statement->execute();
            $id = $statement->insert_id;
            $statement->close();
            $conexao->close();
            return $id;
        }
        
        public function selecionarTodos($ativo) {
            $imagens = array();
            $conexao = getDatabaseConnection();
            $result = $conexao->query("SELECT * FROM tbl_slider" . ($ativo ? " WHERE ativo = 1 " : ""));
            while ($data = $result->fetch_array()) {
                $imagem = new ImagemSlider();
                $imagem->id = $data["id"];
                $imagem->legenda = $data["legenda"];
                $imagem->imagem = $data["imagem"];
                $imagem->ativo = $data["ativo"];
                $imagens[] = $imagem;
            }
            
            $conexao->close();
            return $imagens;
        }
        
        public function selecionar($id) {
            $imagem = null;
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("SELECT * FROM tbl_slider WHERE id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $result = get_result($statement);
            if ($data = array_shift($result)) {
                $imagem = new ImagemSlider();
                $imagem->id = $data["id"];
                $imagem->legenda = $data["legenda"];
                $imagem->imagem = $data["imagem"];
                $imagem->ativo = $data["ativo"];
            }
            
            $statement->close();
            $conexao->close();
            return $imagem;
        }
        
        public function atualizar($imagem) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_slider SET legenda = ?, imagem = ?, ativo = ? WHERE id = ?");
            $statement->bind_param("ssii", $imagem->legenda, $imagem->imagem, $imagem->ativo, $imagem->id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        public function excluir($id) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("DELETE FROM tbl_slider WHERE id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        public function ativar($id, $ativo) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_slider SET ativo = ? WHERE id = ?");
            $statement->bind_param("ii", $ativo, $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
    }
?>