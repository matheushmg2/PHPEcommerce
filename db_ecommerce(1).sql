-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 12-Nov-2020 às 12:21
-- Versão do servidor: 10.4.11-MariaDB
-- versão do PHP: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_ecommerce`
--

DELIMITER $$
--
-- Procedimentos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_carrinho_salvo` (`pidcarrinho` INT, `pdessessaoid` VARCHAR(64), `ptb_usuario_idusuario` INT, `pdescodigopostal` VARCHAR(15), `pvlfrete` DECIMAL(10,2), `pnrdias` INT)  BEGIN
IF pidcarrinho > 0 THEN
UPDATE tb_carrinho SET
dessessaoid = pdessessaoid, 
tb_usuario_idusuario = ptb_usuario_idusuario, descodigopostal = pdescodigopostal, 
vlfrete = pvlfrete, nrdias = pnrdias
WHERE idcarrinho = pidcarrinho;
ELSE 
INSERT INTO tb_carrinho (dessessaoid, tb_usuario_idusuario, descodigopostal, vlfrete, nrdias)
VALUES (pdessessaoid, ptb_usuario_idusuario, pdescodigopostal, pvlfrete, pnrdias);

SET pidcarrinho = LAST_INSERT_ID();
END IF;

SELECT * FROM tb_carrinho WHERE idcarrinho = pidcarrinho;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_categorias_salvar` (`pidcategorias` INT, `pdescategoria` VARCHAR(64))  BEGIN
IF pidcategorias > 0 THEN
UPDATE tb_categorias SET descategorias = pdescategoria WHERE idcategorias = pidcategorias;
ELSE 
INSERT INTO tb_categorias (descategorias) VALUES (pdescategoria);
SET pidcategorias = LAST_INSERT_ID();
END IF;
SELECT * FROM tb_categorias WHERE idcategorias = pidcategorias;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_enderecos_salvo` (`pidenderecos` INT(11), `ptb_pessoas_idpessoas` INT(11), `pdesenderecos` VARCHAR(128), `pdescomplemento` VARCHAR(32), `pdescidades` VARCHAR(150), `pdesestados` VARCHAR(120), `pdespais` VARCHAR(32), `pdescodigopostal` VARCHAR(64), `pdesdistritos` VARCHAR(64))  BEGIN

IF pidenderecos > 0 THEN
UPDATE tb_enderecos SET
tb_pessoas_idpessoas = ptb_pessoas_idpessoas, 
desenderecos = pdesenderecos, 
descomplemento = pdescomplemento, 
descidades = pdescidades, 
desestados = pdesestados, 
despais = pdespais, 
descodigopostal = pdescodigopostal, 
desdistritos = pdesdistritos
WHERE idenderecos = pidenderecos;

ELSE 

INSERT INTO tb_enderecos (tb_pessoas_idpessoas, desenderecos, descomplemento, descidades, desestados, despais, descodigopostal, desdistritos)
VALUES (ptb_pessoas_idpessoas, pdesenderecos, pdescomplemento, pdescidades, pdesestados, pdespais, pdescodigopostal, pdesdistritos);

SET pidenderecos = LAST_INSERT_ID();
END IF;

