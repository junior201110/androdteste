<?php

function jsonEncode($return){
    $json = json_encode($return);
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
    return $json;
}