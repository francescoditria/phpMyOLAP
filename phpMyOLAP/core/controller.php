<?php
session_start();

 include_once(dirname(__FILE__) . '/../config.php'); 
 include_once(dirname(__FILE__) . '/../lib/utility/functions.php');
 include_once(dirname(__FILE__) . '/../lib/olap_engine/metamodel.php'); 
 include_once(dirname(__FILE__) . '/../lib/olap_engine/olap.php');
 
 if (isset($_SESSION["lang"])) 
 {
  $lang3=$_SESSION["lang"];
 }
 else
 { 
  $lang3=$lang;
 }

 
 include_once(dirname(__FILE__) . "/../lang/$lang3");
 include_once(dirname(__FILE__) . '/view.php');
 include_once(dirname(__FILE__) . '/model.php');
 

class Controller {

  private $datapath;


  function do_operation() 
  {
      global $urlsito;
      
      
      $operation=get_input("operation");
      $cubename=get_input("cubename");
      $measurename=get_input("measurename");
      $dimensionname=get_input("dimensionname");
      $hiername=get_input("hiername");
      $levelname=get_input("levelname");
      
      //$level_selected=get_input("level_selected");
      $levels=$_POST["level_selected"];
      $distinct=$_POST["distinct"];
      $join=$_POST["join"];
      $order_by_col=$_POST["order_by_col"];
      $order_by_type=$_POST["order_by_type"];
      $slice=$_POST["slice"];
      $boolean=$_POST["boolean"];
      
      $tablename=get_input("tablename");
      $colname=get_input("colname");
      
      $filename=get_input("filename");
      $export_string=$_POST["export_string"];
      
      $email=get_input("email");
      $subject=get_input("subject");
      $text=get_input("text");
      $shareurl=get_input("shareurl");
  
      $property=get_input("property");
      $cellid=get_input("cellid");
  
      $v=new View();
      $mm=new Metamodel();
      $e=new OlapEngine();
      $m=new Model();
     
      //print "OP $operation<br>";
      
      switch ($operation) {

      case "change_lang":
      $lang2=get_input("lang");
      session_start();
      $_SESSION["lang"]=$lang2;
      //die("stop");
      header ("Location: $urlsito");
      break;
  
      case "get_column_values":
      $query=$mm->get_column_values($cubename,$property);
      $result=$m->exec_query($query);
      $v->show_column_values($cubename,$property,$query,$result,$cellid);
      break;

      case "send_email":
      $res=$this->send_email($email,$subject,$text,$shareurl);
      print $res;
      break;

      case "share":
      $v->show_header();
      $export_string=$_GET["export_string"];
      $res=$this->share($export_string);
      break;

      case "show_form_social":
      $v->show_form_social($export_string);
      //print "OK";
      break;

      case "show_form_email":
      $v->show_form_email($export_string);
      break;

      case "save_file":
      $res=$this->save_file($filename,$export_string);
      //print $res;
      break;
      
      case "save_pdf":
      //print "EXP $export_string";
      //$res=$this->save_pdf($export_string);
      $res=$this->save_pdf($cubename,$slice,$boolean,$levels,$distinct,$join,$order_by_col,$order_by_type);
      break;

      case "save_csv":
      $res=$this->save_csv($cubename,$slice,$boolean,$levels,$distinct,$join,$order_by_col,$order_by_type);
      break;

      case "save_weka":
      $res=$this->save_weka($cubename,$slice,$boolean,$levels,$distinct,$join,$order_by_col,$order_by_type);
      break;

      case "show_form_dim":
      $dims=$mm->change_dim($cubename,$tablename,$colname);
      $v->show_form_dim($dims);
      break;

      case "show_form_hier":
      $hiers=$mm->change_hier($cubename,$tablename,$colname);
      $v->show_form_hier($hiers);
      break;

      case "show_form_drill":
      $levels=$mm->change_level($cubename,$tablename,$colname);
      $v->show_form_drill($levels);
      break;

      case "modify_report":
      $v->show_header();
      $cubes=$mm->get_cubes();
      $v->show_new($cubes);
      $v->modify_report($cubename,$slice,$boolean,$levels,$distinct,$join,$order_by_col,$order_by_type);
      break;
 
      case "execute":
      $v->show_header();
      //print "DATA $cubename,$levels,$slice,$order_by_col,$order_by_type,$distinct,J $join<br>";
      $query=$e->SQLgenerator($cubename,$levels,$slice,$boolean,$order_by_col,$order_by_type,$distinct,$join);
      //print "QUERY $query<br>";
      $result=$m->exec_query($query);
      //print "RES $result";
      $col_name=$mm->get_col_name();
      $v->show_report($result,$cubename,$levels,$slice,$boolean,$order_by_col,$order_by_type,$distinct,$join,$col_name);
      break;
      

      case "show_prop":
      //risposta ajax
      $propname=$mm->get_prop($cubename,$dimensionname,$hiername,$levelname);
      //print "$cubename,$dimensionname,$hiername,$levelname";
      //print $propname[0];
      $last_dim=$mm->get_last_dim($cubename,$dimensionname);
      $last_hier=$mm->get_last_hier($cubename,$dimensionname,$hiername);
      $last_level=$mm->get_last_level($cubename,$dimensionname,$hiername,$levelname);
      $v->show_prop($cubename,$dimensionname,$hiername,$levelname,$propname,$last_dim,$last_hier,$last_level);
      break;

      case "show_levels":
      //risposta ajax
      $levelname=$mm->get_levels($cubename,$dimensionname,$hiername);
      $last_dim=$mm->get_last_dim($cubename,$dimensionname);
      $last_hier=$mm->get_last_hier($cubename,$dimensionname,$hiername);
      $v->show_levels($cubename,$dimensionname,$hiername,$levelname,$last_dim,$last_hier);
      break;



      case "show_hier":
      //risposta ajax
      $hiername=$mm->get_hier($cubename,$dimensionname);
      $last_dim=$mm->get_last_dim($cubename,$dimensionname);
      $v->show_hier($cubename,$dimensionname,$hiername,$last_dim);
      break;
      

      case "show_functions":
      //risposta ajax
      $functionname=$mm->get_functions($cubename,$measurename);
      $v->show_functions($measurename,$cubename,$functionname);
      break;
      
      case "show_measures":
      //risposta ajax
      $measurename=$mm->get_measures($cubename);
      $v->show_measures($measurename,$cubename);
      break;
      
      case "show_dimensions":
      //risposta ajax
      $dimensionname=$mm->get_dimensions($cubename);
      $v->show_dimensions($cubename,$dimensionname);
      break;
  
      case "show_tree":
      //risposta ajax
      $v->show_tree($cubename);
      break;
  
  
      case "new_report":
      $v->show_header();
      $cubename=$mm->get_cubes();
      $v->show_new($cubename);
      break;
      
      case "open_report":
      $v->show_header();
      $filename=$this->get_files();
      $v->show_open($filename);
      break;

      case "open_file":
      $v->show_header();
      $this->open_file($filename);
      break;

      case "delete_file":
      $this->delete_file($filename);
      break;
      
      default:
      $v->show_header();
      $v->show_home();
      break;
         
      }
   
  }
  
  
  
  
  function send_email($email,$subject,$text,$shareurl)
  {

    global $message;
    $messaggio="$text\n$shareurl";
    $mittente="From: phpMyOLAP";
    $a=mail($email,$subject,$messaggio,$mittente);
    if($a==true)
    $res=$message["email_ok"];
    else
    $res=$message["email_error"];
    
    return $res;

  }
  

