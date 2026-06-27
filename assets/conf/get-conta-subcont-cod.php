<?php
    include "conf-dbcon.php";
    $content = "";

    if (isset($_POST['lancamento'])) {
        $lancamento = $_POST['lancamento'];
        // $origem = $_POST['conta_origem'];
        $content .= "<option value=''>Selecione a subconta</option>";

        $get_sub = $conn->prepare("SELECT * FROM CONTA_LANCAMENTO WHERE contas_origem = :lancamento");
        $get_sub->bindParam(':lancamento', $lancamento);
        $get_sub->execute();
        // $sub_contas = $get_sub->fetchAll(PDO::FETCH_ASSOC);
        $ultimate_conta = 0;
        while ($row = $get_sub->fetch(PDO::FETCH_ASSOC)) {
           $content .= "<option value='" . htmlspecialchars($row['codigo']) . "'>" . htmlspecialchars($row['codigo']) . " - " . htmlspecialchars($row['descricao']) . "</option>";
           $descricao = $row['descricao'];
           $ultimate_conta += 1;
        }
            $content .= "<option value='" . $lancamento."." . htmlspecialchars($ultimate_conta >= 9 ? "00".$ultimate_conta + 1 : "000".$ultimate_conta + 1) . "'>" . $lancamento.".". htmlspecialchars($ultimate_conta >= 9 ? "00".$ultimate_conta + 1 : "000".$ultimate_conta + 1) . "</option>";

        echo ($content);
    }

    if (isset($_POST['tipoConta']) && $_POST['tipoConta'] === "contaPrincipal") {
        $get_cod = $conn->prepare("SELECT MAX(codigo) AS max_codigo FROM conta_principal");
        $get_cod->execute();
        $result = $get_cod->fetch(PDO::FETCH_ASSOC);
        $max_codigo = $result['max_codigo'] + 1;
        $content .= $max_codigo;
        echo ($content);
    } else if (isset($_POST['tipoConta']) && $_POST['tipoConta'] === "Subconta" && isset($_POST['contaPrincipal'])) {
        $conta_pai = $_POST['contaPrincipal'];
        $get_cod = $conn->prepare("SELECT COUNT(codigo) AS count_codigo FROM sub_conta_2 WHERE conta_pai = :conta_pai");
        $get_cod->bindParam(':conta_pai', $conta_pai);
        $get_cod->execute();
        $result = $get_cod->fetch(PDO::FETCH_ASSOC);
        $cd = $result['count_codigo'];
        $max_codigo = $_POST['contaPrincipal'] . "." . ($cd + 1);
        // echo ($max_codigo);
        $content .= $max_codigo;
        echo ($content);
    } else if (isset($_POST['tipoConta']) && $_POST['tipoConta'] === "Subconta_2" && isset($_POST['contaPrincipal'])) {
        $conta_pai = $_POST['contaPrincipal'];

        $get_cod = $conn->prepare("SELECT codigo FROM sub_conta_2 WHERE conta_pai = :conta_pai");
        $get_cod->bindParam(':conta_pai', $conta_pai);
        $get_cod->execute();
        $result = $get_cod->fetch(PDO::FETCH_ASSOC);

        // echo ($conta_pai);

        $get_cod = $conn->prepare("SELECT COUNT(codigo) AS count_codigo FROM SUB_CONTA_3 WHERE conta_pai = :conta_pai");
        $get_cod->bindParam(":conta_pai", $result['codigo']);
        $get_cod->execute();
        $result = $get_cod->fetch(PDO::FETCH_ASSOC);
        $cd = $result['count_codigo'];
        // echo ($cd);
        $max_codigo = $_POST['contaPrincipal'] . "." . ($cd + 1);
        // echo ($max_codigo);
        $content .= $max_codigo;
        echo ($content);
    } 
    else if (isset($_POST['tipoConta']) && $_POST['tipoConta'] === "Conta associada" && isset($_POST['contaPrincipal'])) {
        $conta_pai = $_POST['contaPrincipal'];

        // echo " Pai ". $conta_pai;


        $get_cod = $conn->prepare("SELECT codigo FROM SUB_CONTA_4 WHERE conta_pai = :conta_pai");
        $get_cod->bindParam(':conta_pai', $conta_pai);
        $get_cod->execute();
        $result = $get_cod->fetch(PDO::FETCH_ASSOC);

        // echo ($result['codigo'] ? $result['codigo'] : 0);

        // $get_cod = $conn->prepare("SELECT COUNT(codigo) AS count_codigo FROM CONTA_LANCAMENTO WHERE contas_origem = :conta_pai");
        // $get_cod->bindParam(':conta_pai', $result['codigo']);
        // $get_cod->execute();
        // $result = $get_cod->fetch(PDO::FETCH_ASSOC);
        $cd = ($result['codigo'] ?? 0);
        // echo ($cd);
        $max_codigo = $_POST['contaPrincipal'] . "." . ($cd + 1);
        // echo ($max_codigo);
        $content .= $max_codigo;
        echo ($content);
    } 
    // else {
    //     echo json_encode([]);
    // }
?>