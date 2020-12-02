<?php

class Usuario
{
private $pdo;
public $msgErro = ""; // está ok
public function conectar($nome, $host, $usuario, $senha) 

{

global $pdo;
try 
{
$pdo = new PDO("mysql:dbname=" .$nome.";host=".$host,$usuario,$senha);

} catch (PDOException $e){
	$msgErro = $e->getMessage();  
  }
}
	public function cadastrar($nome, $telefone, $email, $senha)
{
global $pdo;
// verificar se já existe um e-mail cadstrado
$sql = $pdo-> prepare ("SELECT cd_usuario FROM usuario WHERE email = :e");
$sql-> bindValue($email);
$sql-> execute();
if($sql->rowCount() > 0)
{
	return false; // já está cadastrada
}
else
{
	//caso não, Cadastrar
	$sql = $pdo->prepare("INSERT INTO usuario (nome,telefone,email, senha) VALUES(':n',':t',':e',':s')");
	$sql->bindValue(":n",$nome);
	$sql->bindValue(":t",$telefone);
	$sql->bindValue(":e",$email);
	$sql->bindValue(":s",md5($senha));
	$sql-> execute();
	return true; //tudo ok!

  }
}

public function logar($email, $senha)
{

global $pdo;

   //Verificar se o e-mail e senha estão cadastrados, se sim, 

    $sql = $pdo->prepare("SELECT cd_usuario FROM usuario WHERE email = :e AND senha = :s");
    $sql-> bindValue(":e",$email);
    $sql-> bindValue(":s",md5($senha));
    $sql->execute();
    if($sql->rowCount() > 0)
    {
    	//entrar no sistema (sessão)
    	$dado = $sql->fetch();
    	session_start();
    	$_SESSION['cd_usuario'] = $dado['cd_usuario'];
    	return true; //cadastrado com sucesso
    }
    else
    {
        return false; //não foi possível logar
    }
  
}
}
?>
