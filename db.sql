CREATE DATABASE aula_login;
USE aula_login;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo  VARCHAR(255) NOT NULL
);

-- Inserir usuário de teste (senha: 123456)
INSERT INTO usuarios (username, senha, tipo) 
VALUES ('aluno', '$2y$10$DMyfppPZVpKztFRwA4KpUeQwHeAPPA5miShAzUrcZK2L3u8eXjCiG', "ALUNO");

INSERT INTO usuarios (username, senha, tipo) 
VALUES ('professor', '$2y$10$DMyfppPZVpKztFRwA4KpUeQwHeAPPA5miShAzUrcZK2L3u8eXjCiG', "PROFESSOR");