SELECT * FROM tb_enderecos WHERE idenderecos = pidenderecos;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pedidos_salvo` (`pidperdidos` INT, `ptb_carrinho_idcarrinho` INT(11), `ptb_usuario_idusuario` INT(11), `ptb_perdidosstatus_idstatus` INT(11), `ptb_enderecos_idenderecos` INT(11), `pvltotal` DECIMAL(10,2))  BEGIN
IF pidperdidos > 0 THEN
UPDATE tb_perdidos SET 
tb_carrinho_idcarrinho = ptb_carrinho_idcarrinho, 
tb_usuario_idusuario = ptb_usuario_idusuario, 
tb_perdidosstatus_idstatus = ptb_perdidosstatus_idstatus, 
tb_enderecos_idenderecos = ptb_enderecos_idenderecos, 
vltotal = pvltotal
WHERE idperdidos = pidperdidos;

ELSE 
INSERT INTO tb_perdidos (tb_carrinho_idcarrinho, tb_usuario_idusuario, tb_perdidosstatus_idstatus, tb_enderecos_idenderecos, vltotal)
VALUES (ptb_carrinho_idcarrinho, ptb_usuario_idusuario, ptb_perdidosstatus_idstatus, ptb_enderecos_idenderecos, pvltotal);

SET pidperdidos = LAST_INSERT_ID();
END IF;

select * from tb_perdidos pedidos 
INNER JOIN tb_perdidosstatus pstatus ON pedidos.tb_perdidosstatus_idstatus = pstatus.idstatus
INNER JOIN tb_carrinho carrinho ON pedidos.tb_carrinho_idcarrinho = carrinho.idcarrinho
INNER JOIN tb_usuario usuario ON pedidos.tb_usuario_idusuario = usuario.idusuario
INNER JOIN tb_enderecos endereco ON pedidos.tb_enderecos_idenderecos = endereco.idenderecos
WHERE pedidos.idperdidos = pidperdidos;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_produtos_salvo` (`pidprodutos` INT(11), `pdesprodutos` VARCHAR(64), `pvlpreco` DECIMAL(10,2), `pvllargura` DECIMAL(10,2), `pvlaltura` DECIMAL(10,2), `pvlcomprimento` DECIMAL(10,2), `pvlpeso` DECIMAL(10,2), `pdesurl` VARCHAR(128))  BEGIN
IF pidprodutos > 0 THEN
UPDATE tb_produtos SET desprodutos = pdesprodutos, vlpreco = pvlpreco, vllargura = pvllargura, vlaltura = pvlaltura, vlcomprimento = pvlcomprimento, vlpeso = pvlpeso, desurl = pdesurl WHERE idprodutos = pidprodutos;
ELSE 
INSERT INTO tb_produtos (desprodutos, vlpreco, vllargura, vlaltura, vlcomprimento, vlpeso, desurl)
VALUES (pdesprodutos, pvlpreco, pvllargura, pvlaltura, pvlcomprimento, pvlpeso, pdesurl);
SET pidprodutos = LAST_INSERT_ID();
END IF;
SELECT * FROM tb_produtos WHERE idprodutos = pidprodutos;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_recuperarsenhausuario_criado` (`piduser` INT, `pdesip` VARCHAR(45))  BEGIN
INSERT INTO tb_recuperarsenhausuario (tb_usuario_idusuario, desip) VALUES(piduser, pdesip);

SELECT * FROM tb_recuperarsenhausuario WHERE idrecuperar = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_usuasrio_delete` (`pidusuario` INT)  BEGIN
DECLARE vidpessoas INT;
SELECT tb_pessoas_idpessoas INTO vidpessoas FROM tb_usuario WHERE idusuario = pidusuario;

DELETE FROM tb_enderecos WHERE tb_pessoas_idpessoas = vidpessoas;

DELETE FROM tb_enderecos WHERE idenderecos IN(SELECT tb_enderecos_idenderecos FROM tb_perdidos WHERE tb_usuario_idusuario = pidusuario);

DELETE FROM tb_pessoas WHERE idpessoas = vidpessoas;

DELETE FROM tb_usuiariologs WHERE tb_usuario_idusuario = pidusuario;

DELETE FROM tb_recuperarsenhausuario  WHERE tb_usuario_idusuario = pidusuario;

DELETE FROM tb_perdidos  WHERE tb_usuario_idusuario = pidusuario;

DELETE FROM tb_carrinhoprodutos WHERE idcarrinhoprodutos IN(SELECT idcarrinho FROM tb_carrinho WHERE tb_usuario_idusuario = pidusuario);

DELETE FROM tb_carrinho WHERE tb_usuario_idusuario = pidusuario;

