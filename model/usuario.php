<?php
    // Modelo para usuários do sistema.
    include_once("../model/nivel.php");
    class Usuario {
        public $id;
        public $nome;
        public $email;
        public $usuario;
        public $senha;
        public $imagem;
        public $ativo;
        public $nivel;
    }
?>