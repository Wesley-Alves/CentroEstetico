<?php
    include_once("database.php");
    // Classe responsável por acessar o banco de dados de um objeto "produto".
    class ProdutoDAO {
        // Seleciona todos os produtos do banco de dados.
        // Retorna um array de produtos.
        public function selecionarTodos() {
            $produtos = array();
            $conexao = getDatabaseConnection();
            $result = $conexao->query("SELECT * FROM tbl_produto");
            while ($data = $result->fetch_array()) {
                $produto = new Produto();
                $produto->id = $data["id"];
                $produto->titulo = $data["titulo"];
                $produto->imagem = $data["imagem"];
                $produto->preco = $data["preco"];
                $produto->descricao = $data["descricao"];
                $produto->ativo = $data["ativo"];
                $produto->produtoDoMes = $data["produtoDoMes"];
                $produto->idSubcategoria = $data["idSubcategoria"];
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
                $produto = new Produto();
                $produto->id = $data["id"];
                $produto->titulo = $data["titulo"];
                $produto->imagem = $data["imagem"];
                $produto->preco = $data["preco"];
                $produto->descricao = $data["descricao"];
                $produto->ativo = $data["ativo"];
                $produto->produtoDoMes = $data["produtoDoMes"];
                $produto->idSubcategoria = $data["idSubcategoria"];
            }
            
            $statement->close();
            $conexao->close();
            return $produto;
        }
        
        // Checa se existe algum produto cadastrado em alguma categoria.
        // Recebe o id de uma categoria e retorna true ou false caso exista ou não um produto.
        public function checarProdutosPorCategoria($categoria) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("SELECT * FROM tbl_produto AS p INNER JOIN tbl_subcategoria AS s ON s.id = p.idSubcategoria INNER JOIN tbl_categoria AS c ON c.id = s.idCategoria WHERE c.id = ?");
            $statement->bind_param("i", $categoria);
            $statement->execute();
            $result = get_result($statement);
            $data = array_shift($result);
            $resultado = is_null($data);
            $statement->close();
            $conexao->close();
            return !$resultado;
        }
        
        // Checa se existe algum produto cadastrado em alguma subcategoria.
        // Recebe o id de uma subcategoria e retorna true ou false caso exista ou não um produto.
        public function checarProdutosPorSubcategoria($subcategoria) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("SELECT * FROM tbl_produto WHERE idSubcategoria = ?");
            $statement->bind_param("i", $subcategoria);
            $statement->execute();
            $result = get_result($statement);
            $data = array_shift($result);
            $resultado = is_null($data);
            $statement->close();
            $conexao->close();
            return !$resultado;
        }
        
        // Insere um novo produto no banco de dados.
        // Recebe um objeto produto e retorna o id inserido no banco.
        public function inserir($produto) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("INSERT INTO tbl_produto (titulo, preco, descricao, imagem, ativo, produtoDoMes, idSubcategoria) VALUES (?, ?, ?, ?, ?, 0, ?)");
            $statement->bind_param("sdssii", $produto->titulo, $produto->preco, $produto->descricao, $produto->imagem, $produto->ativo, $produto->idSubcategoria);
            $statement->execute();
            $id = $statement->insert_id;
            $statement->close();
            $conexao->close();
            return $id;
        }
        
        // Atualiza as informações de um produto no banco de dados.
        // Recebe um objeto produto.
        public function atualizar($produto) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_produto SET titulo = ?, preco = ?, descricao = ?, imagem = ?, ativo = ?, idSubcategoria = ? WHERE id = ?");
            $statement->bind_param("sdssiii", $produto->titulo, $produto->preco, $produto->descricao, $produto->imagem, $produto->ativo, $produto->idSubcategoria, $produto->id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Remove um produto do banco de dados.
        // Recebe o id do produto.
        public function excluir($id) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("DELETE FROM tbl_foto_produto_do_mes WHERE idProduto = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
            
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("DELETE FROM tbl_promocao WHERE idProduto = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
            
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("DELETE FROM tbl_produto WHERE id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Ativa ou destiva um produto.
        // Recebe o id do produto e um boolean que indica se ele será ativado ou desativado.
        public function ativar($id, $ativo) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_produto SET ativo = ? WHERE id = ?");
            $statement->bind_param("ii", $ativo, $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Lista os produtos de acordo com um filtro específico.
        // Caso seja passado o id de uma subcategoria, pega todos os produtos que esta subcategoria contém.
        // Caso seja passado um texto de busca, pega todos os produtos onde contenha o texto no título ou na descrição.
        // Caso nada seja passado, retorna todos os produtos ativos em uma ordem aleatória.
        public function listarProdutos($idSubcategoria, $texto) {
            $produtos = array();
            $conexao = getDatabaseConnection();
            if ($idSubcategoria) {
                $statement = $conexao->prepare("SELECT * FROM tbl_produto WHERE idSubcategoria = ? AND ativo = 1 ORDER BY RAND()");
                $statement->bind_param("i", $idSubcategoria);
                $statement->execute();
                $result = get_result($statement);
                $statement->close();
            } else if ($texto) {
                $texto = "%$texto%";
                $statement = $conexao->prepare("SELECT p.* FROM tbl_produto AS p INNER JOIN tbl_subcategoria AS s ON s.id = p.idSubcategoria INNER JOIN tbl_categoria AS c ON c.id = s.idCategoria WHERE (p.titulo LIKE ? OR p.descricao LIKE ?) AND p.ativo = 1 AND s.ativo = 1 AND c.ativo = 1 ORDER BY RAND()");
                $statement->bind_param("ss", $texto, $texto);
                $statement->execute();
                $result = get_result($statement);
                $statement->close();
            } else {
                $result = $conexao->query("SELECT p.* FROM tbl_produto AS p INNER JOIN tbl_subcategoria AS s ON s.id = p.idSubcategoria INNER JOIN tbl_categoria AS c ON c.id = s.idCategoria WHERE p.ativo = 1 AND s.ativo = 1 AND c.ativo = 1 ORDER BY RAND()");
            }
            
            while ($data = is_array($result) ? array_shift($result) : $result->fetch_array()) {
                $produto = new Produto();
                $produto->id = $data["id"];
                $produto->titulo = $data["titulo"];
                $produto->imagem = $data["imagem"];
                $produto->descricao = $data["descricao"];
                $produto->preco = $data["preco"];
                $produtos[] = $produto;
            }
            
            $conexao->close();
            return $produtos;
        }
        
        // Método para selecionar um produto a partir de seu id de forma detalhada.
        // Recebe o id de um produto e retorna um objeto produto;
        public function mostrarDetalhes($id) {
            $produto = null;
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("SELECT p.*, s.nome AS subcategoria, c.nome AS categoria FROM tbl_produto AS p INNER JOIN tbl_subcategoria AS s on s.id = p.idSubcategoria INNER JOIN tbl_categoria AS c on c.id = s.idCategoria WHERE p.id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $result = get_result($statement);
            if ($data = array_shift($result)) {
                $produto = new Produto();
                $produto->id = $data["id"];
                $produto->titulo = $data["titulo"];
                $produto->imagem = $data["imagem"];
                $produto->descricao = $data["descricao"];
                $produto->preco = $data["preco"];
                $produto->categoria = $data["categoria"];
                $produto->subcategoria = $data["subcategoria"];
            }
            
            $statement->close();
            $conexao->close();
            return $produto;
        }
    
        // Adiciona um clique para as estatisticas de um produto.
        // Recebe o id de um produto.
        public function adicionarClique($id) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_produto SET cliques = cliques + 1 WHERE id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Retorna o total e a média de cliques dos produtos.
        public function getEstatisticas() {
            $conexao = getDatabaseConnection();
            $result = $conexao->query("SELECT SUM(cliques) AS total, TRUNCATE(AVG(cliques), 2) AS media FROM tbl_produto");
            $data = $result->fetch_array();
            $conexao->close();
            return $data;
        }
        
        // Retorna os 6 produtos mais acessados.
        public function selecionarProdutosGrafico() {
            $produtos = array();
            $conexao = getDatabaseConnection();
            $result = $conexao->query("SELECT titulo, cliques, TRUNCATE(cliques / (SELECT SUM(cliques) FROM tbl_produto) * 100, 2) AS porcentagem FROM tbl_produto ORDER BY porcentagem DESC LIMIT 6");
            while ($data = $result->fetch_array()) {
                $produtos[] = $data;
            }
            
            $conexao->close();
            return $produtos;
        }
    }
?>