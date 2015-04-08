<?php
//foreach ($user as $key => $value) {
//	echo json_encode(array("id"=>"1"));
//}

foreach ($user as $key => $value) {
	echo json_encode(array("login"=>$value['login']));
}