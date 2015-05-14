<?php

session_start();
require "vendor/autoload.php";
require 'functions.php';



$app = new \Slim\Slim(array("debug"=>true, 'templates.path'=>'views'));
//$db = new PDO('mysql:host=mysql.hostinger.com.br;dbname=u771591478_notas;charset=utf8','u771591478_root','@junior201110');
$db = new PDO('mysql:host=localhost;dbname=notas;charset=utf8','root','root');
//host=mysql.hostinger.com.br /u771591478_notas/ u771591478_root/ set to @junior201110
$app->get('/',function() use($app){
    $rota['url'] = $app->urlFor('cadastro');
    $app->render('inicio.php',$rota);
})->name("inicio");

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

$app->get('/pedidos/jor/:id/', function($id) use($app, $db){
    $dbquery = $db->prepare("SELECT * FROM pedidos WHERE iduser = '$id'");
    $dbquery->execute() or die("ERRO");

    if($dbquery->rowCount() > 0){

        $data = $dbquery->fetchAll(PDO::FETCH_ASSOC);

        if(is_string($data) || is_object($data))
            return utf8_encode($data);
        $json = json_encode($data);
        if($json == false){
            switch(json_last_error()){
                case JSON_ERROR_NONE:
                    echo '- No errors';
                    break;
                case JSON_ERROR_DEPTH:
                    echo '- MAXIMUM STACK DEPTH EXCEEDED';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    echo '- UNDERFLOW OR THE MODES MISMACH';
                    break;


            }
        }
        echo $json;

    }else{

            echo json_encode(array(array("desc"=>"404")));

    }

});

$app->get('/cadastro.php', function() use($app){

    $app->render('cadastro_user.php');

})->name('cadastro');
$app->post('/cadastro/input', function() use($app, $db){

    $request = $app->request;
    $login = $request->post('login');
    $senha = $request->post('senha');

    /*
    $sql = "INSERT INTO usuario (id, login, senha) VALUES (NULL, '$_POST[login]', '$_POST[senha]');";
    */
    $dbq = $db->prepare("INSERT INTO usuario (id, login, senha) VALUES (NULL , :login, :senha)");
    $inserido=$dbq->execute(array(":login"=>$login,":senha"=>$senha));

    if($inserido){
        $app->flash("mesage","inserido");
    }else{

        $app->flash("erros","ocorreu um erro");

    }

    echo "INSERIDO";

});
$app->get("/delete/pedido/:idp", function($idp) use($app, $db){

    $sql = "DELETE FROM pedidos WHERE idp = '$idp'";
    $dbq = $db->prepare($sql);
    $dbq->execute();
    if($idp)
        $app->redirect("ok");
    else
        echo "NÂO FEITO";
});

$app->post("/insert/pedido", function() use($app, $db){

    //$sql = "INSERT INTO pedidos (idp, desc, produto, data, iduser, nnotas) VALUES (NULL, '$desc', '$produto', '$data', '$iduser', '$nnotas')";
    $sql = "INSERT INTO pedidos (idp, desc, produto, data, iduser, nnotas) VALUES (NULL, 'w', 'r', 'f', '1', '22');";
    $dbq = $db->prepare($sql);
    $feito = $dbq->execute();

    if($feito){
        echo "OK";
    }else{
        $erro = $dbq->errorInfo();
            echo $erro[0] + "<br/>";
            echo $erro[2];


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
function objectToArray($data){
    if(is_array($data) || is_object($data)){
        foreach($data as $key => $value){
            if($value == 'null')
                $value = null;
            $result[$key] = objectToArray($value);

            return $result;


        }
        return $$data;

    }

}

$app->run();