  function delete_file($filename)
  {
    $full_name="$this->datapath/$filename";
    unlink($full_name);  
  }


  function share($string)
  {
    //print "STR $string<br>";
  
    $export=explode("**",$string);
    $cubename=$export[0];
    $levels=explode("!!",$export[1]);
    $slice=explode("!!",$export[2]);
    $boolean=$export[3];
    $order_by_col=$export[4];
    $order_by_type=$export[5];
    $distinct=$export[6];
    $join=$export[7];

      $v=new View();
      $mm=new Metamodel();
      $e=new OlapEngine();
      $m=new Model();
    
      $query=$e->SQLgenerator($cubename,$levels,$slice,$boolean,$order_by_col,$order_by_type,$distinct,$join);
      //print "QUERY $query<br>";
      $result=$m->exec_query($query);
      $col_name=$mm->get_col_name();
      $v->show_report($result,$cubename,$levels,$slice,$boolean,$order_by_col,$order_by_type,$distinct,$join,$col_name);

  }
  
    
  function open_file($filename)
  {
    $full_name="$this->datapath/$filename";
    $string=file_get_contents($full_name);

    $export=explode("**",$string);
    $cubename=$export[0];
    $levels=explode("!!",$export[1]);
    $slice=explode("!!",$export[2]);
    $boolean=$export[3];
    $order_by_col=$export[4];
    $order_by_type=$export[5];
    $distinct=$export[6];
    $join=$export[7];
    fclose($filehandle);

      $v=new View();
      $mm=new Metamodel();
      $e=new OlapEngine();
      $m=new Model();
    
      $query=$e->SQLgenerator($cubename,$levels,$slice,$boolean,$order_by_col,$order_by_type,$distinct,$join);
      //print "QUERY $query<br>";
      $result=$m->exec_query($query);
      $col_name=$mm->get_col_name();
      $v->show_report($result,$cubename,$levels,$slice,$boolean,$order_by_col,$order_by_type,$distinct,$join,$col_name);
    

  }


