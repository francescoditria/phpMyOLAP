<?php
class Model {



function mexec_trans($query) {

global $db;

$host=$db["host"];
$username=$db["user"];
$password=$db["password"];
$name=$db["name"];

$db = mysql_connect($host,$username, $password);

if ($db == FALSE)  {
    $result=false;
  }
elseif (mysql_select_db($name, $db)==FALSE)  {
  $result=false;
  }
else  {
  mysql_query("SET AUTOCOMMIT=0", $db);
  mysql_query("START TRANSACTION", $db);
  mysql_query("BEGIN", $db);
  
  $n=count($query);
  for($i=0;$i<$n;$i++)  {
  $result=mysql_query($query[$i], $db);
    if($result==false)    {
      mysql_query("ROLLBACK", $db);
      return $result;
    }
  }
  mysql_query("COMMIT", $db);
    
  }

 return $result;
}

function exec_query($query){

global $db;

$host=$db["host"];
$username=$db["user"];
$password=$db["password"];
$name=$db["name"];

$db = mysql_connect($host,$username, $password);
if ($db == FALSE)  {
    //print mysql_error();
    $result=false;
}
elseif (mysql_select_db($name, $db)==FALSE)  {
  //print mysql_error();
  $result=false;
}
else  {
  $result=mysql_query($query, $db);
  //print "RES EXEC $result";
}
 return $result;
}


}

?>
