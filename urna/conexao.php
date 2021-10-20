<?php
    /**
     * Criamos nossa conexão dado um usuário (nesse caso o root)
     */
    // User e senha default do phpmyadmin
    $servidor = "localhost";
    $usuario = "root";
    $senha = "";
    $nome_banco = "urna";

    // Criando a conexao
    $mysqli = new mysqli($servidor, $usuario, $senha, $nome_banco);

    // Checando a conexao
    if ($mysqli -> connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
        exit();
    }
    echo "Connected successfully";

    /**
     * Iniciamos uma sessão para pegar os valores dos cookies
     */
    session_start();


    /**
     * Busca a chave do vereador e atualiza no banco o valor dos votos acumulados para aquele número (nulo se não for um número de candidato válido)
     */
    if(isset($_COOKIE["vereador"])){
        $numero_voto = $_COOKIE["vereador"];
        $query_busca = "SELECT VOTOS_ACUMULADOS FROM `vereador` WHERE NUMERO_URNA = $numero_voto";
        $resultado = $mysqli -> query($query_busca);
        $tourresult = $resultado->fetch_array()[0] ?? '';
        $tourresult += 1;
        //echo "<br>Número de votos:".$tourresult;
        $query_insert = "UPDATE `vereador` SET VOTOS_ACUMULADOS=$tourresult WHERE NUMERO_URNA = $numero_voto";
        if(($atualiza = $mysqli -> query($query_insert))===TRUE){
            echo "<br>voto registrado";
        }
        else{
            echo "erro ao registrar o voto";
        }
    }

    /**
     * Faz o mesmo acima, mas na tabela do prefeito
     */
    if(isset($_COOKIE["prefeito"])){
        $numero_voto = $_COOKIE["prefeito"];
        $query_busca = "SELECT VOTOS_ACUMULADOS FROM `prefeito` WHERE NUMERO_URNA = $numero_voto";
        $resultado = $mysqli -> query($query_busca);
        $tourresult = $resultado->fetch_array()[0] ?? '';
        $tourresult += 1;
        //echo "<br>Número de votos:".$tourresult;
        $query_insert = "UPDATE `prefeito` SET VOTOS_ACUMULADOS=$tourresult WHERE NUMERO_URNA = $numero_voto";
        if(($atualiza = $mysqli -> query($query_insert))===TRUE){
            echo "<br>voto registrado";
        }
        else{
            echo "erro ao registrar o voto";
        }
    }

    // Expira todos os cookies da página (deve ser o último a rodar)
    $past = time() - 3600;
    foreach ( $_COOKIE as $key => $value )
    {
        setcookie( $key, $value, $past, '/' );
    }
  ?>