<?php
    // Arquivo responsável pelas funções do banco de dados da tabela de usuários.
    include_once("database.php");
    class UsuarioDAO {
        // Tenta fazer uma autenticação de um usuário.
        // Recebe um nome de usuário e uma senha em MD5.
        // Retorna um objeto usuário caso a autenticação seja bem sucedida ou null caso falhe.
        public function autenticar($nomeUsuario, $senha) {
            $usuario = null;
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("SELECT u.*, n.permFaleConosco, n.permConteudo, n.permProdutos, n.permUsuarios FROM tbl_usuario AS u INNER JOIN tbl_nivel_usuario AS n ON u.idNivel = n.id WHERE u.usuario = ? AND u.senha = ? AND u.ativo = 1");
            $statement->bind_param("ss", $nomeUsuario, $senha);
            $statement->execute();
            $result = get_result($statement);
            $data = array_shift($result);
            if (!is_null($data)) {
                $usuario = new Usuario();
                $usuario->id = $data["id"];
                $usuario->nome = $data["nome"];
                $usuario->email = $data["email"];
                $usuario->usuario = $data["usuario"];
                $usuario->imagem = $data["imagem"];
                $usuario->nivel = new Nivel();
                $usuario->nivel->permFaleConosco = $data["permFaleConosco"];
                $usuario->nivel->permConteudo = $data["permConteudo"];
                $usuario->nivel->permProdutos = $data["permProdutos"];
                $usuario->nivel->permUsuarios = $data["permUsuarios"];
            }
            
            $statement->close();
            $conexao->close();
            return $usuario;
        }
        
        // Checa existe algum usuário cadastrado em algum nível de usuário.
        // Recebe o id de um nível e retorna true ou false caso exista ou não um usuário.
        public function checarUsuariosPorNivel($nivel) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("SELECT * FROM tbl_usuario WHERE idNivel = ?");
            $statement->bind_param("i", $nivel);
            $statement->execute();
            $result = get_result($statement);
            $data = array_shift($result);
            $resultado = is_null($data);
            $statement->close();
            $conexao->close();
            return !$resultado;
        }
        
        // Insere um novo usuário no banco de dados.
        // Recebe um objeto usuário e retorna o id inserido no banco.
        public function inserir($usuario) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("INSERT INTO tbl_usuario (nome, email, usuario, senha, imagem, ativo, idNivel) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $statement->bind_param("sssssii", $usuario->nome, $usuario->email, $usuario->usuario, $usuario->senha, $usuario->imagem, $usuario->ativo, $usuario->nivel->id);
            $statement->execute();
            $id = $statement->insert_id;
            $statement->close();
            $conexao->close();
            return $id;
        }
        
        // Seleciona todos os usuários do banco de dados.
        // Retorna um array de usuários.
        public function selecionarTodos() {
            $usuarios = array();
            $conexao = getDatabaseConnection();
            $result = $conexao->query("SELECT u.*, n.titulo AS nivel FROM tbl_usuario AS u INNER JOIN tbl_nivel_usuario AS n ON u.idNivel = n.id ORDER BY u.id");
            while ($data = $result->fetch_array()) {
                $usuario = new Usuario();
                $usuario->id = $data["id"];
                $usuario->nome = $data["nome"];
                $usuario->email = $data["email"];
                $usuario->usuario = $data["usuario"];
                $usuario->imagem = $data["imagem"];
                $usuario->ativo = $data["ativo"];
                $usuario->nivel = new Nivel();
                $usuario->nivel->titulo = $data["nivel"];
                $usuarios[] = $usuario;
            }
            
            $conexao->close();
            return $usuarios;
        }
        
        // Seleciona um único usuário do banco de dados.
        // Recebe o id do usuário e retorna um objeto usuário.
        public function selecionar($id) {
            $usuario = null;
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("SELECT u.*, n.titulo AS nivel FROM tbl_usuario AS u INNER JOIN tbl_nivel_usuario AS n ON u.idNivel = n.id WHERE u.id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $result = get_result($statement);
            if ($data = array_shift($result)) {
                $usuario = new Usuario();
                $usuario->id = $data["id"];
                $usuario->nome = $data["nome"];
                $usuario->email = $data["email"];
                $usuario->usuario = $data["usuario"];
                $usuario->imagem = $data["imagem"];
                $usuario->ativo = $data["ativo"];
                $usuario->nivel = new Nivel();
                $usuario->nivel->id = $data["idNivel"];
                $usuario->nivel->titulo = $data["nivel"];
            }
            
            $statement->close();
            $conexao->close();
            return $usuario;
        }
        
        // Atualiza as informações de um usuário no banco de dados.
        // Recebe um objeto usuário.
        public function atualizar($usuario) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_usuario SET nome = ?, email = ?, usuario = ?, imagem = ?, ativo = ?, idNivel = ? WHERE id = ?");
            $statement->bind_param("ssssiii", $usuario->nome, $usuario->email, $usuario->usuario, $usuario->imagem, $usuario->ativo, $usuario->nivel->id, $usuario->id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Atualiza a senha de um usuário no banco de dados.
        // Recebe o id do usuário e sua nova senha.
        public function atualizarSenha($id, $senha) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_usuario SET senha = ? WHERE id = ?");
            $statement->bind_param("si", $senha, $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Remove um usuário do banco de dados.
        // Recebe o id do usuário.
        public function excluir($id) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("DELETE FROM tbl_usuario WHERE id = ?");
            $statement->bind_param("i", $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
        
        // Ativa ou destiva um usuário.
        // Recebe o id do usuário e um boolean que indica se ele será ativado ou desativado.
        public function ativar($id, $ativo) {
            $conexao = getDatabaseConnection();
            $statement = $conexao->prepare("UPDATE tbl_usuario SET ativo = ? WHERE id = ?");
            $statement->bind_param("ii", $ativo, $id);
            $statement->execute();
            $statement->close();
            $conexao->close();
        }
    }
?>