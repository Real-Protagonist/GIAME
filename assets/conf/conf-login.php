<?php
require_once 'conf-dbcon.php';
require_once '../models/mdl-user.php';
require_once '../models/mdl-pessoa.php';
header("Content-Type: application/json");

if ($_SERVER["CONTENT_TYPE"] == "application/json") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (is_array($data))
        $_POST = $data;
} else
    echo "Erro";

session_start();
$email_usuario = htmlspecialchars($_POST["email_usuario"]);
$senha_usuario = sha1(md5(($_POST["senha_usuario"])));

// Verificar se o email e a senha correspondem a um usuário no banco de dados
$stmt = $conn->prepare("SELECT COUNT(*) FROM usuario WHERE email LIKE :email_usuario AND password_hash LIKE :senha_usuario");
$stmt->bindParam(':email_usuario', $email_usuario);
$stmt->bindParam(':senha_usuario', $senha_usuario);
$stmt->execute();
if ($stmt->fetchColumn() > 0) {
    $_SESSION['user_id'] = $email_usuario;
    $stmt = $conn->prepare('SELECT id FROM usuario WHERE email LIKE :email_usuario');
    $stmt->bindParam(':email_usuario', $email_usuario);
    $stmt->execute();
    $_SESSION['us_id'] = $stmt->fetchColumn();
    echo json_encode(["message" => "login realizado com sucesso."]);
    exit();
} else {
    session_unset();
    session_destroy();
    session_abort();
    echo json_encode(["error" => "Email ou senha incorretos."]);
    exit();
}