<?php
    include("conf-dbcon.php");
    include("../models/mdl-pessoa.php");
    include("../models/mdl-user.php");
    header("Content-Type: application/json");
    // include("conf-mail.php");

    if ($_SERVER["CONTENT_TYPE"] == "application/json") {
        $data = json_decode(file_get_contents("php://input"), true);
        // Process the JSON data
        if (is_array($data)) {
            $_POST = $data;
        }
    } else {
        echo "Erro";
    }
    // echo $_POST["register"];
    session_start();
    // if (isset($_POST["register"])) {
        $primeiro_nome = htmlspecialchars($_POST["primeiro_nome"]);
        $ultimo_nome = htmlspecialchars($_POST["ultimo_nome"]);
        // $dt_nascimento = htmlspecialchars($_POST["dt_nascimento"]);
        // $nacionalidade = htmlspecialchars($_POST["nacionalidade"]);
        $nif = htmlspecialchars($_POST["nif"]);
        $email_usuario = htmlspecialchars($_POST["email_usuario"]);
        $senha_usuario = sha1(md5(($_POST["senha_usuario"])));

        // Verifica se o email já está registrado
        $stmt = $conn->prepare("SELECT COUNT(*) FROM usuario WHERE email = :email_usuario");
        $stmt->bindParam(':email_usuario', $email_usuario);
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['error'] = "Email já registrado.";
            header("Location: ../register.php");
            exit();
        }

        // Insere na tabela pessoa
        $pessoa = new Pessoa([
            'primeiro_nome' => $primeiro_nome,
            'ultimo_nome' => $ultimo_nome,
            // 'dt_nascimento' => $dt_nascimento,
            // 'nacionalidade' => $nacionalidade,
            'nif' => $nif
        ]);

        $stmt = $conn->prepare("INSERT INTO pessoa (primeiro_nome, ultimo_nome, nif) 
                                VALUES (:primeiro_nome, :ultimo_nome, :nif)");
        $stmt->bindParam(':primeiro_nome', $_POST["primeiro_nome"]);
        $stmt->bindParam(':ultimo_nome', $_POST["ultimo_nome"]);
        // $stmt->bindParam(':dt_nascimento', $pessoa->getDtNascimento());
        // $stmt->bindParam(':nacionalidade', $pessoa->getNacionalidade());
        $stmt->bindParam(':nif', $_POST["nif"]);
        $stmt->execute();
        $id_pessoa = $conn->lastInsertId();

        // Insere na tabela usuarios
        $usuario = new Usuarios([
            'email' => $email_usuario,
            'password_hash' => $senha_usuario,
            'last_login' => null
        ]);

        $last_login = date('Y-m-d H:i:s');

        $stmt = $conn->prepare("INSERT INTO usuario (email, password_hash, last_login, pessoa_id) 
                                VALUES (:email, :password_hash, :last_login, :id_pessoa)");
        $stmt->bindParam(':email', $_POST['email_usuario']);
        $stmt->bindParam(':password_hash', $senha_usuario);
        $stmt->bindParam(':last_login', $last_login);
        $stmt->bindParam(':id_pessoa', $id_pessoa);
        $stmt->execute();

        // Redireciona ou exibe uma mensagem de sucesso
        $_SESSION['success'] = "Registro realizado com sucesso.";
        // header("Location: ../../html/auth/register.html");
        echo json_encode([
            "message" => "Conta criada com sucesso!"
        ]);
        exit();
    // }