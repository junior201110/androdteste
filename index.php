<?php

require "vendor/autoload.php";

$app = new \Slim\Slim(array("debug"=>true, 'templates.path'=>'views'));
//$db = new PDO('mysql:host=mysql.hostinger.com.br;dbname=u771591478_notas;charset=utf8','u771591478_root','@junior201110');
$db = new PDO('mysql:host=localhost;dbname=notas;charset=utf8','root','root');
//host=mysql.hostinger.com.br /u771591478_notas/ u771591478_root/ set to @junior201110
$app->get('/',function() use($app){
         $app->render('inicio.php');
})->name("inicio");

//Requisiçao via JSONObjectRequest
$app->post('/inicio/post/jor', function() use($app,$db){
    //$usuario = json_encode(array("usuario" => array("nome"=>$_POST['nome'],"sobrenome"=>$_POST['snome'])));

    $dbquery = $db->prepare("SELECT * FROM usuario WHERE login = '".$_POST['nome']."' AND senha ='".$_POST['senha']."'");
    $dbquery->execute();

    if($dbquery->rowCount() == 1) {

        $data['user'] = $dbquery->fetchAll(PDO::FETCH_ASSOC);

        if(verificaPlataforma() == true){
            $app->render('inicioJor.php', $data);
        }else{
            $url = $app->urlFor("inicio",array('inicio'));
            $app->render('salaUsuario.php', array('voltar'=>$url));
        }

    }
    else{
        echo json_encode(array("erro"=>"404"));
    }

});

//Requisiçao via JSONArrayRequest
$app->get('/inicio/post/jar/:id', function($nome, $senha) use($app, $db){
    //$usuario = json_encode(array('conexão via JsonArrayRequest!'));

     //echo $usuario;
    $dbquery = $db->prepare("SELECT * FROM usuario WHERE login = '".$nome."' AND senha ='".$senha."'");
    $dbquery->execute();
    if($dbquery->rowCount() == 1) {

        $data['user'] = $dbquery->fetchAll(PDO::FETCH_ASSOC);

        $app->render('inicioJar.php', $data);
    }
    else {
        echo json_encode(array("usuario não cadastrado ou não encontrado"));

    }


});

$app->get('/produtos/jor/:id', function($id) use($app, $db){
    echo json_encode(array("erro"=>"false",'id do produto'=>$id));
});
//carrega pedidos

$app->get('/pedidos/jor/:id', function($id) use($app, $db){
    $dbquery = $db->prepare("SELECT * FROM pedidos WHERE iduser = '$id'");
    $dbquery->execute() or die("ERRO");

    if($dbquery->rowCount() > 0){
        $data['pedidos'] = $dbquery->fetchAll(PDO::FETCH_ASSOC);
        $pedidos = $data['pedidos'];
        $idp = $pedidos[0]['idp'];
            //executa uma query pra selecionar os produtos referente ao pedido
        $dbq = $db->prepare("SELECT * FROM produto WHERE idproduto = '$idp'");
        $dbq->execute();
        $data['produtos'] = $dbq->fetchAll(PDO::FETCH_ASSOC);
        $data['cont']     = $dbquery->rowCount();

        $app->render('produtos.php',$data);



        //$data['cont'] = $dbquery->rowCount()

    }else{
        echo "Não feito";
    }

});


function verificaPlataforma(){

    $iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
    $ipad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
    $android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
    $palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
    $berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
    $ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
    $symbian =  strpos($_SERVER['HTTP_USER_AGENT'],"Symbian");
    if ($iphone || $ipad || $android || $palmpre || $ipod || $berry || $symbian == true){
        return true;
    } else{

        return false;
    }
}

$app->run();

