var xmlhttp;
var url;
var plugin;

var img_minus;
var img_plus;
var img_del;



function set_column_value(cellid,colvalue)
{
//alert(cellid);
document.getElementById(cellid).value=colvalue;
document.getElementById("coprente").style.visibility="hidden";
}


function change_lang(lang)
{
//alert(lang);
//go(\"new_report\");
params="operation=change_lang&lang="+lang;
invio(params);

}


function get_column_values(property,cellid)
{
var cubename = document.getElementById("cubename").value;

  params="operation=get_column_values&property="+property+"&cubename="+cubename+"&cellid="+cellid;
  xmlhttp=GetXmlHttpObject();
  xmlhttp.onreadystatechange=function() {
      if (xmlhttp.readyState==4) {
        var testo=xmlhttp.responseText.trim();
        document.getElementById("divResult").innerHTML=testo;
        document.getElementById("coprente").style.visibility="visible";          
      }}
  xmlhttp.open("POST",url,true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send(params);

}

function send_email()
{
var email = document.getElementById('email').value;
var subject = document.getElementById('oggetto').value;
var text = document.getElementById('testo').value;
var shareurl = document.getElementById('shareurl').value;


params="operation=send_email&email="+email+"&subject="+subject+"&text="+text+"&shareurl="+shareurl;
  xmlhttp=GetXmlHttpObject();
  xmlhttp.onreadystatechange=function() {
      if (xmlhttp.readyState==4) {
        var testo=xmlhttp.responseText.trim();
        alert(testo);
          
      }}
  xmlhttp.open("POST",url,true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send(params);


}


function delete_file(filename,row)
{
params="operation=delete_file&filename="+filename;
  xmlhttp=GetXmlHttpObject();
  xmlhttp.onreadystatechange=function() {
      if (xmlhttp.readyState==4) {
         row.remove();
          
      }}
  xmlhttp.open("POST",url,true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send(params);


}


function export_result(operation,export_string)
{
//alert(export_string);
var filename="";
if(operation=="save_file")
{
filename = prompt("Save report", "Filename");
if(filename=="") return;
}

if(operation=="save_pdf" || operation=="save_csv" || operation=="save_weka")
{
  check_cube(operation);
}
else 
{
  params="operation="+operation+"&export_string="+export_string+"&filename="+filename;
  //alert(params);
  xmlhttp=GetXmlHttpObject();
  xmlhttp.onreadystatechange=function() {
      if (xmlhttp.readyState==4) 
      {
        var testo=xmlhttp.responseText.trim();
        if(operation=="show_form_social" || operation=="show_form_email")
        {
        document.getElementById("divResult").innerHTML=testo;
        document.getElementById("coprente").style.visibility="visible";
        }

          
      }}
  xmlhttp.open("POST",url,true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send(params);
}


}


function restore_condition(property,operator,value,img_del,img_search,op_boolean)
{

var tbl = document.getElementById('condition');
var row = document.createElement("tr");
var idriga=tbl.rows.length;
//var m=idriga+1;

if(idriga==0)
{
var cella = document.createElement("td");

cella.id="td_bool"+idriga;
cella.rowSpan=1;
var inputa = document.createElement( "select" );
var opt2 = document.createElement( "option" );
opt2.value=op_boolean;
opt2.text=op_boolean;
inputa.appendChild(opt2);

var opt3 = document.createElement( "option" );
if(op_boolean=="AND")
new_boolean="OR";
else
new_boolean="AND";


opt3.value=new_boolean;
opt3.text=new_boolean;
inputa.appendChild(opt3);

inputa.setAttribute( "id", "bool"+idriga );
inputa.setAttribute( "name", "bool"+idriga );
inputa.setAttribute( "type", "text" );
cella.appendChild(inputa);
row.appendChild(cella);
}
if(idriga>0)
{
var prec=idriga-1;
var cellaa = document.getElementById("td_bool0");
var x=cellaa.rowSpan;
x++;
cellaa.setAttribute( "rowspan", x);
}



var cell = document.createElement("td");
cell.setAttribute( "id", "property"+idriga );
cell.innerHTML=property;
row.appendChild(cell);


var cell2 = document.createElement("td");
var input2 = document.createElement( "select" );
input2.setAttribute( "id", "operator"+idriga );
input2.setAttribute( "name", "operator"+idriga );
cell2.appendChild(input2);
row.appendChild(cell2);
var opt2 = document.createElement( "option" );
opt2.value=operator;
opt2.text=operator;
input2.appendChild(opt2);

var cell3 = document.createElement("td");
var input3 = document.createElement( "input" );
input3.setAttribute( "id", "value"+idriga );
input3.setAttribute( "name", "value"+idriga );
input3.setAttribute( "type", "text" );
input3.setAttribute( "value", value );
cell3.appendChild(input3);
row.appendChild(cell3);


var cell4 = document.createElement("td");
cell4.align="center";
var rif4= document.createElement("a");
//rif4.href="#";
rif4.style.cursor = "pointer";
rif4.onclick=function() {row.parentNode.removeChild(row);}

var immagine4 = document.createElement( "img" );
immagine4.src=img_del;
immagine4.border=0;
immagine4.width=20;
immagine4.height=20;
cell4.appendChild(rif4);
rif4.appendChild(immagine4);


///////
var rif5= document.createElement("a");
rif5.style.cursor = "pointer";
rif5.onclick=function() {get_column_values(property,"value"+idriga);}
var immagine5 = document.createElement( "img" );
immagine5.src=img_search;
immagine5.border=0;
immagine5.width=20;
immagine5.height=20;
rif5.appendChild(immagine5);
cell4.appendChild(rif5);
////////

row.appendChild(cell4);
tbl.appendChild(row);


}



function build_condition()
{
var v=new Array();
var tbl = document.getElementById('condition');
var nrows=tbl.rows.length;
for(i=0;i<nrows;i++)
{
var bool = document.getElementById('bool0').value;
//var property=document.getElementById('property'+i).value;
var property=document.getElementById('property'+i).innerHTML;
var operator=document.getElementById('operator'+i).value;
var value=document.getElementById('value'+i).value;

// if (isNumeric(value)==false)
// value="|"+value+"|";
v[i]=property+'.'+operator+value;

// alert(property+operator+value);
// /////////////////////////////////////////////////////
// params="operation=replace&property="+property;
// xmlhttp=GetXmlHttpObject();
// xmlhttp.onreadystatechange=function() {
//     if (xmlhttp.readyState==4) {
//         var realname=xmlhttp.responseText.trim();
//         //alert(testo);
//         v[i]=realname+operator+value;
//         alert("cond i="+i+realname);
//         
//     }}
// xmlhttp.open("POST",url,true);
// xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
// xmlhttp.send(params);

////////////////////////////////////////////////////////

}
var string_condition = v.join("--");
return string_condition; 
}



function new_condition(property,img_del,img_search)
{
// alert(property);
// return;
//alert(img_search);

var v=new Array();
v[0]="=";
v[1]="<";
v[2]="<=";
v[3]=">";
v[4]=">=";
v[5]="<>";
v[6]="LIKE";
var m=v.length;

var tbl = document.getElementById('condition');
var row = document.createElement("tr");
var idriga=tbl.rows.length;

//alert(idriga);
if(idriga==0)
{
var cella = document.createElement("td");
// cella.setAttribute( "rowspan", 1);
// cella.setAttribute( "id", "td_bool"+idriga );

cella.id="td_bool"+idriga;
cella.rowSpan=1;
var inputa = document.createElement( "select" );
var opt2 = document.createElement( "option" );
opt2.value="AND";
opt2.text="AND";
inputa.appendChild(opt2);
var opt3 = document.createElement( "option" );
opt3.value="OR";
opt3.text="OR";
inputa.appendChild(opt3);


inputa.setAttribute( "id", "bool"+idriga );
inputa.setAttribute( "name", "bool"+idriga );
inputa.setAttribute( "type", "text" );
cella.appendChild(inputa);
row.appendChild(cella);
}
if(idriga>0)
{
var prec=idriga-1;
var cellaa = document.getElementById("td_bool0");
var x=cellaa.rowSpan;
x++;
cellaa.setAttribute( "rowspan", x);
}


var cell = document.createElement("td");
// var input = document.createElement( "input" );
cell.setAttribute( "id", "property"+idriga );
// input.setAttribute( "name", "property"+idriga );
// input.setAttribute( "type", "text" );
// input.setAttribute( "value", property );
cell.innerHTML=property;
//cell.appendChild(input);
row.appendChild(cell);


var cell2 = document.createElement("td");
var input2 = document.createElement( "select" );
input2.setAttribute( "id", "operator"+idriga );
input2.setAttribute( "name", "operator"+idriga );
//input2.setAttribute( "type", "select" );
//input2.setAttribute( "value", "B" );
cell2.appendChild(input2);
row.appendChild(cell2);
for(j=0;j<m;j++)
        {
        var opt2 = document.createElement( "option" );
        opt2.value=v[j];
        opt2.text=v[j];
        input2.appendChild(opt2);
        }

var cell3 = document.createElement("td");
var input3 = document.createElement( "input" );
input3.setAttribute( "id", "value"+idriga );
input3.setAttribute( "name", "value"+idriga );
input3.setAttribute( "type", "text" );
//input3.setAttribute( "value", "aaaa" );
input3.setAttribute( "placeholder", "value" );
cell3.appendChild(input3);
row.appendChild(cell3);

var cell4 = document.createElement("td");
cell4.align="center";

var rif4= document.createElement("a");
rif4.style.cursor = "pointer";
rif4.onclick=function() {row.parentNode.removeChild(row);}
var immagine4 = document.createElement( "img" );
immagine4.src=img_del;
immagine4.border=0;
immagine4.width=20;
immagine4.height=20;
rif4.appendChild(immagine4);
cell4.appendChild(rif4);

var rif5= document.createElement("a");
rif5.style.cursor = "pointer";
rif5.onclick=function() {get_column_values(property,"value"+idriga);}
var immagine5 = document.createElement( "img" );
immagine5.src=img_search;
immagine5.border=0;
immagine5.width=20;
immagine5.height=20;
rif5.appendChild(immagine5);
cell4.appendChild(rif5);

row.appendChild(cell4);



//tblBody.appendChild(row);
tbl.appendChild(row);



}


function exec_drill(old_index)
{
var new_level = document.getElementById('new_level');
index=new_level.options.selectedIndex;
//alert(index);


m=new_level.options.length;

var elSel = document.getElementById('level_selected');
n=elSel.options.length;

var i=0;
var j=0;

for(i=0;i<n;i++)
{
  
   if(elSel.options[i].value==new_level.options[old_index].value)
   {
    elSel.options[i].text=new_level.options[index].value;
    elSel.options[i].value=new_level.options[index].value;
   }
}

check_cube("execute");
}

function change_aggregation(operation,cubename,tablename,colname)
{
//alert(cubename+","+tablename+","+colname);
params="operation="+operation+"&cubename="+cubename+"&tablename="+tablename+"&colname="+colname;
xmlhttp=GetXmlHttpObject();
xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4) {
        var testo=xmlhttp.responseText.trim();
        //alert(testo);
        document.getElementById("divResult").innerHTML=testo;
        document.getElementById("coprente").style.visibility="visible";
        
    }}
xmlhttp.open("POST",url,true);
xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xmlhttp.send(params);

}

function order_by(column,type)
{

document.getElementById("order_by_col").value=column;
document.getElementById("order_by_type").value=type;
check_cube("execute");

}

function check_cube(operation)
{

var order_by_col = document.getElementById("order_by_col").value;
var order_by_type = document.getElementById("order_by_type").value;
var distinct = document.getElementById("distinct_box").checked;
var join = document.getElementById("join_box").checked;
//var slice=build_condition();
//alert(distinct);
//alert("order="+order_by_col+" "+order_by_type);

var cubename = document.getElementById("cubename").value;
if (cubename=="") return;
//alert(cube);

var elSel = document.getElementById('level_selected');
n=elSel.options.length;
if(n==0) return;
//alert(n);


var i=0;
var elSelNew = document.createElement('select');
elSelNew.name="level_selected[]";
elSelNew.multiple=true;
elSelNew.setAttribute('multiple',true);
elSelNew.setAttribute('style',"visibility:hidden");

for(i=0;i<n;i++)
{
var elOptNew = document.createElement('option');
try {
    elSelNew.add(elOptNew, null);
  }
  catch(ex) {
    elSelNew.add(elOptNew);
  }
elOptNew.text = elSel.options[i].text;
elOptNew.value = elSel.options[i].value;
elOptNew.selected=true;
//alert(elOptNew.value+" "+elOptNew.text);
}


//invia("form_report");
params="operation="+operation+"&cubename="+cubename+"&distinct="+distinct+"&join="+join+"&order_by_col="+order_by_col+"&order_by_type="+order_by_type;
//invio(params);
//alert(params);

var f = document.createElement("form");
f.setAttribute('method',"post");
f.setAttribute('action',url);
document.body.appendChild(f);

var elemento=params.split("&");
n=elemento.length;
for(i=0;i<n;i++){
coppia=elemento[i].split("=");
nome=coppia[0];
valore=coppia[1];

x = document.createElement("input");
x.setAttribute('type',"text");
x.setAttribute('name',nome);
x.setAttribute('value',valore);
x.setAttribute('style',"visibility:hidden");
f.appendChild(x);
}
f.appendChild(elSelNew);

////////////////////////////////////////////CONDIZIONI - SLICE
var v=new Array();
var tbl = document.getElementById('condition');
var nrows=tbl.rows.length;
for(i=0;i<nrows;i++)
{
var bool = document.getElementById('bool0').value;
var property=document.getElementById('property'+i).innerHTML;
var operator=document.getElementById('operator'+i).value;
var value=document.getElementById('value'+i).value;
v[i]=property+'.'+operator+'.'+value;
//v[i]=property+'.'+operator+value;
x = document.createElement("input");
x.setAttribute('type',"text");
x.setAttribute('name','slice[]');
x.setAttribute('value',v[i]);
x.setAttribute('style',"visibility:hidden");
f.appendChild(x);

x = document.createElement("input");
x.setAttribute('type',"text");
x.setAttribute('name','boolean');
x.setAttribute('value',bool);
x.setAttribute('style',"visibility:hidden");
f.appendChild(x);

}
//////////////////////////////////////////////////////////////

f.submit();

}


function delCol(attr)
{
var row = document.getElementById('rep_header');
var cell_del = document.getElementById(attr);
row.removeChild(cell_del);


//rimuovi dai livelli selezionati
var elSel = document.getElementById('level_selected');
var items2 = elSel.getElementsByTagName("option");
var n2=items2.length;

for(i=0;i<n2;i++)
{ 
if(document.getElementById("level_selected").options[i].value==attr)
  cc=i;
}
document.getElementById("level_selected").remove(cc);

n2--;

for(i=0;i<n2;i++)
{
document.getElementById("level_selected").options[i].selected=true; 
}

}



function addCol(dimension,hier,level,prop)
{


var attr=dimension+"."+hier+"."+level+"."+prop;

if (level=="aggregate")
var attr2=prop;
else
var attr2=level+"."+prop;

var tbl = document.getElementById('report');
var row = document.getElementById('rep_header');

var cell = document.createElement("th");
cell.id=attr;
cell.setAttribute("id",attr);


var rif= document.createElement("a");
rif.href="#";
rif.onclick=function() {delCol(attr);}

var immagine = document.createElement("img");
immagine.src=img_del;
immagine.border=0;
immagine.width=20;
immagine.height=20;
rif.appendChild(immagine);

cell.innerHTML=attr2;
cell.appendChild(rif);
row.appendChild(cell);


//aggiungi ai livelli selezionati
var elOptNew = document.createElement('option');
var elSel = document.getElementById('level_selected');
try {
    elSel.add(elOptNew, null);
  }
  catch(ex) {
    elSel.add(elOptNew);
  }
elOptNew.text = attr;
elOptNew.value = attr;
elOptNew.id = "sel"+attr;
var items2 = elSel.getElementsByTagName("option");
var n2=items2.length;
for(i=0;i<n2;i++)
{ document.getElementById("level_selected").options[i].selected=true; }


}



function show_prop(cubename,dimensionname,hiername,levelname)
{
var valore= document.getElementById("hidden_"+dimensionname+"_"+hiername+"_"+levelname).value;
//alert(valore);
if(valore=="open")
{
document.getElementById('divProp_'+dimensionname+"_"+hiername+"_"+levelname).innerHTML=""; 
document.getElementById("hidden_"+dimensionname+"_"+hiername+"_"+levelname).value="closed";
rif= document.getElementById(dimensionname+"-"+hiername+"-"+levelname+"-img_plus");
rif.setAttribute("src",img_plus);
return; 
}

params="operation=show_prop&cubename="+cubename+"&dimensionname="+dimensionname+"&hiername="+hiername+"&levelname="+levelname;
xmlhttp=GetXmlHttpObject();
xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4) {
        var testo=xmlhttp.responseText.trim();
        //alert(testo);
        document.getElementById("divProp_"+dimensionname+"_"+hiername+"_"+levelname).innerHTML=testo;
        var rif= document.getElementById(dimensionname+"-"+hiername+"-"+levelname+"-img_plus");
        rif.setAttribute("src",img_minus);
        document.getElementById("hidden_"+dimensionname+"_"+hiername+"_"+levelname).value="open";
        
    }}
xmlhttp.open("POST",url,true);
xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xmlhttp.send(params);



}



