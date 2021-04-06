<?php

class OlapEngine {

function SQLgenerator($cubename_sel,$levels,$slice,$boolean,$colonna,$ordinamento,$distinct_val,$join_mode)
{
//print "DIS $distinct_val<br>";
//print "JOIN $join_mode ORDER BY $colonna ORDER TYPE $ordinamento<br>";
//print "ppp $levels";
/*
assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_BAIL, 1);
assert_options(ASSERT_QUIET_EVAL, 1);
error_reporting(E_ALL);
ini_set('display_errors', 1);
*/

//print "ORDER $colonna,$ordinamento<br>";
//global $xmlfile;
//$xml=simplexml_load_file($xmlfile);
$xml=buildxml();

$target_list="";
$join_final="";
$group_final="";
$mea="";
$where=array();
$group=array();

//$levels=explode("-",$levels_ser);

if($join_mode=="true")
{
$join2=" inner join ";
}
else
{
$join2=" right join ";
}
//print "JM $jojn_mode --> $join2<br>";

//print "XML $xmlfile, CUB $cubename_sel, LEV $levels_ser<br>";
//*******************************CUBE
$groupby=false;
foreach($xml->Cube as $cube)
{
  $cubename=$cube['name'];
  if($cubename==$cubename_sel)
  {
    $cubetable=$cube->Table;
    $cubetablename=$cubetable['name'];

    //print "CUB TABLE $cubetablename<br>"; 

$nl=count($levels);
$nj=0;
for($i=0;$i<$nl;$i++) 
{
list($dim1,$hier1,$lev1,$prop1)=explode(".",$levels[$i]);
//print "BBB $dim1,$hier1,$lev1,$prop1<br>";

if($dim1=="cube" and $hier1=="cube" and $lev1!="aggregate")
{
  //**************misure del cubo
  foreach($cube->Measure as $measure)
  {
  $measurename=$measure["name"];
  $measurecol=$measure["column"];
  if($measurename==$prop1)
    $target_list.="$measurecol,";
  }
  
  //misure calcolate
  foreach($cube->CalculatedMember as $calc_measure)
  {
  $calc_measurename=$calc_measure["name"];
  if($calc_measurename==$prop1)
  {
    $formula=$calc_measure->Formula;
    $expr=$formula["expression"];
    $alias=$formula["alias"];
    $target_list.="$expr as $alias,";
  }
  }   
}

if($dim1=="cube" and $hier1=="cube" and $lev1=="aggregate")
{
  
  //$a=eregi("(.+)\(",$prop1,$regs);
  $a=preg_match("/(.+)\(/",$prop1,$regs);
  $funz=$regs[1];
  
  //$a=eregi("\((.+)\)",$prop1,$regs);
  $a=preg_match("/\((.+)\)/",$prop1,$regs);
  $measurename=$regs[1];
  
  foreach($cube->Measure as $measure)
  {
  $measurename2=$measure["name"];
  $measurecol=$measure["column"];
  if($measurename2==$measurename)
    $target_list.="$funz($measurecol) as {$funz}_{$measurecol},";
    $groupby=true;
  }
  
  
  //misure calcolate
  foreach($cube->CalculatedMember as $calc_measure)
  {
  $calc_measurename=$calc_measure["name"];
  if($calc_measurename==$measurename)
  {
    $formula=$calc_measure->Formula;
    $expr=$formula["expression"];
    $alias=$formula["alias"];
    $target_list.="$funz($expr) as $alias,";
    $groupby=true;
  }
  }    
}


    foreach($cube->DimensionUsage as $dimension)
    {
      $dimensionname=$dimension['name'];
      //print "d $dimensionname<br>";
      if($dimensionname==$dim1) 
      {
        $fk_cube=$dimension['foreignKey'];
        foreach($xml->Dimension as $dimensioncube)
        {
        $dimensionname2=$dimensioncube['name'];
        if($dimensionname2==$dim1)
        {
        
        foreach($dimensioncube->Hierarchy as $hier)
        {      
            $hiername=$hier['name'];
            //print "H $hiername<br>";
            if($hiername==$hier1)
            {
            
                $pk_hier=$hier['primaryKey'];
                $pk_hiertable=$hier['primaryKeyTable'];

                if($pk_hiertable!="")                    
                {
                $join[$nj]="$join2 $pk_hiertable on $cubetablename.$fk_cube=$pk_hiertable.$pk_hier ";
                $nj=$nj+1;
                }
              
                      
                //************************aggiungi join
                $join[$nj]=$this->buildJoin($hier,$join_mode);
                $nj=$nj+1;                
              
                
                //*********************************
 
                foreach($hier->Level as $level)
                {
                    $levelname=$level['name'];
                    if($levelname==$lev1)
                    {
                      $level_table=$level['table'];
                      if($level_table=="") $level_table=$pk_hiertable;
                      $level_col=$level['column'];
                      $group[]="$level_table.$level_col";
                      //print "AAA $i".$group[$i]; 
                      foreach($level->Property as $prop)
                      {
                        $propname=$prop['name'];
                        if($propname==$prop1)
                        {
                          $level_col=$prop['column'];
                          $target_list.="$level_table.$level_col,";
                                                    

                        }             
}}}}}}}}}}}}


//************************************WHERE
//$n=strlen($slice);
//$slice=substr($slice,0,$n-2);
//$cond=explode("--",$slice);

$nc=count($slice);
//print "NC $nc<br>";

for($i=0;$i<$nc;$i++)
{
//print "COND $slice[$i]<br>";
list($dim_c,$hier_c,$lev_c,$prop_c,$oper,$cond1)=explode(".",$slice[$i]);
//list($dim_c,$hier_c,$lev_c,$prop_c,$cond1)=explode(".",$slice[$i]);

//print "COND $cond1<br>";
//$cond1=$this->trasforma($cond1);
//print "COND $cond1<br>";
if(is_numeric($cond1)==false)
$cond1="\"$cond1\"";

$cond1="$oper $cond1";
//print "COND $cond1<br>";
//aggiungi fk_cube
foreach($cube->DimensionUsage as $dimension_cube)
{
  $dimensionname_cube=$dimension_cube['name'];
  if($dimensionname_cube==$dim_c) 
  {
    $fk_cube=$dimension_cube['foreignKey'];
  }
}



foreach($xml->Dimension as $dimensioncube)
        {
        $dimensionname=$dimensioncube['name'];
        if($dim_c==$dimensionname)
        {
        foreach($dimensioncube->Hierarchy as $hier)
        {      
            $hiername=$hier['name'];
            if($hiername==$hier_c)
            {
                $pk_hier=$hier['primaryKey'];            
                $pk_hiertable=$hier['primaryKeyTable'];
                
                
                //**********************************************
                if($pk_hiertable!="")                    
                {
                
                $join[$nj]="$join2 $pk_hiertable on $cubetablename.$fk_cube=$pk_hiertable.$pk_hier ";
                $nj=$nj+1;
                }
                //************************aggiungi join
                $join[$nj]=$this->buildJoin($hier,$join_mode);
                $nj=$nj+1;                
                //*********************************************
                
                
                foreach($hier->Level as $level)
                {
                    $levelname=$level['name'];
                    if($levelname==$lev_c)
                    {
                      $level_table=$level['table'];
                      $level_col=$level['column'];
                      if($level_table=="") $level_table=$pk_hiertable;
                      foreach($level->Property as $prop)
                      {
                        $propname=$prop['name'];
                        if($propname==$prop_c)
                        {
                          $level_col=$prop['column'];
                          $where[$i]="$level_table.$level_col $cond1";
}}}}}}}}}

//metti in AND
// $where_final="";
// for($i=0;$i<$nc;$i++)
// {
// $where_final = $where_final . " $where[$i] $boolean ";
// }
$numwhere=count($where);
$where_final="";
if($numwhere>0)
$where_final=implode(" $boolean ",$where);
//print "WF $where_final<br>";
//***********************elimina join ridondanti

for($i=0;$i<$nj;$i++) 
{
  for($j=0;$j<$nj;$j++) 
  {
    if ($i!=$j && $join[$i]==$join[$j])
      $join[$j]="";
  }
}

for($i=0;$i<$nj;$i++) 
{
 $join_final.=" $join[$i]";
}

//***********************costruisci target list
$n=strlen($target_list);
$target_list=substr($target_list,0,$n-1);
//print $target_list; 


//******************************costruisci group by
$numg=count($group);

for($i=0;$i<$numg;$i++) 
{
  for($j=0;$j<$numg;$j++) 
  {
    if ($i!=$j && $group[$i]==$group[$j])
      $group[$j]="";
  }
}

//$m45=count($group);
//print "M45 $m45 0=". $group[0]."<br>";
for($i=0;$i<$numg;$i++) 
{
if($group[$i]!="")
 $group_final.="$group[$i],";
}

$n=strlen($group_final);
$group_final=substr($group_final,0,$n-1);

//***********************costruisci query finale
// $n=strlen($where_final);
// $b=strlen($boolean);
// $where_final=substr($where_final,0,$n-$b);
$n=strlen($where_final);

if($distinct_val=="true")
$distinct="distinct";
else
$distinct="";


if($groupby==false or $group_final=="")
{
$query="select $distinct $target_list $mea from $cubetablename $join_final";
if($n>1)
  $query="select $distinct $target_list $mea from $cubetablename $join_final where $where_final";
}
else
{
if($n>1)
  $query="select $distinct $target_list $mea from $cubetablename $join_final where $where_final group by $group_final";
else
  $query="select $distinct $target_list $mea from $cubetablename $join_final group by $group_final";
}

//***************************ORDINAMENTO
//print "COLONNA $colonna<br>";
$tab1="";
if($colonna!="")
list($tab1,$col1)=explode(".",$colonna);
$a=strrpos($join_final,$tab1);

if($tab1!="" && $a!=false && $colonna!="" && $ordinamento!="")
$query="$query order by $tab1.$col1 $ordinamento";

if($tab1=="" && $colonna!="" && $ordinamento!="")
$query="$query order by $col1 $ordinamento";

if($tab1!="" && $a=="" && $colonna!="" && $ordinamento!="")
$query="$query order by $tab1.$col1 $ordinamento";

//print "TAB $tab1 JOIN $join_final A $a COLONNA $colonna ORDINAMENTO $ordinamento<br>";

//print "$query<br>";
return $query; 
}



//******************************************************************



function buildJoin($hier,$join_mode)
{
//print "JM $join_mode<br>";

if($join_mode=="true")
$join2=" inner join ";
else
$join2=" right join ";


foreach($hier->Join as $join)
{
  $i=0;
  $left=$join['leftKey'];
  $right=$join['rightKey'];
  $alias=$join['rightAlias'];
  foreach($join->Table as $table)
  { 
    $t[$i]=$table['name'];
    $i=$i+1;        
  } 
  if($i==2)
  {
    $strJoin=" $join2 $t[1] on $t[0].$left=$t[1].$right";
    return $strJoin; 
  } 
  else
  {
    $strJoin=" $join2 $alias on $t[0].$left=$alias.$right " . $this->buildJoin($join,$join_mode);
    return $strJoin; 
  }     
         
}

}



function trasforma($cond1)
{
$n=strlen($cond1);
$prima2=substr($cond1,0,2);
$seconda=substr($cond1,2,$n);

if(($prima2==">=" or $prima2=="<=") and is_numeric($seconda)==false)
return $cond1="$prima2'$seconda'";

if(($prima2==">=" or $prima2=="<=") and is_numeric($seconda)==true)
return $cond1="$prima2$seconda";


$prima1=substr($cond1,0,1);
$seconda=substr($cond1,1,$n);

if(($prima1=="=" or $prima1=="<" or $prima1==">") and is_numeric($seconda)==false)
return $cond1="$prima1'$seconda'";

if(($prima1=="=" or $prima1=="<" or $prima1==">") and is_numeric($seconda)==true)
return $cond1="$prima1$seconda";


}



}








?>