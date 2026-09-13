-- ====== - CoinHub - Banco de Dados - ====== --

DROP DATABASE IF EXISTS pw2_coinhub;
CREATE DATABASE pw2_coinhub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pw2_coinhub;

-- ====== - Tabelas - ====== --

-- Usuários do sistema / LOGIN
CREATE TABLE tb_usuarios (
    cd_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nm_usuario VARCHAR(100) NOT NULL,
    ds_login VARCHAR(50) NOT NULL UNIQUE,
    ds_senha VARCHAR(255) NOT NULL
);

-- Produtos / estoque
-- nm_produto guarda o nome do pacote (ex.: 1.000 Robux)
CREATE TABLE tb_produtos (
    cd_produto INT PRIMARY KEY AUTO_INCREMENT,
    nm_produto VARCHAR(100) NOT NULL,
    nm_jogo VARCHAR(100) NOT NULL,
    vl_preco DECIMAL(10,2) NOT NULL,
    qt_estoque INT NOT NULL DEFAULT 0,
    CONSTRAINT ck_produto_preco CHECK (vl_preco >= 0),
    CONSTRAINT ck_produto_estoque CHECK (qt_estoque >= 0)
);

-- Compras aumentam o estoque
CREATE TABLE tb_compras (
    cd_compra INT PRIMARY KEY AUTO_INCREMENT,
    cd_produto INT NOT NULL,
    qt_compra INT NOT NULL,
    vl_unitario DECIMAL(10,2) NOT NULL,
    vl_total DECIMAL(10,2) NOT NULL,
    dt_compra DATE NOT NULL,
    CONSTRAINT ck_compra_quantidade CHECK (qt_compra > 0),
    CONSTRAINT ck_compra_valor CHECK (vl_unitario >= 0 AND vl_total >= 0),
    CONSTRAINT fk_compra_produto FOREIGN KEY (cd_produto)
        REFERENCES tb_produtos(cd_produto)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

-- Vendas diminuem o estoque
CREATE TABLE tb_vendas (
    cd_venda INT PRIMARY KEY AUTO_INCREMENT,
    cd_produto INT NOT NULL,
    nm_cliente VARCHAR(100) NOT NULL,
    qt_venda INT NOT NULL,
    vl_unitario DECIMAL(10,2) NOT NULL,
    vl_total DECIMAL(10,2) NOT NULL,
    dt_venda DATE NOT NULL,
    CONSTRAINT ck_venda_quantidade CHECK (qt_venda > 0),
    CONSTRAINT ck_venda_valor CHECK (vl_unitario >= 0 AND vl_total >= 0),
    CONSTRAINT fk_venda_produto FOREIGN KEY (cd_produto)
        REFERENCES tb_produtos(cd_produto)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

-- ====== - Inserts - ====== --

INSERT INTO tb_usuarios (nm_usuario, ds_login, ds_senha)
VALUES ('Administrador', 'admin', '123456');

INSERT INTO tb_produtos (nm_produto, nm_jogo, vl_preco, qt_estoque) VALUES
('1.000 Robux', 'Roblox', 49.90, 12),
('1.000 V-Bucks', 'Fortnite', 39.90, 3),
('5.000 Moedas', 'Genshin Impact', 89.90, 0),
('2.000 CP', 'Call of Duty Mobile', 69.90, 8),
('1.000 Diamantes', 'Free Fire', 24.90, 5),
('3.000 V-Bucks', 'Fortnite', 99.90, 15),
('500 Robux', 'Roblox', 27.90, 2),
('1.500 Moedas', 'Genshin Impact', 59.90, 0);

INSERT INTO tb_compras (cd_produto, qt_compra, vl_unitario, vl_total, dt_compra) VALUES
(1, 50, 35.00, 1750.00, '2026-09-05'),
(2, 30, 28.00, 840.00, '2026-09-03'),
(3, 20, 65.00, 1300.00, '2026-09-01'),
(4, 15, 50.00, 750.00, '2026-08-28'),
(5, 40, 18.00, 720.00, '2026-08-25');

INSERT INTO tb_vendas (cd_produto, nm_cliente, qt_venda, vl_unitario, vl_total, dt_venda) VALUES
(1, 'João', 10, 49.90, 499.00, '2026-09-05'),
(2, 'Carlos', 5, 39.90, 199.50, '2026-09-05'),
(3, 'Marcos', 8, 89.90, 719.20, '2026-09-05'),
(4, 'Pedro', 3, 69.90, 209.70, '2026-09-04'),
(5, 'Lucas', 12, 24.90, 298.80, '2026-09-03');

-- ====== - Selects - ====== --
SELECT * FROM tb_usuarios;
SELECT * FROM tb_produtos;
SELECT * FROM tb_compras;
SELECT * FROM tb_vendas;

-- ====== - Relatórios - ====== --

-- Produtos mais vendidos
SELECT
    p.cd_produto,
    p.nm_produto,
    p.nm_jogo,
    COALESCE(SUM(v.qt_venda), 0) AS qt_vendida
FROM tb_produtos p
LEFT JOIN tb_vendas v ON p.cd_produto = v.cd_produto
GROUP BY p.cd_produto, p.nm_produto, p.nm_jogo
ORDER BY qt_vendida DESC;

-- Produtos menos vendidos
SELECT
    p.cd_produto,
    p.nm_produto,
    p.nm_jogo,
    COALESCE(SUM(v.qt_venda), 0) AS qt_vendida
FROM tb_produtos p
LEFT JOIN tb_vendas v ON p.cd_produto = v.cd_produto
GROUP BY p.cd_produto, p.nm_produto, p.nm_jogo
ORDER BY qt_vendida ASC;

-- ====== - Dashboard - ====== --
SELECT COUNT(*) AS total_produtos FROM tb_produtos;
SELECT COALESCE(SUM(qt_estoque), 0) AS total_estoque FROM tb_produtos;
SELECT COUNT(*) AS estoque_baixo FROM tb_produtos WHERE qt_estoque BETWEEN 1 AND 5;
SELECT COUNT(*) AS sem_estoque FROM tb_produtos WHERE qt_estoque = 0;

SELECT
    nm_produto,
    nm_jogo,
    vl_preco,
    qt_estoque,
    CASE
        WHEN qt_estoque > 5 THEN 'Disponível'
        WHEN qt_estoque BETWEEN 1 AND 5 THEN 'Estoque baixo'
        ELSE 'Sem estoque'
    END AS situacao
FROM tb_produtos;