  function save_pdf($cubename,$slice,$boolean,$levels,$distinct,$join,$order_by_col,$order_by_type)
  {
//    print "STRING $string";

//     $export=explode("**",$string);
//     $cubename=$export[0];
//     $levels=explode("!!",$export[1]);
//     $slice=explode("!!",$export[2]);
//     $boolean=$export[3];
//     $order_by_col=$export[4];
//     $order_by_type=$export[5];
//     $distinct=$export[6];
  
      $v=new View();
      $mm=new Metamodel();
      $e=new OlapEngine();
      $m=new Model();
    
      $query=$e->SQLgenerator($cubename,$levels,$slice,$boolean,$order_by_col,$order_by_type,$distinct,$join);
      $result=$m->exec_query($query);
      //print "QUERY $query";
      
$ncols=mysql_num_fields($result);

define('FPDF_FONTPATH',dirname(__FILE__) . '/../lib/fpdf/font/');
include_once(dirname(__FILE__) . '/../lib/fpdf/fpdf.php'); 

ob_start();
 

$p = new fpdf();
$p->Open();
$p->AddPage();
$p->SetTextColor(0);
$p->SetFont('Arial', '', 8);

$w=10;
$h=10;
$off_h=5;
$off_w=40;


//***************************INTESTAZIONE
  for($i=0;$i<$ncols;$i++)
  {
    $colname=mysql_fetch_field($result);
    $nome=$colname->name;
    $tabella= $colname->table;
    
    if($tabella=="")
    $field="$nome";
    else
    $field="$tabella.$nome";
    
    $p->Text($w, $h, $field);
    $w=$w+$off_w;
  }
//****************************************


$w=10;
$h=$h+$off_h;

while ($row = mysql_fetch_array($result))
{
  for($i=0;$i<$ncols;$i++)
  {
    $colvalue=$row[$i];
    $p->Text($w, $h, $colvalue);
    $w=$w+$off_w;
  }
  $h=$h+$off_h;
  $w=10;

  if($h>200) 
    {
    $p->AddPage();
    $h=10;
    }
}


$p->output();

$contenuto=ob_get_contents();
ob_end_clean();
header("Content-Type: application/text");
header("Content-Disposition: attachment; filename=temp.pdf");
print $contenuto;

  
  }


