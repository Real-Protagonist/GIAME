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
        $content .= "<option aria-readonly='true' value='" . htmlspecialchars($sub_conta['codigo']) . "'>" . htmlspecialchars($sub_conta['codigo']) . " - " . htmlspecialchars($sub_conta['descricao']) . "</option>";

        $get_sunb_3 = $conn->prepare("SELECT * FROM SUB_CONTA_3 WHERE conta_pai = :conta_pai");
        $get_sunb_3->bindParam(':conta_pai', $sub_conta['codigo']);
        $get_sunb_3->execute();
        $sub_contas3 = $get_sunb_3->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($sub_contas3 as $sub_conta_3) {
            $content .= "<option value='" . htmlspecialchars($sub_conta_3['codigo']) . "'>&nbsp;&nbsp;&nbsp;" . htmlspecialchars($sub_conta_3['codigo']) . " - " . htmlspecialchars($sub_conta_3['descricao']) . "</option>";
            
            $get_sunb_3 = $conn->prepare("SELECT * FROM SUB_CONTA_4 WHERE conta_pai = :conta_pai");
            $get_sunb_3->bindParam(':conta_pai', $sub_conta_3['codigo']);
            $get_sunb_3->execute();
            $sub_contas4 = $get_sunb_3->fetchAll(PDO::FETCH_ASSOC);
            foreach ($sub_contas4 as $sub_conta_4) {
                $content .= "<option value='" . htmlspecialchars($sub_conta_4['codigo']) . "'>&nbsp;&nbsp;&nbsp;" . htmlspecialchars($sub_conta_4['codigo']) . " - " . htmlspecialchars($sub_conta_4['descricao']) . "</option>";
            }
        }
    }
    echo ($content);
} else {
    echo json_encode([]);
}

?>