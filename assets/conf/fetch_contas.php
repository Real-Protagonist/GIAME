<?php
    include "conf-dbcon.php";
    session_start();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST['contaPrincipal'])) {
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
        } else {
            echo json_encode([]);
        }
    } else {
        echo json_encode(["error" => true, "message" => "Método de requisição inválido."]);
        exit;
    }
?>