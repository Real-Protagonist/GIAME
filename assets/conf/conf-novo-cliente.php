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


// Aqui você pode processar os dados, como salvá-los em um banco de dados
if ($_SERVER["CONTENT_TYPE"] == "application/json") {
    $nome                   = htmlspecialchars($dados["nome"]);
    $nif                    = htmlspecialchars($dados["nif"]);
    $tipo                   = htmlspecialchars($dados["tipo-empresa"]);
    $objectivo_social       = htmlspecialchars($dados["objecto-social"]);
    $data_constituicao      = htmlspecialchars($dados["data-de-constituicao"]);
    $tipo_empresa           = htmlspecialchars($dados["tipo-empresa"]);
    $representante_legal    = htmlspecialchars($dados["representante-legal"]);
    $sector_atividade       = htmlspecialchars($dados["sector-empresa"]);
    $tamanho_empresa        = htmlspecialchars($dados["tamanho-empresa"]);
    $tipo_sociedade         = htmlspecialchars($dados["tipo-sociedade"]);
    $capital_social         = htmlspecialchars($dados["capital-social"]);
    $particularidade        = htmlspecialchars($dados["particularidade"]);
    $website_empresa        = htmlspecialchars($dados["site-empresa"]);
    $morada                 = htmlspecialchars($dados["morada"]);
    $municipio              = htmlspecialchars($dados["municipio"]);
    $bairro                 = htmlspecialchars($dados["bairro"]);
    $provincia              = htmlspecialchars($dados["provincia"]);
    $contacto               = htmlspecialchars($dados["contacto"]);
    $email_empresa          = htmlspecialchars($dados["email-empresa"]);
    $pessoa_id              = $_SESSION['us_id'] ?? null;

    $endereco = new Endereco([
        'morada'    => htmlspecialchars($dados["morada"]),
        'municipio' => htmlspecialchars($dados["municipio"]),
        'bairro'    => htmlspecialchars($dados["bairro"]),
        'provincia' => htmlspecialchars($dados["provincia"])
    ]);

    $stmt = $conn->prepare("INSERT INTO enderecos (morada, municipio, bairro, provincia) 
                                    VALUES (:morada, :municipio, :bairro, :provincia)");
    $stmt->bindParam(':morada', $morada);
    $stmt->bindParam(':municipio', $municipio);
    $stmt->bindParam(':bairro', $bairro);
    $stmt->bindParam(':provincia', $provincia);
    $stmt->execute();
    $id_endereco = $conn->lastInsertId();

    $stmt = $conn->prepare("INSERT INTO contactos (telefone, email, site, descricao) 
                                VALUES (:telefone, :email, :web_site, :descricao)");
    $stmt->bindParam(':telefone', $contacto);
    $stmt->bindParam(':email', $email_empresa);
    $stmt->bindParam(':web_site', $website_empresa);
    $stmt->bindParam(':descricao', $descricao_empresa);
    $stmt->execute();
    $id_contacto = $conn->lastInsertId();

    $stmt = $conn->prepare("INSERT INTO empresas (nome, nif, tipo, particularidade, endereco, contacto_id, objecto_social, data_fundacao, sector_atividade, representante_legal, tipo_sociedade, tamanho, capital_social, pessoa_id) 
                            VALUES (:nome, :nif, :tipo, :particularidade, :endereco, :contacto_id, :objecto_social, :data_fundacao, :sector_atividade, :representante_legal, :tipo_sociedade, :tamanho, :capital_social, :pessoa_id)");

    $empresa = new Empresa([
        'nome'                  => $nome,
        'nif'                   => $nif,
        'tipo'                  => $tipo,
        'objectivo_social'      => $objectivo_social,
        'data_constituicao'     => $data_constituicao,
        'tipo_sociedade'        => $tipo_sociedade,
        'particularidade'       => $particularidade,
        'representante_legal'   => $representante_legal,
        'sector_atividade'      => $sector_atividade,
        'tamanho'               => $tamanho_empresa,
        'capital_social'        => $capital_social,
        'endereco'              => $id_endereco,
        'contacto_id'           => $id_contacto,
    ]);

    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':nif', $nif);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':objecto_social', $objectivo_social);
    $stmt->bindParam(':data_fundacao', $data_constituicao);
    $stmt->bindParam(':tipo_sociedade', $tipo_sociedade);
    $stmt->bindParam(':representante_legal', $representante_legal);
    $stmt->bindParam(':sector_atividade', $sector_atividade);
    $stmt->bindParam(':particularidade', $particularidade);
    $stmt->bindParam(':tamanho', $tamanho_empresa);
    $stmt->bindParam(':capital_social', $capital_social);
    $stmt->bindParam(':endereco', $id_endereco);
    $stmt->bindParam(':contacto_id', $id_contacto);
    $stmt->bindParam(':pessoa_id', $pessoa_id);

    if ($stmt->execute()) {
        $id_empresa = $conn->lastInsertId();

        // Associar empresa ao usuário
        $stmt = $conn->prepare("INSERT INTO empresas_usr (empresa_id, usuario_id) VALUES (:empresa_id, :usuario_id)");
        $stmt->bindParam(':empresa_id', $id_empresa);
        $stmt->bindParam(':usuario_id', $pessoa_id);
        $stmt->execute();

        // Cadastrar sócio
        if (is_array($dados['socios']))
            $_POST = $dados;
        foreach ($_POST['socios'] as $socio) {
            $nome_socio                 = ($socio["nome"]);
            $participacao               = ($socio["participacao"]);
            $contacto_socio             = ($socio["contacto"]);
            $data_entrada               = date('Y-m-d');

            $stmt = $conn->prepare("INSERT INTO socios (nome_socio, empresa_id, participacao, contacto, data_entrada) 
                                    VALUES (:nome_socio, :empresa_id, :participacao, :contacto, :data_entrada)");
            $stmt->bindParam(':nome_socio', $nome_socio);
            $stmt->bindParam(':empresa_id', $id_empresa);
            $stmt->bindParam(':participacao', $participacao);
            $stmt->bindParam(':contacto', $contacto_socio);
            $stmt->bindParam(':data_entrada', $data_entrada);
            $stmt->execute();
        }

        echo json_encode(["error" => false, "message" => "Cadastro realizado com sucesso."]);
    } else {
        echo json_encode(["error" => true, "message" => "Erro ao cadastrar."]);
        
        // Em caso de erro, remover os dados inseridos
        $stmt = $conn->prepare("DELETE FROM enderecos WHERE id = :id_endereco");
        $stmt->bindParam(':id_endereco', $id_endereco);
        $stmt->execute();

        // Remover contacto
        $stmt = $conn->prepare("DELETE FROM contactos WHERE id = :id_contacto");
        $stmt->bindParam(':id_contacto', $id_contacto);
        $stmt->execute();
    }
} else {
    echo json_encode(["error" => true, "message" => "Tipo de conteúdo inválido."]);
    exit;
}