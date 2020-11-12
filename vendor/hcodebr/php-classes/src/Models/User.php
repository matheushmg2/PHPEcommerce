<?php

namespace Hcode\Models;
use Hcode\DB\Sql;
use Hcode\Models;
use Hcode\Mailer;

class User extends Models {

    const SESSION = 'User';
    const SECRET = "HcodePhp7_Secret";
    const SECRET_IV = "HcodePhp7_Secret_IV";

    const ERROR = "UsuarioError";
    const ERROR_REGISTROS = "UsuarioErrorRegistros";
    const SUCESSO_REGISTROS = "UsuarioSucessoRegistros";

    public static function getGerandoSessionUsuario()
    {
        $User = new User();

        if(isset($_SESSION[User::SESSION]) && (int)$_SESSION[User::SESSION]['idusuario'] > 0){
            
            $User->setDados($_SESSION[User::SESSION]);
            
        }

        return $User;

    }

    public function getNomePessoaUsuario($idPessoa)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT pessoa.despessoas FROM `tb_pessoas` pessoa INNER JOIN tb_usuario usuario on pessoa.idpessoas = usuario.tb_pessoas_idpessoas WHERE pessoa.idpessoas = :idpessoas ", array(":idpessoas"=>$idPessoa));

         return $results[0]['despessoas'];
    }

    public static function verificaUsuarioLogado($inadmin = true)
    {
        if(!isset($_SESSION[User::SESSION]) || 
        !$_SESSION[User::SESSION] || 
        !(int)$_SESSION[User::SESSION]['idusuario'] > 0 )
        {
            // Ou seja, Usuário não está logado
            return false;
        } else {
            if($inadmin === true && (bool)$_SESSION[User::SESSION]['inadmin'] === true) {
                return true;
            } else if($inadmin === false) {
                return true;
            } else {
                return false;
            }
        }
    }

    public static function login($login, $password){

        $sql = new Sql();
        
        $results = $sql->select("SELECT * FROM tb_usuario usuario INNER JOIN tb_pessoas pessoas ON usuario.tb_pessoas_idpessoas = pessoas.idpessoas WHERE usuario.deslogin = :LOGIN", array(":LOGIN"=>$login));

        if(count($results) === 0){
            throw new \Exception("Usuário ou senha Inválida!");
        }
        $dados = $results[0];
        if(password_verify($password, $dados['despassword']) === true){
            $User = new User();

            $dados['despessoas'] = utf8_encode($dados['despessoas']);
            
            $User->setDados($dados);

            $_SESSION[User::SESSION] = $User->getValores();

            /*var_dump($User);
            exit;*/
            return $User;
        } else {
            throw new \Exception("Usuário ou senha Inválida!");
        }

    }

    public static function verificaLogin($inadmin = true)
    {
        if(!User::verificaUsuarioLogado($inadmin))
        {
            if($inadmin){
                header("Location: /Adm/login");
            }else {
                header("Location: /login");
            }
            exit;
        }
    }

    public static function logout(){
        $_SESSION[User::SESSION] = NULL;
    }

    public static function listaAll()
    {
        $sql = new Sql();
        return $sql->select("SELECT * FROM tb_usuario users INNER JOIN tb_pessoas pessoas ON pessoas.idpessoas = users.tb_pessoas_idpessoas order by users.idusuario DESC");
    }

    public function createSalvar()
    {
        $sql = new Sql();
        //$valores = ':' . implode(', :', array_keys($usuario)); 
        $results = $sql->select("CALL sp_usuasrio_salvo(:despessoas, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
            ":despessoas" => utf8_decode($this->getdespessoas()),
            ":deslogin" => $this->getdeslogin(),
            ":despassword" => User::getPasswordHash($this->getdespassword()),
            ":desemail" => $this->getdesemail(),
            ":nrphone" => $this->getnrphone(),
            ":inadmin" => $this->getinadmin()
        ));
        

        $this->setDados($results[0]);
    }

    public static function getPasswordHash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
    }

    public function get($idusuario)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_usuario users INNER JOIN tb_pessoas pessoas ON pessoas.idpessoas = users.tb_pessoas_idpessoas WHERE users.idusuario = :idusuario", array(
            ":idusuario" =>$idusuario
        ));
        $this->setDados($results[0]);
    }

    public function updateSalvar()
    {
        $sql = new Sql();

        $results = $sql->select("CALL sp_usuasrio_update_salvo(:idusuario, :despessoas, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
            ":idusuario" => $this->getidusuario(),
            ":despessoas" => utf8_decode($this->getdespessoas()),
            ":deslogin" => $this->getdeslogin(),
            ":despassword" => User::getPasswordHash($this->getdespassword()),
            ":desemail" => $this->getdesemail(),
            ":nrphone" => $this->getnrphone(),
            ":inadmin" => $this->getinadmin()
        ));

        $this->setDados($results[0]);
    }


    public function delete()
    {
        $sql = new Sql();
        $results = $sql->select("CALL sp_usuasrio_delete(:idusuario)", array(
            ":idusuario" => $this->getidusuario()
        ));
    }

    public static function getEsqueceSenha($email){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_pessoas pessoas INNER JOIN tb_usuario usuario ON usuario.tb_pessoas_idpessoas = pessoas.idpessoas WHERE pessoas.desemail = :email", array(
            ":email" => $email
        ));

        if(count($results) === 0) {
            throw new \Exception("Email Inválido!");
        } else {

            $dados = $results[0];

            $results2 = $sql->select("CALL sp_recuperarsenhausuario_criado(:idusuario, :desip)", array(
                ":idusuario" => $dados["idusuario"],
                ":desip"=>$_SERVER["REMOTE_ADDR"]
            ));

            if(count($results2) === 0){
                throw new \Exception("Não foi possivel recuperar senha!");
            } else {
                $dadosRecuperados = $results2[0];

                echo "<br>dadosRecuperados<br>";
                var_dump($dadosRecuperados);

                $iv = random_bytes(openssl_cipher_iv_length('aes-256-cbc'));

                $codigo = openssl_encrypt($dadosRecuperados['idrecuperar'], 'aes-256-cbc', User::SECRET, 0, $iv);

                $codigo = base64_encode($iv.$codigo);

                echo "<br>codigo<br>";
                var_dump($codigo);

                $link = "http://phploja.com/Adm/forgot/reset?code=$codigo";

                $email = new Mailer($dados['desemail'], $dados['despessoas'], "Redefinir sua Senha", "forgot", array(
                    "name" => $dados['despessoas'],
                    "link" => $link
                ));

                echo "<br>email<br>";
                var_dump($email);

                $email->send();

                return $link;
            }
        }
    }

    public static function validarDescriptografiaEsqueceuASenha($codigo)
    {
        $codigo = base64_decode($codigo);
        $idrecuperar = openssl_decrypt($codigo, 'AES-128-CBC', pack("a16", User::SECRET), 0, pack("a16", User::SECRET_IV));

        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_recuperarsenhausuario recuperar
        INNER JOIN tb_usuario usuario ON usuario.idusuario = recuperar.tb_usuario_idusuario
        INNER JOIN tb_pessoas pessoas ON pessoas.idpessoas = usuario.tb_pessoas_idpessoas
        WHERE recuperar.idrecuperar = :idrecuperar
        AND recuperar.dtrecoperar IS NULL
        AND DATE_ADD(recuperar.dtregistros, INTERVAL 1 HOUR) >= NOW()", array(":idrecuperar" => $idrecuperar));

        //var_dump($results);
        if(count($results) === 0){
            throw new \Exception("Não foi possivel recuperar senha!");
        } else {
            return $results[0];
        }

    }

    // ========================================================= //
    public static function setMsgErros($msg)
    {
        $_SESSION[User::ERROR] = $msg;
    }

    public static function getMsgErros()
    {
        $msg = (isset($_SESSION[User::ERROR])) ? $_SESSION[User::ERROR] : "";

        User::limparMsgErros();

        return $msg;
    }

    public static function limparMsgErros()
    {
        $_SESSION[User::ERROR] = NULL;
    }

    // ========================================================= //
    public static function setMsgErrosDoRegistros($msg)
    {
        $_SESSION[User::ERROR_REGISTROS] = $msg;
    }

    public static function getMsgErrosRegistros()
    {
        $msg = (isset($_SESSION[User::ERROR_REGISTROS])) ? $_SESSION[User::ERROR_REGISTROS] : "";

        User::limparMsgErrosRegistros();

        return $msg;
    }

    public static function limparMsgErrosRegistros()
    {
        $_SESSION[User::ERROR_REGISTROS] = NULL;
    }

    // ========================================================= //
    public static function setMsgSucessoDoRegistros($msg)
    {
        $_SESSION[User::SUCESSO_REGISTROS] = $msg;
    }

    public static function getMsgSucessoRegistros()
    {
        $msg = (isset($_SESSION[User::SUCESSO_REGISTROS])) ? $_SESSION[User::SUCESSO_REGISTROS] : "";

        User::limparMsgSucessoRegistros();

        return $msg;
    }

    public static function limparMsgSucessoRegistros()
    {
        $_SESSION[User::SUCESSO_REGISTROS] = NULL;
    }

    public static function verificarLoginExistente($login)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_usuario WHERE deslogin = :deslogin ", array(":deslogin"=>$login));

         return (count($results) > 0);
    }

    public static function verificarEmailExistente($email)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_usuario WHERE desemail = :desemail ", array(":desemail"=>$email));

         return (count($results) > 0);
    }

    public function setPassword($senha)
    {
        $sql = new Sql();
        $results = $sql->query('UPDATE tb_usuario SET despassword = :despassword WHERE idusuario = :idusuario', [
            ':despassword' => $senha,
            ':idusuario' => $this->getidusuario()
        ]);
    }

    public function getPedidosUsuario()
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_perdidos pedidos 
        INNER JOIN tb_perdidosstatus pstatus ON pedidos.tb_perdidosstatus_idstatus = pstatus.idstatus
        INNER JOIN tb_carrinho carrinho ON pedidos.tb_carrinho_idcarrinho = carrinho.idcarrinho
        INNER JOIN tb_usuario usuario ON pedidos.tb_usuario_idusuario = usuario.idusuario
        INNER JOIN tb_enderecos endereco ON pedidos.tb_enderecos_idenderecos = endereco.idenderecos
        INNER JOIN tb_pessoas pessoas ON pessoas.idpessoas = usuario.tb_pessoas_idpessoas
        WHERE pedidos.tb_usuario_idusuario = :idusuario", array(
            ":idusuario" => $this->getidusuario()
        ));

        return $results;
    }

    public static function getPaginacaoDosUsuarios($Paginas = 1, $QntItensProPg = 3)
    {

        $ComecarPg = ($Paginas - 1) * $QntItensProPg;

        $sql = new Sql();

        $results = $sql->select("SELECT SQL_CALC_FOUND_ROWS * 
            FROM tb_usuario users INNER JOIN tb_pessoas pessoas ON pessoas.idpessoas = users.tb_pessoas_idpessoas order by pessoas.despessoas DESC
            LIMIT $ComecarPg, $QntItensProPg;"
        );

        $resultsTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

        return [
            "Dados" => $results,
            "Total" => (int)$resultsTotal[0]['nrtotal'],
            "PaginasGeradas" => ceil($resultsTotal[0]['nrtotal'] / $QntItensProPg)
        ];
    }

    public static function getPaginacaoDePesquisaAoUsuarios($pesquisa, $Paginas = 1, $QntItensProPg = 3)
    {

        $ComecarPg = ($Paginas - 1) * $QntItensProPg;

        $sql = new Sql();

        $results = $sql->select("SELECT SQL_CALC_FOUND_ROWS * 
            FROM tb_usuario users INNER JOIN tb_pessoas pessoas ON pessoas.idpessoas = users.tb_pessoas_idpessoas
            WHERE pessoas.despessoas LIKE :pesquisa OR users.deslogin LIKE :pesquisa OR pessoas.desemail LIKE :pesquisa
            order by pessoas.despessoas DESC
            LIMIT $ComecarPg, $QntItensProPg;", [
                ":pesquisa" => '%'.$pesquisa.'%'
            ]
        );

        $resultsTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

        return [
            "Dados" => $results,
            "Total" => (int)$resultsTotal[0]['nrtotal'],
            "PaginasGeradas" => ceil($resultsTotal[0]['nrtotal'] / $QntItensProPg)
        ];
    }

}