function show_levels(cubename,dimensionname,hiername)
{
var valore= document.getElementById("hidden_"+dimensionname+"_"+hiername).value;
if(valore=="open")
{
document.getElementById('divLev_'+dimensionname+"_"+hiername).innerHTML=""; 
document.getElementById("hidden_"+dimensionname+"_"+hiername).value="closed";
rif= document.getElementById(dimensionname+"-"+hiername+"-img_plus");
rif.setAttribute("src",img_plus);
return; 
}

params="operation=show_levels&cubename="+cubename+"&dimensionname="+dimensionname+"&hiername="+hiername;
xmlhttp=GetXmlHttpObject();
xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4) {
        var testo=xmlhttp.responseText.trim();
        document.getElementById("divLev_"+dimensionname+"_"+hiername).innerHTML=testo;
        var rif= document.getElementById(dimensionname+"-"+hiername+"-img_plus");
        rif.setAttribute("src",img_minus);
        document.getElementById("hidden_"+dimensionname+"_"+hiername).value="open";
        
    }}
xmlhttp.open("POST",url,true);
xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xmlhttp.send(params);



}


function show_hier(dimensionname,cubename)
{
var valore= document.getElementById("hidden_"+dimensionname).value;
//alert(valore);
if(valore=="open")
{
document.getElementById('divHier_'+dimensionname).innerHTML=""; 
document.getElementById("hidden_"+dimensionname).value="closed";
rif= document.getElementById(dimensionname+"-img_plus");
rif.setAttribute("src",img_plus);
return; 
}

params="operation=show_hier&cubename="+cubename+"&dimensionname="+dimensionname;
xmlhttp=GetXmlHttpObject();
xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4) {
        var testo=xmlhttp.responseText.trim();
        document.getElementById("divHier_"+dimensionname).innerHTML=testo;
        var rif= document.getElementById(dimensionname+"-img_plus");
        rif.setAttribute("src",img_minus);
        document.getElementById("hidden_"+dimensionname).value="open";
        
    }}