DELETE FROM tb_usuario WHERE idusuario = pidusuario;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_usuasrio_salvo` (`pdespessoas` VARCHAR(64), `pdeslogin` VARCHAR(64), `pdespassword` VARCHAR(256), `pdesemail` VARCHAR(128), `pnrphone` BIGINT, `pinadmin` TINYINT)  BEGIN
DECLARE vidpessoas INT;
INSERT INTO tb_pessoas(despessoas, desemail, nrphone) VALUES(pdespessoas, pdesemail, pnrphone);
SET vidpessoas = LAST_INSERT_ID();
INSERT INTO tb_usuario(tb_pessoas_idpessoas, deslogin, despassword, inadmin) VALUES (vidpessoas, pdeslogin, pdespassword, pinadmin);
SELECT * FROM tb_usuario usuario INNER JOIN tb_pessoas pessoa ON pessoa.idpessoas = usuario.tb_pessoas_idpessoas WHERE usuario.idusuario = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_usuasrio_update_salvo` (`pidusuario` INT, `pdespessoas` VARCHAR(64), `pdeslogin` VARCHAR(64), `pdespassword` VARCHAR(256), `pdesemail` VARCHAR(128), `pnrphone` BIGINT, `pinadmin` TINYINT)  BEGIN
DECLARE vidpessoas INT;
SELECT tb_pessoas_idpessoas INTO vidpessoas FROM tb_usuario WHERE idusuario = pidusuario;
UPDATE tb_pessoas SET despessoas = pdespessoas, desemail = pdesemail, nrphone= pnrphone WHERE idpessoas = vidpessoas;
UPDATE tb_usuario SET tb_pessoas_idpessoas = vidpessoas, deslogin = pdeslogin, despassword = pdespassword, inadmin = pinadmin WHERE idusuario = pidusuario;
SELECT * FROM tb_usuario usuario INNER JOIN tb_pessoas pessoa ON pessoa.idpessoas = usuario.tb_pessoas_idpessoas WHERE usuario.idusuario = pidusuario;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_carrinho`
--

CREATE TABLE `tb_carrinho` (
  `idcarrinho` int(11) NOT NULL,
  `dessessaoid` varchar(64) NOT NULL,
  `tb_usuario_idusuario` int(11) DEFAULT NULL,
  `descodigopostal` varchar(15) DEFAULT NULL,
  `vlfrete` decimal(10,2) DEFAULT NULL,
  `nrdias` int(11) DEFAULT NULL,
  `dtregistros` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_carrinho`
--

INSERT INTO `tb_carrinho` (`idcarrinho`, `dessessaoid`, `tb_usuario_idusuario`, `descodigopostal`, `vlfrete`, `nrdias`, `dtregistros`) VALUES
(1, 'vrmopdj5gr1duvt520p9ob3v40', NULL, NULL, NULL, NULL, '2020-08-13 19:11:00'),
(2, '062dgjq7bbqanqm4ds7c5730oe', NULL, '35350000', '60.42', 2, '2020-08-14 03:43:49'),
(3, '6pc8ql4h5dfpeg28h13gurmerq', NULL, '35350000', '72.20', 2, '2020-08-14 16:01:20'),
(4, '0kpsu7aihmka459v9kdnntho3j', NULL, '35350000', '85.79', 2, '2020-08-14 16:02:44'),
(5, 'r9d1e95r4j1coktdr4ikdi0d9j', NULL, NULL, NULL, NULL, '2020-08-14 17:06:39'),
(6, 'hub4ihj7iqprpfeqb83gn442l7', NULL, '35350000', '72.20', 2, '2020-08-14 17:11:29'),
(7, 'lmk0uh9hal5n4jco6l774o6tj8', NULL, '35350000', '60.42', 2, '2020-08-14 18:09:46'),
(8, '4a9ravog5e70cam4vd7pdjvj7c', NULL, NULL, NULL, NULL, '2020-08-14 21:25:36'),
(9, '6rl4at6uebl2kldt4dkb9fubv7', NULL, NULL, NULL, NULL, '2020-08-15 23:17:27'),
(10, '84abuigkpitl5kqjn7acn1i60c', NULL, NULL, NULL, NULL, '2020-08-16 19:50:02'),
(11, '3og8elkv80gulo8qnprggiblr1', NULL, '30110001', '114.75', 4, '2020-08-17 17:52:41'),
(12, 'puuit08hgdv2nj4109ejo1ht3i', NULL, '35352971', '87.90', 2, '2020-08-18 18:03:16'),
(13, 'hfqvt27blrvltpvsso5gj0j4bn', NULL, '30110001', '93.10', 4, '2020-08-18 20:16:44'),
(14, 'b3mhs7p0i6hg26u3tcc06ord5q', NULL, '30110001', '162.06', 4, '2020-08-18 21:19:29'),
(15, 'vfn7do49f79ndd87c1di8ra07p', 1, '30110001', '317.37', 4, '2020-08-19 18:28:04'),
(16, '5m0i92us8m0d4bppacc5tcup21', 1, NULL, NULL, NULL, '2020-08-20 23:26:40');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_carrinhoprodutos`
--

CREATE TABLE `tb_carrinhoprodutos` (
  `idcarrinhoprodutos` int(11) NOT NULL,
  `tb_carrinho_idcarrinho` int(11) NOT NULL,
  `tb_produtos_idprodutos` int(11) NOT NULL,
  `dtremover` datetime DEFAULT NULL,
  `dtregistros` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_carrinhoprodutos`
