<?php
    // Função para retornar a conexão com o banco de dados.
    function getDatabaseConnection() {
        try {
            mysqli_report(MYSQLI_REPORT_STRICT); // Faz com que a mensagem de erro do Mysqli não apareça diretamente, mas sim caia no try-catch.
            $conexao = new mysqli("192.168.0.2", "pc2720181", "senai127", "dbpc2720181");
            $conexao->autocommit(true); // Ativa o moto auto commit para garantir que as atualizações sempre sejam efetuadas.
            $conexao->set_charset("utf8"); // Define a codificação dos dados do Mysql como UTF-8.
            return $conexao;
        } catch (Exception $e) {
            die("Erro interno ao conectar no banco de dados. Tente novamente mais tarde."); // Mata completamente a aplicação, exibindo uma mensagem de erro.
        }
    }

    // Função similar ao get_result do mysqli, porém pega em versões sem a biblioteca necessária.
    function get_result($statement) {
        $result = array();
        $statement->store_result();
        for ($i = 0; $i < $statement->num_rows; $i++) {
            $metadata = $statement->result_metadata();
            $params = array();
            while ($field = $metadata->fetch_field()) {
                $params[] = &$result[$i][$field->name];
            }
            
            call_user_func_array(array($statement, "bind_result"), $params);
            $statement->fetch();
        }
        
        return $result;
    }
?>