xmlhttp.open("POST",url,true);
xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xmlhttp.send(params);

}


function show_functions(cubename,measurename)
{

var valore= document.getElementById("hidden_"+cubename+"_"+measurename).value;
//alert(valore);
if(valore=="open")
{
document.getElementById('divFunctions_'+cubename+"_"+measurename).innerHTML=""; 
document.getElementById("hidden_"+cubename+"_"+measurename).value="closed";
rif= document.getElementById(cubename+"-"+measurename+"-img_plus");
rif.setAttribute("src",img_plus);
return; 
}


params="operation=show_functions&cubename="+cubename+"&measurename="+measurename;
xmlhttp=GetXmlHttpObject();
xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4) {
        var testo=xmlhttp.responseText.trim();
        //alert(testo);
        document.getElementById("divFunctions_"+cubename+"_"+measurename).innerHTML=testo;
        
        var rif= document.getElementById(cubename+"-"+measurename+"-img_plus");
        rif.setAttribute("src",img_minus);
        document.getElementById("hidden_"+cubename+"_"+measurename).value="open";
        
    }}
xmlhttp.open("POST",url,true);
xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xmlhttp.send(params);

}



function show_dimensions(cubename)
{

params="operation=show_dimensions&cubename="+cubename;
xmlhttp=GetXmlHttpObject();
xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4) {
        var testo=xmlhttp.responseText.trim();
        //alert(testo);
        document.getElementById("divDim_"+cubename).innerHTML=testo;
        
    }}
