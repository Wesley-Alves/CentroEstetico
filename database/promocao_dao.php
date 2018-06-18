<?php
    include_once("database.php");
    // Classe responsável por acessar o banco de dados de um objeto "promoção".
    class PromocaoDAO {
        // Insere uma nova promoção no banco de dados.
        // Recebe um objeto promoção e retorna o id inserido no banco.
        public function inserir($promocao) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("INSERT INTO tbl_promocao (idProduto, preco, ativo) VALUES (?, ?, ?)");
            $statement->bind_param("idi", $promocao->idProduto, $promocao->preco, $promocao->ativo);
            $statement->execute();
            $id = $statement->insert_id;
            $statement->close();
            $conexao->close();
            return $id;
        }
        
        // Seleciona todos as promoções do banco de dados.
        // Recebe um boolean ativo para filtrar se deve trazer somente as promoções ativas ou não.
        // Retorna um array de promoções.
        public function selecionarTodos($ativo) {
            $promocoes = array();
            $conexao = getDatabaseConnection();
            $result = $conexao->query("SELECT pc.*, pd.titulo, pd.preco AS precoAntigo, pd.descricao, pd.imagem FROM tbl_promocao AS pc INNER JOIN tbl_produto AS pd ON pc.idProduto = pd.id" . ($ativo ? " WHERE pc.ativo = 1" : ""));
            while ($data = $result->fetch_array()) {
                $promocao = new Promocao();
                $promocao->id = $data["id"];
                $promocao->novoPreco = $data["preco"];
                $promocao->ativo = $data["ativo"];
                $promocao->titulo = $data["titulo"];
                $promocao->preco = $data["precoAntigo"];
                $promocao->descricao = $data["descricao"];
                $promocao->imagem = $data["imagem"];
                $promocoes[] = $promocao;
            }
            
            $conexao->close();
            return $promocoes;
        }
        
        // Seleciona uma única promoção do banco de dados.
        // Recebe o id da promoção e retorna um objeto promoção.
        public function selecionar($id) {
            $promocao = null;
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("SELECT pc.*, pd.titulo, pd.preco AS precoAntigo, pd.descricao, pd.imagem FROM tbl_promocao AS pc INNER JOIN tbl_produto AS pd ON pc.idProduto = pd.id WHERE pc.id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $result = get_result($statement);
            if ($data = array_shift($result)) {
                $promocao = new Promocao();
                $promocao->id = $data["id"];
                $promocao->idProduto = $data["idProduto"];
                $promocao->novoPreco = $data["preco"];
                $promocao->ativo = $data["ativo"];
                $promocao->titulo = $data["titulo"];
                $promocao->preco = $data["precoAntigo"];
                $promocao->descricao = $data["descricao"];
                $promocao->imagem = $data["imagem"];
            }
            
            $statement->close();
            $conexao->close();
            return $promocao;
        }
        
        // Atualiza as informações de uma promoção no banco de dados.
        // Recebe um objeto promoção.
        public function atualizar($promocao) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_promocao SET idProduto = ?, preco = ?, ativo = ? WHERE id = ?");
            $statement->bind_param("idii", $promocao->idProduto, $promocao->preco, $promocao->ativo, $promocao->id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Remove uma promoção do banco de dados.
        // Recebe o id da promoção.
        public function excluir($id) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("DELETE FROM tbl_promocao WHERE id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Ativa ou destiva uma promoção.
        // Recebe o id da promoção e um boolean que indica se ela será ativada ou desativada.
        public function ativar($id, $ativo) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_promocao SET ativo = ? WHERE id = ?");
            $statement->bind_param("ii", $ativo, $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
    }
?>