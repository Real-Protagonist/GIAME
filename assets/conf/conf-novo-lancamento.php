<?php
require_once 'conf-dbcon.php';
include_once '../models/mdl-empresa.php';
include_once '../models/mdl-endereco.php';
include_once '../models/mdl-contacto.php';
header("Content-Type: application/json");
session_start();
$dados = json_decode(file_get_contents("php://input"), true);
if (!$dados) {
    echo json_encode(["error" => true, "message" => "Dados inválidos."]);
    exit;
}

$numero_lancamento = htmlspecialchars($dados["numero_lancamento"]);
$data_lancamento = htmlspecialchars($dados["data_movimento"]);
$descricao_lancamento = htmlspecialchars($dados["descricao_movimento"]);
$conta_lancamento = htmlspecialchars($dados["conta_lance"]);
$empresa_nif = htmlspecialchars($dados["nif_empresa"]);
$total_debito = htmlspecialchars($dados["total_debito"]);
$total_credito = htmlspecialchars($dados["total_credito"]);
$diferenca = htmlspecialchars($dados["diferenca"]);
$ano_lancamento = htmlspecialchars($dados["ano_analise"]);
$linhas_lancamento = ($dados["linhas"]);
$pessoa_id = $_SESSION['us_id'] ?? null;

// echo json_encode([
//     "numero_lancamento" => $numero_lancamento,
//     "data_lancamento" => $data_lancamento,
//     "descricao_lancamento" => $descricao_lancamento,
//     "conta_lancamento" => $conta_lancamento,
//     "empresa_nif" => $empresa_nif,
//     "total_debito" => $total_debito,
//     "total_credito" => $total_credito,
//     "diferenca" => $diferenca,
//     "ano_analise" => $ano_lancamento,
//     "linhas_lancamento" => $linhas_lancamento,
//     "pessoa_id" => $pessoa_id
// ]);

if ($diferenca === "0.00" || $diferenca === "0") {
    $stmt = $conn->prepare("SELECT id_empresa FROM empresas WHERE nif = :nif LIMIT 1");
    $stmt->bindParam(':nif', $empresa_nif);
    $stmt->execute();
    $empresa = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($empresa) {
        $empresa_nif = $empresa['id_empresa'];
    } else {
        echo json_encode(["error" => true, "message" => "Empresa com NIF $nif não encontrada."]);
        exit;
    }
    $stmt = $conn->prepare("INSERT INTO lancamentos (lancamento, data_lancamento, descricao, ano_analise, debito, credito, diferenca, criador_usuario, empresa_id) 
                                VALUES (:numero_lancamento, :data_lancamento, :descricao_lancamento, :ano_analise, :total_debito, :total_credito, :diferenca, :pessoa_id, :empresa_nif)");
    $stmt->bindParam(':numero_lancamento', $numero_lancamento);
    $stmt->bindParam(':data_lancamento', $data_lancamento);
    $stmt->bindParam(':descricao_lancamento', $descricao_lancamento);
    $stmt->bindParam(':ano_analise', $ano_lancamento);
    $stmt->bindParam(':total_debito', $total_debito);
    $stmt->bindParam(':total_credito', $total_credito);
    $stmt->bindParam(':diferenca', $diferenca);
    $stmt->bindParam(':pessoa_id', $pessoa_id);
    $stmt->bindParam(':empresa_nif', $empresa_nif);
    if ($stmt->execute()) {
        $id_lancamento = $conn->lastInsertId();
        if ($linhas_lancamento && is_array($linhas_lancamento)) {
            $linha_stmt = $conn->prepare("INSERT INTO lancamento_itens (lancamento_id, sub_conta_id, valor, tipo) 
                                        VALUES (:lancamento_id, :sub_conta_id, :valor, :tipo)");
            foreach ($linhas_lancamento as $linha) {
                $sub = ($linha["subconta"]);
                $valor = ($linha["valorDebito"] != 0 ? $linha["valorDebito"] : $linha["valorCredito"]);
                $tipo = $linha["valorDebito"] != 0 ? 'Debito' : 'Credito';
                $conta_lancamento = ($linha["conta_lancamento"]);

                // $get_subconta = $conn->prepare("SELECT id FROM sub_conta_2 WHERE codigo = :sub_conta_id LIMIT 1");
                // $get_subconta->bindParam(':sub_conta_id', $sub);
                // $get_subconta->execute();
                // $subconta_result = $get_subconta->fetch(PDO::FETCH_ASSOC);
                // if (!$subconta_result) {
                //     $get_subconta = $conn->prepare("SELECT id FROM SUB_CONTA_3 WHERE codigo = :sub_conta_id LIMIT 1");
                //     $get_subconta->bindParam(':sub_conta_id', $sub);
                //     $get_subconta->execute();
                //     $subconta_result = $get_subconta->fetch(PDO::FETCH_ASSOC);
                //     if (!$subconta_result) {
                //         $get_subconta = $conn->prepare("SELECT id FROM SUB_CONTA_4 WHERE codigo = :sub_conta_id LIMIT 1");
                //         $get_subconta->bindParam(':sub_conta_id', $sub);
                //         $get_subconta->execute();
                //         $subconta_result = $get_subconta->fetch(PDO::FETCH_ASSOC);
                //     }
                // }

                // $linha_stmt->bindParam(':lancamento_id', $id_lancamento);
                // $linha_stmt->bindParam(':sub_conta_id', $subconta_result['id']);
                // $linha_stmt->bindParam(':conta_lancamento', $conta_lancamento);
                // $linha_stmt->bindParam(':valor', $valor);
                // $linha_stmt->bindParam(':tipo', $tipo);
                // $linha_stmt->execute();

                //Caso a conta de lançamento não esteja registra uma nova conta de lançamento é criada associada a subconta selecionada
                $stmt_conta_lancamento = $conn->prepare("SELECT id FROM CONTA_LANCAMENTO WHERE codigo = :conta_lancamento");
                $stmt_conta_lancamento->bindParam(':conta_lancamento', $conta_lancamento);
                $stmt_conta_lancamento->execute();
                $conta_lancamento_result = $stmt_conta_lancamento->fetch(PDO::FETCH_ASSOC);
                if (!$conta_lancamento_result) {
                    $stmt_insert_conta_lancamento = $conn->prepare("INSERT INTO CONTA_LANCAMENTO (codigo, descricao, contas_origem) VALUES (:codigo, :descricao, :contas_origem)");
                    $stmt_insert_conta_lancamento->bindParam(':codigo', $conta_lancamento);
                    $stmt_insert_conta_lancamento->bindParam(':descricao', $linha["descricao"]);
                    $stmt_insert_conta_lancamento->bindParam(':contas_origem', $sub);
                    $stmt_insert_conta_lancamento->execute();
                }
            }
            echo json_encode(["error" => false, "message" => "Lançamento criado com sucesso.", "lancamento_id" => $id_lancamento]);
            exit;
        }
    } else {
        echo json_encode(["error" => true, "message" => "Erro ao criar o lançamento."]);
        exit;
    }

} else {
    echo json_encode(["error" => true, "message" => "A diferença entre débito e crédito não deve ser diferente de zero."]);
    exit;
}