xmlhttp.open("POST",url,true);
xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xmlhttp.send(params);

}


function show_measures(cubename)
{
//alert("show mea");
var valore= document.getElementById("hidden_"+cubename).value;
//alert(valore);
if(valore=="open")
{
document.getElementById('divMeasure_'+cubename).innerHTML=""; 
document.getElementById('divDim_'+cubename).innerHTML=""; 
document.getElementById("hidden_"+cubename).value="closed";
rif= document.getElementById(cubename+"-img_plus");
rif.setAttribute("src",img_plus);
return; 
}

params="operation=show_measures&cubename="+cubename;
xmlhttp=GetXmlHttpObject();
xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4) {
        var testo=xmlhttp.responseText.trim();
        //alert(testo);
        document.getElementById("divMeasure_"+cubename).innerHTML=testo;
        
        var rif= document.getElementById(cubename+"-img_plus");
        rif.setAttribute("src",img_minus);
        document.getElementById("hidden_"+cubename).value="open";
        show_dimensions(cubename);
    }}
xmlhttp.open("POST",url,true);
xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xmlhttp.send(params);

}


function show_tree2(cubename,order_by_col, order_by_type,distinct,join)
{

params="operation=show_tree&cubename="+cubename;

xmlhttp=GetXmlHttpObject();
xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4) {
        var testo=xmlhttp.responseText.trim();
        document.getElementById("divTree").innerHTML=testo;
        show_measures(cubename);
        document.getElementById("order_by_col").value=order_by_col;
        document.getElementById("order_by_type").value=order_by_type;
        
if(distinct=="true")        
document.getElementById("distinct_box").checked=true;
else
document.getElementById("distinct_box").checked=false;

if(join=="true")        
document.getElementById("join_box").checked=true;
else
document.getElementById("join_box").checked=false;


    }}
