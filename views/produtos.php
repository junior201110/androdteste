<?php
/**
 * Created by PhpStorm.
 * User: junior
 * Date: 15/04/15
 * Time: 13:30
 */
foreach ($pedidos as $key1 => $ped) {

	foreach ($produtos as $key2 => $pro) {
        echo json_encode(array(array("cont $key1"=>$key1,"erro"=>"false","idp"=>$ped['idp'], "desc"=>$ped['desc'], "produto"=>$pro['nproduto'],"data"=>$ped['data'])));
    }
}