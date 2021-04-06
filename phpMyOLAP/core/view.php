<?php

class View {

function show_column_values($cubename,$property,$query,$result,$cellid)
{
  global $urlsito;
  global $message;

  //print "$cubename,$property,$cellid<br>$query";

//*************************************************
$ncols=mysql_num_fields($result);

print "<br><br>";
print "<center>";
print "<div style=\"width: 200px; height: 200px;overflow-y: scroll;\">";

print "<table border=1>";

//HEAD
print "<tr>";
  for($i=0;$i<$ncols;$i++)
  {
    $colname=mysql_fetch_field($result);
    $nome=$colname->name;
    $tabella= $colname->table;
    
    if($tabella=="")
    $field="$nome";
    else
    $field="$tabella.$nome";
    
    print "<th>";
    print "$field";
    print "</th>";
    
  }
print "</tr>";  

//BODY
while ($row = mysql_fetch_array($result))
{
print "<tr>";
  for($i=0;$i<$ncols;$i++)
  {
    $colvalue=$row[$i];
    print "<td><a style='cursor:pointer' onclick='set_column_value(\"$cellid\",\"$colvalue\")'>$colvalue</a></td>";
  }
print "</tr>";  
}
print "</table>";
print "</div>";



//*****************************************************
  
//  print "<center>";
  print "<p><table>";
    print "<tr>";
      print "<td align=center>";
        print "<a class=button onclick='document.getElementById(\"coprente\").style.visibility=\"hidden\"'>{$message["close"]}</a>";
      print "</td>";
    print "</tr>";
  print "</table>";
  print "</center>";
  
  
}

function show_form_email($export_string)
{
  global $urlsito;
  global $message;

$shareurl="$urlsito?operation=share&export_string=$export_string";

print "<div style='margin-top:15px;margin-left:15px'>";
print "<b>{$message["email"]}</b><br>";
print "<br><input type=text name='email' id='email' size=50 placeholder=email><br>";
print "<br><input type=text name='oggetto' id='oggetto' size=50 placeholder=subject><br>";
print "<br><textarea name='testo' id='testo' cols=30></textarea>";
print "<br><input type=hidden name='shareurl' id='shareurl' value=\"$shareurl\"><br>";
print "</div>";


  print "<center>";
  print "<p><table>";
    print "<tr>";
      print "<td align=center>";
        print "<a class=button onclick='send_email()'>{$message["ok"]}</a>";
      print "</td>";

      print "<td align=center>";
        print "<a class=button onclick='document.getElementById(\"coprente\").style.visibility=\"hidden\"'>{$message["close"]}</a>";
      print "</td>";
    print "</tr>";
  print "</table>";
  print "</center>";
  
}


function show_form_social($export_string)
{
  global $urlsito;
  global $message;

$img_fb="$urlsito/img/fb.png";
$img_share="$urlsito/img/share.png";
$img_tw="$urlsito/img/twitter.png";
$img_report="$urlsito/img/report.png";
$img_email="$urlsito/img/email.png";
$img_linkedin="$urlsito/img/linkedin.png";
$img_google="$urlsito/img/google.png";

$shareurl="$urlsito?operation=share&export_string=$export_string";

//print "$shareurl";
print "<div style='margin-top:10px'>";
print "<br><br><br>";
print "<center>";

//facebook
print "<script src='http://static.ak.fbcdn.net/connect.php/js/FB.Share' type='text/javascript'></script>";
print "<a icon_url='$img_report' name='fb_share' type='button_count' href='http://www.facebook.com/sharer.php?u=$shareurl' target='blank'>";
print "<img src='$img_fb' width=40px height=40px alt='condividi su facebook'/></a>";

//twittwee
print "<script type='text/javascript' src='http://platform.twitter.com/widgets.js'></script>";
print "<a href='#' onclick=\"window.open('http://twitter.com/share?url=$shareurl');return false;\" title=\"Condividi su Twitter\" target=\"_blank\">";
print "<img src='$img_tw' width=40px height=40px alt='condividi su facebook'/></a>";


//google
print "<a href='https://m.google.com/app/plus/x/?v=compose&content=$shareurl' onclick=\"window.open('https://m.google.com/app/plus/x/?v=compose&content=$shareurl')\",\"gplusshare\";\"return false;\" title=\"Condividi su Google Plus!\" target=\"_blank\">";
print "<img src='$img_google' width=40px height=40px alt='condividi su googleplus'>";
print "</a>";

//linkedin
print "<a href='http://www.linkedin.com/shareArticle?mini=true&amp;url=$shareurl&amp;title=phpMyOLAP'  title='Condividi su LinkedIn' target=\"_blank\">";
print "<img src='$img_linkedin' width=40px height=40px alt='condividi su linkedin'>";
print "</a>";


print "</div>";


  print "<center>";
  print "<p><table>";
    print "<tr>";
      print "<td align=center>";
        print "<a class=button onclick='document.getElementById(\"coprente\").style.visibility=\"hidden\"'>{$message["close"]}</a>";
      print "</td>";
    print "</tr>";
  print "</table>";
  print "</center>";

}

function show_form_dim($dims)
{
  $n=count($dims);
  $dim_found=$dims[$n-1];
  $m=$n+1;
  
  global $urlsito;
  global $message;

  $w="50px";
  
  print "<br>";
  print "<h4>{$message["change_dim"]}</h4>";
  print "<center>";
  print "<table>";
  print "<tr>";
  print "<td>";
  print "<img border=0 src='$urlsito/img/arrow_up.png' width=$w height=$w><br>";
  print "<img border=0 src='$urlsito/img/arrow_down.png' width=$w height=$w>";
  print "</td>";
  print "<td>";
  print "<select size=6 style='width:150px'  id='new_level'>";
  for($i=0;$i<$n-1;$i++)
  {
   list($dim,$hier,$lev,$prop)=explode(".",$dims[$i]);

   if($dim==$dim_found)
   {
    $selected="selected";
    $index=$i;
   }
   else
   $selected="";        

   print "<option $selected value='$dims[$i]'>$dim</option>";
  }
  print "</select>";
  print "</td>";
  print "</tr>";
    print "<tr>";
      print "<td align=center>";
      print "<a class=button onclick='exec_drill($index)'>{$message["ok"]}</a>";
      print "</td>";
      print "<td align=center>";
        print "<a class=button onclick='document.getElementById(\"coprente\").style.visibility=\"hidden\"'>{$message["cancel"]}</a>";
      print "</td>";
    print "</tr>";
  print "</table>";
  print "</center>";
 
}


function show_form_hier($hiers)
{
  $n=count($hiers);
  $hier_found=$hiers[$n-1];
  $m=$n+1;
  
  global $urlsito;
  global $message;

  $w="50px";
  
  print "<br>";
  print "<h4>{$message["change_hier"]}</h4>";
  print "<center>";
  print "<table>";
  print "<tr>";
  print "<td>";
  print "<img border=0 src='$urlsito/img/arrow_up.png' width=$w height=$w><br>";
  print "<img border=0 src='$urlsito/img/arrow_down.png' width=$w height=$w>";
  print "</td>";
  print "<td>";
  print "<select size=6 style='width:150px'  id='new_level'>";
  for($i=0;$i<$n-1;$i++)
  {
   list($dim,$hier,$lev,$prop)=explode(".",$hiers[$i]);

   if($hier==$hier_found)
   {
    $selected="selected";
    $index=$i;
   }
   else
   $selected="";        

   print "<option $selected value='$hiers[$i]'>$hier</option>";
  }
  print "</select>";
  print "</td>";
  print "</tr>";
    print "<tr>";
      print "<td align=center>";
        print "<a class=button onclick='exec_drill($index)'>{$message["ok"]}</a>";
      print "</td>";
      print "<td align=center>";
        print "<a class=button onclick='document.getElementById(\"coprente\").style.visibility=\"hidden\"'>{$message["cancel"]}</a>";
      print "</td>";
    print "</tr>";
  print "</table>";
  print "</center>";
 
}


function show_form_drill($levels)
{
  $n=count($levels);
  $level_found=$levels[$n-1];
  $m=$n+1;
  
  global $urlsito;
  global $message;

  $w="50px";

  
  print "<br>";
  print "<h4>{$message["drill"]}</h4>";
  print "<center>";
  print "<table>";
  print "<tr>";
  print "<td>";
  print "<img border=0 src='$urlsito/img/arrow_up.png' width=$w height=$w><br>";
  print "<img border=0 src='$urlsito/img/arrow_down.png' width=$w height=$w>";
  print "</td>";
  print "<td>";
  print "<select size=6 style='width:150px'  id='new_level'>";
  for($i=0;$i<$n-1;$i++)
  {
   list($dim,$hier,$lev,$prop)=explode(".",$levels[$i]);

   if($lev==$level_found)
   {
    $selected="selected";
    $index=$i;
   }
   else
   $selected="";        

   print "<option $selected value='$levels[$i]'>$lev</option>";
  }
  print "</select>";
  print "</td>";
  print "</tr>";
    print "<tr>";
      print "<td align=center>";
        print "<a class=button onclick='exec_drill($index)'>{$message["ok"]}</a>";
      print "</td>";
      print "<td align=center>";
        print "<a class=button onclick='document.getElementById(\"coprente\").style.visibility=\"hidden\"'>{$message["cancel"]}</a>";
      print "</td>";
    print "</tr>";
  print "</table>";
  print "</center>";
 
}


function modify_report($cubename,$slice,$boolean,$levels,$distinct,$join,$order_by_col,$order_by_type)
{
global $urlsito;
$img_del="$urlsito/img/delete.png";
$img_search="$urlsito/img/search.png";

$order_by_col="";
$order_by_type="";

    print "<script>show_tree2(\"$cubename\",\"$order_by_col\",\"$order_by_type\",\"$distinct\",\"$join\")</script>";

      $n=count($levels);
      for($i=0;$i<$n;$i++)
      {
      //print "LEV $levels[$i]<br>";
      list($dimensionname,$hiername,$levelname,$propname)=explode(".",$levels[$i]);
      print "<script>addCol(\"$dimensionname\",\"$hiername\",\"$levelname\",\"$propname\")</script>";
      }
      
$nc=count($slice);      
for($i=0;$i<$nc;$i++)
{
list($d,$h,$l,$p,$c,$v)=explode(".",$slice[$i]);
//print "\"$d.$h.$l.$p\",\"$c\",\"$v\",\"$img_del\",\"$img_search\",\"$boolean\"";
print "<script>restore_condition(\"$d.$h.$l.$p\",\"$c\",\"$v\",\"$img_del\",\"$img_search\",\"$boolean\");</script>";
}

}

function generate_bar($cubename,$levels,$slice,$boolean,$order_by_col,$order_by_type,$distinct,$join)
{

 global $action;
 global $urlsito;
 global $message;
 
 // $slice="!!";
 
$export[]=$cubename;
$export[]=implode("!!",$levels);
if($slice!="")
$export[]=implode("!!",$slice);
else
$export[]="";
$export[]=$boolean;
$export[]=$order_by_col;
$export[]=$order_by_type;
$export[]=$distinct;
$export[]=$join;
$export_string=implode("**",$export);
 
//print "$export_string<br>";
 
$w="35px";
print "<center>";
print "<table>";
  print "<tr>";
    print "<td>";
      print "<a style='cursor:pointer'>";
        print "<img border=0 src='$urlsito/img/back.png' width=$w height=$w onclick='check_cube(\"modify_report\")'>";
      print "</a>";
    print "</td>";
    
    
    print "<td>";
      print "<a style='cursor:pointer'>";
        print "<img border=0 src='$urlsito/img/save.png' width=$w height=$w onclick='export_result(\"save_file\",\"$export_string\")'>";
      print "</a>";
    print "</td>";
        print "<td>";
      print "<a style='cursor:pointer'>";
        print "<img border=0 src='$urlsito/img/pdf.png' width=$w height=$w onclick='export_result(\"save_pdf\",\"$export_string\")'>";
      print "</a>";
    print "</td>";
    print "<td>";
      print "<a style='cursor:pointer'>";
        print "<img border=0 src='$urlsito/img/csv.png' width=$w height=$w onclick='export_result(\"save_csv\",\"$export_string\")'>";
      print "</a>";
    print "</td>";
    print "<td>";
      print "<a style='cursor:pointer'>";
        print "<img border=0 src='$urlsito/img/weka.jpg' width=$w height=$w onclick='export_result(\"save_weka\",\"$export_string\")'>";
      print "</a>";
    print "</td>";
        print "<td>";
      print "<a style='cursor:pointer'>";
        print "<img border=0 src='$urlsito/img/share.png' width=$w height=$w onclick='export_result(\"show_form_social\",\"$export_string\")'>";
      print "</a>";
    print "</td>";
    print "<td>";
      print "<a style='cursor:pointer'>";
        print "<img border=0 src='$urlsito/img/email.png' width=$w height=$w onclick='export_result(\"show_form_email\",\"$export_string\")'>";
      print "</a>";
    print "</td>";

  print "</tr>";
print "</table>";
print "</center>";

}

function show_report($result,$cubename,$levels,$slice,$boolean,$order_by_col,$order_by_type,$distinct,$join,$col_name)
{
 global $title;
 global $action;
 global $urlsito;
 global $message;


$this->generate_bar($cubename,$levels,$slice,$boolean,$order_by_col,$order_by_type,$distinct,$join); 
 //print "report $cubename";

$ncols=mysql_num_fields($result);

print "<p>";
print "<center>";
print "<table border=1>";

//HEAD
print "<tr>";
  for($i=0;$i<$ncols;$i++)
  {
    $colname=mysql_fetch_field($result);
    $nome=$colname->name;
    $tabella= $colname->table;
    $pk= $colname->primary_key;
    
    $v=false;
    $n_col_name=count($col_name);
    for($k=0;$k<$n_col_name;$k++)
    {
      if($nome==$col_name[$k])
        $v=true;
    }
    
    if($tabella=="")
    $field="$nome";
    else
    $field="$tabella.$nome";
    
    $colonna_ordinamento="$tabella.$nome";    
    print "<th>";
     print "<a style='cursor:pointer'><img border=0 src='$urlsito/img/sort_ascend.png' width=20 height=20 onclick='order_by(\"$colonna_ordinamento\",\"asc\")'></a>";
     print "<a style='cursor:pointer'><img border=0 src='$urlsito/img/sort_descend.png' width=20 height=20 onclick='order_by(\"$colonna_ordinamento\",\"desc\")'></a>";
     
     if($v==true)
     {
       print " <a style='cursor:pointer'><img src='$urlsito/img/level.gif' width=20px height=20px onclick='change_aggregation(\"show_form_drill\",\"$cubename\",\"$tabella\",\"$nome\")'></a>";
       print " <a style='cursor:pointer'><img src='$urlsito/img/hierarchy.gif' width=20px height=20px onclick='change_aggregation(\"show_form_hier\",\"$cubename\",\"$tabella\",\"$nome\")'></a>";
       print " <a style='cursor:pointer'><img src='$urlsito/img/dimension2.gif' width=20px height=20px onclick='change_aggregation(\"show_form_dim\",\"$cubename\",\"$tabella\",\"$nome\")'></a>";
       //print " <a style='cursor:pointer'><img src='$urlsito/img/rotate.png' width=20px height=20px onclick='change_aggregation(\"pivoting\",\"$cubename\",\"$tabella\",\"$nome\")'></a>";
     }
    print "<br>$field";
    
    print "</th>";
    
  }
print "</tr>";  



//BODY
while ($row = mysql_fetch_array($result))
{
print "<tr>";
  for($i=0;$i<$ncols;$i++)
  {
    $colvalue=$row[$i];
    print "<td>$colvalue</td>";
  }
print "</tr>";  
}

print "</table>";
print "</center>";


 $n=count($levels);
 if($distinct=="true")
  $checked="checked";
 else
  $checked="";
 
 if($join=="true")
  $checked2="checked";
 else
  $checked2="";
 
 print "<input type=hidden name='order_by_col' id='order_by_col' value='$order_by_col'>";
 print "<input type=hidden name='order_by_type' id='order_by_type' value='$order_by_type'>";
 print "<input type=hidden name='cubename' id='cubename' value='$cubename'>";
 print "<input type='checkbox' id='distinct_box' name='distinct_box' value='yes' $checked style='visibility:hidden'>"; 
 print "<input type='checkbox' id='join_box' name='join_box' value='yes' $checked2 style='visibility:hidden'>"; 
 print "<select id='level_selected' size=5 name='level_selected[]' multiple style='visibility:hidden'>";
 for($i=0;$i<$n;$i++)
 {
  print "<option value='$levels[$i]' selected>$levels[$i]";
 }
 print "</select>";
 

print "<input type=hidden name='bool0' id='bool0' value='$boolean'>";

//TABLE Condition
$nc=count($slice); 
print "<table border=1 id='condition' style='visibility:hidden'>";
for($i=0;$i<$nc;$i++)
{
list($d,$h,$l,$p,$c,$v)=explode(".",$slice[$i]);
print "<script>restore_condition(\"$d.$h.$l.$p\",\"$c\",\"$v\",'',\"$boolean\");</script>";
}
print "</table>";

 
 ///////////////////////////////////////////DIV
print "<div id='coprente' style='overflow:auto;visibility:hidden;position: absolute;top: 0;left: 0;width: 100%;height: 100%;background: transparent url($urlsito/img/bg.png) repeat;z-index: 30;'>";
print "<center>";
print "<div id=divResult style='margin-top: 150;top:50; left:50; width:30%; height: 50%; margin-left: auto; margin-right: auto;  background-color: white'>";
//position: absolute; margin-top: -25;
print "</div>";
print "</center>";
print "</div>";
 
}





function show_header()
{
 global $title;
 global $action;
 global $urlsito;
 global $message;
 global $lang;
 
print "<head>";
print "<title>$title</title>";
print "<link rel='stylesheet' type='text/css' href='$urlsito/css/style.css' />";
print "<script type='text/javascript' src='$urlsito/js/script.js' language='javascript'></script>";
print "</head>";
print "<script>init('$action');</script>";
print "<script>init_images('$urlsito/img/minus.gif','$urlsito/img/plus.gif','$urlsito/img/delete.png');</script>";

$w="50px";
print "<table>";
print "<tr>";
print "<td valign=top><h3 align=left><a onclick='go(\"\")'>$title</a></h3></td>";
print "<td><a onclick='go(\"new_report\")'><img src='$urlsito/img/new.png' width=$w height=$w title=\"{$message["new"]}\"></a></td>";
print "<td><a onclick='go(\"open_report\")'><img src='$urlsito/img/open.png' width=$w height=$w title=\"{$message["open"]}\"></a></td>";

print "</tr>";
print "</table>"; 

}


function show_home()
{
 global $message;
 global $urlsito;
//print "<h1>{$message["home"]}</h1>";
session_start();
$lang2=$_SESSION["lang"];

$w="26px";

print "<fieldset>";
print "<legend>{$message["legend"]}</legend>";
print "<img src='$urlsito/img/cube.gif'> {$message["cube"]} ";
print "<img src='$urlsito/img/measure.gif'> {$message["measure"]} ";
print "<img src='$urlsito/img/dimension2.gif' width=$w height=$w> {$message["dimension"]} ";
print "<img src='$urlsito/img/hierarchy.gif'> {$message["hier"]} ";
print "<img src='$urlsito/img/level.gif'> {$message["lev"]} ";
print "<img src='$urlsito/img/property.gif'> {$message["prop"]} ";
print "</fieldset>";

print "<p>{$message["desc"]}<p>";

print $message["lang"];
print " <select onchange='change_lang(this.value)'>";
$mod_dir=dirname(__FILE__) . '/../lang'; 
$hnd=opendir($mod_dir);
while($file=readdir($hnd))
{
  
  if ($file != "." and $file != "..")
  {
  if($file==$lang2)
  $selected="selected";
  else
  $selected="";
  
  $nf = strlen($file);
  $file2=substr($file,0,$nf-4);
  print "<option $selected value=$file>$file2</option>>";
  }
}
print "</select>";


}



function show_new($cubename)
{
 global $message;
 global $urlsito;

$n=count($cubename);
for($i=0;$i<$n;$i++)
{
print "<a onclick='show_tree(\"$cubename[$i]\",true)'><img src='$urlsito/img/cube.gif'> $cubename[$i]</a>";
}


print "<center>";
print "<form id='form_report' name='form_report' method='post'>";

print "<table cellspacing=5 cellpadding=5 width=100% border=0>";
  print "<tr>";
    print "<td width=30% valign='top'>";
      print "<p>";
      print "<div id='divTree'></div>";
    print "</td>";

    print "<td valign=top align=center>";
      //*************************************************REPORT **************************************************
print "<table cellspacing=15 cellpadding=1>";
print "<tr>";
print "<td><input type='checkbox' id='distinct_box' name='distinct_box' value='yes' checked>{$message["distinct"]}</td>";
print "<td><input type='checkbox' id='join_box' name='join_box' value='yes' checked>{$message["join"]}</td>";
print "<td><a style='width:120px;cursor:pointer' class='button' onClick='check_cube(\"execute\")'>{$message["create_report"]}</a></td>";
print "</tr>";
print "</table>";
print "<br>";

      print "<table border=1 id='report'>";
        print "<tr id='rep_header'>";
        print "</tr>";
      print "</table>";
   
      //**************************************************condizioni
      print "<p>{$message["condition"]}";      
      print "<table border=1 id='condition'>";
      print "</table>";

      
    print "</td>";
  print "</tr>";
print "</table>";
print "<br>";
   //**********************************************dati selezionati
print "<select id='level_selected' size=5 name='level_selected[]' multiple style='visibility:hidden'>";
print "</select>";

print "</form>";
print "</center>";

print "<input type=hidden name='order_by_col' id='order_by_col' value=''>";
print "<input type=hidden name='order_by_typr' id='order_by_type' value=''>";

///////////////////////////////////////////DIV
print "<div id='coprente' style='overflow:auto;visibility:hidden;position: absolute;top: 0;left: 0;width: 100%;height: 100%;background: transparent url($urlsito/img/bg.png) repeat;z-index: 30;'>";
print "<center>";
print "<div id=divResult style='margin-top: 150;top:50; left:50; width:30%; height: 50%; margin-left: auto; margin-right: auto;  background-color: white'>";
//position: absolute; margin-top: -25;
print "</div>";
print "</center>";
print "</div>";

}

function show_tree($cubename)
{

 global $urlsito;

  print "<br>";
  print "<a onclick='show_measures(\"$cubename\");'>";
  print "<img id='$cubename-img_plus' src='$urlsito/img/plus.gif' width=13px height=13px>";
  print "</a>";
  print "<img src='$urlsito/img/cube.gif' width='13px' height='13px'> $cubename";
  print "<input type=hidden id='hidden_" . $cubename . "' value=closed>";
  print "<div id='divMeasure_"."$cubename' style='margin-left:50px;visibility:visible'></div>";
  print "<div id='divDim_"."$cubename' style='margin-left:50px;visibility:visible'></div>";
  print "<input type=hidden name='cubename' id=cubename value='$cubename'>";

}


function show_prop($cubename,$dimensionname,$hiername,$levelname,$propname,$last_dim,$last_hier,$last_level)
{

 global $urlsito;
 global $message;

 $n=count($propname);
 for($i=0;$i<$n;$i++)
 {
  $c=strcmp($dimensionname,$last_dim);
  if($c!=0) print "<img src='$urlsito/img/link.gif' style='position:absolute;left:68px'>";
  
  $c=strcmp($hiername,$last_hier);
  //print "H $hiername L $last_hier C $c";
  if($c!=0) print "<img src='$urlsito/img/link.gif' style='position:absolute;left:118px'>";
  
  $c=strcmp($levelname,$last_level);
  //print "L $levelname L $last_level C $c";
  if($c!=0) print "<img src='$urlsito/img/link.gif' style='position:absolute;left:168px'>";

  $full_name="$dimensionname.$hiername.$levelname.$propname[$i]";
    
  print "<img src='$urlsito/img/lastlink.gif'>";
  print "<a style='cursor:pointer'>";
  print "<img title='{$message["slice"]}' border=0 src='$urlsito/img/filter.png' width=15 height=15 onclick='new_condition(\"$full_name\",\"$urlsito/img/delete.png\",\"$urlsito/img/search.png\")'>";
  print "</a>";
  print " <img src='$urlsito/img/property.gif' width='13px' height='13px'>";
  print "<a onclick='addCol(\"$dimensionname\",\"$hiername\",\"$levelname\",\"$propname[$i]\")'>$propname[$i]</a>";
  print "<br>";
 
 }
 
}


function show_levels($cubename,$dimensionname,$hiername,$levelname,$last_dim,$last_hier)
{

 global $urlsito;

 $n=count($levelname);
 for($i=0;$i<$n;$i++)
 {
  $c=strcmp($dimensionname,$last_dim);
  if($c!=0) print "<img src='$urlsito/img/link.gif' style='position:absolute;left:68px'>";
  $c=strcmp($hiername,$last_hier);
  if($c!=0)
  { 
    print "<img src='$urlsito/img/link.gif' style='position:absolute;left:118px'>";
  }
  print "<img src='$urlsito/img/lastlink.gif'><a onclick='show_prop(\"$cubename\",\"$dimensionname\",\"$hiername\",\"$levelname[$i]\")'>";
  print "<img id='$dimensionname-$hiername-$levelname[$i]-img_plus' src='$urlsito/img/plus.gif' width=13px height=13px></a>";
  print "<img src='$urlsito/img/level.gif' width='13px' height='13px'> $levelname[$i]";  
  print "<input type=hidden id='hidden_" . $dimensionname . "_".$hiername."_"."$levelname[$i]' value=closed>";
  print "<div id='divProp_".$dimensionname."_"."$hiername"."_"."$levelname[$i]' style='margin-left:50px'></div>";
 
 }
 
}


function show_hier($cubename,$dimensionname,$hiername,$last_dim)
{

 global $urlsito;

 $n=count($hiername);
 for($i=0;$i<$n;$i++)
 {
 
  $c=strcmp($dimensionname,$last_dim);
  if($c!=0) print "<img src='$urlsito/img/link.gif' style='position:absolute;left:68px'>";
  print "<img src='$urlsito/img/lastlink.gif'><a  onclick='show_levels(\"$cubename\",\"$dimensionname\",\"$hiername[$i]\")'>";
  print "<img id='$dimensionname-$hiername[$i]-img_plus' src='$urlsito/img/plus.gif' width=13px height=13px></a>";
  print "<img src='$urlsito/img/hierarchy.gif' width='13px' height='13px'> $hiername[$i]";
  print "<input type=hidden id='hidden_" . $dimensionname . "_"."$hiername[$i]' value=closed>";
  print "<div id='divLev_".$dimensionname."_"."$hiername[$i]' style='margin-left:50px'></div>";

 }
}



function show_dimensions($cubename,$dimensionname)
{

 global $urlsito;

 $n=count($dimensionname);
 for($i=0;$i<$n;$i++)
 {
  print "<img src='$urlsito/img/lastlink.gif'><a onclick='show_hier(\"$dimensionname[$i]\",\"$cubename\")'>";
  print "<img id='$dimensionname[$i]-img_plus' src='$urlsito/img/plus.gif' width=13px height=13px>";
  print "</a><img src='$urlsito/img/dimension2.gif' width='13px' height='13px'> $dimensionname[$i]";
  print "<input type=hidden id='hidden_" . $dimensionname[$i] . "' value=closed>";
  print "<div id='divHier_$dimensionname[$i]' style='margin-left:50px'></div>";
 }
}



function show_measures($measurename,$cubename)
{

 global $urlsito;

 $n=count($measurename);
 for($i=0;$i<$n;$i++)
 {
  print "<img src='$urlsito/img/lastlink.gif'><a onclick='show_functions(\"$cubename\",\"$measurename[$i]\")'>";
  print "<img id='$cubename-$measurename[$i]-img_plus' src='$urlsito/img/plus.gif' width=13px height=13px></a>";
  print "<img src='$urlsito/img/measure.gif' width='13px' height='13px'>";
  print "<a onclick='addCol(\"cube\",\"cube\",\"$cubename\",\"$measurename[$i]\")'>$measurename[$i]</a><br>";
  print "<input type=hidden id='hidden_" . $cubename . "_" . "$measurename[$i]' value=closed>";
  print "<div id='divFunctions_" . $cubename . "_" . "$measurename[$i]' style='margin-left:50px; width=1800px'></div>";

 }
}


function show_functions($measurename,$cubename,$functionname)
{

 global $urlsito;
 $n=count($functionname);
 for($i=0;$i<$n;$i++)
 {
  print "<img src='$urlsito/img/link.gif' style='position:absolute;left:68px'>";
  print "<img src='$urlsito/img/lastlink.gif'>";
  print "<a  onclick='addCol(\"cube\",\"cube\",\"aggregate\",\"$functionname[$i]\")'>";
  print "<img src='$urlsito/img/measure.gif' width='13px' height='13px'> $functionname[$i]</a>";
  print "<br>";
 }
}


function show_open($filename)
{
 global $message;
 global $urlsito;

 $n=count($filename);

//print "N $n<br>";
print "<center>";
print "<h4>{$message["report"]}</h4>";
 print "<table border=1>";
 
 for($i=0;$i<$n;$i++)
 {
 $file=$filename[$i];
 print "<tr>";
 print "<td><a style='cursor:pointer' onclick='open_file(\"$file\")'>$file</a></td>";
 print "<td><img style='cursor:pointer' src='$urlsito/img/delete.png' width='13px' height='13px' onclick='delete_file(\"$file\",this.parentNode.parentNode)'></td>";
 print "</tr>";
 }
 print "</table>";
print "</center>";
 

}

    		
}


?>
