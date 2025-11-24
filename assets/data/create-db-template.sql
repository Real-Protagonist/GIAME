CREATE DATABASE IF NOT EXISTS `giame`
    DEFAULT CHARACTER SET = 'utf8mb4';

DROP DATABASE IF EXISTS `giame`;

USE `giame`;

CREATE TABLE IF NOT EXISTS `pessoa` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `primeiro_nome` VARCHAR(100) NOT NULL,
    `ultimo_nome` VARCHAR(100) NOT NULL,
    `dt_nascimento` DATE NOT NULL,
    `nacionalidade` VARCHAR(100) NOT NULL,
    `nif` VARCHAR(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `usuario` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50),
    `password_hash` VARCHAR(255) NOT NULL,
    `last_login` TIMESTAMP,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `pessoa_id` INT,
    FOREIGN KEY (`pessoa_id`) REFERENCES `pessoa`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

alter table usuario add column last_login TIMESTAMP after password_hash;

CREATE PROCEDURE `InsertPessoa` (
    IN p_primeiro_nome VARCHAR(100),
    IN p_ultimo_nome VARCHAR(100),
    IN p_dt_nascimento DATE,
    IN p_nacionalidade VARCHAR(100),
    IN p_nif VARCHAR(20),
    IN EMAIL VARCHAR(100),
    IN USERNAME VARCHAR(50),
    IN last_login TIMESTAMP
) BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    INSERT INTO `pessoa` (primeiro_nome, ultimo_nome, dt_nascimento, nacionalidade, nif)
    VALUES (p_primeiro_nome, p_ultimo_nome, p_dt_nascimento, p_nacionalidade, p_nif);

    SET @last_pessoa_id = LAST_INSERT_ID();

    INSERT INTO `usuario` (username, email, pessoa_id, last_login)
    VALUES (USERNAME, EMAIL, @last_pessoa_id, last_login);

    COMMIT;
END;

CREATE TABLE IF NOT EXISTS `log_acesso` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `usuario_id` INT,
    `data_hora` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `ip_endereco` VARCHAR(45),
    FOREIGN KEY (`usuario_id`) REFERENCES `usuario`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `enderecos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `morada` VARCHAR(100),
    `bairro` VARCHAR(100) NOT NULL,
    `municipio` VARCHAR(100),
    `provincia` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `contactos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `telefone` VARCHAR(20),
    `email` VARCHAR(100),
    `site` VARCHAR(100),
    `descricao` VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `empresas`;
