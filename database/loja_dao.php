<?php    
    include_once("database.php");
    // Classe responsável por acessar o banco de dados de um objeto "loja".
    class LojaDAO {
        // Seleciona todos os estados cadastrados no banco de dados.
        // Retorna um array de estados.
        public function selecionarEstados() {
            $estados = array();
            $conexao = getDatabaseConnection();
            $result = $conexao->query("SELECT * FROM tbl_estado");
            while ($data = $result->fetch_array()) {
                $estados[] = $data;
            }
            
            $conexao->close();
            return $estados;
        }
        
        // Seleciona todas as cidades cadastradas em um estado.
        // Recebe o id do estado e retorna um array de cidades.
        public function selecionarCidades($idEstado) {
            $cidades = array();
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("SELECT * FROM tbl_cidade WHERE idEstado = ?");
            $statement->bind_param("i", $idEstado);
            $statement->execute();
            $result = get_result($statement);
            while ($data = array_shift($result)) {
                $cidades[] = $data;
            }
            
            $statement->close();
            $conexao->close();
            return $cidades;
        }
        
        // Insere as informações de endereço de uma loja no banco de dados.
        // Recebe um objeto loja e retorna o id do endereço inserido.
        public function inserirEndereco($loja) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("INSERT INTO tbl_endereco (logradouro, numero, bairro, cep, idCidade) VALUES (?, ?, ?, ?, ?)");
            $statement->bind_param("ssssi", $loja->logradouro, $loja->numero, $loja->bairro, $loja->cep, $loja->idCidade);
            $statement->execute();
            $id = $statement->insert_id;
            $statement->close();
            $conexao->close();
            return $id;
        }
        
        // Insere uma loja no banco de dados.
        // Recebe um objeto loja e retorna o id da loja inserida.
        public function inserir($loja) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("INSERT INTO tbl_loja (idEndereco, telefone, ativo) VALUES (?, ?, ?)");
            $statement->bind_param("isi", $loja->idEndereco, $loja->telefone, $loja->ativo);
            $statement->execute();
            $id = $statement->insert_id;
            $statement->close();
            $conexao->close();
            return $id;
        }
        
        // Insere todas as fotos da loja no banco de dados.
        // Recebe um objeto loja.
        public function inserirFotos($loja) {
            $conexao = getDatabaseConnection();
            foreach ($loja->fotos as $foto) {
                $statement = $conexao->prepare("INSERT INTO tbl_foto_loja (imagem, idLoja) VALUES (?, ?)");
                $statement->bind_param("si", $foto, $loja->id);
                $statement->execute();
                $statement->close();
            }
            
            $conexao->close();
        }
        
        // Seleciona uma única loja do banco de dados.
        // Recebe o id da loja e retorna um objeto loja.
        public function selecionar($id) {
            $loja = null;
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("SELECT l.*, e.logradouro, e.numero, e.bairro, e.cep, c.id AS idCidade, c.nome AS cidade, es.id AS idEstado, es.nome AS estado, es.uf FROM tbl_loja AS l INNER JOIN tbl_endereco AS e ON l.idEndereco = e.id INNER JOIN tbl_cidade AS c ON e.idCidade = c.id INNER JOIN tbl_estado AS es ON c.idEstado = es.id WHERE l.id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $result = get_result($statement);
            if ($data = array_shift($result)) {
                $loja = new Loja();
                $loja->id = $data["id"];
                $loja->telefone = $data["telefone"];
                $loja->ativo = $data["ativo"];
                $loja->idEndereco = $data["idEndereco"];
                $loja->logradouro = $data["logradouro"];
                $loja->numero = $data["numero"];
                $loja->bairro = $data["bairro"];
                $loja->cep = $data["cep"];
                $loja->idCidade = $data["idCidade"];
                $loja->cidade = $data["cidade"];
                $loja->idEstado = $data["idEstado"];
                $loja->estado = $data["estado"];
                $loja->uf = $data["uf"];
                $loja->fotos = $this->selecionarFotos($loja->id);
            }
            
            $statement->close();
            $conexao->close();
            return $loja;
        }
        
        // Seleciona todas as fotos de uma loja.
        // Recebe o id da loja e retorna um array de fotos.
        public function selecionarFotos($idLoja) {
            $fotos = array();
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("SELECT imagem FROM tbl_foto_loja WHERE idLoja = ?");
            $statement->bind_param("i", $idLoja);
            $statement->execute();
            $result = get_result($statement);
            while ($data = array_shift($result)) {
                $fotos[] = $data["imagem"];
            }
            
            $statement->close();
            $conexao->close();
            return $fotos;
        }
        
        // Seleciona todos as lojas do banco de dados.
        // Recebe um boolean ativo para filtrar se deve trazer somente as lojas ativas ou não.
        // Retorna um array de lojas.
        public function selecionarTodos($ativo) {
            $lojas = array();
            $conexao = getDatabaseConnection();
            $result = $conexao->query("SELECT l.*, e.logradouro, e.numero, e.bairro, e.cep, c.nome AS cidade, es.nome AS estado, es.uf FROM tbl_loja AS l INNER JOIN tbl_endereco AS e ON l.idEndereco = e.id INNER JOIN tbl_cidade AS c ON e.idCidade = c.id INNER JOIN tbl_estado AS es ON c.idEstado = es.id" . ($ativo ? " WHERE l.ativo = 1" : ""));
            while ($data = $result->fetch_array()) {
                $loja = new Loja();
                $loja->id = $data["id"];
                $loja->telefone = $data["telefone"];
                $loja->ativo = $data["ativo"];
                $loja->logradouro = $data["logradouro"];
                $loja->numero = $data["numero"];
                $loja->bairro = $data["bairro"];
                $loja->cep = $data["cep"];
                $loja->cidade = $data["cidade"];
                $loja->estado = $data["estado"];
                $loja->uf = $data["uf"];
                $loja->fotos = $this->selecionarFotos($loja->id);
                $lojas[] = $loja;
            }
            
            $conexao->close();
            return $lojas;
        }
        
        // Atualiza as informações de uma loja no banco de dados.
        // Recebe um objeto loja.
        public function atualizar($loja) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_loja SET telefone = ?, ativo = ? WHERE id = ?");
            $statement->bind_param("sii", $loja->telefone, $loja->ativo, $loja->id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Atualiza as informações do endereço de uma loja no banco de dados.
        // Recebe um objeto loja.
        public function atualizarEndereco($loja) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_endereco SET logradouro = ?, numero = ?, bairro = ?, cep = ?, idCidade = ? WHERE id = ?");
            $statement->bind_param("ssssii", $loja->logradouro, $loja->numero, $loja->bairro, $loja->cep, $loja->idCidade, $loja->id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Remove uma loja e seu endereço do banco de dados.
        // Recebe o id da loja.
        public function excluir($id) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("DELETE l, e FROM tbl_loja AS l INNER JOIN tbl_endereco AS e ON l.idEndereco = e.id WHERE l.id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Remove todas as fotos de uma loja do banco de dados.
        // Recebe o id da loja.
        public function excluirFotos($idLoja) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("DELETE FROM tbl_foto_loja WHERE idLoja = ?");
            $statement->bind_param("i", $idLoja);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Ativa ou destiva um loja.
        // Recebe o id da loja e um boolean que indica se ela será ativada ou desativada.
        public function ativar($id, $ativo) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_loja SET ativo = ? WHERE id = ?");
            $statement->bind_param("ii", $ativo, $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
    }
?>