<?php
    include "conf-dbcon.php";
    session_start();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST['contaPrincipal']) && !isset($_POST['tp'])) {
            // $conta_pai = $_POST['contaPrincipal'];
            $get_sub = $conn->prepare("SELECT * FROM sub_conta_2");
            // $get_sub->bindParam(':conta_pai', $conta_pai);
            $get_sub->execute();
            $sub_contas = $get_sub->fetchAll(PDO::FETCH_ASSOC);
            
            // $content = "<option value='' disabled selected>Selecione a subconta</option>";
            foreach ($sub_contas as $sub_conta) {
                $content .= "<tr>
                                <td class='positionr'>" . htmlspecialchars($sub_conta['codigo']) . "</td>
                                <td class='positionl'>" . htmlspecialchars($sub_conta['descricao']) . "</td>
                                <td class='positionl'>" . htmlspecialchars($sub_conta['conta_pai']) . "</td>
                                <td class='positionl'>" . htmlspecialchars($sub_conta['nivel']) . "</td>
                            </tr>";
            }
            echo ($content);
        } elseif (isset($_POST['contaPrincipal']) && isset($_POST['tp']) && $_POST['tp'] === 'sb2') {
            $conta_pai = $_POST['contaPrincipal'];
            $get_sub = $conn->prepare("SELECT * FROM SUB_CONTA_3 WHERE conta_pai = :conta_pai");
            $get_sub->bindParam(':conta_pai', $conta_pai);
            $get_sub->execute();
            $sub_contas = $get_sub->fetchAll(PDO::FETCH_ASSOC);
            
            $content = "<option value='' disabled selected>Selecione a subconta</option>";
            foreach ($sub_contas as $sub_conta) {
                $content .= "<option value='" . htmlspecialchars($sub_conta['codigo']) . "'>" . htmlspecialchars($sub_conta['codigo']) . " - " . htmlspecialchars($sub_conta['descricao']) . "</option>";
            }
            echo ($content); 
        } else {
            echo json_encode([]);
        }
    } else {
        echo json_encode(["error" => true, "message" => "Método de requisição inválido."]);
        exit;
    }
?>