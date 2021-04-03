<?php
class Metamodel {

function get_column_values($cubename_sel,$property)
{

list($dim,$hiera,$lev,$prope)=explode(".",$property);
global $xmlfile;
$xml=simplexml_load_file($xmlfile);


foreach($xml->Cube as $cube)
{
$cubename=$cube['name'];
if($cubename==$cubename_sel)
{
//print "FOUND C $cubename<br>";
foreach($cube->DimensionUsage as $dimension)
{
$dimensionname=$dimension['name'];
if($dimensionname==$dim) 
{
//print "FOUND D $dimensionname<br>";
foreach($xml->Dimension as $dimension)
{
  $dimensionname=$dimension['name'];
  if($dimensionname==$dim)
  {
  //print "FOUND2 D $dimensionname<br>";

    foreach($dimension->Hierarchy as $hier)
    {
      $hiername=$hier['name'];
      if($hiername==$hiera)
      {
        //print "FOUND H $hiername<br>";
    
        foreach($hier->Table as $hiertable)
        {
          $tablename=$hiertable["name"];
        }
        
        foreach($hier->Level as $level)
        {
          $levelname=$level['name'];
          $levelcol=$level['column'];
          $leveltable=$level['table'];
          
          if($leveltable=="")
            $table=$tablename;
          else
            $table=$leveltable;
          
          if ($levelname==$lev)
          {
            //print "FOUND L $levelname<br>";
    
            foreach($level->Property as $prop)
            {
              $propname=$prop['name'];
              $colname=$prop['column'];
              
              if($propname==$prope)
              {
              //print "FOUND P $propname<br>";
              $query="select distinct $colname from $table order by $colname";
              }
              
           }            
}}}}}}}}}}

//print "$query<br>";
return $query;


}

function change_dim($cubename_sel,$tabella,$colonna)
{
global $xmlfile;
$xml=simplexml_load_file($xmlfile);


foreach($xml->Cube as $cube)
{
  $cubename=$cube['name'];
  if($cubename==$cubename_sel)
  {
    //print "CUB $cubename<br>";
    foreach($cube->DimensionUsage as $dimension)
    {
      $dimensionname1=$dimension['name'];
      //print "DIM CUB $dimensionname1<br>";
      foreach($xml->Dimension as $dimension_drill)
      {
        $dimension_drill_name=$dimension_drill['name'];
        //print "DIM $dimension_drill_name<br>";
        $a1=strcmp($dimensionname1,$dimension_drill_name);
        //print "CFR $dimensionname1--$dimension_drill_name--$a1<br>";
        if($a1==0)
        { 
          //print "FOUND DIM $dimensionname1 = $dimension_drill_name<br>";
          foreach($dimension_drill->Hierarchy as $hier_drill)
          {
            $hier_drill_name=$hier_drill['name'];
            //print "HIER $hier_drill_name<br>";
            $table2=$hier_drill->Table;
            $table2_name=$table2["name"];
            foreach($hier_drill->Level as $level_drill)
            {
              $level_drill_name=$level_drill['name'];
              //print "LEV $hier_drill_name.$level_drill_name<br>";
              $t=$level_drill['table'];
              //if($t=="") $t=$table2_name;
              if($t!="")
              $level_drill_table=$level_drill['table'];
              else
              $level_drill_table=$table2_name;
              //print "$t<br>";
              $level_drill_column=$level_drill['column'];
              //print "$t.$level_drill_column<br>"; 
              $a=strcmp($level_drill_table,$tabella);
              $b=strcmp($level_drill_column,$colonna);
              //print "$level_drill_table=$tabella ** $level_drill_column=$colonna<br>";
              if($a==0 and $b==0)
              {
                $dim_found=$dimension_drill_name;
                $hier_found=$hier_drill_name;
                $level_found=$level_drill_name;
                //print "F $dim_found $hier_found $level_found<br>";
}}}}}}}}


foreach($xml->Cube as $cube)
{
$cubename=$cube['name'];
if($cubename==$cubename_sel)
{
foreach($cube->DimensionUsage as $dimension)
{
$dimensionname1=$dimension['name'];
foreach($xml->Dimension as $dimension_drill)
{
$dimension_drill_name=$dimension_drill['name'];
//$a1=strcmp($dimensionname1,$dim_found);
$a2=strcmp($dimensionname1,$dimension_drill_name);
if($a2==0)
{
    //first hier first level first prop
    $hier_drill=$dimension_drill->Hierarchy;
    $hier_drill_name=$hier_drill["name"];
    $l1=$hier_drill->Level;
    $level_drill_name=$l1['name'];
    $first_property=$l1->Property;
    $fp=$first_property['name'];
    $valore_opt[]="$dimension_drill_name.$hier_drill_name.$level_drill_name.$fp";
//         if($dimension_drill_name==$dim_found)        
//           print "<option selected value='$valore_opt'>$dimension_drill_name</option>";
//         else
//           print "<option value='$valore_opt'>$dimension_drill_name</option>"; //onclick='alert(\"$valore_opt\")'
}}}}}

$valore_opt[]=$dim_found;
return $valore_opt; 
}


function change_hier($cubename_sel,$tabella,$colonna)
{
global $xmlfile;
$xml=simplexml_load_file($xmlfile);
foreach($xml->Cube as $cube)
{
  $cubename=$cube['name'];
  if($cubename==$cubename_sel)
  {
    //print "CUB $cubename<br>";
    foreach($cube->DimensionUsage as $dimension)
    {
      $dimensionname1=$dimension['name'];
      //print "DIM CUB $dimensionname1<br>";
      foreach($xml->Dimension as $dimension_drill)
      {
        $dimension_drill_name=$dimension_drill['name'];
        //print "DIM $dimension_drill_name<br>";
        $a=strcmp($dimensionname1,$dimension_drill_name);
        if($a==0)
        { 
          //print "FOUND DIM $dimensionname1 = $dimension_drill_name<br>";
          foreach($dimension_drill->Hierarchy as $hier_drill)
          {
            $hier_drill_name=$hier_drill['name'];
            //print "HIER $hier_drill_name<br>";
            $table2=$hier_drill->Table;
            $table2_name=$table2["name"];
            foreach($hier_drill->Level as $level_drill)
            {
              $level_drill_name=$level_drill['name'];
              //print "LEV $hier_drill_name.$level_drill_name<br>";
              $t=$level_drill['table'];
              //if($t=="") $t=$table2_name;
              if($t!="")
              $level_drill_table=$level_drill['table'];
              else
              $level_drill_table=$table2_name;
              //print "$t<br>";
              $level_drill_column=$level_drill['column'];
              //print "$t.$level_drill_column<br>"; 
              $a=strcmp($level_drill_table,$tabella);
              $b=strcmp($level_drill_column,$colonna);
              //print "$level_drill_table=$tabella ** $level_drill_column=$colonna<br>";
              if($a==0 and $b==0)
              {
                $dim_found=$dimension_drill_name;
                $hier_found=$hier_drill_name;
                $level_found=$level_drill_name;
                //print "F $dim_found $hier_found $level_found<br>";
}}}}}}}}



foreach($xml->Cube as $cube)
{
$cubename=$cube['name'];
if($cubename==$cubename_sel)
{
foreach($cube->DimensionUsage as $dimension)
{
$dimensionname1=$dimension['name'];
$a=strcmp($dimensionname1,$dim_found);
if($a==0)
{
foreach($xml->Dimension as $dimension_drill)
{
  $dimension_drill_name=$dimension_drill['name'];
  if($dimension_drill_name==$dim_found)
  {
    foreach($dimension_drill->Hierarchy as $hier_drill)
    {
    $hier_drill_name=$hier_drill['name'];
    
    //first level first prop
    $l1=$hier_drill->Level;
    $level_drill_name=$l1['name'];
    $first_property=$l1->Property;
    $fp=$first_property['name'];
    $valore_opt[]="$dim_found.$hier_drill_name.$level_drill_name.$fp";
//         if($hier_drill_name==$hier_found)        
//           print "<option selected value='$valore_opt'>$hier_drill_name</option>";
//         else
//           print "<option value='$valore_opt'>$hier_drill_name</option>"; //onclick='alert(\"$valore_opt\")'
   
}}}}}}}

$valore_opt[]=$hier_found;
return $valore_opt;

}

function change_level($cubename_sel,$tabella,$colonna)
{
global $xmlfile;
$xml=simplexml_load_file($xmlfile);

//print "PAR $cubename_sel,$tabella,$colonna";

foreach($xml->Cube as $cube)
{
  $cubename=$cube['name'];
  if($cubename==$cubename_sel)
  {
    //print "CUB $cubename<br>";
    foreach($cube->DimensionUsage as $dimension)
    {
      $dimensionname1=$dimension['name'];
      //print "DIM CUB $dimensionname1<br>";
      foreach($xml->Dimension as $dimension_drill)
      {
        $dimension_drill_name=$dimension_drill['name'];
        //print "DIM $dimension_drill_name<br>";
        $a=strcmp($dimensionname1,$dimension_drill_name);
        if($a==0)
        { 
          //print "FOUND DIM $dimensionname1 = $dimension_drill_name<br>";
          foreach($dimension_drill->Hierarchy as $hier_drill)
          {
            $hier_drill_name=$hier_drill['name'];
            //print "HIER $hier_drill_name<br>";
            $table2=$hier_drill->Table;
            $table2_name=$table2["name"];
            foreach($hier_drill->Level as $level_drill)
            {
              $level_drill_name=$level_drill['name'];
              //print "LEV $hier_drill_name.$level_drill_name<br>";
              $t=$level_drill['table'];
              //if($t=="") $t=$table2_name;
              if($t!="")
              $level_drill_table=$level_drill['table'];
              else
              $level_drill_table=$table2_name;
              //print "$t<br>";
              $level_drill_column=$level_drill['column'];
              //print "$t.$level_drill_column<br>"; 
              $a=strcmp($level_drill_table,$tabella);
              $b=strcmp($level_drill_column,$colonna);
              //print "$level_drill_table=$tabella ** $level_drill_column=$colonna<br>";
              if($a==0 and $b==0)
              {
                $dim_found=$dimension_drill_name;
                $hier_found=$hier_drill_name;
                $level_found=$level_drill_name;
                //print "FOUND $dim_found $hier_found $level_found<br>";
}}}}}}}}


foreach($xml->Cube as $cube)
{
$cubename=$cube['name'];
if($cubename==$cubename_sel)
{
foreach($cube->DimensionUsage as $dimension)
{
$dimensionname1=$dimension['name'];
$a=strcmp($dimensionname1,$dim_found);
if($a==0)
{
foreach($xml->Dimension as $dimension_drill)
{
  $dimension_drill_name=$dimension_drill['name'];
  if($dimension_drill_name==$dim_found)
  {
    foreach($dimension_drill->Hierarchy as $hier_drill)
    {
    $hier_drill_name=$hier_drill['name'];
    if($hier_drill_name==$hier_found)
    {        
      foreach($hier_drill->Level as $level_drill)
      {
      $level_drill_name=$level_drill['name'];
      $level_drill_colname=$level_drill['column'];
      $first_property=$level_drill->Property;
      $fp=$first_property['name'];
      $valore_opt[]="$dim_found.$hier_found.$level_drill_name.$fp";
//       if($level_drill_name==$level_found)        
//       print "<option selected value='$valore_opt'>$level_drill_name</option>";
//       else
//       print "<option value='$valore_opt'>$level_drill_name</option>";
        
}}}}}}}}}

$valore_opt[]=$level_found;
return $valore_opt;

}


function get_col_name()
{
global $xmlfile;

$v=false;
$xml=simplexml_load_file($xmlfile);
foreach($xml->Dimension as $dimensioncube)
{
  foreach($dimensioncube->Hierarchy as $hier)
  {
         foreach($hier->Level as $level)
         {
            $level_col[]=$level['column'];
                 
         }
  }        
}
return $level_col;
}




function get_last_level($cubename_sel,$dimensionname_sel,$hiername_sel,$levelname_sel) {

global $xmlfile;
$xml=simplexml_load_file($xmlfile);

foreach($xml->Cube as $cube)
{
$cubename=$cube['name'];
if($cubename==$cubename_sel)
{
foreach($cube->DimensionUsage as $dimension)
{
$dimensionname=$dimension['name'];
if($dimensionname==$dimensionname_sel) 
{
foreach($xml->Dimension as $dimension)
{
  $dimensionname2=$dimension['name'];
  if($dimensionname2==$dimensionname_sel)
  {
    foreach($dimension->Hierarchy as $hier)
    {
      $hiername=$hier['name'];
      if($hiername==$hiername_sel)
      {
        foreach($hier->Level as $level)
        {
          $levelname=$level['name'];  
          $lastL= $levelname;           
        }  
      }
      //$lastH=$hiername;
    }      
  }
}
}
//$lastD=$dimensionname;
}}}

return $lastL;

}





function get_prop($cubename_sel,$dimensionname_sel,$hiername_sel,$levelname_sel) {

global $xmlfile;
$xml=simplexml_load_file($xmlfile);

foreach($xml->Cube as $cube)
{
$cubename=$cube['name'];
if($cubename==$cubename_sel)
{
foreach($cube->DimensionUsage as $dimension)
{
$dimensionname=$dimension['name'];
if($dimensionname==$dimensionname_sel) 
{
foreach($xml->Dimension as $dimension)
{
  $dimensionname=$dimension['name'];
  if($dimensionname==$dimensionname_sel)
  {
    foreach($dimension->Hierarchy as $hier)
    {
      $hiername=$hier['name'];
      if($hiername==$hiername_sel)
      {
        foreach($hier->Level as $level)
        {
          $levelname=$level['name'];
          $levelcol=$level['column'];
          if ($levelname==$levelname_sel)
          {
            foreach($level->Property as $prop)
            {
              $propname[]=$prop['name'];
              
           }            
}}}}}}}}}}

return $propname;

}

function get_levels($cubename_sel,$dimensionname_sel,$hiername_sel) {

global $xmlfile;
$xml=simplexml_load_file($xmlfile);

foreach($xml->Cube as $cube)
{
$cubename=$cube['name'];
if($cubename==$cubename_sel)
{
foreach($cube->DimensionUsage as $dimension)
{
$dimensionname=$dimension['name'];
if($dimensionname==$dimensionname_sel) 
{
foreach($xml->Dimension as $dimension)
{
  $dimensionname2=$dimension['name'];
  if($dimensionname2==$dimensionname_sel)
  {
    foreach($dimension->Hierarchy as $hier)
    {
      $hiername=$hier['name'];
      if($hiername==$hiername_sel)
      {
        foreach($hier->Level as $level)
        {
          $levelname[]=$level['name'];              
        }  
      }
      //$lastH=$hiername;
    }      
  }
}
}
//$lastD=$dimensionname;
}}}

return $levelname;

}


function get_last_hier($cubename_sel,$dimensionname_sel,$hiername_sel) {

global $xmlfile;
$xml=simplexml_load_file($xmlfile);

foreach($xml->Cube as $cube)
{
$cubename=$cube['name'];
if($cubename==$cubename_sel)
{
foreach($cube->DimensionUsage as $dimension)
{
$dimensionname=$dimension['name'];
if($dimensionname==$dimensionname_sel) 
{
foreach($xml->Dimension as $dimension)
{
  $dimensionname2=$dimension['name'];
  if($dimensionname2==$dimensionname_sel)
  {
    foreach($dimension->Hierarchy as $hier)
    {
      $hiername=$hier['name'];
//       if($hiername==$hiername_sel)
//       {
//         foreach($hier->Level as $level)
//         {
//           $levelname=$level['name'];              
//         }  
//       }
      $lastH=$hiername;
    }      
  }
}
}
//$lastD=$dimensionname;
}}}

return $lastH;

}



function get_last_dim($cubename_sel,$dimensionname_sel) {
global $xmlfile;
$xml=simplexml_load_file($xmlfile);

//trova ultima dimensione
foreach($xml->Cube as $cube)
{
  $cubename=$cube['name'];
  if($cubename==$cubename_sel)
  {
    foreach($cube->DimensionUsage as $dimension)
    {
      $dimensionname=$dimension['name'];
      $lastD=$dimensionname;
}}}
return $lastD;

}

function get_hier($cubename_sel,$dimensionname_sel) {

global $xmlfile;
$xml=simplexml_load_file($xmlfile);



foreach($xml->Cube as $cube)
{
  $cubename=$cube['name'];
  if($cubename==$cubename_sel)
  {
    foreach($cube->DimensionUsage as $dimension)
    {
      $dimensionname=$dimension['name'];
      if($dimensionname==$dimensionname_sel) 
      {
        foreach($xml->Dimension as $dimension)
        { 
          $dimensionname2=$dimension['name'];
          if($dimensionname2==$dimensionname_sel)
          {
            foreach($dimension->Hierarchy as $hier)
            {
              $hiername[]=$hier['name'];
            }}}
      }
}}}


return $hiername;
}




function get_functions($cubename_sel,$measurename_sel) {

global $xmlfile;
$xml=simplexml_load_file($xmlfile);

$function[0]="sum";
$function[1]="avg";
$function[2]="count";
$function[3]="min";
$function[4]="max";
$n=count($function);
for($i=0;$i<$n;$i++)
{
$functionname[]="$function[$i]($measurename_sel)";
}

return $functionname;
}



function get_cubes() {

global $xmlfile;
$xml=simplexml_load_file($xmlfile);

//print_r($xml);
    
foreach($xml->Cube as $cube)
{
  $cubename[]=$cube['name'];
    
}
return $cubename;
}


function get_dimensions($cubename_sel) {

global $xmlfile;
$xml=simplexml_load_file($xmlfile);

foreach($xml->Cube as $cube)
{
  $cubename=$cube['name'];
  if($cubename==$cubename_sel)
  {
    foreach($cube->DimensionUsage as $dimension)
    {
      $dimensionname[]=$dimension['name'];
        
    }  
  }
}

return $dimensionname;
}





function get_measures($cubename_sel) {

global $xmlfile;
$xml=simplexml_load_file($xmlfile);

foreach($xml->Cube as $cube)
{
  $cubename=$cube['name'];
  $cubetable=$cube->Table;
  $cubetablename=$cubetable['name'];

  if($cubename==$cubename_sel)
  {
    
    foreach($cube->Measure as $measure)
    {
    $measurecol=$measure['column'];
    if($measurecol!="")
    {
    $measurename[]=$measure['name'];
    
    }
    }
    
    foreach($cube->CalculatedMember as $calc_measure)
    {
      $measurename[]=$calc_measure['name'];
      
    }      
  }
}

return $measurename;
}



}

?>



