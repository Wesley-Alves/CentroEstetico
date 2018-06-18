<?php
    include_once("database.php");
    // Classe responsável por acessar o banco de dados de um objeto "produto" e suas informações relacionadas a "produto do mês".
    class ProdutoDoMesDAO {
        // Insere as fotos de um produto do mês no banco de dados.
        // Recebe o id do produto e um array de fotos.
        public function inserirFotos($idProduto, $fotos) {
            $conexao = getDatabaseConnection();
            foreach ($fotos as $foto) {
                $statement = $conexao->prepare("INSERT INTO tbl_foto_produto_do_mes (imagem, idProduto) VALUES (?, ?)");
                $statement->bind_param("si", $foto, $idProduto);
                $statement->execute();
                $statement->close();
            }
            
            $conexao->close();
        }
        
        // Seleciona todos os produtos do banco de dados.
        // Retorna um array de produtos.
        public function selecionarTodos() {
            $produtos = array();
            $conexao = getDatabaseConnection();
            $result = $conexao->query("SELECT * FROM tbl_produto");
            while ($data = $result->fetch_array()) {
                $produto = new ProdutoDoMes();
                $produto->idProduto = $data["id"];
                $produto->titulo = $data["titulo"];
                $produto->imagemPrincipal = $data["imagem"];
                $produto->preco = $data["preco"];
                $produto->descricao = $data["descricao"];
                $produto->produtoDoMes = $data["produtoDoMes"];
                $produtos[] = $produto;
            }
            
            $conexao->close();
            return $produtos;
        }
        
        // Seleciona um único produto do banco de dados.
        // Recebe o id do produto e retorna um objeto produto.
        public function selecionar($id) {
            $produto = null;
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("SELECT * FROM tbl_produto WHERE id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $result = get_result($statement);
            if ($data = array_shift($result)) {
                $produto = new ProdutoDoMes();
                $produto->idProduto = $data["id"];
                $produto->titulo = $data["titulo"];
                $produto->imagemPrincipal = $data["imagem"];
                $produto->preco = $data["preco"];
                $produto->descricao = $data["descricao"];
                $produto->fotos = $this->selecionarFotos($produto->idProduto);
            }
            
            $statement->close();
            $conexao->close();
            return $produto;
        }
        
        // Seleciona todas as fotos de um produto do mês.
        // Recebe o id do produto e retorna um array de fotos.
        public function selecionarFotos($idProduto) {
            $fotos = array();
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("SELECT imagem FROM tbl_foto_produto_do_mes WHERE idProduto = ?");
            $statement->bind_param("i", $idProduto);
            $statement->execute();
            $result = get_result($statement);
            while ($data = array_shift($result)) {
                $fotos[] = $data["imagem"];
            }
            
            $statement->close();
            $conexao->close();
            return $fotos;
        }
        
        // Seleciona apenas o único produto ativo como produto do mês.
        // Retorna um objeto produto.
        public function selecionarAtual() {
            $produto = null;
            $conexao = getDatabaseConnection();
            $result = $conexao->query("SELECT * FROM tbl_produto WHERE produtoDoMes = 1");
            if ($data = $result->fetch_array()) {
                $produto = new ProdutoDoMes();
                $produto->idProduto = $data["id"];
                $produto->titulo = $data["titulo"];
                $produto->imagemPrincipal = $data["imagem"];
                $produto->preco = $data["preco"];
                $produto->descricao = $data["descricao"];
                $produto->fotos = $this->selecionarFotos($produto->idProduto);
            }
            
            $conexao->close();
            return $produto;
        }
        
        // Remove todas as fotos de um produto do banco de dados.
        // Recebe o id do produto.
        public function excluirFotos($idProduto) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("DELETE FROM tbl_foto_produto_do_mes WHERE idProduto = ?");
            $statement->bind_param("i", $idProduto);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Desativa todos os produtos para que nenhum seja o produto do mês.
        public function desativarTodos() {
            $conexao = getDatabaseConnection();
            $conexao->query("UPDATE tbl_produto SET produtoDoMes = 0");
            $conexao->close();
        }
        
        // Ativa um produto como produto do mês.
        // Recebe o id do produto a ser ativado.
        public function ativar($id) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_produto SET produtoDoMes = 1 WHERE id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
    }
?>