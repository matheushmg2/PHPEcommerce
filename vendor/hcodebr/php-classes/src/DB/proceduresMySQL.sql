CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_usuasrio_salvo`(
pdespessoas VARCHAR(64), 
pdeslogin VARCHAR(64), 
pdespassword VARCHAR(256), 
pdesemail VARCHAR(128), 
pnrphone BIGINT, 
pinadmin TINYINT
)
BEGIN
DECLARE vidpessoas INT;
INSERT INTO tb_pessoas(despessoas, desemail, nrphone) VALUES(pdespessoas, pdesemail, pnrphone);
SET vidpessoas = LAST_INSERT_ID();
INSERT INTO tb_usuario(tb_pessoas_idpessoas, deslogin, despassword, inadmin) VALUES (vidpessoas, pdeslogin, pdespassword, pinadmin);
SELECT * FROM tb_usuario usuario INNER JOIN tb_pessoas pessoa ON pessoa.idpessoas = usuario.tb_pessoas_idpessoas WHERE usuario.idusuario = LAST_INSERT_ID();
END

/* ======================================================================== */

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_usuasrio_update_salvo`(
pidusuario INT,
pdespessoas VARCHAR(64), 
pdeslogin VARCHAR(64), 
pdespassword VARCHAR(256), 
pdesemail VARCHAR(128), 
pnrphone BIGINT, 
pinadmin TINYINT
)
BEGIN
DECLARE vidpessoas INT;
SELECT tb_pessoas_idpessoas INTO vidpessoas FROM tb_usuario WHERE idusuario = pidusuario;
UPDATE tb_pessoas SET despessoas = pdespessoas, desemail = pdesemail, nrphone= pnrphone WHERE idpessoas = vidpessoas;
UPDATE tb_usuario SET tb_pessoas_idpessoas = vidpessoas, deslogin = pdeslogin, despassword = pdespassword, inadmin = pinadmin WHERE idusuario = pidusuario;
SELECT * FROM tb_usuario usuario INNER JOIN tb_pessoas pessoa ON pessoa.idpessoas = usuario.tb_pessoas_idpessoas WHERE usuario.idusuario = pidusuario;
END

/* ======================================================================== */

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_usuasrio_delete`(pidusuario INT)
BEGIN
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

END

/* ======================================================================== */

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_categorias_salvar`(
pidcategorias INT,
pdescategoria VARCHAR(64)
)
BEGIN
IF pidcategorias > 0 THEN
UPDATE tb_categorias SET descategorias = pdescategoria WHERE idcategorias = pidcategorias;
ELSE 
INSERT INTO tb_categorias (descategorias) VALUES (pdescategoria);
SET pidcategorias = LAST_INSERT_ID();
END IF;
SELECT * FROM tb_categorias WHERE idcategorias = pidcategorias;

END

/* ======================================================================== */

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_produtos_salvo`(
pidprodutos int(11),
pdesprodutos varchar(64),
pvlpreco decimal(10,2), 
pvllargura decimal(10,2), 
pvlaltura decimal(10,2), 
pvlcomprimento decimal(10,2), 
pvlpeso decimal(10,2), 
pdesurl varchar(128)
)
BEGIN
IF pidprodutos > 0 THEN
UPDATE tb_produtos SET desprodutos = pdesprodutos, vlpreco = pvlpreco, vllargura = pvllargura, vlaltura = pvlaltura, vlcomprimento = pvlcomprimento, vlpeso = pvlpeso, desurl = pdesurl WHERE idprodutos = pidprodutos;
ELSE 
INSERT INTO tb_produtos (desprodutos, vlpreco, vllargura, vlaltura, vlcomprimento, vlpeso, desurl)
VALUES (pdesprodutos, pvlpreco, pvllargura, pvlaltura, pvlcomprimento, pvlpeso, pdesurl);
SET pidprodutos = LAST_INSERT_ID();
END IF;
SELECT * FROM tb_produtos WHERE idprodutos = pidprodutos;
END

/* ======================================================================== */

CREATE PROCEDURE `sp_carrinho_salvo` (
pidcarrinho INT, 
pdessessaoid VARCHAR(64), 
ptb_usuario_idusuario INT, 
pdescodigopostal VARCHAR(15), 
pvlfrete DECIMAL(10,2),
pnrdias INT
)
BEGIN
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
END

/* ======================================================================== */

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_enderecos_salvo`(
pidenderecos int(11), 
ptb_pessoas_idpessoas int(11), 
pdesenderecos varchar(128),
pdescomplemento varchar(32), 
pdescidades varchar(150), 
pdesestados varchar(120), 
pdespais varchar(32), 
pdescodigopostal varchar(64), 
pdesdistritos varchar(64)
)
BEGIN

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

END

/* ======================================================================== */

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_pedidos_salvo`(
pidperdidos INT, 
ptb_carrinho_idcarrinho int(11), 
ptb_usuario_idusuario int(11), 
ptb_perdidosstatus_idstatus int(11), 
ptb_enderecos_idenderecos int(11), 
pvltotal decimal(10,2)
)
BEGIN
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

END