--

INSERT INTO `tb_carrinhoprodutos` (`idcarrinhoprodutos`, `tb_carrinho_idcarrinho`, `tb_produtos_idprodutos`, `dtremover`, `dtregistros`) VALUES
(1, 2, 8, '2020-08-14 02:10:46', '2020-08-14 04:03:02'),
(2, 2, 8, '2020-08-14 02:10:51', '2020-08-14 04:03:04'),
(3, 2, 8, '2020-08-14 02:10:58', '2020-08-14 04:07:27'),
(4, 2, 8, '2020-08-14 02:25:03', '2020-08-14 04:07:28'),
(5, 2, 9, '2020-08-14 02:10:41', '2020-08-14 04:07:56'),
(6, 2, 9, '2020-08-14 02:10:55', '2020-08-14 04:08:00'),
(7, 2, 8, '2020-08-14 02:25:03', '2020-08-14 05:11:11'),
(8, 2, 8, '2020-08-14 02:25:03', '2020-08-14 05:21:38'),
(9, 2, 7, '2020-08-14 02:32:27', '2020-08-14 05:32:08'),
(10, 3, 6, NULL, '2020-08-14 16:01:20'),
(11, 4, 9, NULL, '2020-08-14 16:02:44'),
(12, 4, 6, NULL, '2020-08-14 17:06:11'),
(13, 6, 6, NULL, '2020-08-14 17:11:30'),
(14, 7, 7, NULL, '2020-08-14 18:09:46'),
(15, 11, 7, '2020-08-17 15:02:00', '2020-08-17 17:52:41'),
(16, 11, 8, '2020-08-17 16:55:10', '2020-08-17 18:01:56'),
(17, 11, 5, '2020-08-17 15:26:02', '2020-08-17 18:25:58'),
(18, 11, 8, '2020-08-17 16:55:13', '2020-08-17 19:01:53'),
(19, 11, 8, '2020-08-17 19:12:16', '2020-08-17 19:01:54'),
(20, 11, 1, '2020-08-17 19:12:45', '2020-08-17 22:12:12'),
(21, 11, 6, NULL, '2020-08-17 22:12:58'),
(22, 11, 6, NULL, '2020-08-17 22:13:21'),
(23, 12, 7, NULL, '2020-08-18 18:03:26'),
(24, 12, 7, NULL, '2020-08-18 18:03:29'),
(25, 13, 9, '2020-08-18 18:00:22', '2020-08-18 20:16:45'),
(26, 13, 7, NULL, '2020-08-18 21:00:18'),
(27, 13, 7, NULL, '2020-08-18 21:00:29'),
(28, 14, 3, '2020-08-18 18:19:43', '2020-08-18 21:19:29'),
(29, 14, 3, '2020-08-18 18:19:43', '2020-08-18 21:19:31'),
(30, 14, 6, '2020-08-18 18:20:29', '2020-08-18 21:19:52'),
(31, 14, 6, '2020-08-18 18:20:29', '2020-08-18 21:19:55'),
(32, 14, 4, '2020-08-18 18:20:32', '2020-08-18 21:20:04'),
(33, 14, 5, NULL, '2020-08-18 21:20:38'),
(34, 14, 5, NULL, '2020-08-18 21:20:41'),
(35, 14, 6, NULL, '2020-08-18 21:45:41'),
(36, 14, 6, NULL, '2020-08-18 21:45:55'),
(37, 15, 7, NULL, '2020-08-19 18:43:29'),
(38, 15, 7, NULL, '2020-08-19 19:11:19'),
(39, 15, 7, NULL, '2020-08-19 19:11:25'),
(40, 15, 6, NULL, '2020-08-19 19:11:32'),
(41, 15, 7, NULL, '2020-08-19 19:11:39'),
(42, 15, 6, NULL, '2020-08-19 19:11:47');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_categorias`
--

CREATE TABLE `tb_categorias` (
  `idcategorias` int(11) NOT NULL,
  `descategorias` varchar(32) NOT NULL,
  `dtregistros` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_categorias`
--