xmlhttp.open("POST",url,true);
xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xmlhttp.send(params);

}


function show_tree(cubename,svuota)
{
//alert("show tree");

params="operation=show_tree&cubename="+cubename;
//alert(params);

xmlhttp=GetXmlHttpObject();
xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4) {
        var testo=xmlhttp.responseText.trim();
        //alert(testo);
        document.getElementById("divTree").innerHTML=testo;
    }}
xmlhttp.open("POST",url,true);
xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xmlhttp.send(params);

}


function init_plugin(plugin_dir)
{
  plugin=plugin_dir;
}

function init_images(php_img_minus,php_img_plus,php_img_del)
{
img_minus=php_img_minus;
img_plus=php_img_plus;
img_del=php_img_del;
}


function bar(plugin_operation)
{
var dati="";
start_plugin("start_plugin",plugin_operation,plugin,dati);
}


function init(action) {url=action;}

function start_plugin(operazione,plugin_operation,plugin_dir,dati){

params="operation="+operazione+"&plugin_operation="+plugin_operation+"&plugin_dir="+plugin_dir;

if(dati!="") params=params+"&"+dati;
if(operazione=="start_plugin") {invio(params);}
if(operazione=="exec_plugin"){invio_ajax(params);}
}


function open_file(file)
{
params="operation=open_file&filename="+file;
//alert(params);
invio(params);
}


