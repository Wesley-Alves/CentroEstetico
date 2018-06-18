<?php
    include_once("database.php");
    // Classe responsável por acessar o banco de dados de um objeto "fale conosco".
    class FaleConoscoDAO {
        // Insere um novo contato no banco de dados.
        // Recebe um objeto com os dados do formulário.
        public function inserir($item) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("INSERT INTO tbl_fale_conosco (nome, email, telefone, celular, profissao, sexo, homePage, facebook, produtos, comentarios) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $statement->bind_param("ssssssssss", $item->nome, $item->email, $item->telefone, $item->celular, $item->profissao, $item->sexo, $item->homePage, $item->facebook, $item->produtos, $item->comentarios);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Seleciona todos os contatos do banco de dados ordenado do mais novo para o mais antigo.
        public function selecionarTodos() {
            $itens = array();
            $conexao = getDatabaseConnection();
            $result = $conexao->query("SELECT * FROM tbl_fale_conosco ORDER BY id DESC");
            while ($data = $result->fetch_array()) {
                $item = new ItemFaleConosco();
                $item->id = $data["id"];
                $item->nome = $data["nome"];
                $item->email = $data["email"];
                $item->telefone = $data["telefone"];
                $item->celular = $data["celular"];
                $item->profissao = $data["profissao"];
                $item->sexo = $data["sexo"];
                $item->homePage = $data["homePage"];
                $item->facebook = $data["facebook"];
                $item->produtos = $data["produtos"];
                $item->comentarios = $data["comentarios"];
                $itens[] = $item;
            }
            
            $conexao->close();
            return $itens;
        }
        
        // Seleciona um único contato do banco de dados.
        // Recebe o id do contato e retorna um objeto com os dados do formulário.
        public function selecionar($id) {
            $item = null;
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("SELECT * FROM tbl_fale_conosco WHERE id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $result = get_result($statement);
            if ($data = array_shift($result)) {
                $item = new ItemFaleConosco();
                $item->id = $data["id"];
                $item->nome = $data["nome"];
                $item->email = $data["email"];
                $item->telefone = $data["telefone"];
                $item->celular = $data["celular"];
                $item->profissao = $data["profissao"];
                $item->sexo = $data["sexo"];
                $item->homePage = $data["homePage"];
                $item->facebook = $data["facebook"];
                $item->produtos = $data["produtos"];
                $item->comentarios = $data["comentarios"];
            }
            
            $statement->close();
            $conexao->close();
            return $item;
        }
        
        // Remove um contato do banco de dados.
        // Recebe o id do contato.
        public function excluir($id) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("DELETE FROM tbl_fale_conosco WHERE id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
    }
?>