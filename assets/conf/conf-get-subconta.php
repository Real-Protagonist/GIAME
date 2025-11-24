<?php
require_once 'conf-dbcon.php';
// $conta = isset($_POST['conta_id']) ? htmlspecialchars($_POST['conta_id']) : null;

$stmt = $conn->prepare("SELECT * FROM sub_conta_2");
// $stmt->bindParam(':conta', $conta);
$stmt->execute();
$subcontas = $stmt->fetchAll(PDO::FETCH_ASSOC);
$content = '';
foreach ($subcontas as &$subconta) {
    $content .= '<option value="' . $subconta['codigo'] . '">' . $subconta['codigo'] . ' - ' . $subconta['descricao'] . '</option>';
    // $subconta['codigo_descricao'] = $subconta['codigo'] . ' - ' . $subconta['descricao'];
}

echo $content;
?>