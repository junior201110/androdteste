<?php

require "vendor/autoload.php";

$app = new \Slim\Slim(array("debug"=>true, 'templates.path'=>'views'));
$db = new PDO('mysql:host=mysql.hostinger.com.br;dbname=u771591478_notas;charset=utf8','u771591478_root','@junior201110');
//host=mysql.hostinger.com.br /u771591478_notas/ u771591478_root/ set to @junior201110
$app->get('/',function(){
    echo "OLA";
});
$app->post('/inicio/post/jor', function() use($app,$db){
    //$usuario = json_encode(array("usuario" => array("nome"=>$_POST['nome'],"sobrenome"=>$_POST['snome'])));

    $dbquery = $db->prepare("SELECT * FROM usuario WHERE login = '".$_POST['nome']."' AND senha ='".$_POST['senha']."'");
    $dbquery->execute();
    if($dbquery->rowCount() == 1) {

        $data['user'] = $dbquery->fetchAll(PDO::FETCH_ASSOC);

        $app->render('inicio.php', $data);
    }
    else
            echo
                json_encode(array("erro 1"=>"erro no php"));

});
$app->post('/inicio/post/jar', function() use($app, $db){
    //$usuario = json_encode(array('conexão via JsonArrayRequest!'));

     //echo $usuario;
    $dbquery = $db->prepare("SELECT * FROM usuario WHERE login = '".$_POST['nome']."' AND senha ='".$_POST['senha']."'");
    $dbquery->execute();
    if($dbquery->rowCount() == 1) {

        $data['user'] = $dbquery->fetchAll(PDO::FETCH_ASSOC);

        $app->render('inicio.php', $data);
    }
    else
        echo
        json_encode(array("usuario não cadastrado ou não encontrado"));


});
$app->get('/inicio/post/:nome', function($nome) use($app){

    echo $nome;

});

$app->run();