  function save_csv($cubename,$slice,$boolean,$levels,$distinct,$join,$order_by_col,$order_by_type)
  {

//     $export=explode("**",$string);
//     $cubename=$export[0];
//     $levels=explode("!!",$export[1]);
//     $slice=explode("!!",$export[2]);
//     $boolean=$export[3];
//     $order_by_col=$export[4];
//     $order_by_type=$export[5];
//     $distinct=$export[6];
  
      $v=new View();
      $mm=new Metamodel();
      $e=new OlapEngine();
      $m=new Model();
    
      $query=$e->SQLgenerator($cubename,$levels,$slice,$boolean,$order_by_col,$order_by_type,$distinct,$join);
      $result=$m->exec_query($query);
      $ncols=mysql_num_fields($result);

while ($row = mysql_fetch_array($result))
{
  for($i=0;$i<$ncols-1;$i++)
  {
    $colvalue=$row[$i];
    print "$colvalue,"; 
  }
  $colvalue=$row[$ncols-1];
  print "$colvalue";
  print "\n";
}


$contenuto=ob_get_contents();
ob_end_clean();

header("Content-Type: application/text");
header("Content-Disposition: attachment; filename=temp.csv");
print $contenuto;

  
  }



  function save_weka($cubename,$slice,$boolean,$levels,$distinct,$join,$order_by_col,$order_by_type)
  {

//     $export=explode("**",$string);
//     $cubename=$export[0];
//     $levels=explode("!!",$export[1]);
//     $slice=explode("!!",$export[2]);
//     $boolean=$export[3];
//     $order_by_col=$export[4];
//     $order_by_type=$export[5];
//     $distinct=$export[6];
   
      $v=new View();
      $mm=new Metamodel();
      $e=new OlapEngine();
      $m=new Model();
    
      $query=$e->SQLgenerator($cubename,$levels,$slice,$boolean,$order_by_col,$order_by_type,$distinct,$join);
      $result=$m->exec_query($query);
      $ncols=mysql_num_fields($result);

print "@RELATION report\n";    
 

//***************************INTESTAZIONE
  for($i=0;$i<$ncols;$i++)
  {
    $colname=mysql_fetch_field($result);
    $nome=$colname->name;
    $tabella= $colname->table;
    
    $numeric=$colname->numeric;
    if($numeric==1) $tipocampo="NUMERIC"; else $tipocampo="STRING";
    if($tabella=="")
    $field="$nome";
    else
    $field="$tabella.$nome";

    print "@ATTRIBUTE $field $tipocampo\n";    
  }
//****************************************


print "@DATA\n";

while ($row = mysql_fetch_array($result))
{
  $riga="";
  for($i=0;$i<$ncols;$i++)
  {
    $colvalue=$row[$i];
    $riga.="$colvalue,";   
  }
  $n=strlen($riga);
  $riga=substr($riga,0,$n-1);

  print "$riga\n";   

}


$contenuto=ob_get_contents();
ob_end_clean();

header("Content-Type: application/text");
header("Content-Disposition: attachment; filename=temp.arff");
print $contenuto;

      
}


  function save_file($filename,$export_string)
  {
    $dir=$this->datapath;  
    $full_name="$dir/$filename";

    $filehandle=fopen($full_name,'wb');
    if ($filehandle==false) return false;
    
    fwrite($filehandle,$export_string);
    fclose($filehandle);
    return true;
  
  }

  function get_files()
  {
    $dir=$this->datapath;  
  
    if ($dh = opendir($dir))
    { 
      while (($file = readdir($dh)) !== false)
      { 
        $test="$dir/$file";
        if(($file !='.')&&($file !='..') && is_file($test))
          $filename[]=$file; 
      } 
      closedir($dh);
      return $filename; 
    }
  
  }
  
  
  function __construct() 
  {
    $this->datapath= dirname(__FILE__) . "/../file";
       
  }

  

    		
}




?>
