<?php
    include "conf-dbcon.php";
    $content = "";

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
    else {
        echo json_encode([]);
    }
?>