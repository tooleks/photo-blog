<?php
header("Content-Type: text/html; charset=cp1251");
error_reporting(0);


if(isset($_POST['txt'])){
    $string = $_POST['txt'];
    $i=0;
    $k = 0;
    while( isset($string[$k]) ){
        $k++;
    }
    $strlen = $k;
    $word = "";

    while ($i <= $strlen){
        if(ord($string[$i]) == 32){
            $word .= "";
        }
        else{
            $word .= $string[$i];
        }
        $i++;
    }
    $a =    strlen($word);

    $word2 = "";
    $i = 0;

    while ($a >= $i)
    {
        $word2 .= $word[$a];
        $a--;
    }
    if ($word == $word2){
        echo "true";
    }
    else {
        echo "false";
    }}
?>