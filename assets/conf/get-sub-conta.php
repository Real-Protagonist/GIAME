<?php
include "conf-dbcon.php";
$content = "";
if (isset($_POST['contaPrincipal'])) {
    $conta_pai = $_POST['contaPrincipal'];
    $get_sub = $conn->prepare("SELECT * FROM sub_conta_2 WHERE conta_pai = :conta_pai");
    $get_sub->bindParam(':conta_pai', $conta_pai);
    $get_sub->execute();
    $sub_contas = $get_sub->fetchAll(PDO::FETCH_ASSOC);
    
    $content .= "<option value='' disabled selected>Selecione a subconta</option>";
    foreach ($sub_contas as $sub_conta) {
        $content .= "<option value='" . htmlspecialchars($sub_conta['codigo']) . "'>" . htmlspecialchars($sub_conta['codigo']) . " - " . htmlspecialchars($sub_conta['descricao']) . "</option>";
    }
    echo ($content);
} else {
    echo json_encode([]);
}

?>