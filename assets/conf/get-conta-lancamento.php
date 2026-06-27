<?php
include "conf-dbcon.php";
$content = "";

if (isset($_POST['conta_lancamento'])) {
    $lancamento = $_POST['conta_lancamento'];
    // $origem = $_POST['conta_origem'];

    $get_sub = $conn->prepare("SELECT codigo, descricao FROM CONTA_LANCAMENTO WHERE codigo = :lancamento");
    $get_sub->bindParam(':lancamento', $lancamento);
    $get_sub->execute();
    $sub_contas = $get_sub->fetch(PDO::FETCH_ASSOC);
    if (!$sub_contas) {
        $content .= "Error404";
    } else {
        $content .= $sub_contas['descricao'];
    }

    echo ($content);
} else {
    echo json_encode([]);
}