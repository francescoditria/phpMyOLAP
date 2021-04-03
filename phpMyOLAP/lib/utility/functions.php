<?php

function validate_email($email)
{

$email = filter_var($email, FILTER_SANITIZE_EMAIL);

if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    return true;
} else {
    return false;
}

}


  
function get_id(){
session_start();
$id=(int) $_SESSION["id"];
return $id;
}  

function get_level(){
session_start();
$level=$_SESSION["livello"];
return $level;
}  


// function get_json($data){
// global $ws;
// 
// $options = array('http' => array('header'  => 'Content-type: application/x-www-form-urlencoded\r\n','method'  => 'POST',     'content' => http_build_query($data), ),);
// $context  = stream_context_create($options);
// $result = file_get_contents($ws, false, $context);
// $json=json_decode($result);
// return $json;
//  
// }

  
function get_input($campo)  {
    $valore=$_POST[$campo];
    if($valore=="") $valore=$_GET[$campo]; 
    
    $valore=addslashes($valore);
    return $valore;
}


function del_last($string)  {
    $n=strlen($string);
    $string=substr($string,0,$n-1);
    return $string;
}


function send_email($to,$subj,$msg)  {
    $header="From: mittente@ii.it";
    $feed=mail($to,$subj,$msg,$header);
    return $feed;
}
  
function generate_pwd()  {
    for ($i=0;$i<=7;$i++)
      $pwd .= chr(rand(97,122));
    return $pwd;
}
  
function convertiIT($data) {
list($anno,$mese,$giorno)=explode("-",$data);
$data="$giorno/$mese/$anno";
return $data;
}

function convertiAM($data) {
if($data=="" or empty($data))
  $data="";
else{
  list($giorno,$mese,$anno)=explode("/",$data);
  $data="$anno-$mese-$giorno";
}
return $data;
}

  
?>