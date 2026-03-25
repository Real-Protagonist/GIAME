<?php
include "conf-dbcon.php";
header("Content-Type: application/json");
session_start();
if ($_SERVER["CONTENT_TYPE"] == "application/json") {
    $data = json_decode(file_get_contents("php://input"), true);
    // Process the JSON data
    if (is_array($data)) {
        $_POST = $data;
    }

    $contaPrincipal = htmlspecialchars($_POST["contaPrincipal"]);
    $subConta = htmlspecialchars($_POST["descricao"]);
    $subCod = htmlspecialchars($_POST["numero_conta"]);
    $tipoConta = htmlspecialchars($_POST["tipoConta"]);
    $subConta2 = htmlspecialchars($_POST["subConta"]);
    
    $nivel = 2;
    // VISUALIZAR DIREITO O CONCEITO
    if ($tipoConta === "conta_principal") {
        $stmt = $conn->prepare("INSERT INTO conta_principal (codigo, descricao) VALUES (:codigo, :descricao)");
        $stmt->bindParam(':codigo', $contaPrincipal);
        $stmt->bindParam(':descricao', $contaPrincipal); // Aqui você pode ajustar a descrição conforme necessário
        if ($stmt->execute()) {
            echo json_encode(["error" => false, "message" => "Conta principal criada com sucesso."]);
        } else {
            echo json_encode(["error" => true, "message" => "Erro ao criar conta principal."]);
        }
    } elseif ($tipoConta === "Subconta") {
        $stmt = $conn->prepare("INSERT INTO sub_conta_2 (codigo, descricao, conta_pai, nivel) VALUES (:codigo, :descricao, :conta_pai, :nivel)");
        $stmt->bindParam(':codigo', $subCod);
        $stmt->bindParam(':descricao', $subConta); // Aqui você pode ajustar a descrição conforme necessário
        $stmt->bindParam(':conta_pai', $contaPrincipal);
        $stmt->bindParam(':nivel', $nivel); // Aqui você pode ajustar o nível conforme necessário
        if ($stmt->execute()) {
            echo json_encode(["error" => false, "message" => "Subconta criada com sucesso."]);
        } else {
            echo json_encode(["error" => true, "message" => "Erro ao criar subconta."]);
        }
    } elseif ($tipoConta === "Subconta_2") {
        $nivel = 3;
        $stmt = $conn->prepare("INSERT INTO SUB_CONTA_3 (codigo, descricao, conta_pai, nivel) VALUES (:codigo, :descricao, :conta_pai, :nivel)");
        $stmt->bindParam(':codigo', $subCod);
        $stmt->bindParam(':descricao', $subConta); // Aqui você pode ajustar a descrição conforme necessário
        $stmt->bindParam(':conta_pai', $subConta2);
        $stmt->bindParam(':nivel', $nivel); // Aqui você pode ajustar o nível conforme necessário
        if ($stmt->execute()) {
            echo json_encode(["error" => false, "message" => "Subconta 2 criada com sucesso."]);
        } else {
            echo json_encode(["error" => true, "message" => "Erro ao criar subconta 2."]);
        }
    } else {
        echo json_encode(["error" => true, "message" => "Dados inválidos."]);
        exit;
    }
} else {
    echo json_encode(["error" => true, "message" => "Tipo de conteúdo inválido."]);
    exit;
}
?>