function go(operation)
{params="operation="+operation;invio(params);}



function remember(no_data,no_email){
document.getElementById("feedback").innerHTML="";
var email=document.getElementById("email").value;
var f=validateEmail(email);

if (email==""){alert(no_data);return;}

if (f==false){alert(no_email);return;}

params="operation=remember_pwd&email="+email;
invio_ajax(params);
}

function register(no_data,no_email){
document.getElementById("feedback").innerHTML="";
var email=document.getElementById("email").value;
var f=validateEmail(email);

if (email==""){alert(no_data);return;}

if (f==false){alert(no_email);return;}

params="operation=register_user&email="+email;
invio_ajax(params);

}




function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
} 




function login(no_data)
{
var email=document.getElementById("email").value;
var pwd=document.getElementById("pwd").value;

if (email=="" || pwd==""){alert(no_data);return;}
 
params="operation=login&email="+email+"&pwd="+pwd;
invio_ajax(params);

}



function invio(params){

var f = document.createElement("form");
f.setAttribute('method',"post");
f.setAttribute('action',url);
document.body.appendChild(f);

var elemento=params.split("&");
n=elemento.length;
for(i=0;i<n;i++){
coppia=elemento[i].split("=");
nome=coppia[0];
valore=coppia[1];

x = document.createElement("input");
x.setAttribute('type',"text");
x.setAttribute('name',nome);
x.setAttribute('value',valore);
x.setAttribute('style',"visibility:hidden");

f.appendChild(x);
}


f.submit();

}




function invio_ajax(params){

xmlhttp=GetXmlHttpObject();
xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4) {
        var testo=xmlhttp.responseText.trim();
        if(testo=="login_ok")
        start_plugin("start_plugin","","home","")
        else
        document.getElementById("feedback").innerHTML=testo;
    }}
xmlhttp.open("POST",url,true);
xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xmlhttp.send(params);

}






function GetXmlHttpObject(){
if (window.XMLHttpRequest)  {  return new XMLHttpRequest();  }
if (window.ActiveXObject)  {  return new ActiveXObject("Microsoft.XMLHTTP"); }
return null;}

