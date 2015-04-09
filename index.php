<?php

require "vendor/autoload.php";

$app = new \Slim\Slim(array("debug"=>true, 'templates.path'=>'views'));
$db = new PDO('mysql:host=mysql.hostinger.com.br;dbname=u771591478_notas;charset=utf8','u771591478_root','@junior201110');
//$db = new PDO('mysql:host=localhost;dbname=notas;charset=utf8','root','root');
//host=mysql.hostinger.com.br /u771591478_notas/ u771591478_root/ set to @junior201110
$app->get('/',function() use($app){
         $app->render('inicio.php');
});
//Requisiçao via JSONObjectRequest
$app->post('/inicio/post/jor', function() use($app,$db){
    //$usuario = json_encode(array("usuario" => array("nome"=>$_POST['nome'],"sobrenome"=>$_POST['snome'])));

    $dbquery = $db->prepare("SELECT * FROM usuario WHERE login = '".$_POST['nome']."' AND senha ='".$_POST['senha']."'");
    $dbquery->execute();
    if($dbquery->rowCount() == 1) {

        $data['user'] = $dbquery->fetchAll(PDO::FETCH_ASSOC);

        $app->render('inicioJor.php', $data);
    }
    else{
        echo json_encode(array("erro"=>"404"));
    }

});
//Requisiçao via JSONArrayRequest
$app->post('/inicio/post/jar', function() use($app, $db){
    //$usuario = json_encode(array('conexão via JsonArrayRequest!'));

     //echo $usuario;
    $dbquery = $db->prepare("SELECT * FROM usuario WHERE login = '".$_POST['nome']."' AND senha ='".$_POST['senha']."'");
    $dbquery->execute();
    if($dbquery->rowCount() == 1) {

        $data['user'] = $dbquery->fetchAll(PDO::FETCH_ASSOC);

        $app->render('inicioJar.php', $data);
    }
    else
        echo
        json_encode(array("usuario não cadastrado ou não encontrado"));


});

$app->run();

