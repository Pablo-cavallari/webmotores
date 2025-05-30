-- Criar o banco de dados
CREATE DATABASE IF NOT EXISTS webmotors;
USE webmotors;

-- Tabela de Usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('user', 'admin') DEFAULT 'user'
);

-- Tabela de Anúncios
CREATE TABLE anuncios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    categoria ENUM('Carro', 'Moto') NOT NULL,
    imagem VARCHAR(255),
    aprovado TINYINT(1) DEFAULT 0, -- 0 = não aprovado, 1 = aprovado
    data DATETIME DEFAULT CURRENT_TIMESTAMP,
    usuario_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