drop table empresas;
CREATE TABLE IF NOT EXISTS `empresas` (
    `id_empresa` INT AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(100) NOT NULL,
    `nif` VARCHAR(20) NOT NULL,
    `tipo` ENUM('Matriz', 'Filial', 'Sucursal') NOT NULL,
    `particularidade` ENUM('Pública', 'Privada', 'Sem fins lucrativos') NOT NULL,
    `endereco` INT,
    `contacto_id` INT,
    `tipo_sociedade` VARCHAR(100),
    `sector_atividade` VARCHAR(100),
    `tamanho` ENUM('Microempresa', 'Pequena', 'Média', 'Grande'),
    `objecto_social` TEXT,
    `capital_social` DECIMAL(15,2),
    `representante_legal` VARCHAR(100),
    `data_fundacao` DATE,
    `pessoa_id` INT,
    FOREIGN KEY (`endereco`) REFERENCES `enderecos`(`id`),
    FOREIGN KEY (`pessoa_id`) REFERENCES `usuario`(`id`),
    FOREIGN KEY (`contacto_id`) REFERENCES `contactos`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

select * from empresas_usr;

drop table empresas_usr;
CREATE TABLE IF NOT EXISTS `empresas_usr` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `empresa_id` INT,
    `usuario_id` INT,
    FOREIGN KEY (`empresa_id`) REFERENCES `empresas`(`id_empresa`),
    FOREIGN KEY (`usuario_id`) REFERENCES `usuario`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS SOCIOS (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `empresa_id` INT,
    `participacao` ENUM('Sócio', 'Acionista') NOT NULL,
    `percentual_participacao` DECIMAL(5,2),
    `data_entrada` DATE,
    `data_saida` DATE,
    FOREIGN KEY (`pessoa_id`) REFERENCES `pessoa`(`id`),
    FOREIGN KEY (`empresa_id`) REFERENCES `empresas`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

drop table socios;

CREATE TABLE IF NOT EXISTS `socios` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `nome_socio` VARCHAR(100) NOT NULL,
    `empresa_id` INT,
    `participacao` DECIMAL(10,2) NOT NULL,
    `contacto` VARCHAR(100),
    `data_entrada` DATE,
    `data_saida` DATE,
    FOREIGN KEY (`empresa_id`) REFERENCES `empresas`(`id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

select * from empresas;

drop table if exists conta_principal;
CREATE TABLE IF NOT EXISTS `conta_principal` (
    `codigo` BIGINT PRIMARY KEY NOT NULL,
    `descricao` VARCHAR(255) NOT NULL,
    `nivel` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

drop table if exists sub_conta_2;
CREATE TABLE IF NOT EXISTS `SUB_CONTA_2` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `codigo` VARCHAR(20) NOT NULL,
    `descricao` VARCHAR(255) NOT NULL,
    `nivel` INT NOT NULL,
    `conta_pai` BIGINT,
    FOREIGN KEY (`conta_pai`) REFERENCES `conta_principal`(`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `SUB_CONTA_3` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `codigo` VARCHAR(20) NOT NULL,
    `descricao` VARCHAR(255) NOT NULL,
    `nivel` INT NOT NULL,
    `conta_pai` INT,
    FOREIGN KEY (`conta_pai`) REFERENCES `sub_conta_2`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `SUB_CONTA_4` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `codigo` VARCHAR(20) NOT NULL,
    `descricao` VARCHAR(255) NOT NULL,
    `nivel` INT NOT NULL,
    `conta_pai` INT,
    FOREIGN KEY (`conta_pai`) REFERENCES `sub_conta_3`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

drop table if exists lancamentos;

CREATE TABLE IF NOT EXISTS `lancamentos` (
    `lancamento_id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `lancamento` BIGINT UNIQUE,
    `empresa_id` INT,
    `data_lancamento` DATE NOT NULL,
    `descricao` TEXT,
    `debito` DECIMAL(15,2) NOT NULL,
    `credito` DECIMAL(15,2) NOT NULL,
    `diferenca` DECIMAL(15,2) NOT NULL,
    `ano_analise` INT NOT NULL,
    `criador_usuario` INT,
    Foreign Key (`criador_usuario`) REFERENCES `usuario`(`id`),
    FOREIGN KEY (`empresa_id`) REFERENCES `empresas`(`id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

drop table if exists lancamento_itens;
CREATE TABLE IF NOT EXISTS `lancamento_itens` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `lancamento_id` BIGINT,
    `sub_conta_id` BIGINT,
    `valor` DECIMAL(15,2) NOT NULL,
    `tipo` ENUM('Debito', 'Credito') NOT NULL,
    FOREIGN KEY (`lancamento_id`) REFERENCES `lancamentos`(`lancamento_id`),
    FOREIGN KEY (`sub_conta_id`) REFERENCES `sub_conta_2`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

alter table lancamento_itens MODIFY column sub_conta_id VARCHAR(20);

CREATE TABLE IF NOT EXISTS `plano_contas` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `codigo` VARCHAR(20) NOT NULL,
    `descricao` VARCHAR(255) NOT NULL,
    `nivel` INT NOT NULL,
    `conta_pai` INT,
    FOREIGN KEY (`conta_pai`) REFERENCES `conta_principal`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `plano_contas` (codigo, descricao, nivel, conta_pai) VALUES
('1', 'Ativo', 1, NULL),
('1.1', 'Ativo Circulante', 2, 1),
('1.1.1', 'Caixa e Equivalentes de Caixa', 3, 1.1);

select * from conta_principal;
INSERT INTO `conta_principal` (`codigo`, `descricao`, `nivel`) VALUES
    (11, 'IMOBILIZAÇÕES CORPÓREAS', '1'),
    (12, 'IMOBILIZAÇÕES INCORPÓREAS', '1'),
    (13, 'INVESTIMENTOS FINANCEIROS', '1'),
    (14, 'IMOBILIZAÇÕES EM CURSO', '1'),
    (18, 'AMORTIZAÇÕES ACUMULADAS', '1'),
    (19, 'PROVISÕES PARA INVESTIMENTOS FINANCEIROS', '1'),
    (21, 'COMPRAS', '1'),
    (22, 'MATÉRIAS-PRIMAS SUBSIDIÁRIAS E DE CONSUMO', '1'),
    (23, 'PRODUTOS E TRABALHOS EM CURSO', '1'),
    (24, 'PRODUTOS E INTERMÉDIOS', '1'),
    (25, 'SUB-PRODUTOS, DESPERDÍCIOS, RESÍDUOS E REFUGOS', '1'),
    (26, 'MERCADORIAS', '1'),
    (27, 'MATÉRIAS-PRIMAS, MERCADORIAS E OUTROS MATERIAIS EM TRÂNSITO', '1'),
    (28, 'ADIANTAMENTO POR CONTA DE COMPRAS', '1'),
    (29, 'PROVISÃO PARA DEPRECIAÇÃO DE EXISTÊNCIAS', '1'),
    (31, 'CLIENTES', '1'),
    (32, 'FORNECEDORES', '1'),
    (33, 'EMPRÉSTIMOS', '1'),
    (34, 'ESTADO', '1'),
    (35, 'ENTIDADES PARTICIPANTES E PARTICIPADAS', '1'),
    (36, 'PESSOAL', '1'),
    (37, 'OUTROS VALORES A RECEBER E A PAGAR', '1'),
    (38, 'PROVISÕES PARA COBRANÇAS DUVIDOSAS', '1'),
    (39, 'PROVISÕES PARA OUTROS RISCOS E ENCARGOS', '1'),
    (41, 'TÍTULOS NEGOCÍAVEIS', '1'),
    (42, 'DEPÓSITOS A PRAZO', '1'),
    (43, 'DEPÓSITOS À ORDEM', '1'),
    (44, 'OUTROS DEPÓSITOS', '1'),
    (45, 'CAIXA', '1'),
    (48, 'CONTA TRANSITÓRIA', '1'),
    (49, 'PROVISÕES PARA APLICAÇÕES DE TESOURARIA', '1'),
    (51, 'CAPITAL', '1'),
    (52, 'AÇÕES/QUOTAS PRÓPRIAS', '1'),
    (53, 'PRÉMIOS DE EMISSÃO', '1'),
    (54, 'PRESTAÇÕES SUPLEMENTARES', '1'),
    (55, 'RESERVAS LEGAIS', '1'),
    (56, 'RESERVAS DE REAVALIAÇÃO', '1'),
    (57, 'RESERVAS COM FINS ESPECIAIS', '1'),
    (58, 'RESERVAS LIVRES', '1'),
    (61, 'VENDAS', '1'),
    (62, 'PRESTAÇÃO DE SERVIÇO', '1'),
    (63, 'OUTROS PROVEITOS OPERACIONAIS', '1'),
    (64, 'VARIAÇÃO NOS INVENTÁRIOS DE PRODUTOS ACABADOS E DE PRODUÇÃO EM CURSO', '1'),
    (65, 'TRABALHOS PARA A PRÓPRIA EMPRESA', '1'),
    (66, 'PROVEITOS E GANHOS FINANCEIROS GERAIS', '1'),
    (67, 'PROVEITOS E GANHOS FINANCEIROS EM FILIAIS E ASSOCIADAS', '1'),
    (68, 'OUTROS PROVEITOS E GANHOS NÃO OPERACIONAIS', '1'),
    (69, 'PROVEITOS E GANHOS EXTRAORDINÁRIOS', '1'),
    (71, 'CUSTO DAS MERCADORIAS VENDIDAS E DAS MATÉRIAS CONSUMIDAS', '1'),
    (72, 'CUSTO COM PESSOAL', '1'),
    (73, 'AMORTIZAÇÕES DO EXERCÍCIO', '1'),
    (75, 'OUTROS CUSTOS E PERDAS OPERACIONAIS', '1'),
    (76, 'CUSTOS E PERDAS FINANCEIROS GERAIS', '1'),
    (77, 'CUSTOS E PERDAS E FINANCEIRAS EM FILIAIS E ASSOCIADAS', '1'),
    (78, 'OUTROS CUSTOS E PERDAS NÃO OPERACIONAIS', '1'),
    (79, 'CUSTOS E PERDAS EXTRAORDINÁRIAS', '1'),
    (81, 'RESULTADOS TRANSITADOS', '1'),
    (82, 'RESULTADOS OPERACIONAIS', '1'),
    (83, 'RESULTADOS FINANCEIROS', '1'),
    (84, 'RESULTADOS FINANCEIROS EM FILIAIS E ASSOCIADAS', '1'),
    (85, 'RESULTADOS NÃO OPERACIONAIS', '1'),
    (86, 'RESULTADOS EXTRAORDINÁRIOS', '1'),
    (87, 'IMPOSTO SOBRE OS LUCROS', '1'),
    (88, 'RESULTADOS LÍQUIDOS DO EXERCÍCIO', '1'),
    (89, 'DIVIDENDOS ANTECIPADOS', '1');

    select * from sub_conta_2;

    INSERT INTO `sub_conta_2` (`codigo`, `descricao`, `nivel`, `conta_pai`) VALUES
    ('22.1', 'Matérias-primas', '2', 22),
    ('22.2', 'Matérias subisidiárias', '2', 22),
    ('22.3', 'Matériais diversos', '2', 22),
    ('22.4', 'Embalagens de consumo', '2', 22),
    ('22.5', 'Outros materiais', '2', 22);
    SELECT * FROM lancamento_itens;

    SELECT li.*, l.data_lancamento, l.lancamento, sc.codigo AS sub_conta_codigo, sc.descricao AS sub_conta_descricao
                                        FROM lancamento_itens li
                                        JOIN lancamentos l ON li.lancamento_id = l.lancamento_id
                                        JOIN sub_conta_2 sc ON li.sub_conta_id = sc.id
                                        JOIN empresas e ON l.empresa_id = e.id_empresa
                                        JOIN usuario u ON l.criador_usuario = u.id
                                        WHERE u.id = 2
                                        ORDER BY l.data_lancamento DESC, l.lancamento DESC
-- Additional table and procedure definitions can be added here as needed.
-- End of create-db-template.sql