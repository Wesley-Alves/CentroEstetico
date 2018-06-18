<?php
    // Remove o usuário da sessão e redireciona para o index do site.
    session_start();
    session_unset();
    header("location:../");
?>