-- =====================================================
-- Banco de dados para Sistema de Diagnóstico Médico
-- =====================================================

-- Criação do banco
CREATE DATABASE IF NOT EXISTS sistema_diagnostico
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;
USE sistema_diagnostico;

-- -----------------------------------------------------
-- Tabela: usuarios (login)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    crm VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------------------------------
-- Tabela: historico (diagnósticos do usuário)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS historico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    doenca VARCHAR(255) NOT NULL,
    sintomas TEXT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- -----------------------------------------------------
-- Tabela: pesquisas (registro de pesquisas do usuário)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS pesquisas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    doenca VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- -----------------------------------------------------
-- Usuário de teste (senha: 123456)
-- -----------------------------------------------------
INSERT INTO usuarios (nome, crm, email, senha) VALUES (
    'Dr. Teste', 'CRM12345', 'teste@medico.com',
    '$2y$10$CwTycUXWue0Thq9StjUM0uJ8nK5QX6Q5XyHbZ1u6C8Q4xZGbM4mG6'
);