INSERT INTO `tb_categorias` (`idcategorias`, `descategorias`, `dtregistros`) VALUES
(1, 'Acer', '2020-08-12 04:36:55'),
(2, 'Motorola', '2020-08-12 04:37:03'),
(3, 'Apple', '2020-08-12 04:37:11'),
(4, 'Samsung', '2020-08-12 04:37:20'),
(5, 'Android', '2020-08-13 13:17:20');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_enderecos`
--

CREATE TABLE `tb_enderecos` (
  `idenderecos` int(11) NOT NULL,
  `tb_pessoas_idpessoas` int(11) NOT NULL,
  `desenderecos` varchar(128) NOT NULL,
  `descomplemento` varchar(32) DEFAULT NULL,
  `descidades` varchar(150) NOT NULL,
  `desestados` varchar(120) NOT NULL,
  `despais` varchar(32) NOT NULL,
  `descodigopostal` varchar(64) NOT NULL,
  `desdistritos` varchar(64) NOT NULL,
  `dtregistros` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_enderecos`
--

INSERT INTO `tb_enderecos` (`idenderecos`, `tb_pessoas_idpessoas`, `desenderecos`, `descomplemento`, `descidades`, `desestados`, `despais`, `descodigopostal`, `desdistritos`, `dtregistros`) VALUES
(1, 2, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-17 19:50:24'),
(2, 1, 'Rua dona Chavinha', 'casa', 'Raul Soares', 'MG', 'Brasil', '35350000', 'progresso', '2020-08-17 19:56:05'),
(3, 3, 'Avenida do Contorno', 'até 1191 - lado ímpar', 'Belo Horizonte', 'MG', 'Brasil', '30110001', 'Centro', '2020-08-17 22:19:03'),
(4, 3, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-17 22:46:19'),
(5, 3, 'Avenida do Contônião', 'até 1191 - lado ímpar', 'Belo Horizonte', 'MG', 'Brasil', '30110001', 'Centro', '2020-08-17 22:48:39'),
(6, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:04:29'),
(7, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:05:51'),
(8, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:07:11'),
(9, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:07:30'),
(10, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:24:17'),
(11, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:24:31'),
(12, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:24:45'),
(13, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:24:58'),
(14, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:25:42'),
(15, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:26:08'),
(16, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:26:33'),
(17, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:27:43'),
(18, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:28:09'),
(19, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:28:46'),
(20, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:29:01'),
(21, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:29:19'),
(22, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:29:32'),
(23, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:29:44'),
(24, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:30:01'),
(25, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:30:46'),
(26, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:31:09'),
(27, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:31:20'),
(28, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:31:33'),
(29, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:31:45'),
(30, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:31:54'),
(31, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:32:14'),
(32, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:32:55'),
(33, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:33:38'),
(34, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:33:49'),
(35, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:34:15'),
(36, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:35:14'),
(37, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:35:25'),
(38, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:35:41'),
(39, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:35:58'),
(40, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:36:18'),
(41, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:46:18'),
(42, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:46:40'),
(43, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:47:38'),
(44, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:47:51'),
(45, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:48:01'),
(46, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:48:52'),
(47, 1, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 18:49:11'),
(48, 3, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 20:17:17'),
(49, 3, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 20:17:41'),
(50, 3, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 20:19:04'),
(51, 3, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 20:21:31'),
(52, 3, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 20:21:49'),
(53, 3, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 20:28:18'),
(54, 3, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 20:28:56'),
(55, 3, 'Rua Aníbal Oliveira Maia 25', 'casa', 'Raul Soares', 'MG', 'Brasil', '35352971', 'Bicuíba', '2020-08-18 20:29:18'),
(56, 4, 'Avenida do Contorno', 'até 1191 - lado ímpar', 'Belo Horizonte', 'MG', 'Brasil', '30110001', 'Centro', '2020-08-18 21:01:37'),
(57, 4, 'Avenida do Contorno', 'até 1191 - lado ímpar', 'Belo Horizonte', 'MG', 'Brasil', '30110001', 'Centro', '2020-08-18 21:17:04'),
(58, 4, 'Avenida do Contorno', 'até 1191 - lado ímpar', 'Belo Horizonte', 'MG', 'Brasil', '30110001', 'Centro', '2020-08-18 21:18:30'),
(59, 4, 'Avenida do Contorno', 'até 1191 - lado ímpar', 'Belo Horizonte', 'MG', 'Brasil', '30110001', 'Centro', '2020-08-18 21:20:57'),
(60, 5, 'Avenida do Contorno', 'até 1191 - lado ímpar', 'Belo Horizonte', 'MG', 'Brasil', '30110001', 'Centro', '2020-08-18 21:46:52'),
(61, 3, 'Avenida do Contorno', 'até 1191 - lado ímpar', 'Belo Horizonte', 'MG', 'Brasil', '30110001', 'Centro', '2020-08-19 18:43:45'),
(62, 3, 'Avenida do Contorno', 'até 1191 - lado ímpar', 'Belo Horizonte', 'MG', 'Brasil', '30110001', 'Centro', '2020-08-19 19:12:22'),
(63, 1, 'Avenida do Contorno', 'até 1191 - lado ímpar', 'Belo Horizonte', 'MG', 'Brasil', '30110001', 'Centro', '2020-08-20 00:41:29');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_perdidos`
--

CREATE TABLE `tb_perdidos` (
  `idperdidos` int(11) NOT NULL,
  `tb_carrinho_idcarrinho` int(11) NOT NULL,
  `tb_usuario_idusuario` int(11) NOT NULL,
  `tb_perdidosstatus_idstatus` int(11) NOT NULL,
  `tb_enderecos_idenderecos` int(11) NOT NULL,
  `vltotal` decimal(10,2) NOT NULL,
  `dtregistros` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_perdidos`
--

INSERT INTO `tb_perdidos` (`idperdidos`, `tb_carrinho_idcarrinho`, `tb_usuario_idusuario`, `tb_perdidosstatus_idstatus`, `tb_enderecos_idenderecos`, `vltotal`, `dtregistros`) VALUES
(4, 13, 3, 1, 55, '727.94', '2020-08-18 20:29:19'),
(5, 13, 4, 1, 56, '2691.10', '2020-08-18 21:01:38'),
(6, 13, 0, 1, 57, '2691.10', '2020-08-18 21:17:04'),
(8, 14, 0, 1, 59, '2357.01', '2020-08-18 21:20:58'),
(9, 14, 0, 1, 60, '6208.08', '2020-08-18 21:46:53'),
(10, 15, 1, 3, 61, '1364.22', '2020-08-19 18:43:45'),
(11, 15, 1, 2, 62, '9288.93', '2020-08-19 19:12:26'),
(12, 15, 1, 1, 63, '9288.93', '2020-08-20 00:41:30');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_perdidosstatus`
--

CREATE TABLE `tb_perdidosstatus` (
  `idstatus` int(11) NOT NULL,
  `desstatus` varchar(32) NOT NULL,
  `dtregistros` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_perdidosstatus`
--

INSERT INTO `tb_perdidosstatus` (`idstatus`, `desstatus`, `dtregistros`) VALUES
(1, 'Em Aberto', '2020-08-18 17:47:42'),
(2, 'Aguardando Pagamento', '2020-08-18 17:47:42'),
(3, 'Pago', '2020-08-18 17:48:24'),
(4, 'Entregue', '2020-08-18 17:48:24');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_pessoas`
--

CREATE TABLE `tb_pessoas` (
  `idpessoas` int(11) NOT NULL,
  `despessoas` varchar(64) NOT NULL,
  `desemail` varchar(128) DEFAULT NULL,
  `nrphone` bigint(20) DEFAULT NULL,
  `dtregistros` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_pessoas`
--

INSERT INTO `tb_pessoas` (`idpessoas`, `despessoas`, `desemail`, `nrphone`, `dtregistros`) VALUES
(1, 'Administrador 2', 'suport.personal18@gmail.com', 33512814, '2020-08-07 16:46:44'),
(2, 'admin1', 'admin1@admin.com', 85207410, '2020-08-11 18:27:31'),
(3, 'loko', 'loko@qwe.qwe', 1111111, '2020-08-15 23:17:27'),
(4, 'face', 'face@face.cad', 211112222, '2020-08-18 21:01:29'),
(5, 'sede', 'sede@sede.sed', 852741, '2020-08-18 21:46:44');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_produtos`
--

CREATE TABLE `tb_produtos` (
  `idprodutos` int(11) NOT NULL,
  `desprodutos` varchar(64) NOT NULL,
  `vlpreco` decimal(10,2) NOT NULL,
  `vllargura` decimal(10,2) NOT NULL,
  `vlaltura` decimal(10,2) NOT NULL,
  `vlcomprimento` decimal(10,2) NOT NULL,
  `vlpeso` decimal(10,2) NOT NULL,
  `desurl` varchar(128) NOT NULL,
  `dtregistros` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_produtos`
--

INSERT INTO `tb_produtos` (`idprodutos`, `desprodutos`, `vlpreco`, `vllargura`, `vlaltura`, `vlcomprimento`, `vlpeso`, `desurl`, `dtregistros`) VALUES
(1, 'Ipad 64GB Wi-fi Tela 9.7\' Câmera 8MP Prata - Apple w', '2499.99', '0.75', '16.95', '24.50', '0.47', 'ipad-32gb', '2020-08-12 20:08:11'),
(3, 'Notebook Acer nitro 5 i7 16gb 1tb', '4300.00', '3.00', '27.00', '50.00', '3.50', 'notebook-acer', '2020-08-12 20:11:31'),
(4, 'Smartphone Android 11', '1200.00', '75.00', '151.00', '80.00', '167.00', 'smartphone-android-11', '2020-08-12 20:12:28'),
(5, 'Smartphone Motorola Moto G5 Plus', '1135.23', '15.20', '7.40', '0.70', '0.16', 'smartphone-motorola-moto-g5-plus', '2020-08-12 21:57:47'),
(6, 'Smartphone Moto Z Play', '1887.78', '14.10', '0.90', '1.16', '0.13', 'smartphone-moto-z-play', '2020-08-12 21:58:20'),
(7, 'Smartphone Samsung Galaxy J5 Pro', '1299.00', '14.60', '7.10', '0.80', '0.16', 'smartphone-samsung-galaxy-j5', '2020-08-12 21:58:56'),
(8, 'Smartphone Samsung Galaxy J7 Prime', '1149.00', '15.10', '7.50', '0.80', '0.16', 'smartphone-samsung-galaxy-j7', '2020-08-12 21:59:37'),
(9, 'Smartphone Samsung Galaxy J3 Dual', '679.90', '14.20', '7.10', '0.70', '0.14', 'smartphone-samsung-galaxy-j3', '2020-08-12 22:00:12');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_produtoscategorias`
--

CREATE TABLE `tb_produtoscategorias` (
  `idcategorias` int(11) NOT NULL,
  `idprodutos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_produtoscategorias`
--

INSERT INTO `tb_produtoscategorias` (`idcategorias`, `idprodutos`) VALUES
(1, 3),
(1, 4),
(2, 5),
(2, 6),
(3, 1),
(4, 7),
(4, 8),
(4, 9),
(5, 4),
(5, 5),
(5, 6),
(5, 7),
(5, 8),
(5, 9);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_recuperarsenhausuario`
--

CREATE TABLE `tb_recuperarsenhausuario` (
  `idrecuperar` int(11) NOT NULL,
  `tb_usuario_idusuario` int(11) NOT NULL,
  `desip` varchar(45) NOT NULL,
  `dtrecoperar` datetime DEFAULT NULL,
  `dtregistros` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_recuperarsenhausuario`
--

INSERT INTO `tb_recuperarsenhausuario` (`idrecuperar`, `tb_usuario_idusuario`, `desip`, `dtrecoperar`, `dtregistros`) VALUES
(1, 2, '127.0.0.1', NULL, '2020-08-11 17:02:52');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_usuario`
--

CREATE TABLE `tb_usuario` (
  `idusuario` int(11) NOT NULL,
  `tb_pessoas_idpessoas` int(11) NOT NULL,
  `deslogin` varchar(64) NOT NULL,
  `despassword` varchar(256) NOT NULL,
  `inadmin` tinyint(4) NOT NULL DEFAULT 0,
  `dtregistros` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_usuario`
--

INSERT INTO `tb_usuario` (`idusuario`, `tb_pessoas_idpessoas`, `deslogin`, `despassword`, `inadmin`, `dtregistros`) VALUES
(1, 1, 'teste1', '$2y$12$QzG2/wPTFer34Wngo5iyzOH4iPe8ZGj4gYJvqm96ySsmkQ658lHte', 1, '2020-08-06 22:01:07'),
(2, 2, 'admin1', '$2y$12$tO9oIqRrszSYjr6NxdyLfe.C/iOHxo1ImjgsTcgvXvJ9emUvTgb2.', 1, '2020-08-11 18:27:31'),
(3, 3, 'loko', '$2y$12$W.ckAhu6LvrpdeAuap2mvOji4cfRNHrCebt7j1fMz6X1mQSPDJBK2', 0, '2020-08-15 23:17:27'),
(4, 4, 'face', '$2y$12$jjVHoIw9Fjoxl4R69lID/OWgZb9u.FBhis7AGIFSyNjcNCqLUbDfi', 0, '2020-08-18 21:01:29'),
(5, 5, 'sede', '$2y$12$iovS8BhjTmVTEFJr9lcLOusjZ25pKraNcHxyY0nAX9xTSuzxFFiIq', 0, '2020-08-18 21:46:44');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_usuiariologs`
--

CREATE TABLE `tb_usuiariologs` (
  `idlog` int(11) NOT NULL,
  `tb_usuario_idusuario` int(11) NOT NULL,
  `deslog` varchar(128) NOT NULL,
  `desip` varchar(45) NOT NULL,
  `desusuarioagente` varchar(128) NOT NULL,
  `dessessionid` varchar(64) NOT NULL,
  `desurl` varchar(128) NOT NULL,
  `dtregistros` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `tb_carrinho`
--
ALTER TABLE `tb_carrinho`
  ADD PRIMARY KEY (`idcarrinho`);

--
-- Índices para tabela `tb_carrinhoprodutos`
--
ALTER TABLE `tb_carrinhoprodutos`
  ADD PRIMARY KEY (`idcarrinhoprodutos`);

--
-- Índices para tabela `tb_categorias`
--
ALTER TABLE `tb_categorias`
  ADD PRIMARY KEY (`idcategorias`);

--
-- Índices para tabela `tb_enderecos`
--
ALTER TABLE `tb_enderecos`
  ADD PRIMARY KEY (`idenderecos`);

--
-- Índices para tabela `tb_perdidos`
--
ALTER TABLE `tb_perdidos`
  ADD PRIMARY KEY (`idperdidos`);

--
-- Índices para tabela `tb_perdidosstatus`
--
ALTER TABLE `tb_perdidosstatus`
  ADD PRIMARY KEY (`idstatus`);

--
-- Índices para tabela `tb_pessoas`
--
ALTER TABLE `tb_pessoas`
  ADD PRIMARY KEY (`idpessoas`);

--
-- Índices para tabela `tb_produtos`
--
ALTER TABLE `tb_produtos`
  ADD PRIMARY KEY (`idprodutos`);

--
-- Índices para tabela `tb_produtoscategorias`
--
ALTER TABLE `tb_produtoscategorias`
  ADD PRIMARY KEY (`idcategorias`,`idprodutos`);

--
-- Índices para tabela `tb_recuperarsenhausuario`
--
ALTER TABLE `tb_recuperarsenhausuario`
  ADD PRIMARY KEY (`idrecuperar`);

--
-- Índices para tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD PRIMARY KEY (`idusuario`);

--
-- Índices para tabela `tb_usuiariologs`
--
ALTER TABLE `tb_usuiariologs`
  ADD PRIMARY KEY (`idlog`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_carrinho`
--
ALTER TABLE `tb_carrinho`
  MODIFY `idcarrinho` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `tb_carrinhoprodutos`
--
ALTER TABLE `tb_carrinhoprodutos`
  MODIFY `idcarrinhoprodutos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de tabela `tb_categorias`
--
ALTER TABLE `tb_categorias`
  MODIFY `idcategorias` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tb_enderecos`
--
ALTER TABLE `tb_enderecos`
  MODIFY `idenderecos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de tabela `tb_perdidos`
--
ALTER TABLE `tb_perdidos`
  MODIFY `idperdidos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `tb_perdidosstatus`
--
ALTER TABLE `tb_perdidosstatus`
  MODIFY `idstatus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tb_pessoas`
--
ALTER TABLE `tb_pessoas`
  MODIFY `idpessoas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `tb_produtos`
--
ALTER TABLE `tb_produtos`
  MODIFY `idprodutos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `tb_recuperarsenhausuario`
--
ALTER TABLE `tb_recuperarsenhausuario`
  MODIFY `idrecuperar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `tb_usuiariologs`
--
ALTER TABLE `tb_usuiariologs`
  MODIFY `idlog` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
